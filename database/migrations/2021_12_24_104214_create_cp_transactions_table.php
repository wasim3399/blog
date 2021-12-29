<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCpTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cp_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('trx_id')->index('trx_id');
            $table->string('merchant_name')->index('merchant_name')->nullable();
            $table->boolean('status')->index('status')->nullable();
            $table->string('date')->index('date')->nullable();
            $table->string('cp_created_at')->nullable();
            $table->string('cp_updated_at')->nullable();
            $table->string('fiat_currency')->index('fiat_currency')->nullable();
            $table->string('country')->index('country')->nullable();
            $table->double('settled_amount', 8, 2)->index('settled_amount');
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cp_transactions');
    }
}
