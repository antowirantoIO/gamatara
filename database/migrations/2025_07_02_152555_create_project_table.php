<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_jenis_kapal')->nullable();
            $table->unsignedBigInteger('id_customer')->nullable();
            $table->unsignedBigInteger('pm_id')->nullable();
            $table->unsignedBigInteger('pe_id_1')->nullable();
            $table->unsignedBigInteger('pe_id_2')->nullable();
            $table->unsignedBigInteger('pa_id')->nullable();
            $table->unsignedBigInteger('id_lokasi_project')->nullable();
            $table->unsignedBigInteger('id_project')->nullable();
            $table->date('target_selesai')->nullable();
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
        Schema::dropIfExists('project');
    }
}
