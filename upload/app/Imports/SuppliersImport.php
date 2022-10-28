<?php

namespace App\Imports;

use App\Supplier;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SuppliersImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return Supplier|null
     */
    public function model(array $row)
    {
        return new Supplier([
           'name'=> $row['name'],
           'email'=> $row['email'],
           'phone_number'    => $row['phone_number'],
           'address' => $row['address'],
           'city'=> $row['city'],
           'state'=> $row['state'],
           'zip'=> $row['zip'],
           'company_name'=> $row['company_name'],
           'account'=> $row['account'],
           'prev_balance'=> !empty($row['prev_balance']) ? $row['prev_balance'] : 0,
           'payment'=> !empty($row['payment']) ? $row['payment'] : 0
        ]);
    }
}