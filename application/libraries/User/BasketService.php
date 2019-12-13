<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPPATH . 'libraries/AbstractService.php';

/**
 * Class BasketService This class provides methods to add products to the shopping cart.
 */
class BasketService extends AbstractService
{
    public function __construct()
    {
        parent::__construct();

        // Load other libraries
        $this->load->library("User/BorrowingService");
        $this->load->library("User/ConnectionService");

        // Load models
        $this->load->model("Product", "product");
        $this->load->model("BasketProduct", "basketproduct");
    }

    /**
     * This method allows to retrieve all the baskets of the user
     *
     *
     * @param String $userId : The Id of the user.
     * @return Array $basket The basket is a map of "product" including the following elements :
     *              id : the basket id number
     *              userNumber : the username number
     *              startDate : the start date of a borrowing
     *              endDate : the end date of a borrowing
     *              idProduct : the product id number
     *              products" => The products as an array of object containing :
     *                  id : the product id number
     *                  idCategory : the category id number of the product
     *                  name :  the name of the product
     *                  description : the description of the product
     */
    public function listAllBasket($userId)
    {
        log_message("info", "List all baskets");

        $basket = $this->basketproduct->getBasketProductsByUser($userId);

        foreach ($basket as &$basketLine) {
            $basketLine->product = $this->product->getProductById($basketLine->idProduct);
        }

        return $basket;
    }

    /**
     * This method allows to delete the product when we push the button "Supprimer" or validate the basket.
     *
     * @param String $id : The Id of the product.
     * @throws Exception If the basket corresponding to the user ID is empty or the corresponding userid is wrong
     */
    public function deleteBasket($id)
    {
        log_message("info", "Delete a basket");

        $userId = ($this->connectionservice->getConnectedUser())["id"];

        $basket = $this->basketproduct->getBasketProductById($id);

        //Unable to delete the basket if the basket corresponding to the user ID is empty
        if ($basket == null || $basket->userNumber !== $userId) {
            log_message("error", "Can't delete the given product  (query is : " . $this->db->last_query() . ")");
            throw new Exception("Cannot delete basket line");
        }

        $this->basketproduct->deleteBasketProduct($id);
    }

    /**
     * This method allows to add the product when we add a product to the shopping cart.
     *
     * @param String $idProduct : The id of the product.
     * @param String $startDate : The date the product was lent.
     * @param String $endDate : The date the product should be returned.
     * @throws Exception If an error occurs during the process of adding a basket.
     */
    public function addBasket($startDate, $endDate, $idProduct)
    {
        log_message("info", "Add a hardware basket");
        try {
            $user = $this->connectionservice->getConnectedUser();
            $userNumber = $user["id"];

            $this->basketproduct->insertBasketHardwareProduct($userNumber, $startDate, $endDate, $idProduct);
        } catch (Exception $e) {
            log_message("error", "Can't add the given product in shopping cart (query is : " . $this->db->last_query() . ")");
            throw new Exception("Error in adding a product to shopping cart");
        }
    }

    /**
     * This method add a consumable product to the basket.
     *
     * @param $startDate
     * @param $endDate
     * @param $comment
     * @param $consumableName
     * @throws Exception
     */
    public function addConsumableBasket($startDate, $endDate, $comment, $consumableName)
    {
        log_message("info", "Add a consumable basket");
        try {
            $user = $this->connectionservice->getConnectedUser();
            $userNumber = $user["id"];

            $this->basketproduct->insertBasketConsumableProduct($userNumber, $startDate, $endDate, $comment, $consumableName);
        } catch (Exception $e) {
            log_message("error", "Can't add the given product in shopping cart (query is : " . $this->db->last_query() . ")");
            throw new Exception("Error in adding a product to shopping cart");
        }
    }

    /**
     * This method allows to modify the basket when we push the button "Modifier".
     *
     * @param String $idBasket : The id of the basket.
     * @param String $startDate : The date the product was lent.
     * @param String $endDate : The date the product should be returned.
     * @throws Exception If an error occurs during the process of modifying a basket.
     */
    public function modifyBasket($idBasket, $startDate, $endDate)
    {
        log_message("info", "Modify a basket");

        try {
            $this->basketproduct->updateBasketProduct($idBasket, $startDate, $endDate);
        } catch (Exception $e) {
            log_message("error", "Can't modify the given basket (query is : " . $this->db->last_query() . ")");
            throw new Exception("Error in modifying a product int the shopping cart");
        }

    }

    /**
     * This method allows to validate the basket when we push the button "Valider mon panier".
     */
    public function validateBasket()
    {
        log_message("info", "Validate the shopping cart");

        $user = $this->connectionservice->getConnectedUser();

        //Get all the products of a user's shopping cart
        $baskets = $this->basketproduct->getBasketProductsByUser($user["id"]);

        $productBasket = array_filter($baskets, function($elem) {
            return isset($elem->idProduct);
        });

        $consumableBasket = array_filter($baskets, function($elem) {
            return !isset($elem->idProduct);
        });

        $basket = array(
            "products" => $productBasket,
            "consumables" => $consumableBasket,
            "user" => $user);

        //Transforming all the products in the shopping cart to borrowings
        $this->borrowingservice->transformToBorrowing($basket);
    }
}



