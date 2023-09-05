<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSelectsToPortChargeInvoiceRowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('port_charge_invoice_rows');

        Schema::create('port_charge_invoice_rows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('port_charge_invoice_id');
            $table->unsignedBigInteger('port_charge_id');
            $table->string('service');
            $table->string('bl_no');
            $table->string('container_no');
            $table->boolean('is_transhipment');
            $table->string('shipment_type');
            $table->string('quotation_type');
            $table->decimal('thc', 10, 2);
            $table->decimal('storage', 10, 2);
            $table->decimal('power', 10, 2);
            $table->decimal('shifting', 10, 2);
            $table->decimal('disinf', 10, 2);
            $table->decimal('hand_fes_em', 10, 2);
            $table->decimal('gat_lift_off_inbnd_em_ft40', 10, 2);
            $table->decimal('gat_lift_on_inbnd_em_ft40', 10, 2);
            $table->decimal('pti', 10, 2);
            $table->decimal('add_plan', 10, 2);
            $table->string('pti_type');
            $table->string('power_days');
            $table->timestamps();
        });
        
        Schema::dropIfExists('port_charge_invoices');
        Schema::create('port_charge_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('payment_type')->nullable();
            $table->string('invoice_type')->nullable();
            $table->string('invoice_no')->nullable();
            $table->date('invoice_date')->nullable();
            $table->decimal('exchange_rate', 10, 2)->nullable();
            $table->string('invoice_status')->nullable();
            $table->string('country_id')->nullable();
            $table->string('port_id')->nullable();
            $table->string('shipping_line_id')->nullable();
            $table->string('vessel_id');
            $table->string('voyage_id');
            $table->string('selected_costs');
            $table->decimal('total_usd', 10, 2)->nullable();
            $table->decimal('invoice_egp', 10, 2)->nullable();
            $table->decimal('invoice_usd', 10, 2)->nullable();
            $table->bigInteger('empty_export_from_id')->nullable();
            $table->bigInteger('empty_export_to_id')->nullable();
            $table->bigInteger('empty_import_from_id')->nullable();
            $table->bigInteger('empty_import_to_id')->nullable();
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
        Schema::table('port_charge_invoice_rows', function (Blueprint $table) {
//            $table->drop
        });
    }
}
