<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPPATH . 'libraries/AbstractService.php';

/**
 * Class BorrowingService This class provides methods to borrow a product for a certain periods
 *
 * Each product can be borrowed for a period.
 */
class BorrowingService extends AbstractService
{
    public function __construct()
    {
        parent::__construct();

        // Load other libraries
        $this->load->library("ConnectionService");
        $this->load->library("EmailService");

        // Load models
        $this->load->model("HardwareBorrowing", "hardwareborrowing");
        $this->load->model("ConsumableBorrowing", "consumableborrowing");
        $this->load->model("Hardware", "hardware");
        $this->load->model("Product", "product");
        $this->load->model("BasketProduct", "basketproduct");
    }

    /**
     * This method transform the basket in a active borrowing.
     * /!\ This have only to be used for hardware borrowing
     *
     * The basket is a map of "product" including the following elements :
     *  - "products" => The products as an array of object containing :
     *      - The basket id
     *      - The start and end date to borrow this product
     *      - The product id
     *  - "user" => The User to attache to the borrow
     *
     * @param array $basket The basket to transform. Containing basket objects
     * @throws Exception If the basket contains product that are not available and when a problem happen when converting
     */
    public function transformToBorrowing($basket)
    {
        log_message("info", "Transform to borrow");

        // Loop through the basket and check if each product is available
        // If the product is available, store the available hardware product to create the borrow later

        $hardware_product = array();
        $not_available_products = array();
        foreach ($basket["products"] as $basket_product) {

            $product_id = $basket_product->idProduct;

            $available_hardware = $this->hardware->getAvailableHardwaresByProductId($basket_product->startDate, $basket_product->endDate, $product_id);
            
            // No hardware available for this product according to the different dates
            if (empty($available_hardware)) {
                array_push($not_available_products, $basket_product);
            } else {
                $hardware = array(
                    "hardwareProduct" => $available_hardware[0],
                    "startDate" => $basket_product->startDate,
                    "endDate" => $basket_product->endDate,
                );

                array_push($hardware_product, $hardware);
            }
        }

        if (!empty($not_available_products)) {
            log_message("info", "Validation of a basket that require non available products");
            $error_message = "There is product that are not available";
            throw new Exception($error_message);
        }

        $userId = ($basket["user"])["id"];

        // Critical section start (transaction)
        $this->db->trans_start();

        foreach ($hardware_product as $hardware) {
            $this->hardwareborrowing->insertHardwareBorrowing($userId, $hardware["hardwareProduct"]->idHardware, $hardware["startDate"], $hardware["endDate"], "", ""); // TODO See for implementing user comment and admin comment
        }

        foreach ($basket["consumables"] as $consumable) {
            $this->consumableborrowing->insertConsumableBorrowing($userId, $consumable->designation, $consumable->startDate, $consumable->endDate, null, "", $consumable->userComment); // TODO See for implementing user comment and admin comment
        }

        $this->basketproduct->deleteBasketProductByUserId($userId);

        // End of critical section
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            // The transaction failed
            log_message("error", "Can't transform to borrowing the given basket (query is : " . $this->db->last_query() . ")");
            throw new Exception("Error during transformToBorrowing");
        }


        // send emails to the user and administrator
        $productArray = array_map(function ($elem) {
            $ar = array(
                "isConsumable" => FALSE,
                "designation" => $elem["hardwareProduct"]->idHardware,//TODO how to fetch the product name ?
                "barcode" => $elem["hardwareProduct"]->idHardware,
                "startDate" => $elem["startDate"],
                "endDate" => $elem["endDate"],
            );

            return $ar;
        }, $hardware_product);

        $productArray = array_merge($productArray, array_map(function ($elem) {
            $ar = array(
                "isConsumable" => TRUE,
                "designation" => $elem->designation,
                "barcode" => "",
                "startDate" => $elem->startDate,
                "endDate" => $elem->endDate,
            );

            return $ar;
        }, $basket["consumables"]));

        $this->emailservice->sendBorrowConfirmationEmail($basket["user"]["email"], $productArray);
    }

    /**
     * This method allow to retrieve all the previous borrow of the user
     *
     * @return array The array of all borrows. Each element of the array is defined as follow :
     *      - bool isConsumable
     *          in case of a consumable
     *              - string designation The designation of the product
     *          in case of a hardware
     *              - array hardware The hardware object
     *      - string designation
     *      - date startDate
     *      - date endDate
     *      - date renderDate (can be null)
     *      - string adminComment
     *      - string userComment
     */
    public function getBorrowingHistory()
    {
        log_message("info", "Get borrow history");

        $connectedUserId = ($this->connectionservice->getConnectedUser())["id"];

        // fetch the hardware and consumables borrows
        $hardwareBorrows = $this->hardwareborrowing->getHardwareBorrowingsByUserId($connectedUserId);
        $consumableBorrows = $this->consumableborrowing->getConsumableBorrowingsByUserId($connectedUserId);

        // add a value to show if a borrow is a consumable or a hardware
        foreach ($hardwareBorrows as &$hardwareBorrow) {
            $hardwareBorrow->isConsumable = FALSE;
            $hardwareBorrow->hardware = $this->hardware->getHardwareByBarCode($hardwareBorrow->idHardware);
            $hardwareBorrow->product = $this->product->getProductById($hardwareBorrow->hardware->idProduct);
        }

        $returnValue = array_merge($hardwareBorrows, array_map(function ($elem) {
            $elem->isConsumable = TRUE;
            return $elem;
        }, $consumableBorrows));

        $returnValue = array_map(function ($elem) {
            $elem->remainingTime = date_diff(new DateTime(), new DateTime($elem->endDate));
            return $elem;
        }, $returnValue);

        return $returnValue;
    }

    /**
     * This method return the history of the current borrowing. A current borrow is a borrow that is not mark as finished.
     * It can be in time or late.
     *
     * @return array The array of current borrows. See getBorrowingHistory() for more information
     */
    public function getCurrentBorrowing()
    {
        log_message("info", "Get current borrow history");

        $allBorrows = $this->getBorrowingHistory();

        return array_filter($allBorrows, function ($elem) {

            if(!array_key_exists("renderDate", $elem)) {
                return true;
            }

            return $elem->renderDate == null;
        });
    }
}
