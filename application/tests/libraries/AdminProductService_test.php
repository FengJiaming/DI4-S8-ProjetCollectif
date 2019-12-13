<?php
include_once APPPATH . 'tests/TestUtilities.php';

class AdminProductService_test extends TestCase
{
    private $adminProductService;

    public function setUp()
    {
        $this->resetInstance();
        $this->CI->load->library('Admin/AdminProductService');
        $this->CI->load->library('User/ProductService');
        $this->adminProductService = $this->CI->adminproductservice;
        $this->productService = $this->CI->productservice;

        $this->CI->load->model("category");
        $this->CI->load->model("product");
        $this->CI->load->model("hardware");

        $this->CI->db->trans_start();
        TestUtilities::deleteAllDbData($this->CI);

        $this->CI->category->insertCategory("C1");
        $category = $this->CI->category->getCategoriesByName("C1");
        $this->CI->product->insertProduct("product1", "none", $category[0]->id);
        $this->CI->product->insertProduct("product2", "none", $category[0]->id);
        $this->CI->product->insertProduct("product3", "none", $category[0]->id);
        $this->CI->product->insertProduct("product4", "none", $category[0]->id);
        $product = $this->CI->product->getProductsByName("product1");
        $this->CI->hardware->insertHardware(1, "none", $product[0]->id);
        $this->CI->hardware->insertHardware(2, "none", $product[0]->id);
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->CI->db->trans_rollback();
    }

    public function test_addProduct()
    {
        $expected = array("name" => "test", "description" => "none");
        $category = $this->CI->category->getCategoriesByName("C1");
        $this->adminProductService->addProduct($expected["name"], $expected["description"], $category[0]->id);
        $product = $this->productService->getProductsByName($expected["name"]);

        $this->assertEquals($expected["description"], $product[0]->description);

        try {
            $this->adminProductService->addProduct($expected["name"], $expected["description"], $category[0]->id);
            $this->fail("The product {$expected["name"]} is supposed to be already created");
        } catch (Exception $exception) {}
    }

    public function test_modifyProduct()
    {
        $expected = array("id" => 0, "name" => "testSucceed", "description" => "was modified");
        $category = $this->CI->category->getCategoriesByName("C1");
        $product = $this->productService->getProductsByName("product1");
        $expected["id"] = $product[0]->id;
        $this->adminProductService->modifyProduct($expected["id"], $expected["name"], $expected["description"], $category[0]->id);
        $product = $this->productService->getProductsByName($expected["name"]);

        $this->assertEquals($expected["name"], $product[0]->name);
        $this->assertEquals($expected["description"], $product[0]->description);

        try {
            $this->adminProductService->modifyProduct($expected["id"], $expected["name"], $expected["description"], $category[0]->id);
            $this->fail("The product {$expected["name"]} is supposed to be already created");
        } catch (Exception $exception) {}
    }

    public function test_getAllProducts()
    {
        $products = $this->adminProductService->getAllProducts();

        $this->assertEquals(4, sizeof($products));
    }

    public function test_removeProductPermanently()
    {
        $product = $this->productService->getProductsByName("product2");
        $this->adminProductService->removeProductPermanently($product[0]->id);
        $product = $this->productService->getProductsByName("product2");

        $this->assertEquals(null, $product[0]);
    }

    public function test_removeProductWithHardwarePermanently()
    {
        $product = $this->productService->getProductsByName("product1");
        $this->adminProductService->removeProductWithHardwarePermanently($product[0]->id);
        $product = $this->productService->getProductsByName("product1");
        $hardware1 = $this->CI->hardware->getHardwareByBarCode(1);
        $hardware2 = $this->CI->hardware->getHardwareByBarCode(2);

        $this->assertEquals(null, $product[0]);
        $this->assertEquals(null, $hardware1);
        $this->assertEquals(null, $hardware2);
    }
}
