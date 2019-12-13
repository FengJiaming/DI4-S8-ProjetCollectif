<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AbstractService
{
    public function __construct()
    {
    }

    /**
     * This is used to use the CI var without defining another var
     *
     * @param string $var
     * @return mixed
     */
    public function __get($var)
    {
        return get_instance()->$var;
    }
}