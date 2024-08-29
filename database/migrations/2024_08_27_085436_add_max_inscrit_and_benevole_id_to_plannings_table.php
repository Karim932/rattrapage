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
        Schema::table('plannings', function (Blueprint $table) {
            $table->unsignedInteger('max_inscrit')->nullable()->after('end_time');
            $table->foreignId('benevole_id')->nullable()->constrained('adhesion_benevoles')->onDelete('cascade')->after('max_inscrit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plannings', function (Blueprint $table) {
            $table->dropColumn('max_inscrit');
            $table->dropForeign(['benevole_id']);
            $table->dropColumn('benevole_id');
        });
    }
};
