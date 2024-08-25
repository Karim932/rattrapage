<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanningsTable extends Migration
{
    public function up()
    {
        Schema::create('plannings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_id');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->enum('status', ['confirmed', 'pending', 'canceled'])->default('pending');
            $table->timestamps();

            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('plannings');
    }
}
