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
        Schema::create('collectes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commercant_id')->constrained('adhesion_commercants');
            $table->dateTime('date_collecte');
            $table->string('status')->default('en attente');
            $table->foreignId('benevole_id')->nullable()->constrained('adhesion_benevoles');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collectes');
    }
};
