<?php

namespace Database\Factories\Cms;

use App\Models\User;
use App\Services\Cms\Models\Comment;
use App\Services\Cms\Models\ContentItem;
use App\Services\Platform\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Services\Cms\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $isGuest = fake()->boolean(30); // 30% chance of guest comment

        return [
            'tenant_id' => Tenant::factory(),
            'content_item_id' => ContentItem::factory(),
            'author_id' => $isGuest ? null : User::factory(),
            'author_name' => $isGuest ? fake()->name() : null,
            'body' => fake()->paragraph(fake()->numberBetween(1, 3)),
            'approved' => false,
        ];
    }

    /**
     * Create an approved comment.
     */
    public function approved(): static
    {
        return $this->state(fn(array $attributes) => [
            'approved' => true,
        ]);
    }

    /**
     * Create a pending comment (not approved).
     */
    public function pending(): static
    {
        return $this->state(fn(array $attributes) => [
            'approved' => false,
        ]);
    }

    /**
     * Create a guest comment (no author_id).
     */
    public function guest(): static
    {
        return $this->state(fn(array $attributes) => [
            'author_id' => null,
            'author_name' => fake()->name(),
        ]);
    }

    /**
     * Create an authenticated user comment.
     */
    public function authenticated(): static
    {
        return $this->state(fn(array $attributes) => [
            'author_id' => User::factory(),
            'author_name' => null,
        ]);
    }

    /**
     * Create a comment for a specific content item.
     */
    public function forContent(ContentItem $contentItem): static
    {
        return $this->state(fn(array $attributes) => [
            'content_item_id' => $contentItem->id,
            'tenant_id' => $contentItem->tenant_id,
        ]);
    }

    /**
     * Create a comment by a specific user.
     */
    public function byUser(User $user): static
    {
        return $this->state(fn(array $attributes) => [
            'author_id' => $user->id,
            'author_name' => null,
        ]);
    }

    /**
     * Create a comment for a specific tenant.
     */
    public function forTenant(Tenant $tenant): static
    {
        return $this->state(fn(array $attributes) => [
            'tenant_id' => $tenant->id,
        ]);
    }
}
