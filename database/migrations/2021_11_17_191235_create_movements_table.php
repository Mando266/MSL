
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('container_id');
            $table->unsignedInteger('container_type_id');
            $table->unsignedInteger('movement_id')->nullable();
            $table->date('movement_date')->nullable();
            $table->unsignedInteger('port_location_id')->nullable();
            $table->unsignedInteger('pol_id')->nullable();
            $table->unsignedInteger('pod_id')->nullable();
            $table->unsignedInteger('vessel_id')->nullable();
            $table->unsignedInteger('voyage_id')->nullable();
            $table->string('booking_no','255')->nullable();
            $table->string('bl_no','255')->nullable();
            $table->string('remarkes')->nullable();
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
        Schema::dropIfExists('movements');
    }
}
