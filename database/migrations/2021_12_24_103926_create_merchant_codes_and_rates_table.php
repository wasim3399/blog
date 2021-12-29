<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantCodesAndRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_codes_and_rates', function (Blueprint $table) {
            $table->id();
            $table->string('organization')->nullable();
            $table->string('merchant_name')->nullable();
            $table->string('organization_email')->nullable();
            $table->boolean('is_btc')->nullable();
            $table->string('internal_code')->nullable();
            $table->string('affiliate_code')->nullable();
            $table->string('address')->nullable();
            $table->string('company_no')->nullable();
            $table->string('transaction_acc_code')->nullable();
            $table->string('invoice_acc_code')->nullable();
            $table->string('invoice_acc_code_2')->nullable();
            $table->string('commission_acc_code')->nullable();
            $table->string('chargeback_commission_acc_code')->nullable();
            $table->string('settlement_acc_code')->nullable();
            $table->string('refund_commission_acc_code')->nullable();
            $table->string('client_funds_acc_code')->nullable();
            $table->string('fx_acc_code')->nullable();
            $table->string('contact_id')->nullable();
            $table->boolean('is_draft')->nullable();
            $table->string('iban_no')->nullable();
            $table->string('bic_no')->nullable();
            $table->string('integration')->nullable();
            $table->double('auth_fee_sold', 8, 2);
            $table->double('security_eea_rate', 8, 2);
            $table->double('security_neea_rate', 8, 2);
            $table->double('commission_eur_eea', 8, 2);
            $table->double('commission_eur_neea', 8, 2);
            $table->double('commission_neur_eea', 8, 2);
            $table->double('commission_neur_neea', 8, 2);
            $table->double('fx_premium', 8, 2);
            $table->double('settlement_fee', 8, 2);
            $table->double('chargeback_fee', 8, 2);
            $table->double('refund_fee', 8, 2);
            $table->boolean('is_accepted')->nullable();
            $table->string('merchant_type')->nullable();
            $table->string('contact_org')->nullable();
            $table->string('test_contact_id')->nullable();
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
        Schema::dropIfExists('merchant_codes_and_rates');
    }
}
