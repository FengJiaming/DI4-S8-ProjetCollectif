<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminListSuperAdminController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library("ConnectionService");
        $this->load->library("SuperAdmin/AdminUserInfoService");
    }

    /**
     * This method show the admin list page
     * @data user : the connected user
     *              id : the user id number
     *              firstName : the user first name
     *              lastName : the user last name
     *              email : the user email
     * @data adminList : the list of current administrators
     *              id : the administrator id
     *              userNumber : the user id number
     *              user : the user info corresponding to the userNumber
     *                  id : the user id number
     *                  firstName : the user first name
     *                  lastName : the user last name
     *                  email : the user email
     * @load AdministratorMenu : the administrator specific menu
     * @load AdministratorBorrowList : the administrator lists of borrows
     */
    public function SuperAdministratorAdminList()
    {
        $data = array();
        $data["user"] = $this->connectionservice->getConnectedUser();
        $data["adminList"] = $this->adminuserinfoservice->getAllAdministrators();

        $this->load->view('AdministratorPages/AdministratorMenu', $data);
        $this->load->view('AdministratorPages/SuperAdministratorAdminList', $data);
    }
}