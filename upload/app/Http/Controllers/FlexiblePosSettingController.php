<?php

namespace App\Http\Controllers;

use App\FlexiblePosSetting;
use App\Http\Controllers\Traits\FileUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Currency;
use App\PaymentType;

class FlexiblePosSettingController extends Controller
{
    use FileUploadTrait;
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $flexiblepossetting = FlexiblePosSetting::orderBy('created_at', 'desc')->get();
        $payment_types = (new PaymentType())->getAll(null, 'all');
        if ($flexiblepossetting->count() > 0) {
            $flexiblepossetting = FlexiblePosSetting::first();
            return $this->edit($flexiblepossetting->id);
        } else {
            $flexiblepossetting = [];
            $currency = new Currency();
            $currencies = $currency->getAll();
            return view('flexiblepos-setting.index', compact('flexiblepossetting', 'currencies', 'payment_types'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $this->validator($input)->validate();
        if (!empty($input['logo_path'])) {
            $input['logo_path'] = $this->uploadImage($request->logo_path, "images/company");
        }
        if (!empty($input['fevicon_path'])) {
            $input['fevicon_path'] = $this->uploadImage($request->fevicon_path, "images/company");
        }
        $setting = new FlexiblePosSetting();
        $setting->saveSettings($input);
        if ($setting->status) {
            $notify = __('You have successfully saved settings.');
        } else {
            $notify = ['danger' => __('You have successfully saved settings.')];
        }
        $data['flexiblepossetting'] = $setting;
        $currency = new Currency();
        $data['currencies'] = $currency->getAll();
        $data['currency_code'] = $data['flexiblepossetting']->currency_code;
        $data['payment_types'] = (new PaymentType())->getAll(null, 'all');
        return $this->sendCommonResponse($data, $notify, 'settings');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $flexiblepossetting = FlexiblePosSetting::findOrFail($id);
        $currency = new Currency();
        $currencies = $currency->getAll();
        $currency_code = $flexiblepossetting->currency_code;
        $payment_types = (new PaymentType())->getAll(null, 'all');
        return view('flexiblepos-setting.index', compact('flexiblepossetting', 'currencies', 'currency_code', 'payment_types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $this->validator($input)->validate();
        $flexiblepossetting = FlexiblePosSetting::findOrFail($id);
        if (!empty($input['logo_path'])) {
            $input['logo_path'] = $this->uploadImage($request->logo_path, "images/company");
            if (file_exists($flexiblepossetting->logo_path) && $flexiblepossetting->logo_path != 'images/fpos.png') {
                unlink($flexiblepossetting->logo_path);
            }
        }
        if (!empty($input['fevicon_path'])) {
            $input['fevicon_path'] = $this->uploadImage($request->fevicon_path, "images/company");
            if (file_exists($flexiblepossetting->fevicon_path) && $flexiblepossetting->fevicon_path != 'images/fevicon.png') {
                unlink($flexiblepossetting->fevicon_path);
            }
        }
        $flexiblepossetting = FlexiblePosSetting::findOrFail($id);
        $flexiblepossetting->saveSettings($input);
        if ($flexiblepossetting->status) {
            $notify = __('You have successfully update settings.');
        } else {
            $notify = ['danger' => __('Your settings can not be update.')];
        }
        $data['flexiblepossetting'] = $flexiblepossetting;
        $currency = new Currency();
        $data['currencies'] = $currency->getAll();
        $data['currency_code'] = $flexiblepossetting->currency_code;
        $data['payment_types'] = (new PaymentType())->getAll(null, 'all');
        return $this->sendCommonResponse($data, $notify, 'settings');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'logo_path' => 'mimes:jpeg,bmp,png|max:5120kb',
            'fevicon_path' => 'mimes:jpeg,bmp,png|max:5120kb',
            'language' => 'required|max:2|min:2',
            "company_name" => "required|max:100",
            "starting_balance" => 'required|numeric|max:9999999999',
            "company_address" => "required"
        ]);
    }

    public function addPaymentType(Request $request)
    {
        $input = $request->all();
        $this->paymentTypeValidator($input)->validate();
        $payment_type = new PaymentType();
        $payment_type->savePaymentType($input);
        $data['payment_types'] = (new PaymentType())->getAll(null, 'all');
        return $this->sendCommonResponse($data, __('Payment type added!'), 'add-payment-type');
    }

    public function updatePaymentType($id)
    {
        $payment_typeObj = new PaymentType();
        $payment_type = $payment_typeObj->getById($id);
        $input['status'] = ($payment_type->status == 1) ? 0 : 1;
        $payment_type->updatePaymentType($input);
        $data['payment_types'] = (new PaymentType())->getAll(null, 'all');
        return $this->sendCommonResponse($data, __('Payment type updated!'), 'add-payment-type');
    }

    public function paymentTypeValidator(array $data)
    {
        return Validator::make($data, [
            "name" => "required|max:100"
        ]);
    }

    public function storeSettings()
    {
        $input = request()->all();
        foreach ($input as $key => $value) {
            if ($key != '_token') {
                setting()->set([$key => $value]);
            }
        }
        setting()->save();
        setting()->forgetAll();
        return $this->sendCommonResponse([], __('Mail settings updated!'));
    }

    private function sendCommonResponse($data = [], $notify = '', $option = null)
    {
        $response = $this->processNotification($notify);
        if ($option == 'add-payment-type') {
            $response['replaceWith']['#paymentTypeTable'] = view('flexiblepos-setting.payment_type', $data)->render();
        } else if ($option == 'settings') {
            $response['replaceWith']['#pos_settings'] = view('flexiblepos-setting.pos_settings', $data)->render();
        }
        return $this->sendResponse($response);
    }
}
