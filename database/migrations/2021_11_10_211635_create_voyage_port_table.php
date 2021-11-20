<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoyagePortTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voyage_port', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('voyage_id');
            $table->unsignedInteger('port_id')->nullable();
            $table->unsignedInteger('terminal_id')->nullable();
            $table->unsignedInteger('vessel_port_id')->nullable();
            $table->unsignedInteger('road_no')->nullable();
            $table->date('eta')->nullable();
            $table->date('etd')->nullable();
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
        Schema::dropIfExists('voyage_port');
    }
}
