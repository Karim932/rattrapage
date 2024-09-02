<?php

namespace Database\Factories;

use App\Models\AdhesionBenevole;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;


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
        $availability = [
            'lundi' => [
                'matin' => $this->faker->boolean,
                'midi' => $this->faker->boolean,
                'soir' => $this->faker->boolean,
            ],
            'mardi' => [
                'matin' => $this->faker->boolean,
                'midi' => $this->faker->boolean,
                'soir' => $this->faker->boolean,
            ],
            'mercredi' => [
                'matin' => $this->faker->boolean,
                'midi' => $this->faker->boolean,
                'soir' => $this->faker->boolean,
            ],
            'jeudi' => [
                'matin' => $this->faker->boolean,
                'midi' => $this->faker->boolean,
                'soir' => $this->faker->boolean,
            ],
            'vendredi' => [
                'matin' => $this->faker->boolean,
                'midi' => $this->faker->boolean,
                'soir' => $this->faker->boolean,
            ],
            'samedi' => [
                'matin' => $this->faker->boolean,
                'midi' => $this->faker->boolean,
                'soir' => $this->faker->boolean,
            ],
            'dimanche' => [
                'matin' => $this->faker->boolean,
                'midi' => $this->faker->boolean,
                'soir' => $this->faker->boolean,
            ],
        ];

        $skillIds = range(1, 10);
        $randomSkills = Arr::random($skillIds, rand(1, 5)); 

        return [
            'user_id' => User::factory(),
            'status' => $this->faker->randomElement(['en cours', 'accepte', 'refuse']),
            'old_benevole' => $this->faker->boolean,
            'motivation' => $this->faker->paragraph,
            'experience' => $this->faker->sentence,
            'permis' => $this->faker->boolean,
            'is_active' => $this->faker->boolean,
            'additional_notes' => $this->faker->sentence,
            'type' => 'benevole',
            'availability_begin' => $this->faker->dateTimeBetween('-1 week', '+1 week'),
            'availability_end' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
            'availability' => $availability,
            'skill_id' => $randomSkills,
            'id_service' => $this->faker->randomElement([41, 42, 43, 44, 45, 46]), 
        ];
    }


    public function configure()
    {
        return $this->afterCreating(function (AdhesionBenevole $benevole) {
            \App\Models\Adhesion::create([
                'candidature_id' => $benevole->id,
                'candidature_type' => AdhesionBenevole::class,
            ]);
        });
    }
}
