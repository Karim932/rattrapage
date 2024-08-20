<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // public function up(): void
    // {
    //     Schema::table('adhesion_commercants', function (Blueprint $table) {
    //         $table->dropColumn('product_type');
    //         $table->dropColumn('participation_frequency');

    //         $table->date('contract_start_date')->nullable();
    //         $table->date('contract_end_date')->nullable();
    //     });
    // }

    // /**
    //  * Reverse the migrations.
    //  */
    // public function down(): void
    // {
    //     Schema::table('adhesion_commercants', function (Blueprint $table) {
    //         $table->string('product_type')->nullable();
    //         $table->integer('participation_frequency')->nullable();
    //     });
    // }
};
