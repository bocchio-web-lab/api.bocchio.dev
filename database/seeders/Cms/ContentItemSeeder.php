<?php

namespace Database\Seeders\Cms;

use App\Models\User;
use App\Services\Cms\Models\ContentItem;
use App\Services\Cms\Models\Tag;
use App\Services\Platform\Models\Tenant;
use Illuminate\Database\Seeder;

class ContentItemSeeder extends Seeder
{
    /**
     * Seed the CMS database with content items.
     */
    public function run(): void
    {
        $this->command->info('Seeding content items...');

        // Get tenants
        $publicBlog = Tenant::where('public_slug', 'bocchio-tech-blog')->first();
        $portfolio = Tenant::where('public_slug', 'bocchio-portfolio')->first();
        $docs = Tenant::where('public_slug', 'api-documentation')->first();

        // Get users
        $owner = User::where('email', 'owner@bocchio.dev')->first();
        $admin = User::where('email', 'admin@bocchio.dev')->first();
        $editor = User::where('email', 'editor@bocchio.dev')->first();

        if (!$publicBlog || !$portfolio || !$docs || !$owner || !$admin) {
            $this->command->error('Required data not found. Please run previous seeders first.');
            return;
        }

        $contentCount = 0;

        // Seed blog posts
        $contentCount += $this->seedBlogPosts($publicBlog, $owner, $editor);

        // Seed blog pages
        $contentCount += $this->seedBlogPages($publicBlog, $owner);

        // Seed portfolio projects
        $contentCount += $this->seedPortfolioProjects($portfolio, $owner);

        // Seed documentation articles
        $contentCount += $this->seedDocumentation($docs, $admin, $editor);

        $this->command->info("✓ Created {$contentCount} content items");
    }

    /**
     * Seed blog posts for public blog tenant.
     */
    protected function seedBlogPosts(Tenant $tenant, User $owner, ?User $editor): int
    {
        $posts = [
            [
                'title' => 'Getting Started with Laravel 11',
                'slug' => 'getting-started-with-laravel-11',
                'type' => 'post',
                'excerpt' => 'A comprehensive guide to building your first Laravel application.',
                'body' => "# Getting Started with Laravel 11\n\nLaravel is a powerful PHP framework that makes web development a breeze...\n\n## Installation\n\nFirst, make sure you have PHP 8.2 or higher installed...",
                'status' => 'published',
                'published_at' => now()->subDays(30),
                'tags' => ['laravel', 'php', 'tutorial'],
            ],
            [
                'title' => 'Building RESTful APIs with Laravel',
                'slug' => 'building-restful-apis-laravel',
                'type' => 'post',
                'excerpt' => 'Learn how to create robust and scalable REST APIs using Laravel.',
                'body' => "# Building RESTful APIs with Laravel\n\nREST APIs are essential for modern web applications...\n\n## Routes and Controllers\n\nLet's start by defining our API routes...",
                'status' => 'published',
                'published_at' => now()->subDays(25),
                'tags' => ['laravel', 'api-development', 'tutorial'],
            ],
            [
                'title' => 'Vue.js and Laravel Integration',
                'slug' => 'vuejs-laravel-integration',
                'type' => 'post',
                'excerpt' => 'Combine the power of Vue.js frontend with Laravel backend.',
                'body' => "# Vue.js and Laravel Integration\n\nIntegrating Vue.js with Laravel creates a powerful full-stack solution...",
                'status' => 'published',
                'published_at' => now()->subDays(20),
                'tags' => ['laravel', 'vuejs', 'javascript'],
            ],
            [
                'title' => 'Database Optimization Best Practices',
                'slug' => 'database-optimization-best-practices',
                'type' => 'post',
                'excerpt' => 'Tips and techniques for optimizing your database queries.',
                'body' => "# Database Optimization Best Practices\n\n## Indexing Strategies\n\nProper indexing is crucial for query performance...",
                'status' => 'published',
                'published_at' => now()->subDays(15),
                'tags' => ['database', 'best-practices'],
            ],
            [
                'title' => 'Testing Laravel Applications',
                'slug' => 'testing-laravel-applications',
                'type' => 'post',
                'excerpt' => 'A guide to testing your Laravel applications effectively.',
                'body' => "# Testing Laravel Applications\n\nTesting is an essential part of software development...",
                'status' => 'published',
                'published_at' => now()->subDays(10),
                'tags' => ['laravel', 'testing', 'best-practices'],
            ],
            [
                'title' => 'Deploying Laravel to Production',
                'slug' => 'deploying-laravel-production',
                'type' => 'post',
                'excerpt' => 'Everything you need to know about deploying Laravel apps.',
                'body' => "# Deploying Laravel to Production\n\n## Server Requirements\n\nBefore deploying, ensure your server meets these requirements...",
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'tags' => ['laravel', 'devops'],
            ],
            [
                'title' => 'Advanced React Patterns',
                'slug' => 'advanced-react-patterns',
                'type' => 'post',
                'excerpt' => 'Explore advanced patterns for building React applications.',
                'body' => "# Advanced React Patterns\n\n## Compound Components\n\nCompound components provide a flexible way to build reusable components...",
                'status' => 'draft',
                'published_at' => null,
                'tags' => ['react', 'javascript'],
            ],
        ];

        $count = 0;
        foreach ($posts as $postData) {
            $tags = $postData['tags'];
            unset($postData['tags']);

            $post = ContentItem::updateOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'slug' => $postData['slug'],
                ],
                array_merge($postData, [
                    'author_id' => $count < 4 ? $owner->id : ($editor?->id ?? $owner->id),
                ])
            );

