<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ConnectionController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('ConnectionService');
    }

    public function login()
    {
        $data = array();
        // set the login form rules
         $this->form_validation->set_rules('login', 'Login', 'required');

        if ($this->form_validation->run() == TRUE) {
            // The user filled the login form
            try {
                $this->connectionservice->login(set_value('login'));
                log_message('debug', 'Login success');

            } catch (Exception $e) {
                $data["error_message"] = "Erreur de connexion";
                log_message('info', 'User tried to connect and failed');
            }
        }

        // If the user is connected at this point, it mean the user can be redirected to user page
        if($this->connectionservice->isUserConnected()) {
            log_message('debug', 'Redirecting to user profil userpage');
            $user = $this->connectionservice->getConnectedUser();
            if($user["type"] === "ADMIN")
            {
                redirect("Admin/BorrowAdminController/AdministratorBorrowingList");
                return;
            }
            redirect('User/ProfilUserController/userPage');
            return;
        }

        // The user is not connected, we show the login page
        $this->load->view('LoginPage', $data);
    }

    public function logout()
    {
        $this->connectionservice->logout();

        redirect("ConnectionController/login");
    }
}