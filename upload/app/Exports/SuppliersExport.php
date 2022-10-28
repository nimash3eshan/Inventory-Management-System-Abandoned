<?php
namespace App\Exports;

use App\Supplier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SuppliersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $items = Supplier::select('name', 'email', 'phone_number', 'address', 'city', 'state', 'zip', 'company_name', 'account','prev_balance', 'payment')->get();
        return $items;
    }

    public function headings(): array
    {
        return [
            'name', 'email', 'phone_number', 'address', 'city', 'state', 'zip', 'company_name', 'account','prev_balance', 'payment'
        ];
    }
}