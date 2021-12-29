<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('organization_name')->after('password')->nullable();
            $table->string('guard_name')->after('organization_name')->nullable();
            $table->string('encrypted_password')->after('guard_name')->nullable();
            $table->string('is_set_password')->after('encrypted_password')->nullable();
            $table->boolean('status')->after('is_set_password')->nullable();
            $table->string('otp_code')->after('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('organization_name')->nullable();
            $table->dropColumn('guard_name')->nullable();
            $table->dropColumn('encrypted_password')->nullable();
            $table->dropColumn('is_set_password')->nullable();
            $table->dropColumn('status')->nullable();
            $table->dropColumn('otp_code')->nullable();
        });
    }
}
