<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up():void
    {
        Schema::table('plannings', function (Blueprint $table) {
            $table->dropForeign(['benevole_id']);
            $table->dropColumn('benevole_id');
        });
    }

    public function down():void
    {
        Schema::table('plannings', function (Blueprint $table) {
            $table->foreignId('benevole_id')->nullable()->constrained('adhesion_benevoles')->onDelete('cascade');
        });
    }

};
