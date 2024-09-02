<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class UpdateSkillIdToJsonFormat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $rows = DB::table('adhesion_benevoles')->select('id', 'skill_id')->get();

        foreach ($rows as $row) {
            if (is_string($row->skill_id) && is_null(json_decode($row->skill_id, true))) {
                $jsonSkills = json_encode([$row->skill_id]);  
            } else {
                $jsonSkills = json_encode($row->skill_id);
            }

            DB::table('adhesion_benevoles')->where('id', $row->id)->update(['skill_id' => $jsonSkills]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $rows = DB::table('adhesion_benevoles')->select('id', 'skill_id')->get();

        foreach ($rows as $row) {
            $arraySkills = json_decode($row->skill_id, true);
            if (is_array($arraySkills)) {
                $skills = count($arraySkills) == 1 ? $arraySkills[0] : $arraySkills;
                
                DB::table('adhesion_benevoles')->where('id', $row->id)->update(['skill_id' => $skills]);
            }
        }
    }
}

