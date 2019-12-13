<?php
include_once APPPATH . 'tests/TestUtilities.php';

class AdminHardwareService_test extends TestCase
{
    private $adminHardwareService;

    public function setUp()
    {
        $this->resetInstance();
        $this->CI->load->library('Admin/AdminHardwareService');
        $this->adminHardwareService = $this->CI->adminhardwareservice;

        $this->CI->load->model("category");
        $this->CI->load->model("product");
        $this->CI->load->model("hardware");

        $this->CI->db->trans_start();
        TestUtilities::deleteAllDbData($this->CI);

        $this->CI->category->insertCategory("C1");
        $category = $this->CI->category->getCategoriesByName("C1");
        $this->CI->product->insertProduct("product1", "none", $category[0]->id);
        $product = $this->CI->product->getProductsByName("product1");
        $this->CI->hardware->insertHardware(1, "none", $product[0]->id);
        $this->CI->hardware->insertHardware(2, "none", $product[0]->id);
        $this->CI->hardware->insertHardware(3, "none", $product[0]->id);
        $this->CI->hardware->insertHardware(4, "none", $product[0]->id);
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->CI->db->trans_rollback();
    }

    public function test_addHardware()
    {
        $expected = array("barCode" => 5, "comment" => "none", "reserved" => 0, "outOfService" => 0);
        $product = $this->CI->product->getProductsByName("product1");
        $this->adminHardwareService->addHardware($expected["barCode"], $expected["comment"], $product[0]->id);
        $hardware = $this->adminHardwareService->getHardwareByBarcode($expected["barCode"]);

        $this->assertEquals($expected["barCode"], $hardware->barCode);
        $this->assertEquals($expected["comment"], $hardware->comment);
        $this->assertEquals($expected["reserved"], $hardware->reserved);
        $this->assertEquals($expected["outOfService"], $hardware->outOfService);

        try {
            $this->adminHardwareService->addHardware($expected["barCode"], $expected["comment"], $product[0]->id);
            $this->fail("The hardware {$expected["barCode"]} is supposed to be already created");
        } catch (Exception $exception) {}
    }

    public function test_modifyHardware()
    {
        $expected = array("barCode" => 1, "comment" => "was modified", "reserved" => 0, "outOfService" => 0);
        $product = $this->CI->product->getProductsByName("product1");
        $this->adminHardwareService->modifyHardware($expected["barCode"], $expected["comment"]);
        $hardware = $this->adminHardwareService->getHardwareByBarcode($expected["barCode"]);

        $this->assertEquals($expected["comment"], $hardware->comment);
        $this->assertEquals($expected["reserved"], $hardware->reserved);
        $this->assertEquals($expected["outOfService"], $hardware->outOfService);
    }

    public function test_getHardwareByBarcode()
    {
        $expected = array("barCode" => 2, "comment" => "none", "reserved" => 0, "outOfService" => 0);
        $hardware = $this->adminHardwareService->getHardwareByBarcode($expected["barCode"]);

        $this->assertEquals($expected["comment"], $hardware->comment);
        $this->assertEquals($expected["reserved"], $hardware->reserved);
        $this->assertEquals($expected["outOfService"], $hardware->outOfService);
    }

    public function test_getAllHardware()
    {
        $hardware = $this->adminHardwareService->getAllHardware();

        $this->assertEquals(4, sizeof($hardware));
    }

    public function test_getAllBorrowedHardware()
    {
        $hardware = $this->adminHardwareService->getAllBorrowedHardware();

        $this->assertEquals(0, sizeof($hardware));
    }

    public function test_getAllBorrowableHardware()
    {
        $hardware = $this->adminHardwareService->getAllBorrowableHardware();

        $this->assertEquals(4, sizeof($hardware));
    }

    public function test_getHardwareByCategoryName()
    {
        $hardware = $this->adminHardwareService->getHardwareByCategoryName("C1");

        $this->assertEquals(4, sizeof($hardware));
    }

    public function test_getHardwareByProductName()
    {
        $hardware = $this->adminHardwareService->getHardwareByProductName("product1");

        $this->assertEquals(4, sizeof($hardware));
    }

    public function test_setHardwareReserved()
    {
        $idHardware = 1;
        $this->adminHardwareService->setHardwareReserved($idHardware, 1);
        $hardware = $this->adminHardwareService->getHardwareByBarcode($idHardware);

        $this->assertEquals(1, $hardware->reserved);

        $this->adminHardwareService->setHardwareReserved($idHardware, 0);
        $hardware = $this->adminHardwareService->getHardwareByBarcode($idHardware);

        $this->assertEquals(0, $hardware->reserved);
    }

    public function test_setHardwareOutOfService()
    {
        $idHardware = 2;
        $this->adminHardwareService->setHardwareOutOfService($idHardware, 1);
        $hardware = $this->adminHardwareService->getHardwareByBarcode($idHardware);

        $this->assertEquals(1, $hardware->outOfService);

        $this->adminHardwareService->setHardwareOutOfService($idHardware, 0);
        $hardware = $this->adminHardwareService->getHardwareByBarcode($idHardware);

        $this->assertEquals(0, $hardware->outOfService);
    }

    public function test_setHardwareDonation()
    {
        $idHardware = 3;
        $this->adminHardwareService->setHardwareDonation($idHardware, 1);
        $hardware = $this->adminHardwareService->getHardwareByBarcode($idHardware);

        $this->assertEquals(1, $hardware->donation);

        $this->adminHardwareService->setHardwareDonation($idHardware, 0);
        $hardware = $this->adminHardwareService->getHardwareByBarcode($idHardware);

        $this->assertEquals(0, $hardware->donation);
    }

    public function test_removeHardwarePermanently()
    {
        $idHardware = 3;
        $this->adminHardwareService->removeHardwarePermanently($idHardware);
        $hardware = $this->adminHardwareService->getHardwareByBarcode($idHardware);

        $this->assertEquals(null, $hardware);
    }
}
