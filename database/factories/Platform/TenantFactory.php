<?php

namespace Database\Factories\Platform;

use App\Models\User;
use App\Services\Platform\Enums\TenantAccessLevel;
use App\Services\Platform\Models\Service;
use App\Services\Platform\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Services\Platform\Models\Tenant>
 */
class TenantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tenant::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->company();

        return [
            'service_id' => Service::factory(),
            'owner_id' => User::factory(),
            'name' => $name,
            'public_slug' => Str::slug($name) . '-' . Str::random(6),
            'access_level' => fake()->randomElement([
                TenantAccessLevel::PUBLIC ,
                TenantAccessLevel::PRIVATE ,
                TenantAccessLevel::TOKEN_PROTECTED,
            ]),
            'public_api_key' => 'pk_' . Str::random(40),
            'settings' => [
                'theme' => fake()->randomElement(['light', 'dark', 'auto']),
                'language' => fake()->randomElement(['en', 'es', 'fr', 'de']),
                'timezone' => fake()->timezone(),
            ],
        ];
    }

    /**
     * Create a public tenant.
     */
    public function public(): static
    {
        return $this->state(fn(array $attributes) => [
            'access_level' => TenantAccessLevel::PUBLIC ,
        ]);
    }

    /**
     * Create a private tenant.
     */
    public function private(): static
    {
        return $this->state(fn(array $attributes) => [
            'access_level' => TenantAccessLevel::PRIVATE ,
        ]);
    }

    /**
     * Create a token-protected tenant.
     */
    public function tokenProtected(): static
    {
        return $this->state(fn(array $attributes) => [
            'access_level' => TenantAccessLevel::TOKEN_PROTECTED,
        ]);
    }

    /**
     * Create a tenant for a specific service.
     */
    public function forService(Service $service): static
    {
        return $this->state(fn(array $attributes) => [
            'service_id' => $service->id,
        ]);
    }

    /**
     * Create a tenant owned by a specific user.
     */
    public function ownedBy(User $user): static
    {
        return $this->state(fn(array $attributes) => [
            'owner_id' => $user->id,
        ]);
    }
}
