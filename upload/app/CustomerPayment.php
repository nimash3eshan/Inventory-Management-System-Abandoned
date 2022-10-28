<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerPayment extends Model
{
    protected $fillable = ['payment', 'customer_id', 'payment_type', 'user_id','comments'];

    public function customer()
    {
    	return $this->belongsTo('App\Customer');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function getCustomerPayment($options=null, $paginate=null) {
        if (!empty($options['date'])) {
            $results = $this->with('user')->whereDate('created_at', '=', $options['date'])->latest();
        } else if (!empty($options['start_date']) && !empty($options['end_date'])) { 
            $results = $this->whereBetween('created_at', [ $options['start_date'].' 00:00:00', $options['end_date'].' 23:59:59'])->latest();
        } else if(!empty($options)) {
            $results = $this->with('user', 'customer')->where($options)->latest();
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
