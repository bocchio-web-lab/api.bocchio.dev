<?php

namespace Database\Seeders\Cms;

use App\Models\User;
use App\Services\Cms\Models\Comment;
use App\Services\Cms\Models\ContentItem;
use App\Services\Platform\Models\Tenant;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Seed the CMS database with comments.
     */
    public function run(): void
    {
        $this->command->info('Seeding comments...');

        // Get public blog tenant (comments only make sense for public content)
        $publicBlog = Tenant::where('public_slug', 'bocchio-tech-blog')->first();

        if (!$publicBlog) {
            $this->command->error('Public blog tenant not found. Please run TenantSeeder first.');
            return;
        }

        // Get published blog posts
        $posts = ContentItem::where('tenant_id', $publicBlog->id)
            ->where('type', 'post')
            ->where('status', 'published')
            ->get();

        if ($posts->isEmpty()) {
            $this->command->error('No published posts found. Please run ContentItemSeeder first.');
            return;
        }

        // Get users for authenticated comments
        $users = User::whereIn('email', ['member@bocchio.dev', 'editor@bocchio.dev'])->get();

        $commentCount = 0;

        // Add comments to first few posts
        foreach ($posts->take(5) as $post) {
            // Add approved authenticated comments
            foreach ($users as $user) {
                Comment::updateOrCreate(
                    [
                        'content_item_id' => $post->id,
                        'author_id' => $user->id,
                    ],
                    [
                        'tenant_id' => $publicBlog->id,
                        'body' => $this->getRandomComment(),
                        'approved' => true,
                    ]
                );
                $commentCount++;
            }

            // Add approved guest comments
            $guestComments = [
                ['author_name' => 'John Smith', 'body' => 'Great article! This helped me understand the concept much better.'],
                ['author_name' => 'Sarah Johnson', 'body' => 'Thanks for sharing this. Very informative and well-written.'],
            ];

            foreach ($guestComments as $guestComment) {
                Comment::create([
                    'tenant_id' => $publicBlog->id,
                    'content_item_id' => $post->id,
                    'author_id' => null,
                    'author_name' => $guestComment['author_name'],
                    'body' => $guestComment['body'],
                    'approved' => true,
                ]);
                $commentCount++;
            }

            // Add one pending comment for moderation
            Comment::create([
                'tenant_id' => $publicBlog->id,
                'content_item_id' => $post->id,
                'author_id' => null,
                'author_name' => 'Anonymous User',
                'body' => 'This is a pending comment awaiting moderation.',
                'approved' => false,
            ]);
            $commentCount++;
        }

        $this->command->info("✓ Created {$commentCount} comments");
    }

    /**
     * Get a random positive comment.
     */
    protected function getRandomComment(): string
    {
        $comments = [
            'Excellent post! This was exactly what I was looking for.',
            'Very helpful tutorial. Keep up the great work!',
            'Thanks for the detailed explanation. Much appreciated!',
            'This is really well explained. Looking forward to more content like this.',
            'Great insights! I learned a lot from this article.',
            'Clear and concise. Thanks for sharing your knowledge!',
        ];

        return $comments[array_rand($comments)];
    }
}
