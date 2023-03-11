<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('invoice_id');
            $table->unsignedInteger('bldraft_id');
            $table->unsignedInteger('bank_transfer')->default(0);
            $table->unsignedInteger('bank_deposit')->default(0);
            $table->unsignedInteger('bank_cash')->default(0);
            $table->unsignedInteger('bank_check')->default(0);
            $table->unsignedInteger('matching')->default(0);
            $table->string('status','255')->default('valid');
            $table->string('notes','1000')->nullable();
            $table->unsignedInteger('total')->default(0);
            $table->unsignedInteger('paid')->default(0);
            $table->unsignedInteger('user_id');
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
        Schema::dropIfExists('receipts');
    }
}