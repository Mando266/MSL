<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSelectsToPortChargeInvoiceRowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('port_charge_invoice_rows', function (Blueprint $table) {
            $table->string('pti_type');
            $table->string('power_days');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('port_charge_invoice_rows', function (Blueprint $table) {
//            $table->drop
        });
    }
}
