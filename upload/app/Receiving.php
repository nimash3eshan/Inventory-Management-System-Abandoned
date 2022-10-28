<?php

namespace App;

use App\Events\PurchaseInvoiceCreated;
use App\ReceivingItem;
use Illuminate\Database\Eloquent\Model;

class Receiving extends Model
{
    const DUE = 0;
    const PAID = 1;
    const HOLD = 2;
    const REFUND = 3;
    const INVOICE_PREFIX = 'RECV';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function supplier()
    {
        return $this->belongsTo('App\Supplier');
    }

    public static function receiving_detailed($receiving_id)
    {
        $receivingitems = ReceivingItem::where('receiving_id', $receiving_id)->get();
        return $receivingitems;
    }

    public function receivingItems()
    {
        return $this->hasMany('App\ReceivingItem');
    }

    public function saveReveiving($input, $refund = false)
    {
        $this->supplier_id = $input['supplier_id'];
        $this->user_id = $input['user_id'];
        $this->payment_type = $input['payment_type'];
        $this->comments = $input['comments'];
        $this->total = $input['total'];
        $this->invoice_no = $input['invoice_no'];
        if ($refund) {
            $this->payment = $this->payment - $input['payment'];
        } else {
            $this->payment = $input['payment'];
            $this->dues = $input['dues'];
        }
        $this->status = $input['status'];
        $this->save();
        return $this;
    }

    public function getStatus()
    {
        $status = '';
        if ($this->status == self::HOLD) {
            $status = '<span class="label label-danger">Hold</span>';
        } else if ($this->status == self::DUE) {
            $status = '<span class="label label-warning">Due</span>';
        } else if ($this->status == self::PAID) {
            $status = '<span class="label label-success">Paid</span>';
        }
        return $status;
    }

    public function getReceivings($options = null, $paginate = null, $search = null)
    {
        $per_page = !empty($search['per_page']) ? $search['per_page'] : 10;
        if (!empty($search['start_date']) || !empty($search['end_date'])) {
            $start_date = !empty($search['start_date']) ? $search['start_date'] : date('Y-m-d');
            $end_date = !empty($search['end_date']) ? $search['end_date'] : date('Y-m-d');
            $receivings = Receiving::with('receivingItems', 'user', 'receivingItems.item')->whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59'])->latest();
            if (!empty($search['supplier'])) {
                $receivings->where('supplier_id', $search['supplier']);
            }
        } else if (!empty($search['supplier'])) {
            $receivings = Receiving::with('receivingItems', 'user', 'receivingItems.item')->where('supplier_id', $search['supplier'])->latest();
        } else if (!empty($options['date'])) {
            $receivings = Receiving::whereDate('created_at', '=', $options['date']);
        } else {
            $receivings = Receiving::with('receivingItems', 'user', 'supplier', 'receivingItems.item')->where($options)->latest();
        }
        if (!empty($paginate) && $paginate == 'paginate') {
            $receivings = $receivings->paginate($per_page);
        } else if (!empty($paginate) && $paginate == 'get') {
            $receivings = $receivings->get();
        }
        return $receivings;
    }

    public function processReceiving($input, $receivingItems, $hold = false, $refund = false)
    {
        $input['dues'] = $dues = $input['total'] - $input['payment'];
        if ($dues > 0) {
            $input['status'] = Receiving::DUE;
        } else if ($dues == 0) {
            $input['status'] = Receiving::PAID;
        }
        if ($hold) {
            $input['status'] = Receiving::HOLD;
        } else if ($refund) {
            $input['status'] = Receiving::REFUND;
        }
        // saving Receivings
        $receiving = $this->saveReveiving($input, $refund);

        if (!$hold) {
            /*
            * updating customer prev Balance
            * Here payment is important for refund
            */
            event(new PurchaseInvoiceCreated($receiving, $input['account_id'], $input['payment']));
        }

        // Process Sale Items
        if (!empty($receivingItems)) {
            foreach ($receivingItems as $value) {
                $receivingItemsData = new ReceivingItem();
                $receivingItemsData->saveReceivingItem($value, $this->id);
                if (!$hold) {
                    //process inventory
                    $items = (new Item())->getById($value->item_id);
                    if ($items->type == 1) {
                        //process item quantity
                        $items->quantity = $items->quantity + $value->quantity;
                        $items->save();
                        $inventory_input = [
                            'item_id' => $value->item_id,
                            'attribute_id' => $value->attribute_id,
                            'user_id' => $this->user_id,
                            'quantity' => $value->quantity,
                            'remarks' => $this->invoice_no
                        ];
                        $inventories = new Inventory;
                        $inventories->saveInventory($inventory_input);
                    }
                }
            }
        }
        //delete all data on SaleTemp model
        ReceivingTemp::truncate();
        return $this;
    }

    public function getById($id)
    {
        return $this->findOrFail($id);
    }
}
