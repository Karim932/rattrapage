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
        Schema::create('annonces', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('image')->nullable();
            $table->string('location');
            $table->decimal('price', 8, 2)->comment('Peut être 0 si aucun échange monétaire n\'est impliqué');
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->string('category');
            $table->text('skills_required');
            $table->enum('exchange_type', ['service_for_service', 'service_for_credits']);
            $table->string('estimated_duration');
            $table->string('availability');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annonces');
    }
};
