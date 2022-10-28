<?php

namespace App;

use Carbon\Carbon;
use App\AppModel;

class Item extends AppModel
{
    protected $guarded = [];

    public function inventory()
    {
        return $this->hasMany('App\Inventory')->orderBy('id', 'DESC');
    }

    public function receivingtemp()
    {
        return $this->hasMany('App\ReceivingTemp')->orderBy('id', 'DESC');
    }

    public function receivingitem()
    {
        return $this->hasMany('App\ReceivingItem')->orderBy('id', 'DESC');
    }

    public function saletemp()
    {
        return $this->hasMany('App\SaleTemp')->orderBy('id', 'DESC');
    }

    public function saleitem()
    {
        return $this->hasMany('App\SaleItem')->orderBy('id', 'DESC');
    }

    public function attributes()
    {
        return $this->hasMany('App\ItemAttribute', 'item_id', 'id')->orderBy('id', 'DESC');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function saveItem($input)
    {

        $result = false;
        $this->upc_ean_isbn = !empty($input['upc_ean_isbn']) ? $input['upc_ean_isbn'] : "";
        $this->item_name = $input['item_name'];
        $this->category_id = !empty($input['category_id']) ? $input['category_id'] : null;
        $this->size = !empty($input['size']) ? $input['size'] : "";;
        $this->description = !empty($input['description']) ? $input['description'] : "";
        $this->cost_price = $input['cost_price'];
        $this->selling_price = $input['selling_price'];
        $this->quantity = !empty($input['quantity']) ? $input['quantity'] : 1;
        $this->stock_limit = !empty($input['stock_limit']) ? $input['stock_limit'] : 0;
        $this->expire_date = !empty($input['expire_date']) ? $input['expire_date'] : null;
        $this->avatar = !empty($input['avatar']) ? $input['avatar'] : "no-foto.png";
        if ($this->save()) {
            $result = true;
        }
        return $result;
    }

    public function checkItemStock($saleItems)
    {
        $result = false;
        foreach ($saleItems as $value) {
            $item = $this->find($value->item_id);
            if ($item->quantity < $value->quantity && $item->stock_limit < $value->quantity) {
                $result = true;
            }
        }
        return $result;
    }

    public function updateItemQty($item_id, $qty, $attribute_id = null)
    {

        if (!empty($attribute_id)) {
            $attribute = $this->findOrFail($attribute_id);
            $attribute->quantity = $attribute->quantity + $qty;
            $attribute->save();
        } else {
            $item = $this->findOrFail($item_id);
            $item->quantity = $item->quantity + $qty;
            $item->save();
        }
    }

    public function getAll($option = null, $search = null)
    {
        $results = $this->with('category')->where(['type' => 1])->latest();
        $per_page = !empty($search['per_page']) ? $search['per_page'] : 10;
        if (!empty($search)) {
            if (!empty($search['search'])) {
                $results = $results->where('item_name', 'LIKE', '%' . $search['search'] . '%')
                    ->orWhere('upc_ean_isbn', 'LIKE', '%' . $search['search'] . '%')
                    ->orWhere('item_name', 'LIKE', '%' . $search['search'] . '%')
                    ->orWhere('size', 'LIKE', '%' . $search['search'] . '%')
                    ->orWhere('description', 'LIKE', '%' . $search['search'] . '%')
                    ->orWhere('cost_price', 'LIKE', '%' . $search['search'] . '%')
                    ->orWhere('selling_price', 'LIKE', '%' . $search['search'] . '%')
                    ->orWhere('quantity', 'LIKE', '%' . $search['search'] . '%');
            }
        }
        if ($option == 'paginate') {
            $results = $results->paginate($per_page);
        } else {
            $results = $results->get();
        }
        return $results;
    }

    public function getById($id)
    {
        $item = $this->findOrFail($id);
        return $item;
    }

    public function getStockLimit($items)
    {
        $stock_limit_items = [];
        foreach ($items as $item) {
            if ($item->quantity <= $item->stock_limit || $this->checkExpire($item->expire_date)) {
                $stock_limit_items[] = $item;
            }
        }
        return $stock_limit_items;
    }

    public function checkExpire($expire_date)
    {
        $result = false;
        if (!empty($expire_date)) {
            $now_date = Carbon::now();
            $expire = Carbon::parse($expire_date)->toDateString();
            $diff = $now_date->diffInDays($expire, false);
            if ($diff <= 0) {
                $result = true;
            }
        }
        return $result;
    }
}
