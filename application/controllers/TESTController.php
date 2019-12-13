<form action="" method="post">
    <input type='text' id="login" name="number" value = "11111111">
    <input type="submit" name="submit" value="提交">
    <input type="submit" name="ajouter_panier" value="Ajouter Produit 1 a panier">
    <input type="submit" name="validate" value="validate basket">
</form>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class TESTController extends CI_Controller
{
    public function index()
    {
        $this->load->model('category');
        $this->load->model('hardware');
        $this->load->model('hardwareBorrowing');
        $this->load->model("basketproduct");
        $this->load->model("borrowing");

        $this->load->library("User/BasketService");
        $this->load->library("Admin/AdminCSVImportService");
        $this->load->library("ConnectionService");

        $this->load->library("LDAPService");

		var_dump($this->hardwareborrowing->getHardwareBorrowingsByUserId(1111111));

        //$this->admincsvimportservice->importHardwareFromCSV('C:\wamp64\www\projetco\application\ressources\CSV.csv');
        //$this->ldapservice->login("gauss","Pop2212!");
/*
        if($_POST['submit']) {
            $this->connectionservice->login($_POST["number"]);
        }

        $user = $this->connectionservice->getConnectedUser();
        var_dump($user);

        echo $user["id"];
        if($_POST['ajouter_panier']) {
            $this->basketservice->addProduct('2019-03-29', '2019-03-30', 1);
        }
        $baskets = $this->basketservice->listAllBasket();
        var_dump($baskets);

<<<<<<<<< Temporary merge branch 1
        // $this->hardware->insertHardware(1544564, "h1", "", 1);
		$availableHardware = $this->hardware->getAvailableHardwaresByProduct(1);
		foreach($availableHardware as &$hardware){
			var_dump($hardware);
		}
=========
        if($_POST['validate']) {
            $this->basketservice->validateBasket();
        }
>>>>>>>>> Temporary merge branch 2

      */

    }

    private function _get_role($username) {

        $filter = '('.$this->member_attribute.'='.$username.')';
        $search = ldap_search($this->ldapconn, $this->basedn, $filter, array('cn'));
        if(! $search ) {
            log_message('error', "Error searching for group:".ldap_error($this->ldapconn));
            show_error('Couldn\'t find groups: '.ldap_error($this->ldapconn));
        }
        $results = ldap_get_entries($this->ldapconn, $search);
        if($results['count'] != 0) {
            for($i = 0; $i < $results['count']; $i++) {
                $role = array_search($results[$i]['cn'][0], $this->roles);
                if($role !== FALSE) {
                    return $role;
                }
            }
        }
        return false;
    }


    public function login() {

    }
    public function getCategories() {
        $this->load->model('category');
        $categories['query'] = $this->category->getCategories();
    }
}
