<?php

namespace Database\Factories;

use App\Models\Adhesion;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\AdhesionCommercant;
use App\Models\AdhesionBenevole;


class AdhesionFactory extends Factory
{
    protected $model = Adhesion::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'nom' => $this->faker->company,
            'email' => $this->faker->unique()->safeEmail,
            'type_demande' => $this->faker->randomElement(['benevole', 'commercant']),
            'statut' => $this->faker->randomElement(['en attente', 'approuvé', 'rejeté']),
            'date_demande' => $this->faker->date()
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Adhesion $adhesion) {
            if ($adhesion->type_demande == 'benevole') {
                AdhesionBenevole::create([
                    'user_id' => \App\Models\User::factory(),
                    'status' => $this->faker->randomElement(['en attente', 'accepté', 'refusé']),
                    'old_benevole' => $this->faker->boolean,
                    'motivation' => $this->faker->paragraph,
                    'experience' => $this->faker->text(200),
                    'availability_begin' => $this->faker->date(),
                    'availability_end' => $this->faker->date(),
                    'hour_month' => $this->faker->numberBetween(10, 100),
                    'permis' => $this->faker->boolean,
                    'is_active' => $this->faker->boolean,
                    'additional_notes' => $this->faker->sentence
                ]);
            } else {
                AdhesionCommercant::create([
                    'user_id' => \App\Models\User::factory(),
                    'company_name' => $this->faker->company,
                    'siret' => $this->faker->numerify('##########'),
                    'address' => $this->faker->address,
                    'city' => $this->faker->city,
                    'postal_code' => $this->faker->postcode,
                    'country' => $this->faker->country,
                    'status' => 'en attente',
                    'is_active' => $this->faker->boolean,
                    'notes' => $this->faker->sentence,
                    'product_type' => $this->faker->word,
                    'opening_hours' => $this->faker->time(),
                    'participation_frequency' => $this->faker->randomElement(['weekly', 'monthly', 'yearly'])
                ]);
            }
        });
    }
}
