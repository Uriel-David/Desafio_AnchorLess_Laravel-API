<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VisaDossierFile>
 */
class VisaDossierFileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'ext' => 'pdf',
            'path' => 'visa_documents/' . $this->faker->uuid() . '.pdf',
            'url' => '/storage/visa_documents/' . $this->faker->uuid() . '.pdf',
            'type' => 'document',
            'tag' => 'passport',
        ];
    }
}
