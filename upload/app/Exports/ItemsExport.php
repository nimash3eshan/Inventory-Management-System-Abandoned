<?php
namespace App\Exports;

use App\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ItemsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $items = Item::select('upc_ean_isbn', 'item_name', 'size', 'description', 'selling_price', 'cost_price', 'quantity', 'type', 'stock_limit','expire_date')->where('type', 1)->get();
        return $items;
    }

    public function headings(): array
    {
        return [
            'upc_ean_isbn',
            'item_name',
            'size',
            'description',
            'selling_price',
            'cost_price',
            'quantity',
            'type',
            'stock_limit',
            'expire_date'
        ];
    }
}