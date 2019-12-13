<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPPATH . 'libraries/AbstractService.php';

/**
 * Class CategoryService This class provides methods to manage the category of the product.
 *
 * Each category contains many products and each product belongs to a category.
 */
class CategoryService extends AbstractService
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model("Category", "category");
        $this->load->library("User/ProductService");
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
    public function getCategoriesByName($name)
    {
        return $this->category->getCategoriesByName($name);
    }

	/**
	 * This method return the category which correspond to the given name
	 * @param $name : the category name to look for
	 * @return mixed : the asked category if it exist
	 */
	public function getCategoryByName($name)
	{
		return $this->category->getCategoryByName($name);
	}

    /**
     * This method return all categories contained in the data base
     * @return mixed : an array of categories
     */
    public function getAllCategories()
    {
        return $this->category->getCategories();
    }

}
