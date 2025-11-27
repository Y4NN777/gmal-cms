<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(4);
        $startDate = fake()->dateTimeBetween('now', '+3 months');
        $endDate = (clone $startDate)->modify('+' . fake()->numberBetween(2, 8) . ' hours');
        
        return [
            'title' => rtrim($title, '.'),
            'slug' => \Illuminate\Support\Str::slug($title) . '-' . fake()->unique()->numberBetween(1000, 9999),
            'description' => fake()->paragraphs(3, true),
            'excerpt' => fake()->sentence(15),
            'short_description' => fake()->sentence(10),
            'start_date' => $startDate,
            'event_date' => $startDate,
            'end_date' => $endDate,
            'location' => fake()->city() . ', ' . fake()->country(),
            'category_id' => \App\Models\Category::inRandomOrder()->first()?->id ?? \App\Models\Category::factory(),
            'status' => fake()->randomElement(['published', 'draft']),
            'is_featured' => fake()->boolean(30),
            'display_order' => fake()->numberBetween(0, 100),
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }
    
    public function published()
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
        ]);
    }
    
    public function featured()
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }
}
