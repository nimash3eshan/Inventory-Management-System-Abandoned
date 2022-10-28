<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalePayment extends Model
{
    protected $fillable = ['payment', 'sale_id', 'comments', 'payment_type'];

    public function sale()
    {
        return $this->belongsTo('App\Sale');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function saveSalePayment($sale)
    {
        if ($sale->status == Sale::REFUND) {
            $this->payment = -$sale->payment;
            $this->dues = 0;
            $this->comments = "REFUND";
        } else {
            $this->payment = $sale->payment;
            $this->dues = $sale->dues;
            $this->comments = $sale->comments;
        }
        $this->payment_type = $sale->payment_type;
        $this->sale_id = $sale->id;
        $this->user_id = $sale->user_id;
        $this->save();
    }

    public function deleteBySaleId($sale_id)
    {
        $this->where('sale_id', $sale_id)->delete();
    }

    public function getSalePayment($customer_id = null, $option = null)
    {
        if (!empty($customer_id)) {
            $results = $this->leftJoin('sales', 'sales.id', '=', 'sale_payments.sale_id')->where('sales.customer_id', $customer_id)->select('sale_payments.created_at', 'sale_payments.payment')->orderBy('sale_payments.id', 'desc')->paginate($option['paginate']);
            return $results;
        } else if (!empty($option['date'])) {
            $results = $this->with('sale.customer')->whereDate('created_at', $option['date'])->latest();
        } else if (!empty($option['start_date']) && !empty($option['end_date'])) {
            $results = $this->with('sale.customer')->whereBetween('created_at', [$option['start_date'] . ' 00:00:00', $option['end_date'] . ' 23:59:59'])->latest();
        } else {
            $results = $this->latest();
        }
        if (!empty($option['paginate'])) {
            $results = $results->paginate($option['paginate']);
        } else if (!empty($option['get'])) {
            $results = $results->get();
        }
        return $results;
    }
}
