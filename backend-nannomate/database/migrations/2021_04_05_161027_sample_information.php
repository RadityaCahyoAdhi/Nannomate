<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SampleInformation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //create observers table
        Schema::create('observer', function (Blueprint $table) {
            $table->id('id_observer');
            $table->string('nama_observer');
            $table->date('tanggal_penelitian'); //yyyy-mm-dd
        });
        //create studi_areas table
        Schema::create('studi_area', function (Blueprint $table) {
            $table->id('id_studi_area');
            $table->unsignedBigInteger('id_observer');
            $table->text('lokasi');
            $table->string('litologi');
            $table->string('formasi');
            $table->string('longitude');
            $table->string('latitude');
        });
        //create samples table
        Schema::create('sample', function (Blueprint $table) {
            $table->id('id_sample');
            $table->unsignedBigInteger('id_studi_area');
            $table->unsignedBigInteger('id_user');
            $table->string('kode_sample');
            $table->string('kelimpahan');
            $table->string('preparasi');
            $table->string('pengawetan');
            $table->string('tujuan');
            $table->string('stopsite');
            $table->string('status');
            $table->text('alasan')->nullable();;
            $table->date('tanggal_dikirim')->nullable(); //yyyy-mm-dd
            $table->date('tanggal_diterima')->nullable(); //yyyy-mm-dd
        });
        //create sample_spesies table
        Schema::create('sample_spesies', function (Blueprint $table) {
            $table->id('id_sample_spesies');
            $table->unsignedBigInteger('id_sample');
            $table->unsignedBigInteger('id_spesies');
            $table->string('jumlah');
        });
        //create spesies_nanofosils table
        Schema::create('spesies_nanofosil', function (Blueprint $table) {
            $table->id('id_spesies');
            $table->string('nama_spesies');
            $table->string('status');
        });
        //create zona_geologis table
        Schema::create('zona_geologi', function (Blueprint $table) {
            $table->id('id_zona');
            $table->unsignedBigInteger('id_spesies');
            $table->unsignedBigInteger('id_umur');
        });
        //create umur_geologis table
        Schema::create('umur_geologi', function (Blueprint $table) {
            $table->id('id_umur');
            $table->string('zona_geo');
            $table->string('umur_geo');
        });
        //create users table
        Schema::create('user', function (Blueprint $table) {
            $table->id('id_user');
            $table->string('nama_lengkap');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role');
            $table->string('status');
        });
        //define studi_areas table foreign key
        Schema::table('studi_area', function (Blueprint $table) {
            $table->foreign('id_observer')->references('id_observer')->on('observer');
        });
        //define samples table foreign key
        Schema::table('sample', function (Blueprint $table) {
            $table->foreign('id_studi_area')->references('id_studi_area')->on('studi_area');
            $table->foreign('id_user')->references('id_user')->on('user');
        });
        //define sample_spesies table foreign key
        Schema::table('sample_spesies', function (Blueprint $table) {
            $table->foreign('id_sample')->references('id_sample')->on('sample');
            $table->foreign('id_spesies')->references('id_spesies')->on('spesies_nanofosil');
        });
        //define zona_geologis table foreign key
        Schema::table('zona_geologi', function (Blueprint $table) {
            $table->foreign('id_spesies')->references('id_spesies')->on('spesies_nanofosil');
            $table->foreign('id_umur')->references('id_umur')->on('umur_geologi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('observer');
		Schema::dropIfExists('studi_area');
		Schema::dropIfExists('sample');
		Schema::dropIfExists('spesies_nannofosil');
        Schema::dropIfExists('zona_geologi');
		Schema::dropIfExists('umur_geologi');
        Schema::dropIfExists('user');
    }
}
