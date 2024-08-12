<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyIsActiveToStringInAdhesionBenevolesTable extends Migration
{
    public function up()
    {
        Schema::table('adhesion_benevoles', function (Blueprint $table) {
            $table->string('is_active')->default('en attente')->change();
        });
        Schema::table('adhesion_commercants', function (Blueprint $table) {
            $table->string('is_active')->default('en attente')->change();
        });
    }

    public function down()
    {
        Schema::table('adhesion_benevoles', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->change();
        });
        Schema::table('adhesion_commercants', function (Blueprint $table) {
            $table->string('is_active')->default('en attente')->change();
        });
    }
}
