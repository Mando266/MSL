<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceChargeDescriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_charge_description', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('invoice_id');
            $table->longText('charge_description')->nullable();                     
            $table->string('size_small','255')->nullable();
            $table->string('size_large','255')->nullable();
            $table->string('total_amount','255')->nullable();
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_charge_description');
    }
}