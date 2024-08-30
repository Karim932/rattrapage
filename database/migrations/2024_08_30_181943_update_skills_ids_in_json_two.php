<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        $allSkillIds = DB::table('skills')->pluck('id')->toArray();
        if (!empty($allSkillIds)) {
            $adhesions = DB::table('adhesion_benevoles')->select('id')->get();
            foreach ($adhesions as $adhesion) {
                $randomSkillIds = $this->getRandomSkillIds($allSkillIds);
                DB::table('adhesion_benevoles')
                    ->where('id', $adhesion->id)
                    ->update(['skill_id' => json_encode($randomSkillIds)]);
            }
        }
    }

    public function down()
    {
        DB::table('adhesion_benevoles')->update(['skill_id' => json_encode([])]);
    }

    private function getRandomSkillIds(array $skillIds)
    {
        $count = rand(1, min(5, count($skillIds)));
        $randomKeys = array_rand($skillIds, $count);
        $randomKeys = is_array($randomKeys) ? $randomKeys : [$randomKeys];
        return array_intersect_key($skillIds, array_flip($randomKeys));
    }
};
