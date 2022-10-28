<?php

namespace App;

use App\Http\Controllers\Traits\FileUploadTrait;
use App\AppModel;

class Customer extends AppModel
{
    use FileUploadTrait;
    const WALKING_CUSTOMER = "Walking Customer";

    protected $fillable = ['name', 'email', 'phone_number', 'prev_balance', 'payment'];

    public function customerPayments()
    {
        return $this->hasMany('App\CustomerPayment');
    }

    public function saveCustomer(array $data)
    {
        $this->name = $data['name'];
        $this->email = (isset($data['email'])) ? $data['email'] : "";
        $this->phone_number = (isset($data['phone_number'])) ? $data['phone_number'] : "";
        $this->address = (isset($data['address'])) ? $data['address'] : "";
        $this->city = (isset($data['city'])) ? $data['city'] : "";
        $this->state = (isset($data['state'])) ? $data['state'] : "";
        $this->zip = (isset($data['zip'])) ? $data['zip'] : "";
        $this->prev_balance = (isset($data['prev_balance'])) ? $data['prev_balance'] : 0;
        $this->payment = (isset($data['payment'])) ? $data['payment'] : 0;
        $this->avatar = !empty($data['avatar']) ? $data['avatar'] : 'no-foto.png';
        $this->save();
    }

    public function updateCustomerBalance($customer_id, $dues, $payment = null)
    {
        $customer = Customer::findOrFail($customer_id);
        $customer->prev_balance = $customer->prev_balance + $dues;
        if (!empty($payment)) {
            $customer->payment = $customer->payment + $payment;
        }
        $customer->update();
    }

    public function getAll($option = null, $search = null)
    {
        $results = $this->where(['type' => 1])->latest();
        $per_page = !empty($search['per_page']) ? $search['per_page'] : 10;
        if (!empty($search)) {
            if (!empty($search['search'])) {
                $results = $results->where('name', 'LIKE', '%' . $search['search'] . '%')
                    ->orWhere('email', 'LIKE', '%' . $search['search'] . '%')
                    ->orWhere('phone_number', 'LIKE', '%' . $search['search'] . '%')
                    ->orWhere('address', 'LIKE', '%' . $search['search'] . '%')
                    ->orWhere('city', 'LIKE', '%' . $search['search'] . '%')
                    ->orWhere('state', 'LIKE', '%' . $search['search'] . '%');
            }
        }
        if ($option == 'paginate') {
            return $results->paginate($per_page);
        } else if ($option == 'select') {
            return $results->pluck('name', 'id');
        } else {
            return $results->get();
        }
    }

    public function getById($id)
    {
        return $this->findOrFail($id);
    }

    /**
     * @param $customer_id
     * A helping function for showing profile
     */
    public function processShowProfile($customer_id)
    {
        $id = $customer_id;
        $sales = (new Sale())->getSales([['customer_id', $id]]);
        $data['total_sales'] = $sales->count();
        $data['total_dues'] = $sales->sum('dues');
        $data['sales'] = $sales->paginate(10);

        $customer_payments = (new CustomerPayment())->getCustomerPayment(['customer_id' => $id]);
        $data['total_customer_payment'] = $customer_payments->sum('payment');
        $data['customer_payments'] = $customer_payments->paginate(3);

        $data['sale_payments'] = (new SalePayment())->getSalePayment($id, ['paginate' => 5]);
        return $data;
    }
}
