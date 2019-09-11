<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContainersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('containers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('proforma_id')->nullable();
            $table->string('identification_or_nit')->nullable();
            $table->string('week_c')->nullable();
            $table->string('week_d')->nullable();
            $table->string('container')->nullable();
            $table->string('booking')->nullable();
            $table->string('bl')->nullable();
            $table->string('shipping_company')->nullable();
            $table->decimal('temperatura', 4, 1)->nullable();
            $table->string('damper')->nullable();
            $table->string('type_of_merchandise')->nullable();
            $table->string('port_of_discharge')->nullable();
            $table->date('fruit_loading_date')->nullable();
            $table->date('ship_departure_date')->nullable();
            $table->integer('estimated_date')->nullable();
            $table->string('agency')->nullable();
            $table->string('company')->nullable();
            $table->string('dock')->nullable();
            $table->string('product_list')->nullable();
            $table->integer('total_container')->nullable();
            $table->integer('peso_carga')->nullable();
            $table->integer('tara')->nullable();
            $table->integer('vgm')->nullable();
            $table->integer('other')->nullable();
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
        Schema::dropIfExists('containers');
    }
}
