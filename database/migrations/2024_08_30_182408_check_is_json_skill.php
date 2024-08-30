<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $records = DB::table('adhesion_benevoles')->get();
        foreach ($records as $record) {
            if (is_string($record->skill_id) && is_array(json_decode($record->skill_id, true))) {
                // C'est déjà un tableau JSON, donc OK
                continue;
            } else {
                // Convertir en JSON
                $skillsArray = (array) json_decode($record->skill_id, true) ?: [];
                DB::table('adhesion_benevoles')
                    ->where('id', $record->id)
                    ->update(['skill_id' => json_encode($skillsArray)]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
