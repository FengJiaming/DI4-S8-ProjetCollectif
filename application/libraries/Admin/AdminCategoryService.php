<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPPATH . 'libraries/AbstractService.php';
/**
 * Class AdminCategoryService This class provides methods for administator to manage the category.
 *
 **/
class AdminCategoryService extends AbstractService
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model("category");
        $this->load->library("User/ProductService");
        $this->load->library("Admin/AdminProductService");
        $this->load->library("Admin/AdminHardwareService");
    }

    /**
     * This method create a category with the given name and insert it in the data base
     * @param $name : the name of the new category
     * @throws Exception : if the category already exist
     */
    public function addCategory($name)
    {
        if ($this->category->getCategoryByName($name) == null)
        {
            $this->category->insertCategory($name);
        }
        else
        {
            log_message("error", "AddCategory : the category {$name} already exist");
            throw new Exception("AddCategory : the category {$name} already exist");
        }
    }

    /**
     * This method modify the category which correspond to the given id
     * @param $id : the id to look for
     * @param $name : the new category name
     * @throws Exception : if a category already have this name
     */
    public function modifyCategory($id, $name)
    {
        if ($this->category->getCategoriesByName($name) == null)
        {
            $this->category->updateCategory($id, $name);
        }
        else
        {
            log_message("error", "ModifyCategory : the category {$name} already exist");
            throw new Exception("ModifyCategory : the category {$name} already exist");
        }
    }

    /**
     * This method return the category which correspond to the given id
     * @param $id : the category name to look for
     * @return mixed : the asked category if it exist
     */
    public function getCategoryById($id)
    {
        return $this->category->getCategoryById($id);
    }

    /**
     * This method return the categories which contain the given name
     * @param $name : the category name to look for
     * @return mixed : the asked category if it exist
     */
    public function getCategoryByName($name)
    {
        return $this->category->getCategoryByName($name);
    }

    /**
     * This method return the categories which contain the given name
     * @param $name : the category name to look for
     * @return mixed : the asked category if it exist
     */
    public function getCategoriesByName($name)
    {
        return $this->category->getCategoriesByName($name);
    }

    /**
     * This method return all categories contained in the data base
     * @return mixed : an array of categories
     */
    public function getAllCategories()
    {
        return $this->category->getCategories();
    }

    /**
     * This method return all categories which have at least one hardware which correspond to the given parameters
     * If the given boolean are false the list will contain only categories which have an hardware in service, not reserved and not donated
     * If a boolean is true the list will contain all categories which have an hardware with the same boolean at true
     * @param $isOutOfService : tell if we search out of service hardware
     * @param $isReserved : tell if we search reserved hardware
     * @param $isDonated : tell if we search donated hardware
     * @return array : the list of categories filtered
     */
    public function getAllCategoriesFiltered($isOutOfService, $isReserved, $isDonated)
    {
        $categoriesFiltered = array();
        $categories = $this->getAllCategories();
        $existingHardware = null;

        foreach ($categories as $category)
        {
            $hardware = $this->adminhardwareservice->getHardwareByCategoryName($category->name);

            for ($increment = 0; $increment < sizeof($hardware) && $existingHardware == null; $increment++)
            {
                if($isOutOfService === true || $isReserved === true || $isDonated === true)
                {
                    if($isOutOfService === true && $hardware[$increment]->outOfService == true)
                    {
                        $existingHardware = $hardware[$increment];
                    }
                    elseif($isReserved === true && $hardware[$increment]->reserved == true)
                    {
                        $existingHardware = $hardware[$increment];
                    }
                    elseif($isDonated === true && $hardware[$increment]->donation == true)
                    {
                        $existingHardware = $hardware[$increment];
                    }
                }
                elseif($hardware[$increment]->outOfService == false && $hardware[$increment]->reserved == false && $hardware[$increment]->donation == false)
                {
                    $existingHardware = $hardware[$increment];
                }
            }

            if($existingHardware != null)
            {
                $categoriesFiltered[] = $category;
                $existingHardware = null;
            }
        }

        return $categoriesFiltered;
    }

    /**
     * This method remove permanently from the data base the category which correspond to the given name
     * @param $id : the id of the category to look for
     */
    public function removeCategoryPermanently($id)
    {
        $this->category->deleteCategory($id);
    }

    /**
     * This method remove permanently from the data base the category which correspond to the given name
     * and all products and hardware of this category
     * @param $id : the id of the category to look for
     */
    public function removeCategoryWithProductPermanently($id)
    {
        $category = $this->getCategoryById($id);
        $associatedProducts = $this->productservice->getProductsByCategoryName($category->name);

        foreach ($associatedProducts as $product)
        {
            $this->adminproductservice->removeProductWithHardwarePermanently($product->id);
        }

        $this->category->deleteCategory($id);
    }
}
