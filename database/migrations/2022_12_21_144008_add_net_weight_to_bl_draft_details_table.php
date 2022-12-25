<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNetWeightToBlDraftDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bl_draft_details', function (Blueprint $table) {
            $table->string('net_weight','255')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bl_draft_details', function (Blueprint $table) {
            $table->dropColumn('net_weight');
        });
    }
}