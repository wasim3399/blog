<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreCalculatedVolumesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_calculated_volumes', function (Blueprint $table) {
            $table->id();
            $table->string('organization_name')->nullable();
            $table->string('merchant_name');
            $table->string('date');
            $table->double('sold_eur_eea_volume', 8, 2);
            $table->double('sold_eur_neea_volume', 8, 2);
            $table->double('sold_neur_eea_volume', 8, 2);
            $table->double('sold_neur_neea_volume', 8, 2);
            $table->double('total_volume', 8, 2);
            $table->integer('total_trx_count')->default(0);
            $table->tinyInteger('update_count')->default(0);
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
        Schema::dropIfExists('pre_calculated_volumes');
    }
}
