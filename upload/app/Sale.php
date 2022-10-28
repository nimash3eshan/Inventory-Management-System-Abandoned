<?php

namespace App;

use App\Events\InvoiceCreated;
use Illuminate\Database\Eloquent\Model;
use App\SaleTemp;
use Illuminate\Support\Facades\Auth;

class Sale extends Model
{
    const HOLD = 0;
    const DUE = 1;
    const PAID = 2;
    const REFUND = 3;
    const INVOICE_PREFIX = 'SALE';

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public static function sale_detailed($sale_id)
    {
        $SaleItems = SaleItem::where('sale_id', $sale_id)->get();
        return $SaleItems;
    }

    public function saleItems()
    {
        return $this->hasMany('App\SaleItem');
    }

    public function sale_payment()
    {
        return $this->hasMany('App\SalePayment', 'sale_id', 'id');
    }

    public function saveSale($input, $refund = false)
    {
        $this->customer_id = $input['customer_id'];
        $this->user_id = $input['user_id'];
        $this->payment_type = $input['payment_type'];
        $this->comments = $input['comments'];
        $this->discount = $input['total_discount'];
        $this->tax = $input['tax'];
        $this->grand_total = $input['total'];
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

    public function processSale($input, $saleItems, $hold = false, $refund = false)
    {
        $input['total_discount'] = $total_discount = $input['discount'] + ($input['discount_percent'] * $input['subtotal']) / 100;
        $input['tax'] = $tax = ($input['subtotal'] * $input['tax_percent']) / 100;
        $input['total'] = $total = $input['subtotal'] + $tax - $total_discount;
        $input['dues'] = $dues = $total - $input['payment'];
        if ($dues > 0) {
            $input['status'] = Sale::DUE;
        } else if ($dues == 0) {
            $input['status'] = Sale::PAID;
        }
        if ($hold) {
            $input['status'] = Sale::HOLD;
        }
        if ($refund) {
            $input['status'] = Sale::REFUND;
        }
        // saving sales
        $sale = $this->saveSale($input, $refund);

        if (!$hold) {
            /*
            * updating customer prev Balance
            * Here payment is important for refund
            */
            event(new InvoiceCreated($sale, $input['account_id'], $input['payment']));
        }

        // Process Sale Items
        if (!empty($saleItems)) {
            foreach ($saleItems as $value) {
                $saleItemsData = new SaleItem();
                $saleItemsData->saveSaleItem($value, $this->id);
                if (!$hold) {
                    //process inventory

                    $items = Item::find($value->item_id);
                    $attributes = ItemAttribute::find($value->attribute_id);
                    if (!empty($attributes->quantity)) {
                        $attributes->quantity = $attributes->quantity - $value->quantity;
                        $attributes->save();
                    }
                    if ($items->type == 1) {
                        //process item quantity
                        $items->quantity = $items->quantity - $value->quantity;
                        $items->save();
                        $inventory_input = [
                            'item_id' => $value->item_id,
                            'attribute_id' => $value->attribute_id,
                            'user_id' => $this->user_id,
                            'quantity' => - ($value->quantity),
                            'remarks' => $this->invoice_no
                        ];
                        $inventories = new Inventory;
                        $inventories->saveInventory($inventory_input);
                    } else {
                        $itemkits = ItemKitItem::where('item_kit_id', $value->item_id)->get();
                        foreach ($itemkits as $item_kit_value) {
                            $inventories = new Inventory;
                            $inventories->item_id = $item_kit_value->item_id;
                            $inventories->user_id = Auth::user()->id;
                            $inventories->in_out_qty = - ($item_kit_value->quantity * $value->quantity);
                            $inventories->remarks = $this->invoice_no;
                            $inventories->save();
                            //process item quantity
                            $item_quantity = Item::find($item_kit_value->item_id);
                            $item_quantity->quantity = $item_quantity->quantity - ($item_kit_value->quantity * $value->quantity);
                            $item_quantity->save();
                            //process attribute quantity
                            $attribute_quantity = ItemAttribute::find($item_kit_value->attribute_id);
                            $attribute_quantity->quantity = $item_quantity->quantity - ($item_kit_value->quantity * $value->quantity);
                            $attribute_quantity->save();
                        }
                    }
                }
            }
        }
        //delete all data on SaleTemp model
        SaleTemp::truncate();
        return $this;
    }

    public function resetSale($option = null)
    {
        if ($this->status != self::HOLD) {
            // 1. customer deduct dues
            $customer = new Customer;
            $customer->updateCustomerBalance($this->customer_id, -$this->dues);

            // 2. delete salepayment
            if ($option != 'refund') {
                $sale_payment = new SalePayment;
                $sale_payment->deleteBySaleId($this->id);
            }
        }

        // 3. delete saleItem
        $sale_item = new SaleItem();
        $sale_item->deleteBySaleId($this->id);

        if ($this->status != self::HOLD) {
            // 4. update item qty
            // 5. Delete Inventory
            $inventory = new Inventory();
            $inventory->deleteBySaleIdAndResetItemQty($this->invoice_no);
        }
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
        } else if ($this->status == self::REFUND) {
            $status = '<span class="label label-danger">Refunded</span>';
        }
        return $status;
    }

    public function getById($id)
    {
        return $this->findOrFail($id);
    }

    public function getSales($options = null, $paginate = null, $search = null)
    {
        $per_page = !empty($search['per_page']) ? $search['per_page'] : 10;
        if (!empty($search['start_date']) || !empty($search['end_date'])) {
            $start_date = !empty($search['start_date']) ? $search['start_date'] : date('Y-m-d');
            $end_date = !empty($search['end_date']) ? $search['end_date'] : date('Y-m-d');
            $sales = Sale::with('saleItems', 'user', 'saleItems.item')->whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59'])->latest();
            if (!empty($search['customer'])) {
                $sales->where('customer_id', $search['customer']);
            }
        } else if (!empty($search['customer'])) {
            $sales = Sale::with('saleItems', 'user', 'saleItems.item')->where('customer_id', $search['customer'])->latest();
        } else if (!empty($options['date'])) {
            $sales = Sale::whereDate('created_at', '=', $options['date']);
        } else {
            $sales = Sale::with('saleItems', 'user', 'customer', 'saleItems.item')->where($options)->latest();
        }
        if (!empty($paginate) && $paginate == 'paginate') {
            $sales = $sales->paginate($per_page);
        } else if (!empty($paginate) && $paginate == 'get') {
            $sales = $sales->get();
        }
        return $sales;
    }
}
