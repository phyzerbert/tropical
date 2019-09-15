<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleProformasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_proformas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference_no')->nullable();
            $table->integer('customer_id')->nullable();
            $table->date('date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('customers_vat')->nullable();
            $table->string('concerning_week')->nullable();
            $table->string('vessel')->nullable();
            $table->string('port_of_discharge')->nullable();
            $table->string('port_of_charge')->nullable();
            $table->string('origin')->nullable();
            $table->string('week_c')->nullable();
            $table->string('week_d')->nullable();
            $table->string('image')->nullable();
            $table->integer('vat_amount')->default(0);
            $table->decimal('total_to_pay', 14, 2)->nullable();
            $table->integer('is_submitted')->default(0);
            $table->text('note')->nullable();
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
        Schema::dropIfExists('sale_proformas');
    }
}
