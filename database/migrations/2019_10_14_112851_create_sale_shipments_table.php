<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_shipments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference_no')->nullable();
            $table->string('week_c')->nullable();
            $table->integer('vat_amount')->default(0);
            $table->decimal('total_to_pay', 14, 2)->nullable();
            $table->integer('is_received')->default(0);
            $table->integer('sale_proforma_id')->nullable();
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
        Schema::dropIfExists('sale_shipments');
    }
}
