<?php
/**
 * Created by PhpStorm.
 * User: Loïc Gervois
 * Date: 20/03/2019
 * Time: 11:51
 */

/**
 * Class Product This class provides methods to do the operating of table Product in database
 */
class Product extends CI_Model
{
    private $id;
    private $name;
    private $description;

    // Secondary key
    private $idCategory;

	/**
     * List all the product stored in the database
     *
	 * @return array All the products stored in the database
	 */
    public function getProducts() {
        $this->db->reset_query();

        $query = $this->db->get('product');
        return $query->result();
    }

    /**
     *  Get the product which correspond to the given id
     *
     * @param $id Id of the product
     * @return product The product which correspond to the given Id
     */
    public function getProductById($id) {
        $this->db->reset_query();

        $this->db->where('id', $id);
        $query = $this->db->get('product');
        return $query->row();
    }

    /**
     * Get the products which correspond to the given name
     *
     * @param $name Name of the product
     * @return array The products which correspond to the given name
     */
    public function getProductsByName($name) {
        $this->db->reset_query();

        $this->db->like('name', $name);
        $query = $this->db->get('product');
        return $query->result();
    }

    /**
     * Get the product which correspond to the given name
     *
     * @param $name Name of the product
     * @return product The product which correspond to the given name
     */
	public function getProductByName($name) {
		$this->db->reset_query();

		$this->db->where('name', $name);
		$query = $this->db->get('product');
		return $query->row();
	}

    /**
     * Get the product which correspond to the given name and categoryName
     *
     * @param $name Name of the product
     * @param $categoryName CategoryName of the product
     * @return product The product which correspond to the given name and categoryName
     */
	public function getProductByNameAndCategoryName($name,$categoryName) {
		$this->db->reset_query();
		$this->db->select('product.id, product.idCategory, product.name, product.description');
		$this->db->from('product');
		$this->db->join('category', 'product.idCategory = category.id', 'inner');
		$this->db->where('category.name', $categoryName);
		$this->db->like('product.name', $name);
		$query = $this->db->get();
		return $query->result();
	}

    /**
     * Get the product which correspond to the given id of category
     *
     * @param $idCategory Id of the category
     * @return array The products which correspond to the given idCategory
     */
    public function getProductByIdCategory($idCategory) {
        $this->db->reset_query();

        $this->db->where('idCategory', $idCategory);
        $query = $this->db->get('product');
        return $query->result();
    }

    /**
     * Get the product which correspond to the given name of category
     *
     * @param $nameCategory The name of category
     * @return array The products which correspond to the given name of category
     */
    public function getProductByNameCategory($nameCategory) {
        $this->db->reset_query();

        $this->db->select('product.id, product.idCategory, product.name, product.description');
        $this->db->from('product');
        $this->db->join('category', 'product.idCategory = category.id');
        $this->db->where('category.name', $nameCategory);

        $query = $this->db->get();
        //var_dump($this->db->last_query());

        return $query->result();
    }

    /**
     * Insert a new product into the database
     *
     * @param $name The name of product
     * @param $description The description of product
     * @param $idCategory The id of category
     */
    public function insertProduct($name, $description, $idCategory) {
        $this->db->reset_query();

        $data = array(
            'name' => $name,
            'description' => $description,
			'idCategory' => $idCategory
        );
        $this->db->insert('product', $data);
    }

    /**
     * Update the product
     *
     * @param $id Id of the product
     * @param $name The name of product
     * @param $description The description of product
     * @param $idCategory The id of category
     */
    public function updateProduct($id, $name, $description, $idCategory) {
        $this->db->reset_query();

        $data = array(
            'name' => $name,
            'description' => $description,
			'idCategory' => $idCategory
        );
        $this->db->where('id', $id);
        $this->db->update('product', $data);
    }

    /**
     * Delete a product
     *
     * @param $id The id of the product
     */
    public function deleteProduct($id) {
        $this->db->reset_query();

        $this->db->delete('product', array('id' => $id));
    }

    /**
     * Get the hardwares which correspond to the given name of product
     *
     * @param $productName The name of the product
     * @return array The hardwares which correspond to the given name of product
     */
    public function getHardwares($productName) {
        $this->db->reset_query();

        $this->db->from('Product');
        $this->db->where('name', $productName);

        $this->db->join('hardware', 'product.id = hardware.idProduct');

        $query = $this->db->get();
        //var_dump($this->db->last_query());

        return $query->result();
    }

    /**
     * Get all available products
     *
     * @return array The available products
     */
    public function getAvailableProducts() {
        $this->db->reset_query();

        $this->db->from('Hardware');
        $this->db->select('idProduct');
        $this->db->where("reserved = 0 AND outOfService = 0 AND donation = 0");
        $this->db->distinct();

        $query = $this->db->get();
        //var_dump($this->db->last_query());

        $products = array();

        foreach ($query->result() as $row) {
            $idProduct = $row->idProduct;
            $products[] = $this->getProductById($idProduct);
        }

        return $products;
    }

    /**
     * Get product of last id
     *
     * @return Product The products which correspond to the max id
     */
    public function getLastId() {
        $this->db->reset_query();

        $this->db->select_max('id');
        $query = $this->db->get('product');

        return $query->result();
    }
}
