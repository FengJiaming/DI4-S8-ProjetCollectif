<?php
/**
 * Created by PhpStorm.
 * User: Loïc Gervois
 * Date: 20/03/2019
 * Time: 11:51
 */

/**
 * Class Hardware This class provides methods to do the operating of table Hardware in database
 */
class Hardware extends CI_Model
{
    private $barCode;
    private $comment;
    private $reserved;
    private $outOfService;
    private $donation;

    // Foreign key
    private $idProduct;

    /**
     * List all the hardwares stored in the database
     *
     * @return array All the hardwares stored in the database
     */
    public function getHardwares() {
        $this->db->reset_query();

        $query = $this->db->get('hardware');
        return $query->result();
    }

    /**
     * Get the hardware which correspond to the given id
     *
     * @param $barCode Id of the hardware
     * @return Hardware The Hardware which correspond to the given Id
     */
    public function getHardwareByBarCode($barCode) {
        $this->db->reset_query();
        $this->db->where('barCode', $barCode);
        $query = $this->db->get('hardware');
        return $query->row();
    }

    /**
     * Insert a new hardware into the database
     *
     * @param $barcode Barcode of the Hardware
     * @param $comment Comment of the Hardware
     * @param $idProduct Id of the product who insert into Hardware
     */
    public function insertHardware($barcode, $comment, $idProduct) {
        $this->db->reset_query();

        $data = array(
            'barCode' => $barcode,
            'comment' => $comment,
			'idProduct' => $idProduct,
			'reserved' => 0,
            'outOfService' => 0,
            'donation' => 0
        );
        $this->db->insert('hardware', $data);
    }

    /**
     * Update the hardware
     *
     * @param $barcode Barcode of the Hardware
     * @param $comment Comment of the Hardware
     */
    public function updateHardware($barcode, $comment) {
        $this->db->reset_query();

        $data = array(
            'comment' => $comment
        );
        $this->db->where('barCode', $barcode);
        $this->db->update('hardware', $data);
    }

    /**
     * Delete a hardware
     *
     * @param $id The id of hardware
     */
    public function deleteHardware($id) {
        $this->db->reset_query();

        $this->db->delete('hardware', array('barCode' => $id));
    }

	public function getAvailableHardwaresByProduct($idProduct){
		$this->db->from('hardware');
		$this->db->join('product', 'product.id = hardware.idProduct');
		$this->db->where('reserved',FALSE);
		$this->db->where('outOfService',FALSE);
		$query = $this->db->get();
		return $query->result();
	}

    /* @brief AVOIR TOUS LES HARDWARES DISPONIBLES ENTRE DEUX DATES D'UN CERTAIN PRODUIT */
    // Pour un id de produit donné, donner tous les hardwares disponibles entre deux dates
    // SELECT * FROM Hardware WHERE idProduct = $id
    // SELECT idHardware FROM HardwareBorrowing WHERE startDate IS NOT BETWEEN $startDate AND $endDate
                                                    //endDate IS NOT BETWEEN $startDate AND $endDate
    // AND idHardware = (SELECT * FROM Hardware WHERE idProduct = $id);
    // Pour un id de produit donné, donne tous les hardwares disponibles entre deux dates

    /**
     * Get available hardwares by id of product, start date and end date
     *
     * @param $startDate The start date of hardwareBorrowing
     * @param $endDate The end date of hardwareBorrowing
     * @param $idProduct The id of product
     * @return array All the hardwares available in the database
     */
    public function getAvailableHardwaresByProductId($startDate, $endDate, $idProduct) {
        $this->db->reset_query();

        $this->db->from('Hardware');
        $this->db->where('idProduct', $idProduct);
        $this->db->join('hardwareBorrowing', 'hardware.barCode = hardwareBorrowing.idHardware');
        $this->db->where("startDate > '".$startDate."' AND startDate > '".$endDate."'");
        $this->db->or_where("endDate < '".$startDate."' AND endDate < '".$endDate."'");

        $query = $this->db->get();
        //var_dump($this->db->last_query());

        return $query->result();
    }

