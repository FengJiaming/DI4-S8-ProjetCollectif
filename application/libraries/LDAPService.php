<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPPATH . 'libraries/AbstractService.php';

/**
 * Class LDAPService This class provides methods to connect to the ldap server and obtain user information.
 *
 * Each product can be borrowed for a period.
 */
class LDAPService extends AbstractService
{
    public function __construct()
    {
        parent::__construct();

        //Load LDAP configuration file
        $this->load->config('ldap_auth');

        $this->_init();
    }

    /**
     * This method initializes the LDAP service
     */
    private function _init()
    {
        // Verify that the LDAP extension has been loaded/built-in
        if (!function_exists('ldap_connect')) {
            show_error('LDAP functionality not present.  Either load the module ldap php module or use a php with ldap support compiled in.');
            log_message('error', 'LDAP functionality not present in php.');
        }

        //Extract information from the configuration file
        $this->hosts = $this->config->item('hosts');
        $this->ports = $this->config->item('ports');
        $this->basedn = $this->config->item('basedn');
        $this->organisation_unit = $this->config->item('organisation_unit');
        $this->auditlog = $this->config->item('auditlog');
        $this->password = $this->config->item('password');

    }

    /**
     * This method verifies whether the user exists on the ldap server and returns user information..
     *
     * The userinfo is a array of "user" including the following elements :
     *      - The user id
     *      - The firstname and lastname of the user
     *      - The email address
     *      - The type of the user
     *
     * @param String $username The name of the user
     * @return Array $user_info Array of user information stored
     * @throws Exception If the connection to the ldap server fails or the user authentication fails.
     */
    function login($username)
    {

        if($this->_authenticate($username)){

            // Set the user data
            $user_info = $this->getUserInfo($username);
            $user_info["id"] = $username;
            // Record the login
            $this->_audit("Successful login: " ."(" . $username . ") from " . $this->input->ip_address());

            return $user_info;
        } else {
            log_message('info', 'Error connecting to '.$username);
            throw new Exception("No such user found");
        }
    }

    /**
     * This method verifies whether the user exists on the ldap server and returns user information..
     *
     * @param String $username The name of the user
     * @return TRUE If we successfully bind to the ldap server with the $username
     * @throws Exception If the connection to the ldap server fails or the user authentication fails.
     */
    private function _authenticate($username)
    {

        foreach ($this->hosts as $host) {
            $this->ldapconn = ldap_connect($host, 389) or die("Could not connect to LDAP server.");
            if ($this->ldapconn) {
                break;
            } else {
                log_message('info', 'Error connecting to '.$host);
            }
        }
        // At this point, $this->ldapconn should be set.
        if (!$this->ldapconn) {
            log_message('error', "Couldn't connect to any LDAP servers.  Bailing...");
            show_error('Error connecting to your LDAP server(s).  Please check the connection and try again.');
        }

        // These to ldap_set_options are needed for binding to AD properly
        // They should also work with any modern LDAP service.
        ldap_set_option($this->ldapconn, LDAP_OPT_REFERRALS, 0);
        ldap_set_option($this->ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);

        // Now actually try to bind as the user
        if(!ldap_bind($this->ldapconn,'CN='.$username.',OU='.$this->organisation_unit.','.$this->basedn, $this->password)) {
            $this->_audit("Failed login attempt: ".$username." from ".$_SERVER['REMOTE_ADDR']);
            return FALSE;
        } else {
            return TRUE;
        }

    }

    /**
     * This method gets user information from LDAP server by given username.
     *
     * The userinfo is a array of "user" including the following elements :
     *      - The user id
     *      - The firstname and lastname of the user
     *      - The email address
     *      - The type of the user
     *
     * @param String $username The name of the user
     * @return Array $user_info Array of user information stored
     * @throws Exception If the connection to the ldap server fails or the user authentication fails.
     */
    private function getUserInfo($username){
        $search = ldap_search($this->ldapconn, $this->basedn, '(CN='.$username.')',array('userprincipalname', 'name', 'givenName'));
        $info = ldap_get_entries($this->ldapconn, $search);

        $firstname = $info[0]['name'][0];
        $lastname = $info[0]['givenname'][0];
        $email = $info[0]['userprincipalname'][0];

        $userinfo = array("id" => "", "firstname" => $firstname, "lastname" => $lastname, "email" => $email, "type" => "USER");
        return $userinfo;
    }

    /**
     * This method stores the error information in the log.
     *
     * @param String $msg Error message.
     * @return TRUE If the information is logged to the log ,else FALSE.
     */
    private function _audit($msg){
        $date = date('Y/m/d H:i:s');
        if( ! file_put_contents($this->auditlog, $date.": ".$msg."\n",FILE_APPEND)) {
            log_message('info', 'Error opening audit log '.$this->auditlog);
            return FALSE;
        }
        return TRUE;
    }

    //In order to test in the external network that cannot connect to LDAP
    private $mock_users = array(
        array("id" => "11111111", "firstname" => "Test1", "lastname" => "Test1", "email" => "test1@example.com", "type" => "ADMIN"),
        array("id" => "22222222", "firstname" => "Test2", "lastname" => "Test2", "email" => "test2@example.com", "type" => "USER"),
        array("id" => "33333333", "firstname" => "Test3", "lastname" => "Test3", "email" => "test3@example.com", "type" => "USER"),
        array("id" => "44444444", "firstname" => "Test4", "lastname" => "Test4", "email" => "test4@example.com", "type" => "USER"),
    );

    private $test = true;

    /**
     * This method allow us to verify the username in the test environment or under actual conditions.
     *
     * @param String $StudentID
     * @return $user_info When we connect wo LDAP server.
     * @return $user When we user mock_user.
     * @throws Exception
     */
    function findInfoById($StudentID )
    {
        if(!$this->test) {
            return $this->login($StudentID);
        } else {
            foreach ($this->mock_users as $user) {
                if ($user["id"] === $StudentID) {
                    return $user;
                }
            }
            throw new Exception("No such user found");
        }
    }
}