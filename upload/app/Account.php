<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['name', 'company', 'branch_name', 'account_no', 'pin', 'email', 'balance', 'user_id'];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function getAll($option = null)
    {
        if ($option == 'paginate') {
            return $this->with('user')->latest()->paginate(10);
        } else if ($option == 'select') {
            return $this->pluck('name', 'id');
        }
        return $this->with('user')->latest()->get();
    }

    public function getAccount($opt = null)
    {
        return $this->where($opt)->first();
    }

    public function saveAccount($input)
    {
        $this->name = $input['name'];
        $this->company = $input['company'];
        $this->user_id = $input['user_id'];
        $this->branch_name = !empty($input['branch_name']) ? $input['branch_name'] : null;
        $this->account_no = !empty($input['account_no']) ? $input['account_no'] : null;
        $this->pin = !empty($input['pin']) ? $input['pin'] : null;
        $this->email = !empty($input['email']) ? $input['email'] : null;
        $this->balance = !empty($input['balance']) ? $input['balance'] : 0;
        if ($this->save()) {
            return $this;
        }
        return false;
    }

    public function updateBalance($account_id, $debit = null, $credit = null)
    {
        $account = $this->findOrFail($account_id);
        if (!empty($debit)) {
            $account->balance += $debit;
        }
        if (!empty($credit)) {
            $account->balance -= $credit;
        }
        $account->save();
    }
}
