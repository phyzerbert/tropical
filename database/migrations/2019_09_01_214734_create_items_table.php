<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('product_id')->nullable();
            $table->decimal('quantity', 8, 2)->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->decimal('amount', 8, 2)->nullable();
            $table->decimal('surcharge_reduction', 8, 2)->nullable();
            $table->decimal('total_amount', 8, 2)->nullable();
            $table->integer('itemable_id')->nullable();
            $table->string('itemable_type')->nullable();            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
