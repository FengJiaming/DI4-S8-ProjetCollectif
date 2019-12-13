<?php

require_once APPPATH. "/libraries/REST_Controller.php";
use Restserver\Libraries\REST_Controller;

class AdminListRestSuperAdminController extends REST_Controller
{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);

        $this->load->library('SuperAdmin/AdminUserInfoService');
    }

    /**
     * This method add a new administrator to the database
     * @post userNumber : the id number of the user to raise to admin level
     * @throws Exception : if a parameter is null
     */
    public function SuperAdminAddAdmin_post()
    {
        $admin = array();
        $admin["userNumber"] = $this->post("userNumber");

        if($admin["userNumber"] != null)
        {
            $this->adminuserinfoservice->addAdmin($admin["userNumber"]);
        }
        else
        {
            log_message("error", "AddAdmin : all fields has to be fulfilled");
            throw new Exception("AddAdmin : all fields has to be fulfilled");
        }
    }

    /**
     * This method remove admin rights to the user which correspond to the given user number
     * @param $userNumber : the user number to look for
     */
    public function SuperAdminRemoveAdmin_delete($userNumber)
    {
        $this->adminuserinfoservice->removeAdminPermanently($userNumber);
    }
}