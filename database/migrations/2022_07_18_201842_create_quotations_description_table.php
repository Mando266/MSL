<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationsDescriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotations_description', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('quotation_id');
            $table->string('charge_desc','255')->nullable();
            $table->string('mode','255')->nullable();
            $table->string('currency','255')->nullable();
            $table->string('charge_unit','255')->nullable();
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
        Schema::dropIfExists('quotations_description');
    }
}
