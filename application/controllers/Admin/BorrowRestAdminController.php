<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH. "/libraries/REST_Controller.php";
use Restserver\Libraries\REST_Controller;

class BorrowRestAdminController extends REST_Controller
{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);

        $this->load->library('Admin/AdminBorrowingService');
    }

    /**
     * This method return the hardware borrows with the associated user which are not returned
     * @reponse $borrows : an array of hardware borrows
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
     */
    public function AdminGetCurrentHardwareBorrowingList_get()
    {



        $this->response($this->adminborrowingservice->getCurrentHardwareBorrowings());
    }

    /**
     * This method return the consumables borrows with the associated user which are not returned
     * @reponse $borrows : an array of consumables borrows
     *              id : the borrow id
     *              designation : the designation of the consumable borrowed
     *              startDate : the begin of the borrow
     *              endDate : the supposed end of the borrow
     *              renderDate : the real end of the borrow
     *              adminComment : the comment let by the administrator
     *              userComment : the comment let by the user
     *              userNumber : the id number of the user
     *              remainingTime : the difference between the end date and today
     */
    public function AdminGetCurrentConsumableBorrowingList_get()
    {
        $this->response($this->adminborrowingservice->getCurrentConsumableBorrowings());
    }

    /**
     * This method return all hardware borrows with the associated user
     * @reponse $borrows : an array of hardware borrows
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
     */
    public function AdminGetHistoryHardwareBorrowingList_get()
    {
        $this->response($this->adminborrowingservice->getHardwareBorrowingsHistory());
    }

    /**
     * This method return all consumables borrows with the associated user
     * @reponse $borrows : an array of consumables borrows
     *              id : the borrow id
     *              designation : the designation of the consumable borrowed
     *              startDate : the begin of the borrow
     *              endDate : the supposed end of the borrow
     *              renderDate : the real end of the borrow
     *              adminComment : the comment let by the administrator
     *              userComment : the comment let by the user
     *              userNumber : the id number of the user
     *              remainingTime : the difference between the end date and today
     */
    public function AdminGetHistoryConsumableBorrowingList_get()
    {
        $this->response($this->adminborrowingservice->getConsumableBorrowingsHistory());
    }

    /**
     * This method allow to an administrator to refuse an hardware borrow
     * @post userNumber : the id number of the user
     * @post barCode : the bar code of the hardware borrowed
     * @post startDate : the begin of the borrow
     * @post endDate : the supposed end of the borrow
     * @post refused : tell if the borrow is refused
     * @throws Exception : if a parameter is null
     */
    public function AdminSetHardwareBorrowingRefused_post()
    {
        $borrowInfo = array();
        $borrowInfo["userNumber"] = $this->post("userNumber");
        $borrowInfo["barCode"] = $this->post("barCode");
        $borrowInfo["startDate"] = $this->post("startDate");
        $borrowInfo["endDate"] = $this->post("endDate");
        $borrowInfo["refused"] = $this->post("refused");

        if($borrowInfo["userNumber"] != null && $borrowInfo["barCode"] != null && $borrowInfo["startDate"] != null && $borrowInfo["endDate"] != null && $borrowInfo["refused"] != null)
        {
            $borrow = $this->adminborrowingservice->getHardwareBorrowingByUserDateAndBarCode($borrowInfo["userNumber"], $borrowInfo["barCode"], $borrowInfo["startDate"], $borrowInfo["endDate"]);
            $this->adminborrowingservice->setRefusedHardwareBorrowing($borrow->id, $borrowInfo["refused"]);
        }
        else
        {
            log_message("error", "RefuseHardwareBorrowing : all fields has to be fulfilled");
            throw new Exception("RefuseHardwareBorrowing : all fields has to be fulfilled");
        }
    }

    /**
     * This method set an hardware borrow as ready to be picked up
     * @post userNumber : the id number of the user
     * @post barCode : the bar code of the hardware borrowed
     * @post startDate : the begin of the borrow
     * @post endDate : the supposed end of the borrow
     * @post ready : tell if the borrow is ready
     * @throws Exception : if a parameter is null
     */
    public function AdminSetHardwareBorrowingReady_post()
    {
        $borrowInfo = array();
        $borrowInfo["userNumber"] = $this->post("userNumber");
        $borrowInfo["barCode"] = $this->post("barCode");
        $borrowInfo["startDate"] = $this->post("startDate");
        $borrowInfo["endDate"] = $this->post("endDate");
        $borrowInfo["ready"] = $this->post("ready");

        if($borrowInfo["userNumber"] != null && $borrowInfo["barCode"] != null && $borrowInfo["startDate"] != null && $borrowInfo["endDate"] != null && $borrowInfo["ready"] != null)
        {
            $borrow = $this->adminborrowingservice->getHardwareBorrowingByUserDateAndBarCode($borrowInfo["userNumber"], $borrowInfo["barCode"], $borrowInfo["startDate"], $borrowInfo["endDate"]);
            $this->adminborrowingservice->setReady($borrow->id, $borrowInfo["ready"]);
        }
        else
        {
            log_message("error", "SetHardwareBorrowingReady : all fields has to be fulfilled");
            throw new Exception("SetHardwareBorrowingReady : all fields has to be fulfilled");
        }
    }

    /**
     * This method set an hardware borrow as picked up
     * @post userNumber : the id number of the user
     * @post barCode : the bar code of the hardware borrowed
     * @post startDate : the begin of the borrow
     * @post endDate : the supposed end of the borrow
     * @post pickedUp : tell if the borrow is picked up
     * @throws Exception : if a parameter is null
     */
    public function AdminSetHardwareBorrowingPickedUp_post()
    {
        $borrowInfo = array();
        $borrowInfo["userNumber"] = $this->post("userNumber");
        $borrowInfo["barCode"] = $this->post("barCode");
        $borrowInfo["startDate"] = $this->post("startDate");
        $borrowInfo["endDate"] = $this->post("endDate");
        $borrowInfo["pickedUp"] = $this->post("pickedUp");

        if($borrowInfo["userNumber"] != null && $borrowInfo["barCode"] != null && $borrowInfo["startDate"] != null && $borrowInfo["endDate"] != null && $borrowInfo["pickedUp"] != null)
        {
            $borrow = $this->adminborrowingservice->getHardwareBorrowingByUserDateAndBarCode($borrowInfo["userNumber"], $borrowInfo["barCode"], $borrowInfo["startDate"], $borrowInfo["endDate"]);
            $this->adminborrowingservice->setPickedUp($borrow->id, $borrowInfo["pickedUp"]);
        }
        else
        {
            log_message("error", "SetHardwareBorrowingPickedUp : all fields has to be fulfilled");
            throw new Exception("SetHardwareBorrowingPickedUp : all fields has to be fulfilled");
        }
    }

    /**
     * This method return the hardware borrow with the associated user which correspond to the given parameters
     * @post userNumber : the id number of the user
     * @post barCode : the bar code of the hardware borrowed
     * @post startDate : the begin of the borrow
     * @post endDate : the supposed end of the borrow
     * @reponse $borrow : the asked hardware borrow
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
     * @throws Exception : if a parameter is null
     */
    public function AdminGetHardwareBorrowing_post()
    {
        $borrowInfo = array();
        $borrowInfo["userNumber"] = $this->post("userNumber");
        $borrowInfo["barCode"] = $this->post("barCode");
        $borrowInfo["startDate"] = $this->post("startDate");
        $borrowInfo["endDate"] = $this->post("endDate");

        if($borrowInfo["userNumber"] != null && $borrowInfo["barCode"] != null && $borrowInfo["startDate"] != null && $borrowInfo["endDate"] != null)
        {
            $this->response($this->adminborrowingservice->getHardwareBorrowingByUserDateAndBarCode($borrowInfo["userNumber"], $borrowInfo["barCode"], $borrowInfo["startDate"], $borrowInfo["endDate"]));
        }
        else
        {
            log_message("error", "GetHardwareBorrowing : all fields has to be fulfilled");
            throw new Exception("GetHardwareBorrowing : all fields has to be fulfilled");
        }
    }

    /**
     * This method return the consumable borrow with the associated user which correspond to the given parameters
     * @post userNumber : the id number of the user
     * @post designation : the designation of the consumable borrowed
     * @post startDate : the begin of the borrow
     * @post endDate : the supposed end of the borrow
     * @reponse $borrows : the asked consumable borrow
     *              id : the borrow id
     *              designation : the designation of the consumable borrowed
     *              startDate : the begin of the borrow
     *              endDate : the supposed end of the borrow
     *              renderDate : the real end of the borrow
     *              adminComment : the comment let by the administrator
     *              userComment : the comment let by the user
     *              userNumber : the id number of the user
     *              remainingTime : the difference between the end date and today
     * @throws Exception : if a parameter is null
     */
    public function AdminGetConsumableBorrowing_post()
    {
        $borrowInfo = array();
        $borrowInfo["userNumber"] = $this->post("userNumber");
        $borrowInfo["designation"] = $this->post("designation");
        $borrowInfo["startDate"] = $this->post("startDate");
        $borrowInfo["endDate"] = $this->post("endDate");

        if($borrowInfo["userNumber"] != null && $borrowInfo["designation"] != null && $borrowInfo["startDate"] != null && $borrowInfo["endDate"] != null)
        {
            $this->response($this->adminborrowingservice->getConsumableBorrowingByUserDateAndDesignation($borrowInfo["userNumber"], $borrowInfo["designation"], $borrowInfo["startDate"], $borrowInfo["endDate"]));
        }
        else
        {
            log_message("error", "GetConsumableBorrowing : all fields has to be fulfilled");
            throw new Exception("GetConsumableBorrowing : all fields has to be fulfilled");
        }
    }

    /**
     * This method set an hardware borrow as returned and set the render date of the borrow
     * By default if the parameter render date is null it will be set as the current date
     * @post userNumber : the id number of the user
     * @post barCode : the bar code of the hardware borrowed
     * @post startDate : the begin of the borrow
     * @post endDate : the supposed end of the borrow
     * @post renderDate : the date of the return which is the current date by default if the parameter is null
     * @throws Exception : if a parameter is null except for the render date
     */
    public function AdminReturnHardwareBorrowing_post()
    {
        $borrowInfo = array();
        $borrowInfo["userNumber"] = $this->post("userNumber");
        $borrowInfo["barCode"] = $this->post("barCode");
        $borrowInfo["startDate"] = $this->post("startDate");
        $borrowInfo["endDate"] = $this->post("endDate");
        $borrowInfo["renderDate"] = $this->post("renderDate");

        if($borrowInfo["userNumber"] != null && $borrowInfo["barCode"] != null && $borrowInfo["startDate"] != null && $borrowInfo["endDate"] != null)
        {
            $borrow = $this->adminborrowingservice->getHardwareBorrowingByUserDateAndBarCode($borrowInfo["userNumber"], $borrowInfo["barCode"], $borrowInfo["startDate"], $borrowInfo["endDate"]);
            if($borrowInfo["renderDate"] == null)
            {
                $this->adminborrowingservice->returnHardwareBorrowing($borrow->id);
            }
            else
            {
                $this->adminborrowingservice->returnHardwareBorrowing($borrow->id, $borrowInfo["renderDate"]);
            }
        }
        else
        {
            log_message("error", "ReturnHardwareBorrowing : all fields has to be fulfilled");
            throw new Exception("ReturnHardwareBorrowing : all fields has to be fulfilled");
        }
    }

    /**
     * This method set a consumable borrow as returned and set the render date of the borrow
     * By default if the parameter render date is null it will be set as the current date
     * @post userNumber : the id number of the user
     * @post designation : the designation of the consumable borrowed
     * @post startDate : the begin of the borrow
     * @post endDate : the supposed end of the borrow
     * @post renderDate : the date of the return which is the current date by default if the parameter is null
     * @throws Exception : if a parameter is null except for the render date
     */
    public function AdminReturnConsumableBorrowing_post()
    {
        $borrowInfo = array();
        $borrowInfo["userNumber"] = $this->post("userNumber");
        $borrowInfo["designation"] = $this->post("designation");
        $borrowInfo["startDate"] = $this->post("startDate");
        $borrowInfo["endDate"] = $this->post("endDate");
        $borrowInfo["renderDate"] = $this->post("renderDate");

        if($borrowInfo["userNumber"] != null && $borrowInfo["designation"] != null && $borrowInfo["startDate"] != null && $borrowInfo["endDate"] != null)
        {
            $borrow = $this->adminborrowingservice->getConsumableBorrowingByUserDateAndDesignation($borrowInfo["userNumber"], $borrowInfo["designation"], $borrowInfo["startDate"], $borrowInfo["endDate"]);
            if($borrowInfo["renderDate"] == null)
            {
                $this->adminborrowingservice->returnConsumableBorrowing($borrow->id);
            }
            else
            {
                $this->adminborrowingservice->returnConsumableBorrowing($borrow->id, $borrowInfo["renderDate"]);
            }
        }
        else
        {
            log_message("error", "ReturnConsumableBorrowing : all fields has to be fulfilled");
            throw new Exception("ReturnConsumableBorrowing : all fields has to be fulfilled");
        }
    }
}
