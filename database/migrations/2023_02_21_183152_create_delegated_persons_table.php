<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDelegatedPersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delegated_persons', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('trucker_id');
            $table->string('name','255')->nullable();                     
            $table->date('degattion_from')->nullable();
            $table->date('degattion_to')->nullable();
            $table->string('id_number','255')->nullable();                     
            $table->string('mobile','255')->nullable();          
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
        Schema::dropIfExists('delegated_persons');
    }
}