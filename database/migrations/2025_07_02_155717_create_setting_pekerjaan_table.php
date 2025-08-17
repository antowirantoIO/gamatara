<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingPekerjaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_pekerjaan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pekerjaan');
            $table->unsignedBigInteger('id_sub_kategori');
            $table->timestamps();

            $table->foreign('id_pekerjaan')->references('id')->on('pekerjaan')->onDelete('cascade');
            $table->foreign('id_sub_kategori')->references('id')->on('sub_kategori')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setting_pekerjaan');
    }
}
