<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectPekerjaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_pekerjaan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_vendor');
            $table->string('kode_unik');
            $table->unsignedBigInteger('id_lokasi');
            $table->unsignedBigInteger('id_pekerjaan');
            $table->unsignedBigInteger('id_project');
            $table->unsignedBigInteger('id_kategori');
            $table->unsignedBigInteger('id_subkategori');
            $table->decimal('harga_customer', 10, 2);
            $table->text('deskripsi_subkategori')->nullable();
            $table->timestamps();

            $table->foreign('id_vendor')->references('id')->on('vendor')->onDelete('cascade');
            $table->foreign('id_lokasi')->references('id')->on('lokasi_project')->onDelete('cascade');
            $table->foreign('id_pekerjaan')->references('id')->on('pekerjaan')->onDelete('cascade');
            $table->foreign('id_project')->references('id')->on('project')->onDelete('cascade');
            $table->foreign('id_kategori')->references('id')->on('kategori')->onDelete('cascade');
            $table->foreign('id_subkategori')->references('id')->on('sub_kategori')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_pekerjaan');
    }
}
