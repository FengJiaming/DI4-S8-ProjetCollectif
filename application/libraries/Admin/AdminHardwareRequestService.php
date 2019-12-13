<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPPATH . 'libraries/AbstractService.php';
/**
 * Class AdminHardwareRequestService This class provides methods for administator to manage requests from the user..
 *
 **/
class AdminHardwareRequestService extends AbstractService
{
    public function __construct()
    {
        parent::__construct();

        // Load models
        $this->load->model("ProductRequest", "productrequest");


    }

	/**
	 * This method return all unread requests contained in the data base
	 * @return mixed : an array of unread request
	 */
	public function getUnreadRequests()
	{
		return $this->productrequest->getUnreadRequests();
	}


	/**
	 * This method return all unread requests contained in the data base
	 * @return mixed : an array of unread request
	 */
	public function getReadRequests()
	{
		return $this->productrequest->getReadRequests();
	}

    /**
     * This method return all unread requests contained in the data base
     * @return int : number of the unread requests.
     */
	public function countUnreadRequests(){
		return $this->productrequest->countUnreadRequests();
	}

    /**
     * This method marks the request as read
     * @param $id : the id of the request
     */
	public function readRequest($id)
    {
        $this->productrequest->setRequestRead($id);
    }

    /**
     * This method marks the request as unread
     * @param $id : the id of the request
     */
    public function unreadRequest($id)
    {
        $this->productrequest->setRequestUnread($id);
    }
}
