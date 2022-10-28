<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReceivingPayment extends Model
{
    protected $fillable = ['receiving_id','payment','user_id','payment_type','comments'];

    public function receiving()
    {
    	return $this->belongsTo('App\Receiving');
    }

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function getReceivingPayment($supplier_id=null, $option=null) {
        if(!empty($supplier_id)) {

            $results = $this->leftJoin('receivings', 'receivings.id', '=', 'receiving_payments.receiving_id')->where('receivings.supplier_id', $supplier_id)->select('receiving_payments.created_at', 'receiving_payments.payment')->orderBy('receiving_payments.id', 'desc')->paginate($option['paginate']);
            return $results;
        }else if(!empty($option['date'])) {
            $results = $this->with('receiving.supplier')->whereDate('created_at', '=', $option['date']);
        } else if (!empty($option['start_date']) && !empty($option['end_date'])) { 
            $results = $this->whereBetween('created_at', [ $option['start_date'].' 00:00:00', $option['end_date'].' 23:59:59'])->latest();
        } else {
            $results = $this->latest();
        }
        if(!empty($option['paginate'])) {
            $results = $results->paginate($option['paginate']);
        } else if (!empty($option['get'])) {
            $results = $results->get();
        }
        return $results;
    }

    public function saveReceivingPayment($receiving)
    {
        if ($receiving->status == Sale::REFUND) {
            $this->payment = - $receiving->payment;
            $this->dues = 0;
            $this->comments = "REFUND";
        } else {
            $this->payment = $receiving->payment;
            $this->dues = $receiving->dues;
            $this->comments = $receiving->comments;
        }
        $this->payment_type = $receiving->payment_type;
        $this->receiving_id = $receiving->id;
        $this->user_id = $receiving->user_id;
        $this->save();
    }
}
