<?php
/**
 * Created by PhpStorm.
 * User: Loïc Gervois
 * Date: 20/03/2019
 * Time: 11:53
 */

/**
 * Class ProductRequest This class provides methods to do the operating of table Request in database
 */
class ProductRequest extends CI_Model
{
    private $id;
    private $userNumber;

    // Object of the message
	private $date;
    private $productType;
    private $message;
    private $read;

    /**
     * List all the requests stored in the database
     *
     * @return array All the requests stored in the database
     */
    public function getRequests() {
        $this->db->reset_query();

        $query = $this->db->get('request');
        return $query->result();
    }

	/**
     * Get all the unread requests from the database
     *
	 * @return array All the unread requests from the database
	 */
    public function getUnreadRequests() {
        $this->db->reset_query();

        $this->db->where('read', 0);
        $query = $this->db->get('request');
        return $query->result();
    }

	/**
	 * Get all the read requests from the database
	 *
	 * @return array All the read requests from the database
	 */
	public function getReadRequests() {
		$this->db->reset_query();

		$this->db->where('read', 1);
		$query = $this->db->get('request');
		return $query->result();
	}


	/**
	 * Get all the unread requests from the database
	 *
	 * @return array All the unread requests from the database
	 */
	public function countUnreadRequests() {
		$this->db->reset_query();
		$this->db->where('read', 0);
		return $this->db->count_all_results('request');
	}


	/**
	 * Set the request as read
	 * @param $id : the id of the request
	 */
	public function setRequestRead($id)
	{
		$this->db->reset_query();

		$data = array(
			'read' => 1
		);
		$this->db->where('id', $id);
		$this->db->update('request', $data);
	}


	/**
	 * Set the request as unread
	 * @param $id : the id of the request
	 */
	public function setRequestUnread($id)
	{
		$this->db->reset_query();

		$data = array(
			'read' => 0
		);
		$this->db->where('id', $id);
		$this->db->update('request', $data);
	}

	/**
	 * Get the request which correspond to the given id
	 *
	 * @param $id Id of the request
	 * @return request The request which correspond to the given Id
	 */
	public function getRequestById($id) {
		$this->db->reset_query();

		$this->db->where('id', $id);
		$query = $this->db->get('request');
		return $query->row();
	}

    /**
     * Insert a new request into the database
     *
     * @param $userNumber The userNumber of request
     * @param $productType The productType of request
     * @param $message The message of request
     */
    public function insertRequest($userNumber, $productType, $message) {
        $this->db->reset_query();

        $data = array (
            'userNumber' => $userNumber,
            'productType' => $productType,
            'message' => $message,
			'date' => date("Y-m-d")
        );
        $this->db->insert('request', $data);
    }

    /**
     * Delete a request
     *
     * @param $id Id of the request
     */
    public function deleteRequest($id) {
        $this->db->reset_query();

        $this->db->delete('request', array('id' => $id));
    }





}