            // Attach tags
            $tagIds = Tag::where('tenant_id', $tenant->id)
                ->whereIn('slug', $tags)
                ->pluck('id');
            $post->tags()->sync($tagIds);

            $count++;
        }

        return $count;
    }

    /**
     * Seed pages for public blog tenant.
     */
    protected function seedBlogPages(Tenant $tenant, User $owner): int
    {
        $pages = [
            [
                'title' => 'About',
                'slug' => 'about',
                'type' => 'page',
                'body' => "# About\n\nWelcome to Bocchio Tech Blog! This is a platform where we share insights, tutorials, and best practices about web development...",
                'status' => 'published',
                'published_at' => now()->subMonths(2),
            ],
            [
                'title' => 'Contact',
                'slug' => 'contact',
                'type' => 'page',
                'body' => "# Contact\n\nGet in touch with us!\n\n**Email:** contact@bocchio.dev\n\n**Twitter:** @bocchiodev",
                'status' => 'published',
                'published_at' => now()->subMonths(2),
            ],
        ];

        $count = 0;
        foreach ($pages as $pageData) {
            ContentItem::updateOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'slug' => $pageData['slug'],
                ],
                array_merge($pageData, ['author_id' => $owner->id])
            );
            $count++;
        }

        return $count;
    }

    /**
     * Seed projects for portfolio tenant.
     */
    protected function seedPortfolioProjects(Tenant $tenant, User $owner): int
    {
        $projects = [
            [
                'title' => 'E-Commerce Platform',
                'slug' => 'ecommerce-platform',
                'type' => 'project',
                'excerpt' => 'A full-featured online shopping platform with payment integration.',
                'body' => "# E-Commerce Platform\n\nA comprehensive e-commerce solution built with Laravel and Vue.js...",
                'status' => 'published',
                'published_at' => now()->subMonths(3),
                'meta' => [
                    'project_url' => 'https://shop.example.com',
                    'github_url' => 'https://github.com/bocchio/ecommerce',
                    'technologies' => ['Laravel', 'Vue.js', 'Stripe', 'Redis'],
                ],
                'tags' => ['web-development', 'ecommerce', 'saas'],
            ],
            [
                'title' => 'Content Management System',
                'slug' => 'cms-project',
                'type' => 'project',
                'excerpt' => 'A flexible multi-tenant CMS for managing websites and blogs.',
                'body' => "# Content Management System\n\nThis CMS allows users to create and manage content across multiple sites...",
                'status' => 'published',
                'published_at' => now()->subMonths(2),
                'meta' => [
                    'project_url' => 'https://cms.bocchio.dev',
                    'github_url' => 'https://github.com/bocchio/cms',
                    'technologies' => ['Laravel', 'React', 'PostgreSQL'],
                ],
                'tags' => ['web-development', 'cms', 'saas'],
            ],
            [
                'title' => 'Mobile Task Manager',
                'slug' => 'mobile-task-manager',
                'type' => 'project',
                'excerpt' => 'Cross-platform mobile app for managing tasks and projects.',
                'body' => "# Mobile Task Manager\n\nA productivity app built with React Native...",
                'status' => 'published',
                'published_at' => now()->subMonths(1),
                'meta' => [
                    'project_url' => 'https://taskmanager.example.com',
                    'technologies' => ['React Native', 'Laravel', 'MySQL'],
                ],
                'tags' => ['mobile-apps', 'web-development'],
            ],
        ];

        $count = 0;
        foreach ($projects as $projectData) {
            $tags = $projectData['tags'] ?? [];
            unset($projectData['tags']);

            $project = ContentItem::updateOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'slug' => $projectData['slug'],
                ],
                array_merge($projectData, ['author_id' => $owner->id])
            );

            // Attach tags
            if (!empty($tags)) {
                $tagIds = Tag::where('tenant_id', $tenant->id)
                    ->whereIn('slug', $tags)
                    ->pluck('id');
                $project->tags()->sync($tagIds);
            }

            $count++;
        }

        return $count;
    }

    /**
     * Seed documentation articles.
     */
    protected function seedDocumentation(Tenant $tenant, User $admin, ?User $editor): int
    {
        $docs = [
            [
                'title' => 'API Authentication',
                'slug' => 'api-authentication',
                'type' => 'post',
                'excerpt' => 'Learn how to authenticate with our API using tokens.',
                'body' => "# API Authentication\n\n## Bearer Tokens\n\nAll API requests require authentication using Bearer tokens...",
                'status' => 'published',
                'published_at' => now()->subMonths(1),
                'tags' => ['authentication', 'security'],
            ],
            [
                'title' => 'REST API Reference',
                'slug' => 'rest-api-reference',
                'type' => 'post',
                'excerpt' => 'Complete reference for all REST API endpoints.',
                'body' => "# REST API Reference\n\n## Base URL\n\n```\nhttps://api.bocchio.dev/v1\n```\n\n## Endpoints\n\n### Users\n\n...",
                'status' => 'published',
                'published_at' => now()->subMonths(1),
                'tags' => ['rest-api'],
            ],
            [
                'title' => 'Rate Limiting',
                'slug' => 'rate-limiting',
                'type' => 'post',
                'excerpt' => 'Understanding API rate limits and how to handle them.',
                'body' => "# Rate Limiting\n\n## Limits\n\n- Free tier: 100 requests per hour\n- Pro tier: 1000 requests per hour...",
                'status' => 'published',
                'published_at' => now()->subWeeks(3),
                'tags' => ['rate-limiting', 'rest-api'],
            ],
            [
                'title' => 'Webhook Integration',
                'slug' => 'webhook-integration',
                'type' => 'post',
                'excerpt' => 'Set up webhooks to receive real-time notifications.',
                'body' => "# Webhook Integration\n\nWebhooks allow you to receive real-time notifications when events occur...",
                'status' => 'published',
                'published_at' => now()->subWeeks(2),
                'tags' => ['webhooks', 'rest-api'],
            ],
        ];

        $count = 0;
        foreach ($docs as $docData) {
            $tags = $docData['tags'];
            unset($docData['tags']);

            $doc = ContentItem::updateOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'slug' => $docData['slug'],
                ],
                array_merge($docData, [
                    'author_id' => $count < 2 ? $admin->id : ($editor?->id ?? $admin->id),
                ])
            );

            // Attach tags
            $tagIds = Tag::where('tenant_id', $tenant->id)
                ->whereIn('slug', $tags)
                ->pluck('id');
            $doc->tags()->sync($tagIds);

            $count++;
        }

        return $count;
    }
}
