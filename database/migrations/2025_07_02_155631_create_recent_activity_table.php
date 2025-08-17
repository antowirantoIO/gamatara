<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecentActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recent_activity', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_pekerjaan_id');
            $table->unsignedBigInteger('id_pekerjaan');
            $table->timestamps();

            $table->foreign('project_pekerjaan_id')->references('id')->on('project_pekerjaan')->onDelete('cascade');
            $table->foreign('id_pekerjaan')->references('id')->on('pekerjaan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recent_activity');
    }
}
