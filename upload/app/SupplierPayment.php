<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierPayment extends Model
{
    protected $fillable = ['supplier_id','payment','user_id','payment_type','comments'];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function supplier()
    {
    	return $this->belongsTo('App\Supplier');
    }

    public function getSupplierPayment($options=null, $paginate=null) {
        if (!empty($options['date'])) {
            $results = $this->with('user', 'supplier')->whereDate('created_at', '=', $options['date'])->latest();
        } else if (!empty($options['start_date']) && !empty($options['end_date'])) { 
            $results = $this->whereBetween('created_at', [ $options['start_date'].' 00:00:00', $options['end_date'].' 23:59:59'])->latest();
        } else if ($options) {
            $results = $this->with('user', 'supplier')->where($options)->latest();
        } else {
            $results = $this->latest();
        }
        if (!empty($paginate) && $paginate == 'paginate') {
            $results = $results->paginate(10);
        } else if (!empty($paginate) && $paginate == 'get') {
            $results = $results->get();
        }
        return $results;
    }
}
