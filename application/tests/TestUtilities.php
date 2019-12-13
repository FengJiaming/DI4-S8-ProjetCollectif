<?php


class TestUtilities
{
    /**
     * !!! WARNING !!!
     * This method delete all data contained in the database
     * Use it only in transaction for more safety
     * @param $CI : the access to all class in the project
     */
    public static function deleteAllDbData($CI)
    {
        $CI->load->model("administrator");
        $CI->load->model("basketproduct");
        $CI->load->model("category");
        $CI->load->model("consumableborrowing");
        $CI->load->model("hardware");
        $CI->load->model("hardwareborrowing");
        $CI->load->model("product");
        $CI->load->model("request");

        $tableData = $CI->administrator->getAdministrators();
        if(sizeof($tableData) != 0)
        {
            foreach ($tableData as $line)
            {
                echo $line->userNumber;
                $CI->administrator->deleteAdministrator($line->userNumber);
            }
        }

        $tableData = $CI->consumableborrowing->getConsumableBorrowings();
        if(sizeof($tableData) != 0)
        {
            foreach ($tableData as $line)
            {
                $CI->consumableborrowing->deleteConsumableBorrowing($line->id);
            }
        }

        $tableData = $CI->hardwareborrowing->getHardwareBorrowings();
        if(sizeof($tableData) != 0)
        {
            foreach ($tableData as $line)
            {
                $CI->hardwareborrowing->deleteHardwareBorrowing($line->id);
            }
        }

        $tableData = $CI->basketproduct->getBasketProducts();
        if(sizeof($tableData) != 0)
        {
            foreach ($tableData as $line)
            {
                $CI->basketproduct->deleteBasketProduct($line->id);
            }
        }

        $tableData = $CI->hardware->getHardwares();
        if(sizeof($tableData) != 0)
        {
            foreach ($tableData as $line)
            {
                $CI->hardware->deleteHardware($line->barCode);
            }
        }

        $tableData = $CI->product->getProducts();
        if(sizeof($tableData) != 0)
        {
            foreach ($tableData as $line)
            {
                $CI->product->deleteProduct($line->id);
            }
        }

        $tableData = $CI->category->getCategories();
        if(sizeof($tableData) != 0)
        {
            foreach ($tableData as $line)
            {
                $CI->category->deleteCategory($line->id);
            }
        }

        $tableData = $CI->request->getRequests();
        if(sizeof($tableData) != 0)
        {
            foreach ($tableData as $line)
            {
                $CI->request->deleteRequest($line->id);
            }
        }
    }
}