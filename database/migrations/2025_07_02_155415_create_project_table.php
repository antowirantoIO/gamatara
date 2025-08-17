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
            $table->unsignedBigInteger('id_jenis_kapal');
            $table->unsignedBigInteger('id_customer');
            $table->unsignedBigInteger('pm_id');
            $table->unsignedBigInteger('pe_id_1');
            $table->unsignedBigInteger('pe_id_2')->nullable();
            $table->unsignedBigInteger('pa_id');
            $table->unsignedBigInteger('id_lokasi_project');
            $table->string('code');
            $table->unsignedBigInteger('status_survey');
            $table->boolean('status');
            $table->string('nama_project');
            $table->string('displacement');
            $table->string('contact_person');
            $table->string('nomor_contact_person');
            $table->timestamps();

            $table->foreign('id_jenis_kapal')->references('id')->on('jenis_kapal')->onDelete('cascade');
            $table->foreign('id_customer')->references('id')->on('customer')->onDelete('cascade');
            $table->foreign('pm_id')->references('id')->on('pm')->onDelete('cascade');
            $table->foreign('pe_id_1')->references('id')->on('pe')->onDelete('cascade');
            $table->foreign('pe_id_2')->references('id')->on('pe')->onDelete('cascade');
            $table->foreign('pa_id')->references('id')->on('pa')->onDelete('cascade');
            $table->foreign('id_lokasi_project')->references('id')->on('lokasi_project')->onDelete('cascade');
            $table->foreign('status_survey')->references('id')->on('status_survey')->onDelete('cascade');
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
