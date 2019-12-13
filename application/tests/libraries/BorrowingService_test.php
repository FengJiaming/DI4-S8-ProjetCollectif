<?php

class BorrowingService_test extends TestCase
{
    private $borrowingService;

    public function setUp()
    {
        $this->resetInstance();
        $this->CI->load->library('User/BorrowingService');
        $this->borrowingService = $this->CI->borrowingservice;
    }

    public function test_transformToBorrowing()
    {
        $basket = array(
            "products" => array(
                array("endDate" => null, "startDate" => null, "productId" => 1),
                array("endDate" => null, "startDate" => null, "productId" => 2),
                array("endDate" => null, "startDate" => null, "productId" => 3),
            ),
            "user" => array("id" => "11111111", "username" => "Test1", "email" => "test1@example.com"),
        );

        MonkeyPatch::patchMethod(
            'productservice',
            array('getAllProductsBorrowable' => array((object) array('id' => 1111)))
        );

        $this->borrowingService->transformToBorrowing($basket);
    }
}