<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableSampleSpesies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //create sample_spesies table
        Schema::create('sample_spesies', function (Blueprint $table) {
            $table->id('id_sample_spesies');
            $table->unsignedBigInteger('id_sample');
            $table->unsignedBigInteger('id_spesies');
        });
        //define sample_spesies table foreign key
        Schema::table('sample_spesies', function (Blueprint $table) {
            $table->foreign('id_sample')->references('id_sample')->on('sample');
            $table->foreign('id_spesies')->references('id_spesies')->on('spesies_nanofosil');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
