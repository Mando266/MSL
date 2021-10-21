<?php

use Illuminate\Database\Seeder;

class CustomerRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('customers_role')->truncate();
        $customer_roles = [
            ['name' => 'Shipper'],
            ['name' => 'Consignee'],
            ['name' => 'Notify'],
            ['name' => 'Booking Party'],        
            ['name' => 'Agreement Party'],        
        ];

		DB::table('customers_role')->insert($customer_roles);
        }
}
