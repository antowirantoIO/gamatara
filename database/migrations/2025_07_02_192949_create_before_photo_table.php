<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeforePhotoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('before_photo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_project')->nullable();
            $table->string('kode_unik')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
        });

        Schema::create('after_photo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_project')->nullable();
            $table->string('kode_unik')->nullable();
            $table->string('photo')->nullable();
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
        Schema::dropIfExists('before_photo');
        Schema::dropIfExists('after_photo');
    }
}
