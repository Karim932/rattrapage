<?php

namespace Database\Factories;

use App\Models\AdhesionBenevole;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdhesionBenevoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AdhesionBenevole::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
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
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (AdhesionBenevole $benevole) {
            \App\Models\Adhesion::create([
                'candidature_id' => $benevole->id,
                'candidature_type' => AdhesionBenevole::class,
                // autres champs comme statut, etc.
            ]);
        });
    }
}
