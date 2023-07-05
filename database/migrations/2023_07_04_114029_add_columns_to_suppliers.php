<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToSuppliers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('address_line_1');
            $table->string('phone');
            $table->string('email');
            $table->string('tax_card')->nullable();
            $table->bigInteger('currency_id')->nullable();
            $table->string('secondary_currency_id')->nullable();
            $table->text('note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn('address_line_1');
            $table->dropColumn('phone');
            $table->dropColumn('email');
            $table->dropColumn('tax_card');
            $table->dropColumn('currency_id');
            $table->dropColumn('secondary_currency_id');
            $table->dropColumn('note');
        });
    }
}
