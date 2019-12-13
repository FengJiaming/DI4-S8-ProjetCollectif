<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| LDAP_Configuration
| -------------------------------------------------------------------
| This file will contain the settings needed to access the LDAP server.
|
*/
$config['hosts'] = array('10.236.5.1');//set this to your LDAP server name.
$config['ports'] = 389;
$config['basedn'] = 'DC=mydomain,DC=local';
$config['organization_unit'] = 'Projet_GestionInventaireDII';
$config['auditlog'] = 'application/logs/audit.log';  // Some place to log attempted logins (separate from message log)
$config['password'] = 'azerty37!';
?>