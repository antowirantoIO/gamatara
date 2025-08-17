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
            $table->unsignedBigInteger('id_vendor');
            $table->unsignedBigInteger('on_request_id');
            $table->string('no_spk');
            $table->text('keluhan');
            $table->unsignedBigInteger('id_pm_approval')->nullable();
            $table->timestamp('pm_date_approval')->nullable();
            $table->unsignedBigInteger('id_bod_approval')->nullable();
            $table->timestamp('bod_date_approval')->nullable();
            $table->timestamps();

            $table->foreign('id_vendor')->references('id')->on('vendor')->onDelete('cascade');
            $table->foreign('on_request_id')->references('id')->on('project')->onDelete('cascade');
            $table->foreign('id_pm_approval')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_bod_approval')->references('id')->on('users')->onDelete('cascade');
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
