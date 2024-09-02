<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAdhesionBenevolesTable extends Migration
{
    public function up()
    {
        Schema::table('adhesion_benevoles', function (Blueprint $table) {
            $table->dropColumn('hour_month');
            $table->json('availability')->nullable();
            $table->json('skill_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('adhesion_benevoles', function (Blueprint $table) {
            $table->integer('hour_month')->nullable();
            $table->dropColumn(['availability', 'skills']);
        });
    }
}

