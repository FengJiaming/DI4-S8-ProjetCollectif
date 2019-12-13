<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BasketUserController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('ConnectionService');
        $this->load->library('User/BasketService');
        $this->load->library('User/ProductService');
    }
    /**
     * This method show the user basket page
     * @data user : the connected user
     *              id : the user id number
     *              firstName : the user first name
     *              lastName : the user last name
     *              email : the user email
     * @data basket : the list of all the baskets of the user
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
     * @data usedCalendar : the calendar of the borrowed product
     * @load UserBasket : the user basket page
     */
    public function userBasket()
    {
        $data = array();
        $data["user"] = $this->connectionservice->getConnectedUser();
        $data["basket"] = $this->basketservice->listAllBasket($data["user"]["id"]);
        $data["usedCalendar"] = array();

        foreach ($data["basket"] as $basketLine) {
            if (!isset($basketLine->idProduct)) {
                continue;
            }
            $data["usedCalendar"][$basketLine->idProduct] = $this->productservice->getProductBorrowCalendar($basketLine->idProduct);
        }

        $this->load->view('UserPages/UserBasket', $data);
    }

    public function validateUserBasket()
    {
        try {
            $this->basketservice->validateBasket();
        } catch (Exception $e) {
            //TODO Show error page
            return;
        }

        redirect("User/ProfilUserController/userPage");
    }

    public function deleteBasketLine($basketId)
    {
        $this->basketservice->deleteBasket($basketId);

        redirect("User/BasketUserController/userBasket");
    }
}