    /**
     * Get hardwares who are borrowed today
     *
     * @return array All the hardwares who are borrowed today in the database
     */
    public function getHardwaresBorrowedToday() {
        $this->db->reset_query();

        $date = date("Y-m-d");

        //var_dump($date);

        $this->db->from('Hardware');
        $this->db->join('hardwareBorrowing', 'hardware.barCode = hardwareBorrowing.idHardware');

        $this->db->where("startDate < '".$date."' AND endDate > '".$date."'");

        //var_dump("startDate > '".$date."' OR endDate < '".$date."'");

        $finalQuery = $this->db->get();

        return $finalQuery->result();
    }

    // DONE : Si un hardware n'est pas dans borrowing, il ne s'affichera pas
    // DONE : Ne pas prendre ceux empruntés aujourd'hui (car ils ont aussi des anciens records)
    /**
     * Get hardwares who are not borrowed today
     *
     * @return array All the hardwares who are not borrowed today in the database
     */
    public function getHardwaresNotBorrowedToday() {
        $borrowedToday = $this->getHardwaresBorrowedToday();
        $idBorrowed = array();
        foreach ($borrowedToday as &$b) {
            $s = "'".$b->barCode."'";
            $idBorrowed[] = $s;
        }


        var_dump($idBorrowed);
        $idSeparated = implode(",", $idBorrowed);
        //var_dump($idSeparated);

        $this->db->reset_query();

        $date = date("Y-m-d");

        //var_dump($date);

        $this->db->from('Hardware');
        $this->db->join('hardwareBorrowing', 'hardware.barCode = hardwareBorrowing.idHardware');

        if (sizeof($idBorrowed) != 0)
            $this->db->where("startDate > '".$date."' OR endDate < '".$date."'
                             AND barcode NOT IN (".$idSeparated.")");
        else
            $this->db->where("startDate > '".$date."' OR endDate < '".$date."'");

        //var_dump("startDate > '".$date."' OR endDate < '".$date."'");

        $query1 = $this->db->get()->result();

        $this->db->from('Hardware');
        $this->db->where("barcode NOT IN (SELECT hardwareBorrowing.idHardware FROM HardwareBorrowing)");

        $query2 = $this->db->get()->result();

        $finalQuery = array_merge($query1, $query2);

        // Suppression des doublons
        $result = array();
        $temp = array(); // contiendra les ids à éviter
        foreach($finalQuery as $a) {
            if ( !in_array($a->barCode, $temp) ) {
                $result[] = $a;
                $temp[] = $a->barCode;
            }
        }

        //var_dump($result);

        return $result;
    }

    /**
     * Set if hardware is reserved
     *
     * @param $barCode Barcode of the Hardware
     */
    public function setReserved($barCode) {
        $this->db->reset_query();

        $data = array(
            'reserved' => 1
        );
        $this->db->where('barCode', $barCode);
        $this->db->update('hardware', $data);
    }

    /**
     * Set if hardware is not reserved
     *
     * @param $barCode Barcode of the Hardware
     */
    public function setNotReserved($barCode) {
        $this->db->reset_query();

        $data = array(
            'reserved' => 0
        );
        $this->db->where('barCode', $barCode);
        $this->db->update('hardware', $data);
    }

    /**
     * Set if the hardware is out of service
     *
     * @param $barCode Barcode of the Hardware
     */
    public function setOutOfService($barCode) {
        $this->db->reset_query();

        $data = array(
            'outOfService' => 1
        );
        $this->db->where('barCode', $barCode);
        $this->db->update('hardware', $data);
    }

    /**
     * Set if the hardware is in service
     *
     * @param $barCode Barcode of the Hardware
     */
    public function setInService($barCode) {
        $this->db->reset_query();

        $data = array(
            'outOfService' => 0
        );
        $this->db->where('barCode', $barCode);
        $this->db->update('hardware', $data);
    }

    /**
     * Set if there is a donation
     *
     * @param $barCode Barcode of the Hardware
     */
    public function setDonationTrue($barCode) {
        $this->db->reset_query();

        $data = array(
            'donation' => 1
        );
        $this->db->where('barCode', $barCode);
        $this->db->update('hardware', $data);
    }

    /**
     * Set if there is not a donation
     *
     * @param $barCode Barcode of the Hardware
     */
    public function setDonationFalse($barCode) {
        $this->db->reset_query();

        $data = array(
            'donation' => 0
        );
        $this->db->where('barCode', $barCode);
        $this->db->update('hardware', $data);
    }
}
