<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPPATH . 'libraries/AbstractService.php';
/**
 * Class AdminCSVImportService This class provides methods for administator to import data from csv.
 *
 **/
class AdminCSVImportService extends AbstractService
{

	//Hardware
	const BARCODE = 0;
	const CATEGORY = 1;
	const PRODUCT = 2;
	const COMMENT = 3;
	const USER = 4;
	const RETURN_DATE = 5;
	const OUT_OF_SERVICE = 6;
	const RESERVED = 7;
	const DONATION = 8;

	//
	const DESIGNATION = 0;
	const ADMINCOMMENT = 2;


	public function __construct()
	{
		parent::__construct();

		$this->load->library("Admin/AdminBorrowingService");
		$this->load->library("Admin/AdminCategoryService");
		$this->load->library("Admin/AdminProductService");
		$this->load->library("Admin/AdminHardwareService");

	}





	// 0 : barcode
	// 1 : category
	// 2 : product
	// 3 : comment
	// 4 : user
	// 5 : return date
	// 6 : out of service
	// 7 : reserved (practical work)
	// 8 : donation


    /**
     * This method import into the data base : hardwares from the csv file in parameter
     * @param $path : the path of the csv file
     * @return array|false|null : the number of line import in the data base
     * @throws Exception : if the csv file doesn't exist or isn't readable
     */
	public function importHardwareFromCSV($path)
	{
		//create "Divers" category if not exists
		$divers = "Divers";
		try {
			$this->admincategoryservice->addCategory($divers);

		} catch (Exception $exception) {
			log_message("error", $exception);
			//the divers category already exists
		}
		$diversCategory = $this->admincategoryservice->getCategoryByName("Divers");
		$row = 0;
		$insertedRows = 0;
		$failedRows =0;

		//open the CSV file
		if (($handle = fopen($path, "r")) !== FALSE) {
			//for each row
			while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {

				$row += 1;
					try {
						if(sizeof($data)!=9){
							throw new Exception("Invalid format");
						}
						$category = null;




						//create the category if not exists
						if ($data[self::CATEGORY] !== null && $data[self::CATEGORY] != "") {
							try {
								$this->admincategoryservice->addCategory($data[self::CATEGORY]);

							} catch (Exception $exception) {
								// The category already exists
								log_message("info", "Hardware CSV Import row ".$row." ".$exception );

							}
							$category = $this->admincategoryservice->getCategoryByName($data[self::CATEGORY]);
						}


						//create the product if not exists
						if ($data[self::PRODUCT] !== null && $data[self::PRODUCT] != "") {
							try {
								// if the product has a category

								if ($data[self::CATEGORY] !== null && $data[self::CATEGORY] !== "") {
									$this->adminproductservice->addProduct($data[self::PRODUCT], "", $category->id);

								} // if the product doesn't have any category -> add it to "Divers" category
								else {
									if ($diversCategory != null) {
										$this->adminproductservice->addProduct($data[self::PRODUCT], "", $diversCategory->id);
									}
								}

							} catch (Exception $exception) {
								log_message("info","Hardware CSV Import row ".$row." ". $exception );
							}
							$product = $this->adminproductservice->getProductByName($data[self::PRODUCT]);
						}


						//create the hardware
						if ($data[self::BARCODE] !== null && $data[self::BARCODE] != "") {

							try {
								$this->adminhardwareservice->addHardware($data[self::BARCODE], $data[self::COMMENT], $product->id);

							} catch
							(Exception $exception) {
								log_message("info","Hardware CSV Import row ".$row." ". $exception );
								//the hardware already exists
							}
							$this->adminhardwareservice->getHardwareByBarcode($data[self::BARCODE]);

							if (strtolower($data[self::OUT_OF_SERVICE]) == 'true' || strtolower($data[self::OUT_OF_SERVICE]) == 'vrai' || strtolower($data[self::OUT_OF_SERVICE]) == 'vraie') {
								$this->adminhardwareservice->setHardwareOutOfService($data[self::BARCODE], true);
							}

							if (strtolower($data[self::RESERVED]) == 'true' || strtolower($data[self::RESERVED]) == 'vrai' || strtolower($data[self::RESERVED]) == 'vraie') {
								$this->adminhardwareservice->setHardwareReserved($data[self::BARCODE], true);
							}

							if (strtolower($data[self::DONATION]) == 'true' || strtolower($data[self::DONATION]) == 'vrai' || strtolower($data[self::DONATION]) == 'vraie') {
								$this->adminhardwareservice->setHardwareDonation($data[self::BARCODE], true);
							}

							//create the borrowings
							if ($data[self::USER] !== null && $data[self::USER] != null) {
								// TODO : Look for the usernumber whith his name and surname
								$hardwares = array();
								//if there is a return date -> put the date in the right format
								if ($data[self::RETURN_DATE] !== "" ) {
									$returnDate = date("Y-m-d", strtotime($data[self::RETURN_DATE]));
									if (strtotime($returnDate)< strtotime(date("Y-m-d")) ) {
										$hardware = array(
											'barcode' => $data[self::BARCODE],
											'startDate' => $returnDate,
											'endDate' => $returnDate,
											'adminComment' => null,
											'renderDate' => $returnDate,
											'userComment' => null
										);
									}
									else{
										$hardware = array(
											'barcode' => $data[self::BARCODE],
											'startDate' => $returnDate,
											'endDate' => $returnDate,
											'adminComment' => null,
											'renderDate' => null,
											'userComment' => null
										);
									}
								} //if there is no return date -> set end date in 2050
								else {

									$hardware = array(
										'barcode' => $data[self::BARCODE],
										'startDate' => date("Y-m-d"),
										'endDate' => "2050-12-31",
										'adminComment' => null,
										'renderDate' => null,
										'userComment' => null
									);

								}
								array_push($hardwares, $hardware);
								//TODO changer le userNumber avec celui trouvé
								try{
									$this->adminborrowingservice->addHardwareBorrowing(11111111, $hardwares);
									$insertedRows+=1;
								}catch(Exception $exception){
									//the hardware borowing already exists;
									log_message("info","Hardware CSV Import row ".$row." ". $exception );
								}


							}

						}

					} catch
					(Exception $exception) {
						log_message("error", $exception);
						$failedRows +=1 ;
					}

			}
			fclose($handle);
		} else {
			log_message("error", "ImportDataFromCSV : the file {$path} doesn't exist or isn't readable");
			throw new Exception("Erreur lors de la lecture du fichier : impossible d'ouvrir le fichier");
		}
		$data["insertedRows"] = $insertedRows;
		$data["failedRows"] = $failedRows;
		return $data;
	}

