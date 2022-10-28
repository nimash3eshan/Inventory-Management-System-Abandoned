<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function saveInventory($input)
    {
        $this->item_id = $input['item_id'];
        $this->attribute_id = !empty($input['attribute_id']) ? $input['attribute_id'] : null;
        $this->user_id = $input['user_id'];
        $this->in_out_qty = $input['quantity'];
        $this->remarks = $input['remarks'];
        $this->save();
    }

    public function deleteBySaleIdAndResetItemQty($sale_invoice_no)
    {
        $inventories = $this->where('remarks', $sale_invoice_no)->get();
        foreach ($inventories as $inventory) {
            $item = new Item();
            $item->updateItemQty($inventory->item_id, (-$inventory->in_out_qty), $inventory->attribute_id);
        }
        $this->where('remarks', $sale_invoice_no)->delete();
    }

    public function getAll($filter, $option)
    {
        if (!empty($filter['item_ids'])) {
            $results = $this->whereIn('item_id', $filter['item_ids']);
        }
        if (!empty($option['paginate'])) {
            $results = $results->paginate($option['paginate']);
        } else if (!empty($option['get'])) {
            $results = $results->get();
        }
        return $results;
    }
}
