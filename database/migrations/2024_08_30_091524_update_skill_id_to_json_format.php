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
        // Récupérer toutes les lignes de la table qui nécessitent une mise à jour
        $rows = DB::table('adhesion_benevoles')->select('id', 'skill_id')->get();

        foreach ($rows as $row) {
            // Convertir le champ skill_id en chaîne JSON si ce n'est pas déjà le cas
            if (is_string($row->skill_id) && is_null(json_decode($row->skill_id, true))) {
                // Convertir le tableau en chaîne JSON
                $jsonSkills = json_encode([$row->skill_id]);  // Assurez-vous que c'est un tableau avant l'encodage
            } else {
                $jsonSkills = json_encode($row->skill_id);
            }

            // Mettre à jour la base de données avec la nouvelle chaîne JSON
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
        // Récupérer toutes les lignes de la table qui ont été mises à jour
        $rows = DB::table('adhesion_benevoles')->select('id', 'skill_id')->get();

        foreach ($rows as $row) {
            // Convertir le champ skill_id de JSON en tableau PHP
            $arraySkills = json_decode($row->skill_id, true);
            if (is_array($arraySkills)) {
                // Si le résultat est un tableau unique, prenez la première valeur sinon laissez comme tableau
                $skills = count($arraySkills) == 1 ? $arraySkills[0] : $arraySkills;
                
                // Mettre à jour la base de données avec le tableau
                DB::table('adhesion_benevoles')->where('id', $row->id)->update(['skill_id' => $skills]);
            }
        }
    }
}

