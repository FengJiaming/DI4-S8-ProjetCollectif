<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ConnectionHook
{
    /**
     * The connection hook is based on the URI of the requested controller
     */
    public function pre_controller()
    {
        log_message("info", "ConnectionHook triggered");


        $ci =& get_instance();

        $ci->load->helper('url');
        $ci->load->library('ConnectionService');

        // Get the routed uri
        $type = $ci->uri->ruri_string();

        // if the rooted URI point to a Controller that need authentication
        $requestAdminConnected = stripos($type, 'AdminController');
        $requestConnected = $requestAdminConnected || stripos($type, 'UserController');

        if($requestConnected)
        {
            log_message("info", "ConnectionHook detected a request to URL that require authentication");

            // Check if the user is connected
            if(!$ci->connectionservice->isUserConnected()) {

                log_message("info", 'The user is not connected. Redirect him to login page');
                // redirect to the connection page
                redirect('ConnectionController/login');
            }

            if($requestAdminConnected)
            {
                $user = $ci->connectionservice->getConnectedUser();

                if($user["type"] !== "ADMIN")
                {
                    log_message("info", 'The user is connected but is not a admin. Redirect him to login page');

                    show_error('Admin page required but the connected user is not admin', 403);
                }
            }
        }
    }
}