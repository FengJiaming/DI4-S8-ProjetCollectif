<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPPATH . 'libraries/AbstractService.php';
/**
 * Class AdminBorrowingService This class provides methods for administator to handle the operations "Borrowing".
 *
 **/
class AdminBorrowingService extends AbstractService
{
    public function __construct()
    {
        parent::__construct();

        // Load models
        $this->load->model("ConsumableBorrowing", "consumableborrowing");
        $this->load->model("HardwareBorrowing", "hardwareborrowing");
        $this->load->library("Admin/AdminCategoryService");
        $this->load->library("User/ProductService");
        $this->load->library("Admin/AdminHardwareService");
        $this->load->library("LDAPService");

    }

    /**
     * This method create a borrowing for consumable products. The borrow is attached to th given user.
     *
     * The $consumables is a array of product. Each product have the following information:
     *      - designation The consumable designation
     *      - startDate The borrow start date
     *      - endDate The borrow end date
     *      - adminComment The admin comment (possibly state of the consumable or other observations)
     *      - user comment
     * @param string $userId The user id to attach the borrow to
     * @param array $consumables The consumable product to borrow
     * @throws Exception If the borrow cannot be created because of unexpected error
     */
    public function addConsumableBorrowing($userId, $consumables)
    {
        log_message("info", "Borrow a consumable product");

        // Critical section start (transaction)
        $this->db->trans_start();

        foreach ($consumables as $consumable) {
            $this->consumableborrowing->insertConsumableBorrowing($userId, $consumable["designation"], $consumable["startDate"], $consumable["endDate"], $consumable['renderDate'], $consumable["adminComment"], $consumable["userComment"]);

        }


        // End of critical section
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            // The transaction failed
            log_message("error", "Can't create the borrow for consumable product (query is : " . $this->db->last_query() . ")");
            throw new Exception("Error during addConsumableBorrowing");
        }

