<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPPATH . 'libraries/AbstractService.php';
/**
 * Class AdminHardwareService This class provides methods for administator to manage hardware.
 *
 **/
class AdminHardwareService extends AbstractService
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model("Hardware", "hardware");
        $this->load->model("Product", "product");
        $this->load->model("Category", "category");
    }

    /**
     * This method create a new hardware with the given specifications and insert it in the database
     * @param $barcode : the barcode of the new hardware
     * @param $comment : comments concerning the new hardware
     * @param $idProduct : the id of the product which correspond to the new hardware
     * @throws Exception : if the hardware already exist
     */
    public function addHardware($barcode, $comment, $idProduct)
    {
        if ($this->hardware->getHardwareByBarCode($barcode) == null)
        {
            $this->hardware->insertHardware($barcode, $comment, $idProduct);
        }
        else
        {
            log_message("error", "AddHardware : the hardware {$barcode} already exist");
            throw new Exception("AddHardware : the hardware {$barcode} already exist");
        }
    }

    /**
     * This method modify the hardware which correspond to the given barcode with the given specifications
     * @param $barcode : the barcode to look for
     * @param $comment : new comments concerning the hardware
     * @throws Exception : if the hardware already exist
     */
    public function modifyHardware($barcode, $comment)
    {
        $this->hardware->updateHardware($barcode, $comment);
    }

    /**
     * This method return all hardware contained in the data base
     * @return mixed : an array of hardware
     */
    public function getAllHardware()
    {
        return $this->hardware->getHardwares();
    }

    /**
     * This method return all hardware which correspond to the given parameters
     * The list is filtered by the product name
     * If the given boolean are false the list will contain only hardware in service, not reserved and not donated
     * If a boolean is true the list will contain all hardware which have the same boolean at true
     * @param $productName : the name of the product to look for
     * @param $isOutOfService : tell if we search out of service hardware
     * @param $isReserved : tell if we search reserved hardware
     * @param $isDonated : tell if we search donated hardware
     * @return array : the list of hardware filtered
     */
    public function getAllHardwareFilteredByProductName($productName, $isOutOfService, $isReserved, $isDonated)
    {
        $hardwareFiltered = array();
        $hardware = $this->getHardwareByProductName($productName);

        foreach ($hardware as $item)
        {
            if($isOutOfService === true || $isReserved === true || $isDonated === true)
            {
                if($isOutOfService === true && $item->outOfService == true)
                {
                    $hardwareFiltered[] = $item;
                }
                elseif($isReserved === true && $item->reserved == true)
                {
                    $hardwareFiltered[] = $item;
                }
                elseif($isDonated === true && $item->donation == true)
                {
                    $hardwareFiltered[] = $item;
                }
            }
            elseif($item->outOfService == false && $item->reserved == false && $item->donation == false)
            {
                $hardwareFiltered[] = $item;
            }
        }

        return $hardwareFiltered;
    }

    /**
     * This method return all hardware borrowable at this time
     * @return mixed : an array of hardware
     */
    public function getAllBorrowableHardware()
    {
        return $this->hardware->getHardwaresNotBorrowedToday();
    }

    /**
     * This method return all hardware borrowed at this time
     * @return mixed : an array of hardware
     */
    public function getAllBorrowedHardware()
    {
        return $this->hardware->getHardwaresBorrowedToday();
    }

    /**
     * This method return all hardware which is in the given category
     * @param $categoryName : the category name to look for
     * @return mixed : an array of hardware
     */
    public function getHardwareByCategoryName($categoryName)
    {
        return $this->category->getHardwares($categoryName);
    }

    /**
     * This method return all hardware which correspond to the given product name
     * @param $productName : the product name to look for
     * @return mixed : an array od hardware
     */
    public function getHardwareByProductName($productName)
    {
        return $this->product->getHardwares($productName);
    }

    /**
     * This method return the hardware which correspond to the given barcode
     * @param $barcode : the barcode to look for
     * @return mixed : the asked hardware if it exist
     */
    public function getHardwareByBarcode($barcode)
    {
        return $this->hardware->getHardwareByBarCode($barcode);
    }

    /**
     * This method set if an hardware is reserved or not
     * @param $barCode : the barcode of the hardware to look for
     * @param $reserved : tell if the hardware is reserved
     */
    public function setHardwareReserved($barCode, $reserved)
    {
        if($reserved)
        {
            $this->hardware->setReserved($barCode);
        }
        else
        {
            $this->hardware->setNotReserved($barCode);
        }
    }

    /**
     * This method set if an hardware is out of service
     * @param $barCode : the barcode of the hardware to look for
     * @param $outOfService : tell if the hardware is out of service
     */
    public function setHardwareOutOfService($barCode, $outOfService)
    {
        if($outOfService)
        {
            $this->hardware->setOutOfService($barCode);
        }
        else
        {
            $this->hardware->setInService($barCode);
        }
    }

    /**
     * This method set if an hardware is donated
     * @param $barCode : the barcode of the hardware to look for
     * @param $donated : tell if the hardware is donated
     */
    public function setHardwareDonation($barCode, $donated)
    {
        if($donated)
        {
            $this->hardware->setDonationTrue($barCode);
        }
        else
        {
            $this->hardware->setDonationFalse($barCode);
        }
    }

    /**
     * This method remove permanently from the data base the hardware which correspond to the given barcode
     * @param $barCode : the barcode to look for
     */
    public function removeHardwarePermanently($barCode)
    {
        $this->hardware->deleteHardware($barCode);
    }
}
