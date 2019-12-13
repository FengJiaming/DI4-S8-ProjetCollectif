<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH. "/libraries/REST_Controller.php";
use Restserver\Libraries\REST_Controller;

class BasketRestUserController extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('ConnectionService');
        $this->load->library('User/BasketService');
    }

    public function updateLine_put($basketId)
    {
        log_message("debug", "Update basket line id:" . $basketId);

        $startDate = $this->put('startDate');
        $endDate = $this->put('endDate');

        if(!isset($startDate) || !isset($endDate)) {
            $this->response(array(), REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        $this->basketservice->modifyBasket($basketId, $startDate, $endDate);

        $this->response(array(), REST_Controller::HTTP_OK);
    }
}