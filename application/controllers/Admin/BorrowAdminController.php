<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BorrowAdminController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('ConnectionService');
        $this->load->library('Admin/AdminBorrowingService');
        $this->load->library('Admin/AdminHardwareRequestService');
    }

    /**
     * This method show the admin borrow list page
     * @data user : the connected user
     *              id : the user id number
     *              firstName : the user first name
     *              lastName : the user last name
     *              email : the user email
     * @data hardwareBorrowingList : the list of hardware borrow
     *              id : the borrow id
     *              idHardware : the bar code of the hardware borrowed
     *              startDate : the begin of the borrow
     *              endDate : the supposed end of the borrow
     *              renderDate : the real end of the borrow
     *              adminComment : the comment let by the administrator
     *              userComment : the comment let by the user
     *              userNumber : the id number of the user
     *              ready : tell if the borrow is ready to be picked up
     *              pickedUp : tell if the borrow is already picked up
     *              productName : the name of the associated product
     *              categoryName : the name of the associated category
     *              remainingTime : the difference between the end date and today
     * @data nbUnreadRequests : the number of the unread request
     * @load AdministratorMenu : the administrator specific menu
     * @load AdministratorBorrowList : the administrator lists of borrows
     */
    public function AdministratorBorrowingList()
    {
        $data = array();
        $data["user"] = $this->connectionservice->getConnectedUser();
        $data["hardwareBorrowingList"] = $this->adminborrowingservice->getCurrentHardwareBorrowings();
        $data["nbUnreadRequests"]= $this->adminhardwarerequestservice->countUnreadRequests();
        $this->load->view('AdministratorPages/AdministratorMenu', $data);
        $this->load->view('AdministratorPages/AdministratorBorrowList', $data);
    }

    /**
     * This method show the admin borrow page for set a borrow as returned
     * @data user : the connected user
     *              id : the user id number
     *              firstName : the user first name
     *              lastName : the user last name
     *              email : the user email
     * @load AdministratorMenu : the administrator specific menu
     * @load AdministratorBorrowingComeback : the administrator page for set a borrow as returned
     */
    public function AdministratorBorrowingComeback()
    {
        $data = array();
        $data["user"] = $this->connectionservice->getConnectedUser();
        $data["nbUnreadRequests"]= $this->adminhardwarerequestservice->countUnreadRequests();
        $this->load->view('AdministratorPages/AdministratorMenu', $data);
        $this->load->view('AdministratorPages/AdministratorBorrowingComeback', $data);
    }
}