<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ReceivingTemp extends Model {

	public function item()
    {
        return $this->belongsTo('App\Item');
    }

    public function attribute(){
        return $this->belongsTo('App\ItemAttribute', 'attribute_id');
    }

    public function saveRecivingTemp($input) {
        $this->item_id = $input['item_id'];
        $this->attribute_id = !empty($input['attribute_id']) ? $input['attribute_id'] : null;
        $this->cost_price = $input['cost_price'];
        $this->quantity = !empty($input['quantity']) ? $input['quantity'] : 1;
        $this->total_cost = $input['cost_price'] * $this->quantity;
        if($this->save()) {
            return $this;
        } 
        return false;
    }

}
