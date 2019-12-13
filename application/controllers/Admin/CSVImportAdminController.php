<?php
/**
 * Created by PhpStorm.
 * User: nathS
 * Date: 28/04/2019
 * Time: 12:25
 */

class CSVImportAdminController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library('Admin/AdminHardwareRequestService');
	}

	public function importCSV(){
		$data["nbUnreadRequests"]= $this->adminhardwarerequestservice->countUnreadRequests();
		$this->load->view('AdministratorPages/AdministratorMenu', $data);
		$this->load->view('AdministratorPages/AdministratorCSVImport');

	}

	public function importHardware()
	{
		$config['upload_path'] = './assets/uploads/';
		$config['allowed_types'] = 'csv';


		$this->load->library('upload', $config);
		$data["nbUnreadRequests"]= $this->adminhardwarerequestservice->countUnreadRequests();
		if (!$this->upload->do_upload('csvfile')) {

			$data["error"] =  "Fichier invalide";
			$this->load->view('AdministratorPages/AdministratorMenu', $data);
			$this->load->view('ErrorSuccess',$data);
			$this->load->view('AdministratorPages/AdministratorCSVImport');
		} else {
			$data = $this->upload->data();
			$filePath = $data['full_path'];
			$this->load->library('Admin/AdminCSVImportService');
			try {
				$result = $this->admincsvimportservice->importHardwareFromCSV($filePath);
				$this->load->view('AdministratorPages/AdministratorMenu', $data);
				$data["success"] = $result["insertedRows"]." lignes affectées";
				$this->load->view('ErrorSuccess',$data);
				$this->load->view('AdministratorPages/AdministratorCSVImport');
			} catch (Exception $exception) {
				$data["error"] = $exception;
				$this->load->view('AdministratorPages/AdministratorMenu', $data);
				$this->load->view('ErrorSuccess',$data);
				$this->load->view('AdministratorPages/AdministratorCSVImport');
			}


		}
	}

	public function importConsumable()
	{
		$config['upload_path'] = './assets/uploads/';
		$config['allowed_types'] = 'csv';


		$this->load->library('upload', $config);
		$data["nbUnreadRequests"]= $this->adminhardwarerequestservice->countUnreadRequests();
		if (!$this->upload->do_upload('csvfile')) {

			$data["error"] =  "Fichier invalide";
			$this->load->view('AdministratorPages/AdministratorMenu', $data);
			$this->load->view('ErrorSuccess',$data);
			$this->load->view('AdministratorPages/AdministratorCSVImport');
		} else {
			$data = $this->upload->data();
			$filePath = $data['full_path'];
			$this->load->library('Admin/AdminCSVImportService');
			try {
				$result = $this->admincsvimportservice->importConsumableFromCSV($filePath);
				$this->load->view('AdministratorPages/AdministratorMenu', $data);
				$data["success"] = $result["insertedRows"]." lignes affectées";
				$this->load->view('ErrorSuccess',$data);
				$this->load->view('AdministratorPages/AdministratorCSVImport');
			} catch (Exception $exception) {
				$data["error"] = $exception;
				$this->load->view('AdministratorPages/AdministratorMenu', $data);
				$this->load->view('ErrorSuccess',$data);
				$this->load->view('AdministratorPages/AdministratorCSVImport');
			}


		}
	}
}

?>
