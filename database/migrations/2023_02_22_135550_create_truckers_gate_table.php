<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTruckersGateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('truckers_gate', function (Blueprint $table) {
            $table->id();
            $table->BigInteger('company_id')->nullable();
            $table->unsignedInteger('trucker_id');
            $table->unsignedInteger('booking_id');
            $table->string('certificate_type','255')->nullable();                     
            $table->string('beneficiry_name','255')->nullable();                     
            $table->date('valid_to')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('inception_date')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('branch_name','255')->nullable();                     
            $table->BigInteger('gross_premium')->nullable();
            $table->BigInteger('net_contribution')->nullable();
            $table->boolean("shipment")->nullable();
            $table->string('operator','255')->nullable();                     
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
        Schema::dropIfExists('truckers_gate');
    }
}
