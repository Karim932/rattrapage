<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('adhesion_benevoles', function (Blueprint $table) {
            $table->unsignedBigInteger('id_service')->nullable();
            $table->foreign('id_service')->references('id')->on('services')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('adhesionBenevoles', function (Blueprint $table) {
            $table->dropForeign(['id_service']);
            $table->dropColumn('id_service');
        });
    }
};
