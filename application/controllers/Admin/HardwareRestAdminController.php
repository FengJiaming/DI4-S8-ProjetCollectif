<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH. "/libraries/REST_Controller.php";
use Restserver\Libraries\REST_Controller;

class HardwareRestAdminController extends REST_Controller
{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);

        $this->load->library('Admin/AdminCategoryService');
        $this->load->library('User/ProductService');
        $this->load->library('Admin/AdminProductService');
        $this->load->library('Admin/AdminHardwareService');
    }

    /**
     * This method insert a new hardware in the database
     * If the given product doesn't exist it will be automatically created
     * If the given category doesn't exist it will be automatically created
     * @post barCode : the new hardware's barCode
     * @post comment : the new hardware's comment
     * @post reserved : tell if the new hardware is reserved
     * @post productName : the product' name in which the new hardware will be affected
     * @post categoryName : the category's name in which the new hardware will be affected
     * @throws Exception : if a parameter is null or if the barCode is already taken
     */
    public function AdminAddHardware_post()
    {
        $hardware = array();
        $hardware["barCode"] = $this->post('barCode');
        $hardware["comment"] = $this->post('comment');
        $hardware["reserved"] = $this->post('reserved');
        $hardware["productName"] = $this->post('productName');
        $hardware["categoryName"] = $this->post('categoryName');

        if($hardware["barCode"] != null && $hardware["comment"] != null && $hardware["reserved"] != null && $hardware["productName"] != null && $hardware["categoryName"] != null)
        {
            $category = $this->admincategoryservice->getCategoryByName($hardware["categoryName"]);
            if($category[0]->name != $hardware["categoryName"])
            {
                $this->admincategoryservice->addCategory($hardware["categoryName"]);
                $category = $this->admincategoryservice->getCategoryByName($hardware["categoryName"]);
            }

            $product = $this->productservice->getProductByName($hardware["productName"]);
            if($product[0]->name != $hardware["productName"])
            {
                $this->adminproductservice->addProduct($hardware["productName"], "", $category[0]->id);
                $product = $this->productservice->getProductByName($hardware["productName"]);
            }

            $this->adminhardwareservice->addHardware($hardware["barCode"], $hardware["comment"], $product[0]->id);
            $this->adminhardwareservice->setHardwareReserved($hardware["barCode"], $hardware["reserved"]);
        }
        else
        {
            log_message("error", "AddHardware : all fields has to be fulfilled");
            throw new Exception("AddHardware : all fields has to be fulfilled");
        }
    }

    /**
     * This method modify an hardware in the data base
     * If the given product doesn't exist it will be automatically created
     * The given category has to be already created
     * @post barCode : the hardware's barCode to look for
     * @post comment : the hardware's new comment
     * @post reserved : tell if the hardware is reserved
     * @post outOfService : tell if the hardware is out of service
     * @post donation : tell if the hardware is donated
     * @post productName : the product' name in which the new hardware will be affected
     * @post categoryName : the category's name in which the new hardware will be affected
     * @throws Exception : if a parameter is null or if the given category doesn't exist
     */
    public function AdminModifyHardware_put()
    {
        $hardware = array();
        $hardware["barCode"] = $this->put('barCode');
        $hardware["comment"] = $this->put('comment');
        $hardware["reserved"] = $this->put('reserved');
        $hardware["outOfService"] = $this->put("outOfService");
        $hardware["donation"] = $this->put("donation");
        $hardware["productName"] = $this->put('productName');
        $hardware["categoryName"] = $this->put('categoryName');

        if($hardware["barCode"] != null && $hardware["comment"] != null && $hardware["reserved"] != null && $hardware["outOfService"] != null && $hardware["donation"] != null && $hardware["productName"] != null && $hardware["categoryName"] != null)
        {
            $category = $this->admincategoryservice->getCategoryByName($hardware["categoryName"]);
            if($category[0] != null)
            {
                $product = $this->productservice->getProductByName($hardware["productName"]);
                if($product[0]->name != $hardware["productName"])
                {
                    $this->adminproductservice->addProduct($hardware["productName"], "", $category[0]->id);
                    $product = $this->productservice->getProductByName($hardware["productName"]);
                }

                $this->adminhardwareservice->addHardware($hardware["barCode"], $hardware["comment"], $product[0]->id);
                $this->adminhardwareservice->setHardwareReserved($hardware["barCode"], $hardware["reserved"]);
                $this->adminhardwareservice->setHardwareOutOfService($hardware["barCode"], $hardware["outOfService"]);
                $this->adminhardwareservice->setHardwareDonation($hardware["barCode"], $hardware["donation"]);
            }
            else
            {
                log_message("error", "ModifyHardware : the given category doesn't exit");
                throw new Exception("ModifyHardware : the given category doesn't exit");
            }
        }
        else
        {
            log_message("error", "ModifyHardware : all fields has to be fulfilled");
            throw new Exception("ModifyHardware : all fields has to be fulfilled");
        }
    }

    /**
     * This method return all hardware which are contained in the given product
     * This method filter by outOfService, reserved and donation
     * @post productName : the product's name to look for
     * @post outOfService : tell if an hardware is out of service
     * @post reserved : tell if an hardware is reserved
     * @post donation : tell if an hardware is donated
     * @return mixed : an array of hardware
     *              barCode : the hardware barCode
     *              comment : the hardware comment
     *              reserved : tell if the hardware is reserved
     *              outOfService : tell if the hardware is out of service
     *              donation : tell if the hardware is donated
     *              idProduct : the product's id associated to the hardware
     * @throws Exception : if a parameter is null
     */
    public function AdminGetHardwareFilteredByProductName_post()
    {
        $filter = array();
        $filter["productName"] = $this->post("productName");
        $filter["outOfService"] = $this->post("outOfService");
        $filter["reserved"] = $this->post("reserved");
        $filter["donation"] = $this->post("donation");

        if($filter["productName"] == null)
        {
            log_message("error", "GetHardwareFilteredByCategoryName : all fields has to be fulfilled");
            throw new Exception("GetHardwareFilteredByCategoryName : all fields has to be fulfilled");
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

        $this->response($this->adminhardwareservice->getAllHardwareFilteredByProductName($filter["productName"], $filter["outOfService"], $filter["reserved"], $filter["donation"]));
    }

    /**
     * This method remove from the database the hardware which correspond to the given barCode
     * @param $barCode : the hardware's barCode to look for
     */
    public function AdminRemoveHardware_delete($barCode)
    {
        $this->adminhardwareservice->removeHardwarePermanently($barCode);
    }
}
