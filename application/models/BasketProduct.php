<?php
/**
 * Created by PhpStorm.
 * User: nathS
 * Date: 27/03/2019
 * Time: 11:00
 */

/**
 * Class BasketProduct This class provides methods to do the operating of table BasketProduct in database
 */
class BasketProduct extends CI_Model
{
    private $id;
    private $userNumber;
    private $startDate;
    private $endDate;

    // Foreign key
    private $idProduct;

    /**
     * List all the basketProducts stored in the database
     *
     * @return array All the basketProducts stored in the database
     */
    public function getBasketProducts()
    {
        $this->db->reset_query();

        $query = $this->db->get('BasketProduct');
        return $query->result();
    }

    /**
     * Get the basket product which correspond to the given id
     *
     * @param $id Id of the basket product
     * @return basketProduct The basketProduct which correspond to the given id
     */
    public function getBasketProductById($id)
    {
        $this->db->reset_query();

        $this->db->where('id', $id);
        $query = $this->db->get('BasketProduct');
        return $query->row();
    }

    /**
     * List the basketProducts of the given User
     *
     * @param $userNumber The userNumber of the user
     * @return array The basketProducts of the given user
     */
    public function getBasketProductsByUser($userNumber)
    {
        $this->db->reset_query();

        $this->db->where('userNumber', $userNumber);
        $query = $this->db->get('BasketProduct');
        return $query->result();
    }

    /**
     * Insert a new basketProduct in the database. This basket product is linked to a hardware product
     *
     * @param int $userNumber UserNumber of the owner of the basketProduct
     * @param Date $startDate
     * @param Date $endDate
     * @param int $idProduct Product which correspond to the basket
     */
    public function insertBasketHardwareProduct($userNumber, $startDate, $endDate, $idProduct)
    {
        $this->db->reset_query();

        $data = array(
            'idProduct' => $idProduct,
            'userNumber' => $userNumber,
            'startDate' => $startDate,
            'endDate' => $endDate
        );
        $this->db->insert('BasketProduct', $data);
    }

    /**
     * Insert a nex basketProduct in the database. This basket product is not linked to any hardware.
     * This only contains a consumable
     *
     * @param $userNumber UserNumber of the owner of the basketProduct
     * @param $startDate
     * @param $endDate
     * @param $designation
     */
    public function insertBasketConsumableProduct($userNumber, $startDate, $endDate, $comment, $consumableName)
    {
        $this->db->reset_query();

        $data = array(
            'userComment' => $comment,
            'designation' => $consumableName,
            'userNumber' => $userNumber,
            'startDate' => $startDate,
            'endDate' => $endDate
        );

        $this->db->insert('BasketProduct', $data);
    }

    /**
     * Modify an existing basketProduct
     *
     * @param $id    Id of the basketProduct to modify
     * @param $startDate
     * @param $endDate
     */
    public function updateBasketProduct($id, $startDate, $endDate)
    {
        $this->db->reset_query();

        $data = array(
            'startDate' => $startDate,
            'endDate' => $endDate,
        );
        $this->db->where('id', $id);
        $this->db->update('BasketProduct', $data);
    }

    /**
     * Delete the basketProduct corresponding to the given id
     *
     * @param $id
     */
    public function deleteBasketProduct($id)
    {
        $this->db->reset_query();
        $this->db->delete('BasketProduct', array('id' => $id));
    }

    /**
     * Delete the basketProducts of the given user
     *
     * @param $userNumber UserNumber of the user
     */
    public function deleteBasketProductByUserId($userNumber)
    {
        $this->db->reset_query();
        $this->db->delete('BasketProduct', array('userNumber' => $userNumber));
    }
}