	public function importConsumableFromCSV($path)
	{


		$row = 0;
		$insertedRows = 0;
		$failedRows =0;

		//open the CSV file
		if (($handle = fopen($path, "r")) !== FALSE) {
			//for each row
			while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {

				$row += 1;
				try {
					if(sizeof($data)!=6){
						throw new Exception("Invalid format");
					}
					$category = null;


					//create the consummable borrowing
					if ($data[self::DESIGNATION] !== null && $data[self::DESIGNATION] != "") {

						try{
							$consumables = array();
							//if there is a return date -> put the date in the right format
							if ($data[self::RETURN_DATE] !== "" ) {
								$returnDate = date("Y-m-d", strtotime($data[self::RETURN_DATE]));

								if (strtotime($returnDate)< strtotime(date("Y-m-d")) ) {

									$consumable = array(
										'designation' => $data[self::DESIGNATION],
										'startDate' => $returnDate,
										'endDate' => $returnDate,
										'adminComment' => null,
										'renderDate' => $returnDate,
										'userComment' =>null
									);
								}
								else{
									$consumable = array(
										'designation' => $data[self::DESIGNATION],
										'startDate' => $returnDate,
										'endDate' => $returnDate,
										'adminComment' => null,
										'renderDate' => null,
										'userComment' => null
									);
								}

							} //if there is no return date -> set end date in 2050 and start date = today
							else {

								$consumable = array(
									'designation' => $data[self::DESIGNATION],
									'startDate' => date("Y-m-d"),
									'endDate' => "2050-12-31",
									'adminComment' => null,
									'renderDate' => null,
									'userComment' => null
								);

							}
							array_push($consumables, $consumable);
							$this->adminborrowingservice->addConsumableBorrowing(11111111, $consumables);
							$insertedRows+=1;
							}catch(Exception $exception){
								//the hardware borowing already exists;
								log_message("info","Hardware CSV Import row ".$row." ". $exception );
							}


						}



				} catch
				(Exception $exception) {
					log_message("error", $exception);
					$failedRows +=1 ;
				}

			}
			fclose($handle);
		} else {
			log_message("error", "ImportDataFromCSV : the file {$path} doesn't exist or isn't readable");
			throw new Exception("Erreur lors de la lecture du fichier : impossible d'ouvrir le fichier");
		}
		$data["insertedRows"] = $insertedRows;
		$data["failedRows"] = $failedRows;
		return $data;
	}


}
