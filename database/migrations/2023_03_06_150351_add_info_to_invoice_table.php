<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInfoToInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice', function (Blueprint $table) {
            $table->unsignedInteger('place_of_acceptence')->nullable();
            $table->unsignedInteger('load_port')->nullable();
            $table->unsignedInteger('booking_ref')->nullable();
            $table->unsignedInteger('voyage_id')->nullable();
            $table->unsignedInteger('discharge_port')->nullable();
            $table->unsignedInteger('port_of_delivery')->nullable();
            $table->unsignedInteger('equipment_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice', function (Blueprint $table) {
            $table->dropColumn('place_of_acceptence');
            $table->dropColumn('load_port');
            $table->dropColumn('booking_ref');
            $table->dropColumn('voyage_id');
            $table->dropColumn('discharge_port');
            $table->dropColumn('port_of_delivery');
            $table->dropColumn('equipment_type');
        });
    }
}