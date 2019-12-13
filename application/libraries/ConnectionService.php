<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPPATH . 'libraries/AbstractService.php';

class ConnectionService extends AbstractService
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library("LDAPService");
    }

    /**
     * Use this function to login
     * This will search the student id in the LDAP server and store the different configuration in the session
     *
     * @param String $login The student ID
     * @throws Exception When the Student id doesn't correspond to any line in the ldap service
     */
    public function login($login)
    {
        try {
            $user = $this->ldapservice->findInfoById($login);

            $this->internalLogUser($user);

        } catch (Exception $e) {
            log_message('error', 'The LDAP server doesnt know the requested student ID {$login}');
            throw new Exception("No student found for this login " . $login);
        }
    }

    /**
     * Store the user in the session or somewhere to keep him connected
     *
     * @param string $user The user to store information about
     */
    private function internalLogUser($user)
    {
        $this->session->set_userdata("connected_user", $user);
    }

    /**
     * Check if there is a connected user in the session
     *
     * @return bool True if the user is authenticated, else otherwise
     */
    public function isUserConnected()
    {
        return $this->session->has_userdata("connected_user");
    }


    /**
     * @return mixed The connected user
     * @throws Exception In the case the user is not connected
     */
    public function getConnectedUser()
    {
        if (!$this->isUserConnected()) {
            throw new Exception("User not connected");
        }

        return $this->session->userdata("connected_user");
    }

    /**
     * Unset the session inked to the connected user
     */
    public function logout()
    {
        $this->session->unset_userdata("connected_user");
    }
}