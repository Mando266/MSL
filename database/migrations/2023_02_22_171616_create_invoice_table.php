<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() 
    {
        Schema::create('invoice', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no','255')->nullable();
            $table->BigInteger('company_id')->nullable();
            $table->string('customer','255')->nullable();
            $table->unsignedInteger('bldraft_id');
            $table->date('date')->nullable();
            $table->date('departure')->nullable();
            $table->string('invoice_kind','255')->nullable();
            $table->string('invoice_status','255')->nullable();
            $table->string('rate','255')->nullable();
            $table->string('type','255')->nullable();
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
        Schema::dropIfExists('invoice');
    }
}
