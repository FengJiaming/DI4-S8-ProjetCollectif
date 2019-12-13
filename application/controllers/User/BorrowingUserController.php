<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BorrowingUserController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('ConnectionService');
        $this->load->library('User/BasketService');
        $this->load->library('User/ProductService');
        $this->load->library('User/CategoryService');
        $this->load->library('EmailService');
    }

    public function userBorrowing()
    {
        $data = array();
        $data["user"] = $this->connectionservice->getConnectedUser();
        $data["categories"] = $this->categoryservice->getAllCategories();

        $this->form_validation->set_rules('CategoryName', 'CategoryName', 'trim');
        $this->form_validation->set_rules('ProductName', 'ProductName', 'trim');


        if ($this->form_validation->run() == TRUE) {
            try {
                if (set_value('CategoryName') != 0 && set_value('ProductName') == "") {
                    $nameCategory = $this->categoryservice->getCategoryById(set_value('CategoryName'))->name;
                    $data["products"] = $this->productservice->getProductsByCategoryName($nameCategory);
                } elseif (set_value('CategoryName') == 0 && set_value('ProductName') != "") {
                    $data["products"] = $this->productservice->getProductsByName(set_value('ProductName'));
                } elseif (set_value('CategoryName') != 0 && set_value('ProductName') != "") {
                    $nameCategory = $this->categoryservice->getCategoryById(set_value('CategoryName'))->name;
                    $data["products"] = $this->productservice->getProductByNameAndCategory(set_value('ProductName'), $nameCategory);
                }

            } catch (Exception $e) {
            }
        } else {
            $data["products"] = $this->productservice->getAllBorrowableProducts();
        }

        $this->load->view('UserPages/UserBorrowing', $data);
    }

    public function userProducts($idProduct)
    {
        $this->form_validation->set_rules('startDate', 'startDate', 'required');
        $this->form_validation->set_rules('endDate', 'endDate', 'required');

        $data = array();
        $data["user"] = $this->connectionservice->getConnectedUser();
        $data["product"] = $this->productservice->getProductById($idProduct);
        $data["usedCalendar"] = $this->productservice->getProductBorrowCalendar($idProduct);

        if ($this->form_validation->run() == TRUE) {
            try {

                $this->basketservice->addBasket(set_value("startDate"), set_value("endDate"), $idProduct);
                $data["validation"] = TRUE;

                //redirect("User/BasketUserController/userBasket");

            } catch (Exception $e) {
                $data["validation"] = FALSE;
            }
        }

        $this->load->view('UserPages/UserProducts', $data);
    }

    public function userImpossibleBorrowing($idProduct)
    {
        $data = array();
        $data["user"] = $this->connectionservice->getConnectedUser();
        $data["product"] = $this->productservice->getProductById($idProduct);

        // set the login form rules
        $this->form_validation->set_rules('Commentaries', 'Commentaries', 'required');

        if ($this->form_validation->run() == TRUE) {

            try {

                //TODO send email
                $data["isSent"] = TRUE;
            } catch (Exception $e) {
            }
        }

        $this->load->view('UserPages/UserImpossibleBorrowing', $data);
    }

    public function sendMessage()
    {

        redirect("User/ProfilUserController/userPage");
    }

    public function userBorrowingConsumable()
    {
        $data = array();
        $data["user"] = $this->connectionservice->getConnectedUser();
        $data["product"] = $this->productservice->getProductById(1);
        $data["usedCalendar"] = $this->productservice->getProductBorrowCalendar(1);

        // set the login form rules
        $this->form_validation->set_rules('comment', 'Commentaire sur la demande', 'required');
        $this->form_validation->set_rules('consumableName', 'Nom du consommable', 'required');
        $this->form_validation->set_rules('startDate', 'Date de debut de l\'emprunt', 'required');
        $this->form_validation->set_rules('endDate', 'Date de fin de l\'emprunt', 'required');

        if ($this->form_validation->run() == TRUE) {
            try {
                $this->basketservice->addConsumableBasket($this->input->post("startDate"),
                    $this->input->post("endDate"),
                    $this->input->post("comment"),
                    $this->input->post("consumableName"));

                $data["isSent"] = TRUE;
            } catch (Exception $e) {
                $data["error_message"] = "Erreur d'envoie de la demande";
            }
        }

        $this->load->view('UserPages/UserBorrowingConsumables', $data);
    }

    public function userBorrowingNewProduct()
    {
        $data = array();
        $data["user"] = $this->connectionservice->getConnectedUser();

        // set the login form rules
        $this->form_validation->set_rules('comment', 'Commentaire', 'required');
        $this->form_validation->set_rules('productName', 'Nom du produit', 'required');

        if ($this->form_validation->run() == TRUE) {

            try {
                $this->productservice->requestNewProduct(set_value("productName"), set_value("comment"));

                $data["isSent"] = TRUE;
            } catch (Exception $e) {
                $data["error_message"] = "Erreur d'envoie de la demande";
            }
        }

        $this->load->view('UserPages/UserBorrowingNewProduct', $data);
    }

    public function sendMessageConsumable()
    {

        redirect("User/BorrowingUserController/userBorrowing");
    }
}