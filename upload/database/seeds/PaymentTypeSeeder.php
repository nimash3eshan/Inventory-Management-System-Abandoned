<?php

use App\PaymentType;
use Illuminate\Database\Seeder;

class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $payment_types = ['Cash', 'Check', 'Debit Card', 'Credit Card'];
        foreach ($payment_types as $type) {
            if(! PaymentType::where('slug', Illuminate\Support\Str::slug($type))->first()) {
                $payment_type = new PaymentType();
                $payment_type->name = $type;
                $payment_type->slug = Illuminate\Support\Str::slug($type);
                $payment_type->save();
            }
        }
    }
}
