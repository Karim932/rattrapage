<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyIsActiveToBooleanInAdhesionTables extends Migration
{
    public function up()
    {
        // Modification pour la table adhesion_commercants
        Schema::table('adhesion_commercants', function (Blueprint $table) {
            $table->boolean('is_active')->default(0)->change();
        });

        // Modification pour la table adhesion_benevoles
        Schema::table('adhesion_benevoles', function (Blueprint $table) {
            $table->boolean('is_active')->default(0)->change();
        });
    }

    public function down()
    {
        // Revertir les changements pour adhesion_commercants
        Schema::table('adhesion_commercants', function (Blueprint $table) {
            $table->string('is_active')->default('en attente')->change();
        });

        // Revertir les changements pour adhesion_benevoles
        Schema::table('adhesion_benevoles', function (Blueprint $table) {
            $table->string('is_active')->default('en attente')->change();
        });
    }
}

