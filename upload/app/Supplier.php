<?php

namespace App;

use App\Http\Controllers\Traits\FileUploadTrait;
use App\AppModel;

class Supplier extends AppModel
{
    use FileUploadTrait;

    protected $fillable = ['company_name', 'name', 'email', 'phone_number', 'address', 'city', 'state', 'zip', 'comments', 'account', 'prev_balance', 'payment'];

    public function updateSupplierBalance($supplier_id, $dues)
    {
        $supplier = $this->getById($supplier_id);
        $supplier->prev_balance = $supplier->prev_balance + $dues;
        $supplier->update();
    }

    public function saveSupplier(array $data)
    {
        $this->company_name = $data['company_name'];
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->phone_number = $data['phone_number'];
        $this->address = $data['address'];
        $this->city = $data['city'];
        $this->state = $data['state'];
        $this->zip = $data['zip'];
        $this->account = $data['account'];
        $this->prev_balance = !empty($data['prev_balance']) ? $data['prev_balance'] : 0;
        $this->payment = $data['payment'];
        $this->avatar = !empty($data['avatar']) ? $data['avatar'] : 'no-foto.png';
        $this->save();
    }

    public function getAll($option = null, $keys = null, $search = null)
    {
        $results = $this->latest();
        $per_page = !empty($search['per_page']) ? $search['per_page'] : 10;
        if (!empty($search)) {
            if (!empty($search['search'])) {
                $results = $results->where('name', 'LIKE', '%' . $search['search'] . '%')
                    ->orWhere('email', 'LIKE', '%' . $search['search'] . '%')
                    ->orWhere('phone_number', 'LIKE', '%' . $search['search'] . '%')
                    ->orWhere('address', 'LIKE', '%' . $search['search'] . '%')
                    ->orWhere('city', 'LIKE', '%' . $search['search'] . '%')
                    ->orWhere('state', 'LIKE', '%' . $search['search'] . '%')
                    ->orWhere('company_name', 'LIKE', '%' . $search['search'] . '%')
                    ->orWhere('account', 'LIKE', '%' . $search['search'] . '%');
            }
        }
        if ($option == 'paginate') {
            $results = $results->paginate($per_page);
        } else if ($option == 'select') {
            if (!empty($keys)) {
                $results = $results->pluck($keys[0], $keys[1]);
            } else {
                $results = $results->pluck('name', 'id');
            }
        } else {
            $results = $results->get();
        }
        return $results;
    }

    public function getById($id)
    {
        return $this->findOrFail($id);
    }

    public function processShowProfile($supplier_id)
    {
        $id = $supplier_id;
        $receivings = (new Receiving())->getReceivings(['supplier_id' => $id]);
        $data['total_receivings'] = $receivings->count();
        $data['total_dues'] = $receivings->sum('dues');
        $data['receivings'] = $receivings->paginate(10);
        $all_supplier_payments = (new SupplierPayment())->getSupplierPayment(['supplier_id' => $id]);
        $data['total_supplier_payment'] = $all_supplier_payments->sum('payment');
        $data['supplier_payments'] = $all_supplier_payments->paginate(3);
        $data['receiving_payments'] = (new ReceivingPayment())->getReceivingPayment($id, ['paginate' => 5]);
        return $data;
    }
}
