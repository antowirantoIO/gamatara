<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePmPePaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pm', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_karyawan');
            $table->timestamps();
            $table->foreign('id_karyawan')->references('id')->on('karyawan')->onDelete('cascade');
        });

        Schema::create('pe', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_karyawan');
            $table->unsignedBigInteger('id_pm');
            $table->timestamps();
            $table->foreign('id_karyawan')->references('id')->on('karyawan')->onDelete('cascade');
            $table->foreign('id_pm')->references('id')->on('pm')->onDelete('cascade');
        });

        Schema::create('pa', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_karyawan');
            $table->unsignedBigInteger('id_pm');
            $table->timestamps();
            $table->foreign('id_karyawan')->references('id')->on('karyawan')->onDelete('cascade');
            $table->foreign('id_pm')->references('id')->on('pm')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pa');
        Schema::dropIfExists('pe');
        Schema::dropIfExists('pm');
    }
}
