<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/

/**
 * Used to catch connection
 */
$hook['post_controller_constructor'][] = array(
    'class'    => 'ConnectionHook',
    'function' => 'pre_controller',
    'filename' => 'ConnectionHook.php',
    'filepath' => 'hooks',
    'params'   => array()
);
