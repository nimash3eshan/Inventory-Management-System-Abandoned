<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Expense extends Model
{
    protected $fillable = ['expense_category_id', 'qty', 'unit_price', 'total', 'payment', 'payment_type', 'dues', 'description'];

    public function expense_category()
    {
        return $this->belongsTo('App\ExpenseCategory');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getAll($filter = null, $option = null, $search = null)
    {
        if (!empty($search)) {
            $results = $this->latest();
            if (!empty($search['search'])) {
                $results = $results->where('unit_price', 'LIKE', '%' . $search['search'] . '%')
                    ->orWhere('description', 'LIKE', '%' . $search['search'] . '%')
                    ->orWhere('payment', 'LIKE', '%' . $search['search'] . '%')
                    ->orWhere('dues', 'LIKE', '%' . $search['search'] . '%')
                    ->orWhere('total', 'LIKE', '%' . $search['search'] . '%')
                    ->orWhere('payment_type', 'LIKE', '%' . $search['search'] . '%');
            }
            $option['paginate'] = !empty($search['per_page']) ? $search['per_page'] : 10;
        } else if (!empty($filter)) {
            $results = $this->with('user', 'expense_category')->where($filter)->latest();
        } else if (!empty($option['date'])) {
            $results = $this->whereDate('created_at', '=', $option['date'])->latest();
        } else if (!empty($option['start_date']) && !empty($option['end_date'])) {
            $results = $this->whereBetween('created_at', [$option['start_date'] . ' 00:00:00', $option['end_date'] . ' 23:59:59'])->latest();
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

    public function saveExpense($input)
    {
        $this->expense_category_id = $input['expense_category_id'];
        $this->description = $input['description'];
        $qty = $this->qty = !empty($input['qty']) ? $input['qty'] : 1;
        $unit_price = $this->unit_price = $input['unit_price'];
        $total = $this->total = $qty * $unit_price;
        $payment = $this->payment = !empty($input['payment']) ? $input['payment'] : $input['unit_price'];
        $this->dues = $total - $payment;
        $this->payment_type = $input['payment_type'];
        $this->user_id = Auth::user()->id;
        if ($this->save()) {
            return $this;
        }
        return false;
    }
}
