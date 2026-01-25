<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\Cms\Models\Comment;
use App\Services\Cms\Models\ContentItem;
use App\Services\Cms\Models\Tag;
use App\Services\Platform\Models\Service;
use App\Services\Platform\Models\Tenant;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Seed the application's database with demo data across all three databases.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting demo data seeding...');

        // Create users (identity_db)
        $this->command->info('Creating users...');
        $owner = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $editor = User::factory()->create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
        ]);

        $additionalUsers = User::factory(8)->create();
        $allUsers = collect([$owner, $editor])->merge($additionalUsers);

        $this->command->info("✓ Created {$allUsers->count()} users");

        // Create services (platform_db)
        $this->command->info('Creating services...');
        $cmsService = Service::factory()->cms()->create();
        $ecommerceService = Service::factory()->ecommerce()->create();
        $additionalServices = Service::factory(3)->create();

        $this->command->info('✓ Created 5 services');

        // Create tenants for CMS service (platform_db)
        $this->command->info('Creating tenants...');

        // Public blog tenant
        $publicBlog = Tenant::factory()
            ->forService($cmsService)
            ->ownedBy($owner)
            ->public()
            ->create([
                'name' => 'Tech Blog',
                'public_slug' => 'tech-blog-abc123',
            ]);

        // Private portfolio tenant
        $privatePortfolio = Tenant::factory()
            ->forService($cmsService)
            ->ownedBy($owner)
            ->private()
            ->create([
                'name' => 'Private Portfolio',
                'public_slug' => 'portfolio-xyz789',
            ]);

        // Token-protected documentation tenant
        $tokenProtectedDocs = Tenant::factory()
            ->forService($cmsService)
            ->ownedBy($editor)
            ->tokenProtected()
            ->create([
                'name' => 'API Documentation',
                'public_slug' => 'api-docs-def456',
            ]);

        // Additional tenants for other services
        $additionalTenants = Tenant::factory(5)
            ->recycle($allUsers)
            ->recycle([$cmsService, $ecommerceService, ...$additionalServices])
            ->create();

        $allTenants = collect([$publicBlog, $privatePortfolio, $tokenProtectedDocs])
            ->merge($additionalTenants);

        $this->command->info("✓ Created {$allTenants->count()} tenants");

        // Attach users to tenants (platform_db)
        $this->command->info('Attaching users to tenants...');
        $publicBlog->users()->attach($editor, ['role' => 'editor']);
        $publicBlog->users()->attach($additionalUsers->random(2), ['role' => 'member']);

        $privatePortfolio->users()->attach($editor, ['role' => 'admin']);

        $tokenProtectedDocs->users()->attach($owner, ['role' => 'admin']);
        $tokenProtectedDocs->users()->attach($additionalUsers->random(3), ['role' => 'member']);

        $this->command->info('✓ Attached users to tenants');

        // Create tags for tenants (cms_db)
        $this->command->info('Creating tags...');

        // Tags for public blog
        $blogTags = collect([
            Tag::factory()->forTenant($publicBlog)->create(['name' => 'Laravel']),
            Tag::factory()->forTenant($publicBlog)->create(['name' => 'PHP']),
            Tag::factory()->forTenant($publicBlog)->create(['name' => 'JavaScript']),
            Tag::factory()->forTenant($publicBlog)->create(['name' => 'Vue.js']),
            Tag::factory()->forTenant($publicBlog)->create(['name' => 'Tutorial']),
            Tag::factory()->forTenant($publicBlog)->create(['name' => 'Best Practices']),
        ]);

        // Tags for documentation
        $docsTags = collect([
            Tag::factory()->forTenant($tokenProtectedDocs)->create(['name' => 'API']),
            Tag::factory()->forTenant($tokenProtectedDocs)->create(['name' => 'REST']),
            Tag::factory()->forTenant($tokenProtectedDocs)->create(['name' => 'Authentication']),
        ]);

        // Tags for portfolio
        $portfolioTags = Tag::factory(5)->forTenant($privatePortfolio)->create();

        $totalTags = $blogTags->count() + $docsTags->count() + $portfolioTags->count();
        $this->command->info("✓ Created {$totalTags} tags");

        // Create content items (cms_db)
        $this->command->info('Creating content items...');

        // Blog posts
        $blogPosts = ContentItem::factory(20)
            ->forTenant($publicBlog)
            ->byAuthor($owner)
            ->post()
            ->published()
            ->create();

        // Attach random tags to blog posts
        $blogPosts->each(function ($post) use ($blogTags) {
            $post->tags()->attach($blogTags->random(rand(2, 4)));
        });

        // Blog pages
        $aboutPage = ContentItem::factory()
            ->forTenant($publicBlog)
            ->byAuthor($owner)
            ->page()
            ->published()
            ->create([
                'title' => 'About',
                'slug' => 'about',
            ]);

        $contactPage = ContentItem::factory()
            ->forTenant($publicBlog)
            ->byAuthor($owner)
            ->page()
            ->published()
            ->create([
                'title' => 'Contact',
                'slug' => 'contact',
            ]);

        // Draft blog posts
        $draftPosts = ContentItem::factory(5)
            ->forTenant($publicBlog)
            ->byAuthor($owner)
            ->post()
            ->draft()
            ->create();

        // Portfolio projects
        $projects = ContentItem::factory(10)
            ->forTenant($privatePortfolio)
            ->byAuthor($owner)
            ->project()
            ->published()
            ->create();

        $projects->each(function ($project) use ($portfolioTags) {
            $project->tags()->attach($portfolioTags->random(rand(2, 3)));
        });

        // Documentation articles
        $docs = ContentItem::factory(15)
            ->forTenant($tokenProtectedDocs)
            ->byAuthor($editor)
            ->post()
            ->published()
            ->create();

        $docs->each(function ($doc) use ($docsTags) {
            $doc->tags()->attach($docsTags->random(rand(1, 2)));
        });

        $totalContent = $blogPosts->count() + 2 + $draftPosts->count() + $projects->count() + $docs->count();
        $this->command->info("✓ Created {$totalContent} content items");

        // Create comments (cms_db)
        $this->command->info('Creating comments...');

        $approvedComments = 0;
        $pendingComments = 0;

        // Comments on blog posts
        $blogPosts->random(15)->each(function ($post) use ($allUsers, &$approvedComments, &$pendingComments) {
            // Approved authenticated comments
            Comment::factory(rand(2, 5))
                ->forContent($post)
                ->approved()
                ->authenticated()
                ->create()
                ->each(function ($comment) use ($allUsers) {
                    $comment->update(['author_id' => $allUsers->random()->id]);
                });
            $approvedComments += rand(2, 5);

            // Approved guest comments
            Comment::factory(rand(1, 3))
                ->forContent($post)
                ->approved()
                ->guest()
                ->create();
            $approvedComments += rand(1, 3);

            // Pending comments
            Comment::factory(rand(0, 2))
                ->forContent($post)
                ->pending()
                ->create();
            $pendingComments += rand(0, 2);
        });

        $this->command->info("✓ Created comments (approved: ~{$approvedComments}, pending: ~{$pendingComments})");

        // Summary
        $this->command->newLine();
        $this->command->info('🎉 Demo data seeding completed!');
        $this->command->newLine();
        $this->command->table(
            ['Database', 'Model', 'Count'],
            [
                ['identity_db', 'Users', $allUsers->count()],
                ['platform_db', 'Services', '5'],
                ['platform_db', 'Tenants', $allTenants->count()],
                ['cms_db', 'Tags', $totalTags],
                ['cms_db', 'Content Items', $totalContent],
                ['cms_db', 'Comments', "~" . ($approvedComments + $pendingComments)],
            ]
        );
        $this->command->newLine();
        $this->command->info('Demo tenants created:');
        $this->command->info("  • Public Blog: {$publicBlog->public_slug} (access: public)");
        $this->command->info("  • Private Portfolio: {$privatePortfolio->public_slug} (access: private)");
        $this->command->info("  • API Docs: {$tokenProtectedDocs->public_slug} (access: token_protected)");
        $this->command->info("    API Key: {$tokenProtectedDocs->public_api_key}");
    }
}
