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
            $table->string('semana')->nullable();
            $table->string('contenedor')->nullable();
            $table->string('precinto')->nullable();
            $table->decimal('temperatura', 4, 1)->nullable();
            $table->string('damper')->nullable();
            $table->string('booking')->nullable();
            $table->string('port_of_discharge')->nullable();
            $table->date('fetcha')->nullable();
            $table->string('embarcadero')->nullable();
            $table->string('tipo_de_mercancia')->nullable();
            $table->string('agencia_aduanera')->nullable();
            $table->string('company_or_person')->nullable();
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
