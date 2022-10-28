<?php

use App\Account;
use App\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (! $user = User::where('email', 'admin@flexibleit.net')->first()) {
            $user = User::create([
                'name' => 'admin',
                'email' => 'admin@flexibleit.net',
                'password' => bcrypt('password'),
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ]);
        }
        
        $account = new Account();
        $caccount = $account->getAccount(['user_id'=>$user->id]);
        if (empty($caccount)) {
            $account->saveAccount([
                'name'=>'Cash',
                'company'=>'Own',
                'user_id'=>$user->id
            ]);
        } 
        

        /*$faker = Faker\Factory::create();
        for($i = 0; $i < 100; $i++) {
            App\User::create([
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => bcrypt('password'),
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]);
        }*/
    }
}
