<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_request', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('on_request_id')->nullable();
            $table->unsignedBigInteger('id_pm_approval')->nullable();
            $table->unsignedBigInteger('id_bod_approval')->nullable();
            $table->unsignedBigInteger('id_vendor')->nullable();
            $table->longText('keluhan')->nullable();
            $table->string('no_spk', 100)->nullable();
            $table->timestamp('bod_date_approval')->nullable();
            $table->timestamp('pm_date_approval')->nullable();
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
        Schema::dropIfExists('project_request');
    }
}
