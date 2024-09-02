<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('adhesion_benevoles', function (Blueprint $table) {
            $table->string('type')->default('Bénévole')->after('id'); 
        });

        Schema::table('adhesion_commercants', function (Blueprint $table) {
            $table->string('type')->default('Commerçant')->after('id'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adhesion_benevoles', function (Blueprint $table) {
            $table->dropColumn('type');
        });
        Schema::table('adhesion_commercants', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
