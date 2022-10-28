<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PaymentType extends Model
{
    public function savePaymentType($input)
    {
        $this->name = $input['name'];
        $this->slug = Str::slug($input['name']);
        if($this->save()) {
            return true;
        }
        return false;
    }

    public function getAll($option=null, $type=null)
    {
        if($type == 'all') {
            return $this->latest()->get();
        }
        if($option == 'select') {
            return $this->where(['status'=>1])->latest()->pluck('name', 'slug');
        }
        return $this->where(['status'=>1])->latest()->get();
    }

    public function updatePaymentType($input)
    {
        $this->status = $input['status'];
        $this->save();
    }

    public function getById($id) {
        return $this->findOrFail($id);
    }
}
