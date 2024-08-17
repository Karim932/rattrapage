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
        Schema::create('adhesion_benevoles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('status')->default('en attente');
            $table->boolean('old_benevole');
            $table->text('motivation');
            $table->text('experience');
            $table->date('availability_begin');
            $table->date('availability_end');
            $table->integer('hour_month');
            $table->boolean('permis')->default(false);
            $table->boolean('is_active')->default(true);
            $table->text('additional_notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adhesion_benevoles');
    }
};
