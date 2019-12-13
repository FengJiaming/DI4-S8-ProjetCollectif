<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPPATH . 'libraries/AbstractService.php';
/**
 * Class AdminProductService This class provides methods for administator to manage products.
 *
 **/
class AdminProductService extends AbstractService
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model("Product");
        $this->load->library("User/ProductService");
        $this->load->library("Admin/AdminHardwareService");
    }

    /**
     * This method create a new product with the given specifications and insert it in the data base
     * @param $name : the name of the new product
     * @param $description : the description of the new product
     * @param $idCategory : the category of the new product
     * @throws Exception : if the product already exist
     */
    public function addProduct($name, $description, $idCategory)
    {
        if ($this->product->getProductByName($name) == null)
        {
            $this->product->insertProduct($name, $description, $idCategory);
        }
        else
        {
            log_message("error", "AddProduct : the product {$name} already exist");
            throw new Exception("AddProduct : the product {$name} already exist");
        }
    }

    /**
     * This method modify the product which correspond to the given id
     * @param $id : the id of the product to look for
     * @param $name : the new name of the product
     * @param $description : the new description of the product
     * @param $idCategory : the new category of the product
     * @throws Exception : if the product already exist
     */
    public function modifyProduct($id, $name, $description, $idCategory)
    {
        if ($this->product->getProductsByName($name) == null)
        {
            $this->product->updateProduct($id, $name, $description, $idCategory);
        }
        else
        {
            log_message("error", "ModifyProduct : the product {$name} already exist");
            throw new Exception("ModifyProduct : the product {$name} already exist");
        }
    }

	/**
	 * This method return the product which correspond to the given name
	 * @param $name : the name to look for
	 * @return mixed : the asked product if it exist
	 */
	public function getProductByName($name)
	{
		return $this->product->getProductByName($name);
	}

    /**
     * This method return all products contained in the data base
     * @return mixed : an array of products
     */
    public function getAllProducts()
    {
        return $this->product->getProducts();
    }

    /**
     * This method return all products which have at least one hardware which correspond to the given parameters
     * The list is filtered by the category name
     * If the given boolean are false the list will contain only products which have an hardware in service, not reserved and not donated
     * If a boolean is true the list will contain all products which have an hardware with the same boolean at true
     * @param $categoryName : the category name to look for
     * @param $isOutOfService : tell if we search out of service hardware
     * @param $isReserved : tell if we search reserved hardware
     * @param $isDonated : tell if we search donated hardware
     * @return array : the list of products filtered
     */
    public function getProductsFilteredByCategoryName($categoryName, $isOutOfService, $isReserved, $isDonated)
    {
        $productsFiltered = array();
        $products = $this->productservice->getProductsByCategoryName($categoryName);
        $existingHardware = null;

        foreach ($products as $product)
        {
            $hardware = $this->adminhardwareservice->getHardwareByProductName($product->name);

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
                $productsFiltered[] = $product;
                $existingHardware = null;
            }
        }

        return $productsFiltered;
    }

    /**
     * This method remove permanently from the data base the product which correspond to the given name
     * @param $id : the id of the product to look for
     */
    public function removeProductPermanently($id)
    {
        $this->product->deleteProduct($id);
    }

    /**
     * This method remove permanently from the data base the product which correspond to the given name
     * and all hardware of this product
     * @param $id : the id of the product to look for
     */
    public function removeProductWithHardwarePermanently($id)
    {
        $product = $this->productservice->getProductById($id);
        $associatedHardware = $this->adminhardwareservice->getHardwareByProductName($product->name);

        foreach ($associatedHardware as $hardware)
        {
            $this->adminhardwareservice->removeHardwarePermanently($hardware->barCode);
        }

        $this->product->deleteProduct($id);
    }
}
