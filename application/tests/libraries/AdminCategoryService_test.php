<?php
include_once APPPATH . 'tests/TestUtilities.php';

class AdminCategoryService_test extends TestCase
{
    private $adminCategoryService;

    public function setUp()
    {
        $this->resetInstance();
        $this->CI->load->library('Admin/AdminCategoryService');
        $this->adminCategoryService = $this->CI->admincategoryservice;

        $this->CI->load->model("category");
        $this->CI->load->model("product");
        $this->CI->load->model("hardware");

        $this->CI->db->trans_start();
        TestUtilities::deleteAllDbData($this->CI);

        $this->CI->category->insertCategory("C1");
        $this->CI->category->insertCategory("C2");
        $category = $this->CI->category->getCategoriesByName("C1");
        $this->CI->product->insertProduct("product1", "none", $category[0]->id);
        $this->CI->product->insertProduct("product2", "none", $category[0]->id);
        $product = $this->CI->product->getProductsByName("product1");
        $this->CI->hardware->insertHardware(1, "none", $product[0]->id);
        $this->CI->hardware->insertHardware(2, "none", $product[0]->id);
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->CI->db->trans_rollback();
    }

    public function test_addCategory()
    {
        $expected = "test";
        $this->adminCategoryService->addCategory($expected);
        $category = $this->adminCategoryService->getCategoriesByName($expected);

        $this->assertEquals($expected, $category[0]->name);

        try {
            $this->adminCategoryService->addCategory($expected);
            $this->fail("The category {$expected} is supposed to be already created");
        } catch (Exception $exception) {}
    }

    public function test_modifyCategory()
    {
        $expected = "testSucceed";
        $category = $this->adminCategoryService->getCategoriesByName("C1");
        $this->adminCategoryService->modifyCategory($category[0]->id, $expected);
        $category = $this->adminCategoryService->getCategoriesByName($expected);

        $this->assertEquals($expected, $category[0]->name);

        try {
            $this->adminCategoryService->modifyCategory($category[0]->id, $expected);
            $this->fail("The category {$expected} is supposed to be already created");
        } catch (Exception $exception) {}
    }

    public function test_getCategoryById()
    {
        $expected = $this->adminCategoryService->getCategoriesByName("C1");
        $category = $this->adminCategoryService->getCategoryById($expected[0]->id);

        $this->assertEquals($expected[0]->name, $category->name);
    }

    public function test_getCategoryByName()
    {
        $category = $this->adminCategoryService->getCategoriesByName("C2");

        $this->assertEquals("C2", $category[0]->name);
    }

    public function test_getAllCategories()
    {
        $categories = $this->adminCategoryService->getAllCategories();

        $this->assertEquals(2, sizeof($categories));
    }

    public function test_removeCategoryPermanently()
    {
        $category = $this->adminCategoryService->getCategoriesByName("C2");
        $this->adminCategoryService->removeCategoryPermanently($category[0]->id);
        $category = $this->adminCategoryService->getCategoriesByName("C2");

        $this->assertEquals(null, $category[0]);
    }

    public function test_removeCategoryWithProductPermanently()
    {
        $category = $this->adminCategoryService->getCategoriesByName("C1");
        $this->adminCategoryService->removeCategoryWithProductPermanently($category[0]->id);
        $category = $this->adminCategoryService->getCategoriesByName("C1");
        $product1 = $this->CI->product->getProductsByName("product1");
        $product2 = $this->CI->product->getProductsByName("product2");
        $hardware1 = $this->CI->hardware->getHardwareByBarCode(1);
        $hardware2 = $this->CI->hardware->getHardwareByBarCode(2);

        $this->assertEquals(null, $category[0]);
        $this->assertEquals(null, $product1[0]);
        $this->assertEquals(null, $product2[0]);
        $this->assertEquals(null, $hardware1);
        $this->assertEquals(null, $hardware2);
    }
}
