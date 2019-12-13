<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPPATH . 'libraries/AbstractService.php';

/**
 * Class ProductService This class provides methods to manage the product.
 *
 */
class ProductService extends AbstractService
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model("Product", "product");
        $this->load->model("Hardware", "hardware");
        $this->load->model("HardwareBorrowing", "hardwareborrowing");
        $this->load->model("ProductRequest", "productrequest");
        $this->load->library('EmailService');
    }

    /**
     * This method return the product which correspond to the given id
     * @param $id : the id to look for
     * @return mixed : the asked product if it exist
     */
    public function getProductById($id)
    {
        return $this->product->getProductById($id);
    }

    /**
     * This method return the product which correspond to the given name
     * @param $name : the name to look for
     * @return mixed : the asked product if it exist
     */
    public function getProductByName($name)
    {
        return $this->product->getProductByName($name);
    }

    /**
     * This method return the product which correspond to the given name
     * @param $name : the name to look for
     * @return mixed : the asked product if it exist
     */
    public function getProductsByName($name)
    {
        return $this->product->getProductsByName($name);
    }

    /**
     * This method return all products borrowable at this time
     * @return mixed : an array of products
     */
    public function getAllBorrowableProducts()
    {
        return $this->product->getAvailableProducts();
    }

    /**
     * This method return all products in the category which correspond to the given name
     * @param $categoryName : the category name to look for
     * @return mixed : an array of products
     */
    public function getProductsByCategoryName($categoryName)
    {
        return $this->product->getProductByNameCategory($categoryName);
    }

    /**
     * This method return the product which correspond to the given name and categoryName
     * @param $name : the product name to look for
     * @param $categoryName : the category of the
     * @return mixed : the asked category if it exist
     */
    public function getProductByNameAndCategory($name, $categoryName)
    {
        return $this->product->getProductByNameAndCategoryName($name, $categoryName);
    }

    /**
     * This method tell if the product which correspond to the given id
     * is borrowable between the given start and end dates
     * @param $id : the id to look for
     * @param $startDate : the period starting date
     * @param $endDate : the period ending date
     * @return bool : true if the product is borrowable, false otherwise
     */
    public function isBorrowable($id, $startDate, $endDate)
    {
        $borrowable = false;

        if ($this->hardware->getAvailableHardwaresByProductId($startDate, $endDate, $id) != null) {
            $borrowable = true;
        }

        return $borrowable;
    }

    /**
     * This method return all products associated to the product which correspond to the given name NOT IMPLEMENT YET
     * @param $name : the name to look for
     * @return mixed : an array of products
     */
    public function getAllProductsAssociatedTo($name)
    {

    }

    /**
     * This method return a array of all date where the given product is not borrowable.
     * If all products are not available for one date, It add this date to the return array
     * @param int $productId The product id to check
     * @return array The array of date where the product is not borrowable
     * @throws Exception (Should never throw because the date are always well formatted)
     */
    public function getProductBorrowCalendar($productId)
    {
        $hardwares = $this->hardwareborrowing->getAvailableHardwareBorrowingsByProductId($productId);

        if(empty($hardwareStartDates))
        {
            return array();
        }

        $hardwareStartDates = array_map(function ($e) {
            return new DateTime($e->startDate);
        }, $hardwares);

        $hardwareEndDates = array_map(function ($e) {
            return new DateTime($e->endDate);
        }, $hardwares);

        // find the first start date and the last
        $min = min($hardwareStartDates);
        $max = max($hardwareEndDates);

        // iterate through all the date between the min and the max
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($min, $interval, $max);

        $calendar = array();

        foreach ($period as $date) {
            $add = FALSE;
            foreach ($hardwares as $hardware) {
                if (($date >= new DateTime($hardware->startDate)) && ($date <= new DateTime($hardware->endDate))) {
                    $add = TRUE;
                    break;
                }
            }

            if ($add) {
                array_push($calendar, $date->format("Y-m-d"));
            }
        }

        return $calendar;
    }

    public function requestNewProduct($productName, $comment)
    {
        $user = $this->connectionservice->getConnectedUser();

        $this->productrequest->insertRequest($user["id"], $productName, $comment);

        $this->emailservice->sendProductRequest($user["email"], array(
            "productName" => $productName,
            "comment" => $comment,
            "user" => $user
        ));
    }
}
