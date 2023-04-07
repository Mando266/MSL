<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParentIdToBldraftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bl_draft', function (Blueprint $table) {
            $table->unsignedInteger('parent_id')->nullable();
            $table->unsignedInteger('has_child')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bl_draft', function (Blueprint $table) {
            $table->dropColumn('parent_id');
            $table->dropColumn('has_child');
        });
    }
}