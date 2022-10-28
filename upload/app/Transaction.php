<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    const PAYMENT = 1;
    const RECEIPT = 2;
    const CHARGE = 3;

    protected $fillable = ['transaction_type', 'amount', 'transaction_with', 'account_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function updateAccountBalance($action)
    {
        $account = Account::findOrFail($this->account_id);
        if ($action == 'delete') {
            if ($this->transaction_type == Transaction::RECEIPT) {
                $account->balance = $account->balance - $this->amount;
            } else {
                $account->balance = $account->balance + $this->amount;
            }
        } elseif ($action == 'create') {
            if ($this->transaction_type == Transaction::RECEIPT) {
                $account->balance = $account->balance + $this->amount;
            } else {
                $account->balance = $account->balance - $this->amount;
            }
        }
        $account->update();
    }

    public function getAll($option=null) {
        if($option=='paginate') {
            return $this->with('user','account')->latest()->paginate('10');
        }
        return $this->with('user','account')->latest()->get();
    }
}
