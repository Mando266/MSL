<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTruckersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('truckers', function (Blueprint $table) {
            $table->id();
            $table->string('company_name','255')->nullable();                     
            $table->string('contact_person','255')->nullable();                     
            $table->string('mobile','255')->nullable();                     
            $table->string('phone','255')->nullable();                     
            $table->string('email','255')->nullable();                     
            $table->longText('address')->nullable();                     
            $table->string('tax','255')->nullable();                     
            $table->string('certificat')->nullable();
            $table->string('pdf2')->nullable();
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
        Schema::dropIfExists('truckers');
    }
}