        // send emails to the user and administrator
        // TODO send email
    }

    /**
     * This method create a borrowing for hardware products. The borrow is attached to th given user.
     *
     * The $hardwares is a array of product. Each product have the following information:
     *        - barcode
     *      - startDate The borrow start date
     *      - endDate The borrow end date
     *      - adminComment The admin comment (possibly state of the consumable or other observations)
     *        - renderDate The borrow render date
     *      - user comment
     * @param string $userId The user id to attach the borrow to
     * @param array $hardwares The consumable product to borrow
     * @throws Exception If the borrow cannot be created because of unexpected error
     */
    public function addHardwareBorrowing($userId, $hardwares)
    {
        log_message("info", "Borrow a hardware product");

        // Critical section start (transaction)
        $this->db->trans_start();

        foreach ($hardwares as $hardware) {

        	if(!$this->hardwareborrowing->exists( $userId, $hardware['barcode'], $hardware['startDate'], $hardware['endDate'])){
				$borrowId = $this->hardwareborrowing->insertHardwareBorrowing($userId, $hardware['barcode'], $hardware['startDate'], $hardware['endDate'], $hardware['adminComment'], $hardware['userComment']);
				if ($hardware['renderDate'] !== "") {
					$this->hardwareborrowing->setRenderDate($borrowId, $hardware['renderDate']);
				}
			}else{
				throw new Exception("The hardware borrowing already exists");
			}



        }

        // End of critical section
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            // The transaction failed
            log_message("error", "Can't create the borrow for hardware product (query is : " . $this->db->last_query() . ")");
            throw new Exception("Error during addConsumableBorrowing");
        }

        //TODO send email
    }

    /**
     * Fetch all the hardware borrows
     *
     * @return mixed The array of hardware borrows
     * @throws Exception
     */
    public function getHardwareBorrowingsHistory()
    {
        log_message("info", "getHardwareBorrowingsHistory");

        $hardwareBorrowings = $this->hardwareborrowing->getHardwareBorrowings();

        foreach ($hardwareBorrowings as &$borrow) {
            $hardware = $this->adminhardwareservice->getHardwareByBarcode($borrow->idHardware);
            $product = $this->productservice->getProductById($hardware->idProduct);
            $borrow->productName = $product->name;
            $category = $this->admincategoryservice->getCategoryById($product->idCategory);
            $borrow->categoryName = $category->name;
            $borrow->remainingTime = date_diff(new DateTime(), new DateTime($borrow->endDate));
        }

        return $hardwareBorrowings;
    }

    /**
     * Fetch all the consumable borrows
     *
     * @return mixed The array of consumable borrows
     * @throws Exception
     */
    public function getConsumableBorrowingsHistory()
    {
        log_message("info", "getConsumableBorrowingsHistory");

        $consumableBorrows = $this->consumableborrowing->getConsumableBorrowings();

        foreach ($consumableBorrows as &$borrow) {
            $borrow->user = $this->ldapservice->findInfoById($borrow->userNumber);
            $borrow->remainingTime = date_diff(new DateTime(), new DateTime($borrow->endDate));
        }

        return $consumableBorrows;
    }

    /**
     * Return all the current hardware borrows
     *
     * @return array Array of hardware borrows
     * @throws Exception
     */
    public function getCurrentHardwareBorrowings()
    {
        log_message("info", "getCurrentHardwareBorrowings");

        $hardwareBorrows = $this->getHardwareBorrowingsHistory();

        return array_filter($hardwareBorrows, function ($elem) {

            // maybe change with property_exists
            if (!isset($elem->renderDate)) {
                return true;
            }

            return $elem->renderDate == null;
        });
    }

    /**
     * Return all the current consumable borrows
     *
     * @return array Array of consumable borrows
     * @throws Exception
     */
    public function getCurrentConsumableBorrowings()
    {
        log_message("info", "getCurrentConsumableBorrowings");

        return $consumableBorrows = $this->getConsumableBorrowingsHistory();

        return array_filter($consumableBorrows, function ($elem) {


            if (!isset($elem->renderDate) ) {
                return true;
            }

            return $elem->renderDate == null;
        });
    }

    /**
     * Return the hardware borrowing which correspond to the given user number, id hardware, start date and end date if it exist
     * @param $userNumber : the number of the user
     * @param $barCode : the bar code of the hardware
     * @param $startDate : the start date of the borrow
     * @param $endDate : the en date of the borrow
     * @return mixed : the borrow
     */
    public function getHardwareBorrowingByUserDateAndBarCode($userNumber, $barCode, $startDate, $endDate)
    {
        return $this->hardwareborrowing->exists($userNumber, $barCode, $startDate, $endDate);
    }

    /**
     * Return the hardware borrowing which correspond to the given user number, designation, start date and end date if it exist
     * @param $userNumber : the number of the user
     * @param $designation : the designation of the consumable
     * @param $startDate : the start date of the borrow
     * @param $endDate : the en date of the borrow
     * @return mixed : the borrow
     */
    public function getConsumableBorrowingByUserDateAndDesignation($userNumber, $designation, $startDate, $endDate)
    {
        return $this->consumableborrowing->exists($userNumber, $designation, $startDate, $endDate);
    }

    /**
     * This method set the borrow state to refused
     *
     * You can only use this method to refuse a hardware borrow
     *
     * @param int $borrowId The hardware borrow id
     * @param $refused
     * @throws Exception When the $borrowId doesn't refer to any id in the db
     */
    public function setRefusedHardwareBorrowing($borrowId, $refused)
    {
        $hardwareBorrow = $this->hardwareborrowing->getHardwareBorrowingById($borrowId);

        if ($hardwareBorrow == null) {
            throw new Exception("refuseHardwareBorrowing : The given hardware borrow id doesn't exist");
        }

        if($refused)
        {
            $this->hardwareborrowing->setRefused($hardwareBorrow->id);
        }
        else
        {
            $this->hardwareborrowing->setNotRefused($hardwareBorrow->id);
        }

        // TODO method to cancel borrow
    }

    /**
     * This method can be used to return hardware borrowing
     * @param int $borrowId The hardware borrowing id
     * @param DateTime $renderDate The render date (by default null == the current date)
     * @throws Exception If the current date can't be accessed
     */
    public function returnHardwareBorrowing($borrowId, $renderDate = null)
    {
        if ($renderDate == null) {
            $renderDate = new DateTime();
        }

        $this->hardwareborrowing->setRenderDate($borrowId, $renderDate);

        //TODO send email (maybe not, let's discuss it)
    }

    /**
     * This method can be used to return consumable borrowing
     * @param int $borrowId The consumable borrowing id
     * @param DateTime $renderDate The render date (by default null == the current date)
     * @throws Exception If the current date can't be accessed
     */
    public function returnConsumableBorrowing($borrowId, $renderDate = null)
    {
        if ($renderDate == null) {
            $renderDate = new DateTime();
        }

        $this->consumableborrowing->setRenderDate($borrowId, $renderDate);
        //TODO send email (maybe not, let's discuss it)
    }

    /**
     * This method is used to set the ready flag of a hardware borrowing
     * @param int $borrowId The hardware borrow id
     * @param $ready
     */
    public function setReady($borrowId, $ready)
    {
        if($ready)
        {
            $this->hardwareborrowing->setReady($borrowId);
        }
        else
        {
            $this->hardwareborrowing->setNotReady($borrowId);
        }

        //TODO send email
    }

    /**
     * This method is used to set the picked up flag of a hardware borrowing
     * @param int $borrowId The hardware borrow id
     */
    public function setPickedUp($borrowId, $pickdeUp)
    {
        if($pickdeUp)
        {
            $this->hardwareborrowing->setReady($borrowId);
            $this->hardwareborrowing->setPickedUp($borrowId);
        }
        else
        {
            $this->hardwareborrowing->setNotPickedUp($borrowId);
        }

        //TODO send email (maybe ? )
    }
}
