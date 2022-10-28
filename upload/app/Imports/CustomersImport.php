<?php

namespace App\Imports;

use App\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomersImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return Customer|null
     */
    public function model(array $row)
    {
        return new Customer([
           'name'=> $row['name'],
           'email'=> $row['email'],
           'phone_number'    => $row['phone_number'],
           'address' => $row['address'],
           'city'=> $row['city'],
           'state'=> $row['state'],
           'zip'=> $row['zip'],
           'prev_balance'=> !empty($row['prev_balance']) ? $row['prev_balance'] : 0,
           'payment'=> !empty($row['payment']) ? $row['payment'] : 0
        ]);
    }
}