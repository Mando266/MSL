<?php

use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('currency')->truncate();
        $currency = [
            ['name' => 'USD'],
            ['name' => 'EUR'],
            ['name' => 'JPY'],
            ['name' => 'GBP'],
            ['name' => 'CHF'],
            ['name' => 'CAD'],
            ['name' => 'AED'],
            ['name' => 'EGP'],
            ['name' => 'UAE'],
            ['name' => 'KWD'],
            ['name' => 'JOD'],
            ['name' => 'SAR'],
            ['name' => 'OMR'],
            ['name' => 'BHD'],
        ];
        DB::table('currency')->insert($currency);
    }
}
