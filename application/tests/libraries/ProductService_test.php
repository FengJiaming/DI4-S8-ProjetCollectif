<?php
include_once APPPATH . 'tests/TestUtilities.php';

class ProductService_test extends TestCase
{
    private $productService;

    public function setUp()
    {
        $this->resetInstance();
        $this->CI->load->library('User/ProductService');
        $this->productService = $this->CI->productservice;

        $this->CI->load->model("category");
        $this->CI->load->model("product");

        $this->CI->db->trans_start();
        TestUtilities::deleteAllDbData($this->CI);

        $this->CI->category->insertCategory("C1");
        $category = $this->CI->category->getCategoriesByName("C1");
        $this->CI->product->insertProduct("product1", "none", $category[0]->id);
        $this->CI->product->insertProduct("product2", "none", $category[0]->id);
        $this->CI->product->insertProduct("product3", "none", $category[0]->id);
        $this->CI->product->insertProduct("product4", "none", $category[0]->id);
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->CI->db->trans_rollback();
    }

    public function test_getProductById()
    {
        $expected = array("id" => 1, "name" => "product1", "description" => "none");
        $product = $this->productService->getProductsByName($expected["name"]);
        $expected["id"] = $product[0]->id;
        $product = $this->productService->getProductById($expected["id"]);

        $this->assertEquals($expected["name"], $product->name);
        $this->assertEquals($expected["description"], $product->description);
    }

    public function test_getProductByName()
    {
        $expected = array("name" => "product2", "description" => "none");
        $product = $this->productService->getProductsByName($expected["name"]);

        $this->assertEquals($expected["name"], $product[0]->name);
        $this->assertEquals($expected["description"], $product[0]->description);
    }

    public function test_getAllBorrowableProducts()
    {
        $products = $this->productService->getAllBorrowableProducts();

        $this->assertEquals(0, sizeof($products));
    }

    public function test_getProductsByCategoryName()
    {
        $products = $this->productService->getProductsByCategoryName("C1");

        $this->assertEquals(4, sizeof($products));
    }

    public function test_isBorrowable()
    {
        $product = $this->productService->getProductsByName("product3");
        $id = $product[0]->id;
        $startDate = "01/01/2000";
        $endDate = "02/01/2000";

        $this->assertEquals(false, $this->productService->isBorrowable($id, $startDate, $endDate));
    }

    public function test_getAllProductsAssociatedTo()
    {

    }
}
