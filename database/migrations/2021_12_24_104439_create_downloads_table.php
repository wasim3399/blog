<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDownloadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('downloads', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();;
            $table->integer('organization_id')->default(0);
            $table->enum('user_type', ['admin', 'merchant'])->default('admin');
            $table->string('role')->nullable();
            $table->string('folder_name')->nullable();
            $table->string('file_path')->nullable();
            $table->string('directory_path')->nullable();
            $table->string('zip_path')->nullable();
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->tinyInteger('is_downloaded')->default(0);
            $table->tinyInteger('failed_job')->default(0);
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
        Schema::dropIfExists('downloads');
    }
}
