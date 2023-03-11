<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEgpDebitAndCreditToCustomersHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers_history', function (Blueprint $table) {
            $table->unsignedInteger('credit_egp')->default(0);
            $table->unsignedInteger('debit_egp')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers_history', function (Blueprint $table) {
            $table->dropColumn('credit_egp');
            $table->dropColumn('debit_egp');
        });
    }
}