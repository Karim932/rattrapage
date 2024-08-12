<?php

namespace Database\Factories;

use App\Models\AdhesionCommercant;
use App\Models\Adhesion;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdhesionCommercantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AdhesionCommercant::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
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
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (AdhesionCommercant $benevole) {
            \App\Models\Adhesion::create([
                'candidature_id' => $benevole->id,
                'candidature_type' => AdhesionCommercant::class,
                // autres champs comme statut, etc.
            ]);
        });
    }
}

