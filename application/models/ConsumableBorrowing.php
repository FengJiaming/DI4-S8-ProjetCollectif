<?php
/**
 * Created by PhpStorm.
 * User: Loïc Gervois
 * Date: 20/03/2019
 * Time: 11:52
 */

/**
 * Class ConsumableBorrowing This class provides methods to do the operating of table ConsumableBorrowing in database
 */
class ConsumableBorrowing extends CI_Model
{
    private $id;
    private $designation;
    private $startDate;
    private $endDate;
    private $renderDate;
    private $adminComment;
    private $userComment;
    private $userNumber;

	/**
	 * List all the consumable borrowings stored in the database
	 *
	 * @return array Array which contains all the consumableBorrowings
	 */
    public function getConsumableBorrowings() {
        $this->db->reset_query();

        $query = $this->db->get('ConsumableBorrowing');
        return $query->result();
    }

public function getCurrentConsumableBorrowings(){
	$this->db->reset_query();
	$this->db->where('renderDate IS NULL', null, false);
	$query = $this->db->get('ConsumableBorrowing');
	return $query->result();
}


	/**
	 * Get the consumable borrowing corresponding to the given ID
	 *
	 * @param $id Id of the consumable borrowing
	 * @return  The consumable borrowing which correspond to the given ID
	 */
    public function getConsumableBorrowingById($id) {
        $this->db->reset_query();

        $this->db->where('id', $id);
        $query = $this->db->get('ConsumableBorrowing');

        return $query->row();
    }

	/**
	 * List all the consumable borrowings of the given user
	 *
	 * @param $userId Id of the user
	 * @return Return the list of consumableBorrowings of the given user
	 */
    public function getConsumableBorrowingsByUserId($userId) {
        $this->db->reset_query();
        $this->db->where('userNumber', $userId);
		$query = $this->db->get('consumableBorrowing');
        return $query->result();
    }

	/**
	 * Insert a new consumableBorrowing in the database
	 *
	 * @param $userNumber id of the user which is borrowing /!\
	 * @param $designation
	 * @param $startDate
	 * @param $endDate
	 * @param $renderDate
	 * @param $adminComment
	 * @param $userComment
	 * @return The Id of the consumableBorrowing inserted in the database
	 */
    public function insertConsumableBorrowing($userNumber, $designation,
                                              $startDate, $endDate, $renderDate, $adminComment, $userComment) {
        $this->db->reset_query();

        $data = array (
            'userNumber' => $userNumber,
            'designation' => $designation,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'renderDate' => $renderDate,
            'adminComment' => $adminComment,
            'userComment' => $userComment,
        );
        $this->db->insert('consumableBorrowing', $data);
		return $this->db->insert_id();
    }

	/**
	 * Modify an existing consumableBorrowing
	 * /!\ the consumableBorrowing must exist in the database
	 *
	 * @param $id Id of the consumableBorrowing to modify
	 * @param $userNumber
	 * @param $designation
	 * @param $startDate
	 * @param $endDate
	 * @param $adminComment
	 * @param $userComment
	 */
    public function updateConsumableBorrowing($id,$userNumber, $designation,
											   $startDate, $endDate, $adminComment, $userComment) {
        $this->db->reset_query();

		$data = array (
			'userNumber', $userNumber,
			'designation' => $designation,
			'startDate' => $startDate,
			'endDate' => $endDate,
			'renderDate' => NULL,
			'adminComment' => $adminComment,
			'userComment' => $userComment,
		);
		$this->db->where('id',$id);
		$this->db->update('consumableBorrowing', $data);
    }

	/**
	 * Delete the consumableBorrowing which correspond to the given id
	 *
	 * @param $idBorrowing Id of the consumableBorrowing to delete
	 */
    public function deleteConsumableBorrowing($id) {
        $this->db->reset_query();

		$this->db->delete('consumableBorrowing', array('id' => $id));
    }

	/**
	 * Set the renderDate of the consumableBorrowing which correspond to the given ID
	 *
	 * @param $id
	 * @param $renderDate
	 */
    public function setRenderDate($id, $renderDate) {
        $this->db->reset_query();

        $data = array (
            'renderDate' => $renderDate
        );
        $this->db->where('id', $id);
        $this->db->update('consumableBorrowing', $data);
    }

    /**
     * Return the hardware borrowing which correspond to the given user number, designation, start date and end date if it exist
     * @param $userNumber : the number of the user
     * @param $designation : the designation of the consumable
     * @param $startDate : the start date of the borrow
     * @param $endDate : the en date of the borrow
     * @return mixed : the borrow
     */
    public function exists( $userNumber, $designation, $startDate, $endDate){
        $this->db->reset_query();

        $this->db->where('userNumber', $userNumber);
        $this->db->where('designation', $designation);
        $this->db->where('startDate', $startDate);
        $this->db->where('endDate', $endDate);
        $query = $this->db->get('consumableBorrowing');
        return $query->row();
    }
}
