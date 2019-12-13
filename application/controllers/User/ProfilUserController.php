<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProfilUserController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('ConnectionService');
        $this->load->library('User/BorrowingService');
    }

    public function userPage()
    {
        $data = array();
        $data["user"] = $this->connectionservice->getConnectedUser();
        $data["borrows"] = $this->borrowingservice->getCurrentBorrowing();

        $this->load->view('UserPages/UserPage', $data);
    }

    public function userHistory()
    {
        $data = array();
        $data["user"] = $this->connectionservice->getConnectedUser();
        $data["borrows"] = $this->borrowingservice->getBorrowingHistory();

        $this->load->view('UserPages/UserHistory', $data);
    }

}