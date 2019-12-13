<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH. "/libraries/REST_Controller.php";
use Restserver\Libraries\REST_Controller;

class ProductRestAdminController extends REST_Controller
{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);

        $this->load->library('Admin/AdminCategoryService');
        $this->load->library('User/ProductService');
        $this->load->library('Admin/AdminProductService');
    }

    /**
     * This method insert a new product in the data base
     * If the given category doesn't exist it will be automatically created
     * @post name : the new product's name
     * @post description : the new product's description
     * @post categoryName : the category's name in which the new product will be affected
     * @throws Exception : if a parameter is null or if the product's name is already taken
     */
    public function AdminAddProduct_post()
    {
        $product = array();
        $product["name"] = $this->post("name");
        $product["description"] = $this->post("description");
        $product["categoryName"] = $this->post("categoryName");

        if($product["name"] != null && $product["description"] != null && $product["categoryName"] != null)
        {
            $category = $this->admincategoryservice->getCategoryByName($product["categoryName"]);
            if($category[0]->name != $product["categoryName"])
            {
                $this->admincategoryservice->addCategory($product["categoryName"]);
                $category = $this->admincategoryservice->getCategoryByName($product["categoryName"]);
            }

            $this->adminproductservice->addProduct($product["name"], $product["description"], $category[0]->id);
        }
        else
        {
            log_message("error", "AddProduct : all fields has to be fulfilled");
            throw new Exception("AddProduct : all fields has to be fulfilled");
        }
    }

    /**
     * This method modify the product which correspond to the given name in the data base
     * If the given category doesn't exist it will be automatically created
     * @post currentName : the current product's name
     * @post newName : the product's new name
     * @post description : the new product's description
     * @post categoryName : the category's name in which the new product will be affected
     * @throws Exception : if a parameter is null or if the product's name is already taken
     */
    public function AdminModifyProduct_put()
    {
        $product = array();
        $product["currentName"] = $this->put("currentName");
        $product["newName"] = $this->put("newName");
        $product["description"] = $this->put("description");
        $product["categoryName"] = $this->put("categoryName");

        if($product["currentName"] != null && $product["newName"] != null && $product["description"] != null && $product["categoryName"] != null)
        {
            $category = $this->admincategoryservice->getCategoryByName($product["categoryName"]);
            if($category[0]->name != $product["categoryName"])
            {
                $this->admincategoryservice->addCategory($product["categoryName"]);
                $category = $this->admincategoryservice->getCategoryByName($product["categoryName"]);
            }

            $dbProduct = $this->productservice->getProductByName($product["currentName"]);
            $this->adminproductservice->modifyProduct($dbProduct[0]->id, $product["newName"], $product["description"], $category[0]->id);
        }
        else
        {
            log_message("error", "ModifyProduct : all fields has to be fulfilled");
            throw new Exception("ModifyProduct : all fields has to be fulfilled");
        }
    }

    /**
     * This method return all products which are contained in the given category
     * This method filter by outOfService, reserved and donation
     * @post categoryName : the category's name to look for
     * @post outOfService : tell if an hardware is out of service
     * @post reserved : tell if an hardware is reserved
     * @post donation : tell if an hardware is donated
     * @return mixed : an array of product
     *              id : the product id
     *              name : the product name
     *              description : the product description
     *              idCategory : the product category's id
     * @throws Exception : if a parameter is null
     */
    public function AdminGetProductsFilteredByCategoryName_post()
    {
        $filter = array();
        $filter["categoryName"] = $this->post("categoryName");
        $filter["outOfService"] = $this->post("outOfService");
        $filter["reserved"] = $this->post("reserved");
        $filter["donation"] = $this->post("donation");

        if($filter["categoryName"] == null)
        {
            log_message("error", "GetProductsFilteredByCategoryName : all fields has to be fulfilled");
            throw new Exception("GetProductsFilteredByCategoryName : all fields has to be fulfilled");
        }

        if($filter["outOfService"] == 1)
        {
            $filter["outOfService"] = true;
        }
        else
        {
            $filter["outOfService"] = false;
        }
        if($filter["reserved"] == 1)
        {
            $filter["reserved"] = true;
        }
        else
        {
            $filter["reserved"] = false;
        }
        if($filter["donation"] == 1)
        {
            $filter["donation"] = true;
        }
        else
        {
            $filter["donation"] = false;
        }

        $this->response($this->adminproductservice->getProductsFilteredByCategoryName($filter["categoryName"], $filter["outOfService"], $filter["reserved"], $filter["donation"]));
    }

    /**
     * This method remove the product from the database which correspond to the given name
     * if cascade is true this method will also remove the hardware contained in this product
     * @param $productName : the product's name to look for
     * @param $cascade : tell if the method will remove in cascade
     */
    public function AdminRemoveProduct_delete($productName, $cascade)
    {
        $product = $this->productservice->getProductByName($productName);

        if($cascade)
        {
            $this->adminproductservice->removeProductWithHardwarePermanently($product[0]->id);
        }
        else
        {
            $this->adminproductservice->removeProductPermanently($product[0]->id);
        }
    }
}