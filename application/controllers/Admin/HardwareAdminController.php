<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HardwareAdminController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('ConnectionService');
        $this->load->library('Admin/AdminCategoryService');
        $this->load->library('User/ProductService');
        $this->load->library('Admin/AdminProductService');
        $this->load->library('Admin/AdminHardwareRequestService');
        $this->load->library('LDAPService');
    }

    /**
     * This method show the product list view in a borrowing purpose
     * @data user : the connected user
     *              id : the user id number
     *              firstName : the user first name
     *              lastName : the user last name
     *              email : the user email
     * @date productList : the list of all borrowable products
     *              id : the product id
     *              name : the product name
     *              description : the product description
     *              idCategory : the product category's id
     * @load AdministratorMenu : the administrator specific menu
     * @load UserBorrowing : the borrowable product list page
     */
    public function AdministratorHardwareBorrow()
    {
        $data = array();
        $data["user"] = $this->connectionservice->getConnectedUser();
        $data["productList"] = $this->productservice->getAllBorrowableProducts();

        $this->load->view('AdministratorPages/AdministratorMenu', $data);
        $this->load->view('UserPages/UserBorrowing', $data);
    }

    /**
     * This method show the hardware list view in a management purpose
     * @data user : the connected user
     *              id : the user id number
     *              firstName : the user first name
     *              lastName : the user last name
     *              email : the user email
     * @data categoryList : the list of all category in the data base
     *              id : the category id
     *              name : the category name
     * @data nbUnreadRequests : the number of the unread request
     * @load AdministratorMenu : the administrator specific menu
     * @load AdministratorHardwareManagement : the hardware list page
     */
    public function AdministratorHardwareManagement()
    {
        $filter = array();
        $filter["outOfService"] = $this->input->post("outOfService");
        $filter["reserved"] = $this->input->post("reserved");
        $filter["donation"] = $this->input->post("donation");

        if($filter["outOfService"] === "on")
        {
            $filter["outOfService"] = true;
        }
        else
        {
            $filter["outOfService"] = false;
        }
        if($filter["reserved"] === "on")
        {
            $filter["reserved"] = true;
        }
        else
        {
            $filter["reserved"] = false;
        }
        if($filter["donation"] === "on")
        {
            $filter["donation"] = true;
        }
        else
        {
            $filter["donation"] = false;
        }

        $data = array();
        $data["user"] = $this->connectionservice->getConnectedUser();
        $data["categoryList"] = $this->admincategoryservice->getAllCategoriesFiltered($filter["outOfService"], $filter["reserved"], $filter["donation"]);
        $data["filters"] = $filter;
        $data["nbUnreadRequests"]= $this->adminhardwarerequestservice->countUnreadRequests();
        $this->load->view('AdministratorPages/AdministratorMenu', $data);
        $this->load->view('AdministratorPages/AdministratorHardwareManagement', $data);
    }

    /**
     * This method show the hardware request list view
     * @data user : the connected user
     *              id : the user id number
     *              firstName : the user first name
     *              lastName : the user last name
     *              email : the user email
     * @data requestList : the list of all request in the data base
     *              id : the request id
     *              userNumber : the user id who made this request
     *              productName : the product name requested
     *              message : the message let by the user
     * @data nbUnreadRequests : the number of the unread request
     * @load AdministratorMenu : the administrator specific menu
     * @load AdministratorHardwareRequest : the request list page
     */
    public function AdministratorHardwareRequest()
    {
        $data = array();
        $data["user"] = $this->connectionservice->getConnectedUser();
		$data["nbUnreadRequests"]= $this->adminhardwarerequestservice->countUnreadRequests();


		$unreadRequests = $this->adminhardwarerequestservice->getUnreadRequests();
		foreach ($unreadRequests as $unreadRequest){
			try{
				$user = $this->ldapservice-> findInfoById($unreadRequest->userNumber);
				$unreadRequest->firstname= $user['firstname'];
				$unreadRequest->lastname= $user['lastname'];
			}catch(Exception $exception){
				//user not found
				$unreadRequest->firstname= "";
				$unreadRequest->lastname= "";
			}

		}
		$readRequests =  $this->adminhardwarerequestservice->getReadRequests();
		foreach ($readRequests as $readRequest){
			try{
			$user = $this->ldapservice-> findInfoById($readRequest->userNumber);
			$readRequest->firstname= $user['firstname'];
			$readRequest->lastname= $user['lastname'];
			}catch(Exception $exception){
				//user not found
				$readRequest->firstname= "";
				$readRequest->lastname= "";
			}
		}
		$data['unreadRequests']= $unreadRequests;
		$data['readRequests']= $readRequests;

        $this->load->view('AdministratorPages/AdministratorMenu', $data);
        $this->load->view('AdministratorPages/AdministratorHardwareRequest', $data);
    }

    /**
     * This method marks the request as read.
     * @param $requestID : the id number of the request
     */
    public function readAdminRequest($requestID)
    {
        $this->adminhardwarerequestservice->readRequest($requestID);
        redirect("Admin/HardwareAdminController/AdministratorHardwareRequest");
    }

    /**
     * This method marks the request as unread.
     * @param $requestID : the id number of the request
     */
    public function unreadAdminRequest($requestID)
    {
        $this->adminhardwarerequestservice->unreadRequest($requestID);
        redirect("Admin/HardwareAdminController/AdministratorHardwareRequest");
    }
}
