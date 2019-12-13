<?php
/**
 * Created by PhpStorm.
 * User: Loïc Gervois
 * Date: 20/03/2019
 * Time: 11:53
 */

/**
 * Class Administrator This class provides methods to do the operating of table Administrator in database
 */
class Administrator extends CI_Model
{
    private $id;
    private $userNumber;

	/**
	 * List all the administrators stored in the database
	 *
	 * @return array All the administrators stored in the database
	 */
    public function getAdministrators() {
        $this->db->reset_query();

        $query = $this->db->get('administrator');
        return $query->result();
    }

	/**
	 * Get the administrator which correspond to the given id
	 *
	 * @param $id Id of the administrator
	 * @return Administrator The administrator which correspond to the given Id
	 */
    public function getAdministratorById($id) {
        $this->db->reset_query();

        $this->db->where('id', $id);
        $query = $this->db->get('administrator');
        return $query->row();
    }

	/**
	 * Insert a new administrator in the database
	 *
	 * @param $userNumber UserNumber of the administrator
	 */
    public function insertAdministrator($userNumber) {
        $this->db->reset_query();

        $data = array (
            'userNumber' => $userNumber
        );
        $this->db->insert('administrator', $data);
    }

    // Update if the userNumber doesn't exist in the table Administrator

	/**
	 * @param $id
	 * @param $userNumber
	 * @return bool
	 */
    public function updateAdministrator($id, $userNumber) {
        $this->db->reset_query();

        if (!$this->existsAdministrator($userNumber)) {
            $this->db->where('id', $id);
            $data = array (
                'userNumber' => $userNumber
            );
            $this->db->update('administrator', $data);
            return true;
        } else {
            return false;
        }
    }

	/**
	 * @param $userNumber
	 */
    public function deleteAdministrator($userNumber) {
        $this->db->reset_query();

        if ($this->existsAdministrator($userNumber)) {
            $this->db->delete('administrator', array('userNumber' => $userNumber));
        }
    }

    /** If the userNumber exists in the administrators
     * @param $userNumber
     * @return Administrator
     */
    public function existsAdministrator($userNumber) {
        $this->db->reset_query();

        $this->db->where('userNumber', $userNumber);
        $query = $this->db->get('administrator');
        return $query->row();
    }
}
