<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH. "/libraries/REST_Controller.php";
use Restserver\Libraries\REST_Controller;

class CategoryRestAdminController extends REST_Controller
{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);

        $this->load->library('Admin/AdminCategoryService');
    }

    /**
     * This method insert a new category in the date base
     * @post name : the new category's name
     * @throws Exception : if the name is null or if it's already taken by an other category
     */
    public function AdminAddCategory_post()
    {
        $categoryName = $this->post("name");

        if($categoryName != null)
        {
            $this->admincategoryservice->addCategory($categoryName);
        }
        else
        {
            log_message("error", "AddCategory : all fields has to be fulfilled");
            throw new Exception("AddCategory : all fields has to be fulfilled");
        }
    }

    /**
     * This method modify a category in the data base
     * @post currentName : the current name of the category
     * @post newName : the new name of the category
     * @throws Exception : if a parameter is null or if a category already have the new name
     */
    public function AdminModifyCategory_put()
    {
        $category = array();
        $category["currentName"] = $this->put("currentName");
        $category["newName"] = $this->put("newName");

        if($category["currentName"] != null && $category["newName"] != null)
        {
            $dbCategory = $this->admincategoryservice->getCategoryByName($category["currentName"]);
            $this->admincategoryservice->modifyCategory($dbCategory[0]->id, $category["newName"]);
        }
        else
        {
            log_message("error", "ModifyCategory : all fields has to be fulfilled");
            throw new Exception("ModifyCategory : all fields has to be fulfilled");
        }
    }

    /**
     * This method remove from the data base the category which correspond to the given name
     * if cascade is true this method will also remove the product and the hardware contained in this category
     * @param $categoryName : the category's name too delete
     * @param $cascade : tell if the method will remove in cascade
     */
    public function AdminRemoveCategory_delete($categoryName, $cascade)
    {
        $category = $this->admincategoryservice->getCategoryByName($categoryName);

        if($cascade)
        {
            $this->admincategoryservice->removeCategoryWithProductPermanently($category[0]->id);
        }
        else
        {
            $this->admincategoryservice->removeCategoryPermanently($category[0]->id);
        }
    }
}