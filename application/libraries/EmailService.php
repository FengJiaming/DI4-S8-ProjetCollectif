<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPPATH . 'libraries/AbstractService.php';

class SendMailConfigException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

class EmailService extends AbstractService
{
    private $email_settings;

    public function __construct()
    {
        parent::__construct();

        try {
            $this->load->library('parser');
            $this->load->library('email');

            $this->config->load('email', TRUE);

            $email_settings = $this->config->item('email');

            $this->email_settings = $email_settings;

            $config_array = array(
                "wordwrap" => $email_settings["wordwrap"],
                "mailtype" => $email_settings["mailtype"],
                "protocol" => $email_settings["protocol"],
                "smtp_host" => $email_settings["smtp_host"],
                "smtp_user" => $email_settings["smtp_user"],
                "smtp_pass" => $email_settings["smtp_pass"],
                "smtp_crypto" => $email_settings["smtp_crypto"],
                "smtp_port" => $email_settings["smtp_port"],
                "newline" => $email_settings["newline"],
                "crlf" => $email_settings["crlf"]
            );

            $this->email->initialize($config_array);
        } catch (Throwable $t) {
            log_message("error", "error sending email. exception is :" . $t->getMessage());
        }
    }

    private function resetMail()
    {
        if (!$this->email_settings["send_email"]) {
            log_message("info", "Email not sent because the send_email config is set to false");
            throw new SendMailConfigException();
        }

        $this->email->clear(TRUE);

        $this->email->from($this->email_settings["sender"], $this->email_settings["sendername"], $this->email_settings["notsentemail"]);
    }

    /**
     * This method send a email to the given client email.
     * This need (into the data array param) the following product information :
     *  isConsumable : boolean value to check if the product is a consumable
     *  designation : The product name or the consumable description
     *  barcode : The inventory Id of the given hardware
     *  startDate : the start date of the borrow
     *  endDate : the end date of the borrow
     *
     * @param $clientEmail
     * @param $data
     */
    public function sendBorrowConfirmationEmail($clientEmail, $data)
    {
        try {
            $this->resetMail();

            $this->email->to($clientEmail);

            $this->email->subject('Confirmation d\'emprunt');
            $this->email->message($this->parser->parse('email/borrow_confirmation', $data, TRUE));

            if (!$this->email->send(FALSE)) {
                throw new Exception($this->email->print_debugger());
            }
        } catch (SendMailConfigException $t) {
        } catch (Throwable $t) {
            log_message("error", "error sending email. exception is :" . $t->getMessage());
        }
    }

    public function sendBorrowReadyEmail($clientEmail, $data)
    {
        try {
            $this->resetMail();

            $this->email->to($clientEmail);

            $this->email->subject('Votre emprunt est prêt');
            $this->email->message($this->parser->parse('email/borrow_ready', $data, TRUE));

            if (!$this->email->send(FALSE)) {
                throw new Exception($this->email->print_debugger());
            }
        } catch (SendMailConfigException $t) {
        } catch (Throwable $t) {
            log_message("error", "error sending email. exception is :" . $t->getMessage());
        }
    }

    public function sendFewDaysLeftEmail($clientEmail, $data)
    {
        try {
            $this->resetMail();

            $this->email->to($clientEmail);

            $this->email->subject('Attention : Il vous reste peu de temps');
            $this->email->message($this->parser->parse('email/borrow_few_time_left', $data, TRUE));

            if (!$this->email->send(FALSE)) {
                throw new Exception($this->email->print_debugger());
            }
        } catch (SendMailConfigException $t) {
        } catch (Throwable $t) {
            log_message("error", "error sending email. exception is :" . $t->getMessage());
        }
    }

    public function sendLateBorrowEmail($clientEmail, $data)
    {
        try {
            $this->resetMail();

            $this->email->to($clientEmail);

            $this->email->subject('Attention : Vous avez un retard');
            $this->email->message($this->parser->parse('email/borrow_late', $data, TRUE));

            if (!$this->email->send(FALSE)) {
                throw new Exception($this->email->print_debugger());
            }
        } catch (Throwable $t) {
            log_message("error", "error sending email. exception is :" . $t->getMessage());
        }
    }

    public function sendProductRequest($clientEmail, $data)
    {
        try {
            $this->resetMail();

            $this->email->to($this->email_settings["admin_email"]);
            $this->email->reply_to($clientEmail);

            $this->email->subject('Nouvelle demande de matériel');
            $this->email->message($this->parser->parse('email/product_request', $data, TRUE));

            if (!$this->email->send(FALSE)) {
                throw new Exception($this->email->print_debugger());
            }
        } catch (SendMailConfigException $t) {
        } catch (Throwable $t) {
            log_message("error", "error sending email. exception is :" . $t->getMessage());
            throw $t;
        }
    }
}