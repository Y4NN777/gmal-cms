<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Testimonial>
 */
class TestimonialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'position' => fake()->jobTitle(),
            'organization' => fake()->company(),
            'content' => fake()->paragraph(4),
            'rating' => fake()->numberBetween(4, 5),
            'status' => 'approved',
            'is_featured' => fake()->boolean(40),
            'display_order' => fake()->numberBetween(0, 100),
        ];
    }
    
    public function approved()
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'approved_by' => 1,
        ]);
    }
    
    public function featured()
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }
}
