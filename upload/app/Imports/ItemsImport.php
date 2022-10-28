<?php

namespace App\Imports;

use App\Item;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ItemsImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return Item|null
     */
    public function model(array $row)
    {
        return new Item([
           'upc_ean_isbn'=> $row['upc_ean_isbn'],
           'item_name'=> $row['item_name'],
           'size'    => $row['size'],
           'description' => $row['description'],
           'selling_price'=> $row['selling_price'],
           'cost_price'=> $row['cost_price'],
           'quantity'=> !empty($row['quantity']) ? $row['quantity'] : 0,
           'type'=> !empty($row['type']) ? $row['type'] : 1,
           'stock_limit'=> !empty($row['stock_limit']) ? $row['stock_limit'] : 0,  
           'expire_date'=> !empty($row['expire_date'] && $this->isValidDateTime($row['expire_date'])) ? $row['expire_date'] : date('Y-m-d', strtotime('+1 Month')),  
        ]);
    }

    public function isValidDateTime($dateTime)
    {    
        if (preg_match("/^(\d{4})-(\d{2})-(\d{2})$/", $dateTime, $matches)) 
        {     
            if (checkdate($matches[2],$matches[3], $matches[1])) {             
                return true;        
            }    
        }     
        return false; 
    } 
}