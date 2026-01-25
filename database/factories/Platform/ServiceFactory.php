<?php

namespace Database\Factories\Platform;

use App\Services\Platform\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Services\Platform\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Service::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $services = ['CMS', 'E-Commerce', 'Analytics', 'Forum', 'Gallery', 'Documentation'];
        $name = fake()->randomElement($services) . ' ' . fake()->word();

        return [
            'name' => $name,
            'slug' => fake()->unique()->slug(),
            'description' => fake()->sentence(10),
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the service is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Create a CMS service.
     */
    public function cms(): static
    {
        return $this->state(fn(array $attributes) => [
            'name' => 'Content Management System',
            'slug' => 'cms',
            'description' => 'Manage and publish content with this powerful CMS service.',
        ]);
    }

    /**
     * Create an E-Commerce service.
     */
    public function ecommerce(): static
    {
        return $this->state(fn(array $attributes) => [
            'name' => 'E-Commerce Platform',
            'slug' => 'ecommerce',
            'description' => 'Build and manage your online store with comprehensive e-commerce tools.',
        ]);
    }
}
