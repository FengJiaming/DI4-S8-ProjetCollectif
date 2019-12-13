<?php
/**
 * Created by PhpStorm.
 * User: Loïc Gervois
 * Date: 20/03/2019
 * Time: 11:52
 */

/**
 * Class HardwareBorrowing This class provides methods to do the operating of table HardwareBorrowing in database
 */
class HardwareBorrowing extends CI_Model
{
	private $id;
	private $startDate;
	private $endDate;
	private $renderDate;
	private $adminComment;
	private $userComment;
	private $userNumber;
	private $ready;
	private $pickedUp;
	private $canceled;

    // Foreign keys
    private $idHardware;

    /**
     * List all the hardwareBorrowings stored in the database
     *
     * @return array All the hardwareBorrowings stored in the database
     */
	public function getHardwareBorrowings() {
        $this->db->reset_query();

		$query = $this->db->get('hardwareBorrowing');
		return $query->result();
	}

public function getCurrentHardwareBorrowings(){
	$this->db->reset_query();

	$this->db->where('renderDate IS NULL', null, false);
	$query = $this->db->get('hardwareBorrowing');
	return $query->result();

}

    /**
     * Get all the hardware borrowings related to the given product id
     * @param $productId
     * @return mixed
     */
    public function getAvailableHardwareBorrowingsByProductId($productId) {
        $this->db->reset_query();

        $this->db->from('hardwareBorrowing');
        $this->db->join('hardware', 'hardware.barCode = hardwareBorrowing.idHardware');
        $this->db->where('hardware.idProduct', $productId);
        $this->db->where('hardware.reserved', false);
        $this->db->where('hardware.outOfService', false);
        $this->db->where('hardware.donation', false);

        $query = $this->db->get();

        return $query->result();
    }

    /**
     * Get the hardwareBorrowing which correspond to the given id
     *
     * @param $id Id of the hardwareBorrowing
     * @return HardwareBorrowing The HardwareBorrowing which correspond to the given Id
     */
	public function getHardwareBorrowingById($id) {
        $this->db->reset_query();

        $this->db->where('id', $id);
		$query = $this->db->get('hardwareBorrowing');
		return $query->row();
	}

    /**
     * Get the hardwareBorrowing which correspond to the given user number
     *
     * @param $userNumber The number of user who borrowed hardware
     * @return array All the hardwareBorrowings which correspond to the given user number
     */
	public function getHardwareBorrowingsByUserId($userNumber) {
        $this->db->reset_query();

		$this->db->where('userNumber', $userNumber);
		$query = $this->db->get('hardwareBorrowing');
		return $query->result();
	}

    /**
     * Insert a new hardwareBorrowing into the database
     *
     * @param $userNumber The number of user who borrowed hardware
     * @param $idHardware The id of hardware
     * @param $startDate  The start date of borrow
     * @param $endDate    The end date of borrow
     * @param $adminComment The comment of administrator
     * @param $userComment  The commet of user
     */
	public function insertHardwareBorrowing($userNumber,$idHardware, $startDate, $endDate, $adminComment, $userComment) {
        $this->db->reset_query();

		$data = array (
			'userNumber'=>$userNumber,
			'idHardware' => $idHardware,
			'startDate' => $startDate,
			'endDate' => $endDate,
			'renderDate' => NULL,
			'adminComment' => $adminComment,
			'userComment' => $userComment,
			'ready' => 0,
			'pickedUp' =>0

		);
		$this->db->insert('hardwareBorrowing',$data);
		return $this->db->insert_id();
	}

    /**
     * Delete a HardwareBorrowing
     *
     * @param $id The id of HardwareBorrowing
     */
	public function deleteHardwareBorrowing($id) {
        $this->db->reset_query();

		$this->db->delete('hardwareBorrowing', array('id' => $id));
	}

    /**
     * Set the render date
     *
     * @param $id Id of hardwareBorrowing
     * @param $renderDate The render date
     */
	public function setRenderDate( $id, $renderDate) {
        $this->db->reset_query();

	    $data = array (
	        'renderDate' => $renderDate
        );
	    $this->db->where('id', $id);
	    $this->db->update('hardwareBorrowing', $data);
    }

    /**
     * Set if it has picked up
     *
     * @param $id Id of hardwareBorrowing
     */
	public function setPickedUp($id) {
		$this->db->reset_query();

		$data = array (
			'pickedUp' => 1
		);
		$this->db->where('id', $id);
		$this->db->update('hardwareBorrowing', $data);
	}

    /**
     * Set if it hasn't picked up
     *
     * @param $id Id of hardwareBorrowing
     */
	public function setNotPickedUp($id)
    {
        $this->db->reset_query();

        $data = array (
            'pickedUp' => 0
        );
        $this->db->where('id', $id);
        $this->db->update('hardwareBorrowing', $data);
    }

    /**
     * Set it is ready
     *
     * @param $id Id of hardwareBorrowing
     */
	public function setReady ($id) {
		$this->db->reset_query();

		$data = array (
			'ready' => 1
		);
		$this->db->where('id', $id);
		$this->db->update('hardwareBorrowing', $data);
	}

    /**
     * Set it isn't ready
     *
     * @param $id Id of hardwareBorrowing
     */
	public function setNotReady($id)
    {
        $this->db->reset_query();

        $data = array (
            'ready' => 0
        );
        $this->db->where('id', $id);
        $this->db->update('hardwareBorrowing', $data);
    }

    /**
     * Set it is refused
     * @param $id : the id of the borrow
     */
	public function setRefused($id){
	    $this->db->reset_query();

	    $data = array(
	        'refused' => 1
        );
	    $this->db->where('id', $id);
	    $this->db->update('hardwareBorrowing', $data);
    }

    /**
     * Set it isn't refused
     * @param $id : the id of the borrow
     */
    public function setNotRefused($id)
    {
        $this->db->reset_query();

        $data = array(
            'refused' => 0
        );
        $this->db->where('id', $id);
        $this->db->update('hardwareBorrowing', $data);
    }

    /**
     * Return the hardware borrowing which correspond to the given user number, id hardware, start date and end date if it exist
     * @param $userNumber : the number of the user
     * @param $idHardware : the bar code of the hardware
     * @param $startDate : the start date of the borrow
     * @param $endDate : the en date of the borrow
     * @return mixed : the borrow
     */
    public function exists( $userNumber, $idHardware, $startDate, $endDate){
	$this->db->reset_query();

	$this->db->where('userNumber', $userNumber);
	$this->db->where('idHardware', $idHardware);
	$this->db->where('startDate', $startDate);
	$this->db->where('endDate', $endDate);
	$query = $this->db->get('hardwareBorrowing');
	return $query->row();
    }


}
