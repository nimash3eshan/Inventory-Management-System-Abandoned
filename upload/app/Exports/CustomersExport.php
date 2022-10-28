<?php
namespace App\Exports;

use App\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $items = Customer::select('name', 'email', 'phone_number', 'address', 'city', 'state', 'zip', 'prev_balance', 'payment')->where('type', 1)->get();
        return $items;
    }

    public function headings(): array
    {
        return [
            'name', 'email', 'phone_number', 'address', 'city', 'state', 'zip', 'prev_balance', 'payment'
        ];
    }
}