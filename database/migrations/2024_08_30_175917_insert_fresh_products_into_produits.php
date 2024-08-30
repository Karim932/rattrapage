<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;



return new class extends Migration
{

    public function up(): void
    {
        DB::table('produits')->insert([
            ['nom' => 'Salade (batavia)', 'code_barre' => '1000000001', 'categorie' => 'Frais'],
            ['nom' => 'Tomates', 'code_barre' => '1000000002', 'categorie' => 'Frais'],
            ['nom' => 'Pommes de terre', 'code_barre' => '1000000003', 'categorie' => 'Frais'],
            ['nom' => 'Carottes', 'code_barre' => '1000000004', 'categorie' => 'Frais'],
            ['nom' => 'Oignons', 'code_barre' => '1000000005', 'categorie' => 'Frais'],
            ['nom' => 'Laitue', 'code_barre' => '1000000006', 'categorie' => 'Frais'],
            ['nom' => 'Choux', 'code_barre' => '1000000007', 'categorie' => 'Frais'],
            ['nom' => 'Pommes', 'code_barre' => '1000000008', 'categorie' => 'Frais'],
            ['nom' => 'Bananes', 'code_barre' => '1000000009', 'categorie' => 'Frais'],
            ['nom' => 'Oranges', 'code_barre' => '1000000010', 'categorie' => 'Frais'],
            ['nom' => 'Viande de bœuf (1kg)', 'code_barre' => '1000000011', 'categorie' => 'Frais'],
            ['nom' => 'Viande de poulet (1kg)', 'code_barre' => '1000000012', 'categorie' => 'Frais'],
            ['nom' => 'Poisson (1kg)', 'code_barre' => '1000000013', 'categorie' => 'Frais'],
            ['nom' => 'Pain', 'code_barre' => '1000000014', 'categorie' => 'Frais'],
            ['nom' => 'Fromage', 'code_barre' => '1000000015', 'categorie' => 'Frais'],
            ['nom' => 'Lait (sans code-barres)', 'code_barre' => '1000000016', 'categorie' => 'Frais'],
            ['nom' => 'Yaourt (sans code-barres)', 'code_barre' => '1000000017', 'categorie' => 'Frais'],
            ['nom' => 'Œufs', 'code_barre' => '1000000018', 'categorie' => 'Frais'],
            ['nom' => 'Riz (1kg)', 'code_barre' => '1000000019', 'categorie' => 'Frais'],
            ['nom' => 'Pâtes (500g)', 'code_barre' => '1000000020', 'categorie' => 'Frais'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('produits')->whereIn('code_barre', [
            '1000000001', '1000000002', '1000000003', '1000000004',
            '1000000005', '1000000006', '1000000007', '1000000008',
            '1000000009', '1000000010', '1000000011', '1000000012',
            '1000000013', '1000000014', '1000000015', '1000000016',
            '1000000017', '1000000018', '1000000019', '1000000020'
        ])->delete();
    }
};