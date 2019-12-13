<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPPATH . 'libraries/AbstractService.php';
/**
 * Class AdminUserInfoService This class provides methods to manage the superadmin.
 *
 **/
class AdminUserInfoService extends AbstractService
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model("Administrator", "administrator");
        $this->load->library("LDAPService");
    }

    /**
     * This method insert an administrator in the database if isn't already an admin
     * @param $userNumber : the user number of the new admin
     */
    public function addAdmin($userNumber)
    {
        if($this->administrator->existsAdministrator($userNumber) != null)
        {
            $this->administrator->insertAdministrator($userNumber);
        }
    }

    /**
     * This method return all administrators contained in the database
     * @return mixed : an array of administrator with corresponding users info
     */
    public function getAllAdministrators()
    {
        $administrators = $this->administrator->getAdministrators();
        foreach ($administrators as $administrator)
        {
            $administrator->user = $this->ldapservice->findInfoById($administrator->userNumber);
        }

        return $administrators;
    }

    /**
     * This method return the admin which correspond to the given id
     * @param $id : the id to look for
     * @return mixed : the admin with user info or null if it doesn't exist
     */
    public function getAdminById($id)
    {
        $administrator = $this->administrator->getAdministratorById($id);
        $administrator->user = $this->ldapservice->findInfoById($administrator->userNumber);

        return $administrator;
    }

    /**
     * This method return the admin which correspond to the given user number
     * if this user is an admin, null otherwise
     * @param $userNumber : the user number to look for
     * @return mixed : the admin with user info or null if the user isn't an admin
     */
    public function getAdminByUserNumber($userNumber)
    {
        $administrator = $this->administrator->existsAdministrator($userNumber);
        $administrator->user = $this->ldapservice->findInfoById($administrator->userNumber);

        return $administrator;
    }

    /**
     * This method remove from the database the admin which correspond to the given user number
     * @param $userNumber : the user number to look for
     */
    public function removeAdminPermanently($userNumber)
    {
        $this->administrator->deleteAdministrator($userNumber);
    }
}