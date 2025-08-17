<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_person');
            $table->string('nomor_contact_person');
            $table->text('alamat');
            $table->string('email')->nullable();
            $table->string('npwp')->nullable();
            $table->unsignedBigInteger('kategori_vendor')->nullable();
            $table->text('ttd')->nullable();
            $table->timestamps();

            $table->foreign('kategori_vendor')->references('id')->on('kategori_vendor')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor');
    }
}
