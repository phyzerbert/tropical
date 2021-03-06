<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference_no')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('customers_vat')->nullable();
            $table->date('delivery_date')->nullable();
            $table->string('concerning_week')->nullable();
            $table->string('loading_week')->nullable();
            $table->string('shipment')->nullable();
            $table->string('vessel')->nullable();
            $table->string('port_of_discharge')->nullable();
            $table->string('origin')->nullable();
            $table->integer('vat_amount')->default(0);
            $table->decimal('total_to_pay', 14, 2)->nullable();
            $table->string('image')->nullable();
            $table->text('note')->nullable();
            $table->integer('proforma_id')->nullable();
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
        Schema::dropIfExists('invoices');
    }
}
