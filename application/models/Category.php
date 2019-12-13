<?php
/**
 * Created by PhpStorm.
 * User: Loïc Gervois
 * Date: 20/03/2019
 * Time: 11:51
 */

/**
 * Class Category This class provides methods to do the operating of table Category in database
 */
class Category extends CI_Model
{
	private $id;
	private $name;

	/**
	 * List all the categories stored in the database
	 *
	 * @return array The list of all categories
	 */
	public function getCategories() {
        $this->db->reset_query();

		$query = $this->db->get('category');
		return $query->result();
	}

	/**
	 * Get the category which correspond to the givne id
	 *
	 * @param $id Id of the category needed
	 * @return The category which correspond to the given ID
	 */
	public function getCategoryById($id) {
        $this->db->reset_query();

        $this->db->where('id', $id);
        $query = $this->db->get('category');
        return $query->row();
    }

	/**
	 * List the categories which contains the given name
	 * SELECT * FROM Category WHERE name like %name%
	 *
	 * @param $name name that we are looking for
	 * @return array List of the categories containing the given name
	 */
    public function getCategoriesByName($name) {
        $this->db->reset_query();

	    $this->db->like('name', $name);
	    $query = $this->db->get('category');
	    return $query->result();
    }

	/**
	 * Get the category which correspond to the given name
	 *
	 * @param $name Name of the category
	 * @return category The category which correspond to the given name
	 */
	public function getCategoryByName($name) {
		$this->db->reset_query();

		$this->db->where('name', $name);
		$query = $this->db->get('category');
		return $query->row();
	}

	/**
	 * Insert a new category in the database
	 *
	 * @param $name The name of the new category
	 */
	public function insertCategory($name) {
        $this->db->reset_query();

        $data = array(
            'name' => $name
        );
        $this->db->insert('category', $data);
	}

	/**
	 * Modify an existing category
	 *
	 * @param $id Id of the existing category
	 * @param $name new name of the category
	 */
    public function updateCategory($id, $name) {
        $this->db->reset_query();

	    $data = array(
            'name' => $name
        );
        $this->db->where('id', $id);
        $this->db->update('category', $data);
	}

	/**
	 * Delete a category from the database
	 *
	 * @param $id Id of the category to delete
	 */
	public function deleteCategory($id) {
        $this->db->reset_query();
        $this->db->delete('category', array('id' => $id));
    }

	/**
	 * List all the products stored in the database
	 *
	 * @param $id
	 * @return array All the product stored in the database
	 */
    public function getProducts($id) {
        $this->db->reset_query();

        $this->db->from('Category');
        $this->db->where('id', $id);

        $this->db->join('product', 'category.id = product.idCategory');

        $query = $this->db->get();
        return $query->result();
    }

	/**
	 * List all hardwares of the given category
	 *
	 * @param $categoryName name of the category
	 * @return array Array of hardwares of the given category
	 */
    public function getHardwares($categoryName) {
        $this->db->reset_query();

	    $this->db->from('Category');
	    $this->db->where('Category.name', $categoryName);

	    $this->db->join('product', 'category.id = product.idCategory');
	    $this->db->join('hardware', 'product.id = hardware.idProduct');

        $query = $this->db->get();
        return $query->result();
    }
}
