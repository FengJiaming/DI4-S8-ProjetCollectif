<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config["sender"] = "materiel.emprunt@gmail.com";
$config["notsentemail"] = "materiel.emprunt+pasditribue@gmail.com";
$config["sendername"] = "'Service d\'emprunt de materiel'";
$config["wordwrap"] = FALSE;
$config["mailtype"] = "html";
$config["protocol"] = "smtp";
$config["smtp_host"] = "smtp.gmail.com";
$config["smtp_user"] = $config["sender"];
$config["smtp_pass"] = "Password2019";
$config["smtp_crypto"] = "ssl";
$config["smtp_port"] = 465;
$config["crlf"] = "\r\n";
$config["newline"] = "\r\n";
$config["admin_email"] = $config["sender"];

// Set this to TRUE if you want to send email.
// If set to FALSE the email service functions will always return without having send the email
$config["send_email"] = TRUE;