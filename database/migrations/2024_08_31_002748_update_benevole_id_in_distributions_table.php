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
        Schema::table('distributions', function (Blueprint $table) {
            
            $table->dropForeign(['benevole_id']);
            $table->dropColumn('benevole_id');
            $table->foreignId('benevole_id')->nullable()->constrained('adhesion_benevoles')->after('date_souhaitee');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distributions', function (Blueprint $table) {
            //
        });
    }
};
