# Database Seeders

This project uses a structured approach to database seeding, with separate seeders for each database and service area.

## Structure

```
database/seeders/
├── DatabaseSeeder.php          # Main seeder (orchestrates all seeders)
├── DemoDataSeeder.php          # Demo data with factories (optional)
├── Identity/
│   └── UserSeeder.php          # Seeds identity_db users
├── Platform/
│   ├── ServiceSeeder.php       # Seeds platform_db services
│   └── TenantSeeder.php        # Seeds platform_db tenants
└── Cms/
    ├── TagSeeder.php           # Seeds cms_db tags
    ├── ContentItemSeeder.php   # Seeds cms_db content
    └── CommentSeeder.php       # Seeds cms_db comments
```

## Seeding Order

The seeders must be run in the correct order due to database relationships:

1. **Identity Database** → Users
2. **Platform Database** → Services → Tenants
3. **CMS Database** → Tags → Content Items → Comments

The main `DatabaseSeeder` handles this automatically.

## Usage

### Seed All Databases

Run the main seeder to populate all databases with structured, realistic data:

```bash
php artisan db:seed
```

Or:

```bash
php artisan migrate:fresh --seed
```

### Seed Individual Databases

You can run individual seeders in order:

```bash
# Identity database
php artisan db:seed --class=Database\\Seeders\\Identity\\UserSeeder

# Platform database
php artisan db:seed --class=Database\\Seeders\\Platform\\ServiceSeeder
php artisan db:seed --class=Database\\Seeders\\Platform\\TenantSeeder

# CMS database
php artisan db:seed --class=Database\\Seeders\\Cms\\TagSeeder
php artisan db:seed --class=Database\\Seeders\\Cms\\ContentItemSeeder
php artisan db:seed --class=Database\\Seeders\\Cms\\CommentSeeder
```

### Demo Data (Optional)

For testing or development, use the `DemoDataSeeder` which uses factories to generate large amounts of realistic data:

```bash
php artisan db:seed --class=DemoDataSeeder
```

This creates:
- 10 users with various roles
- 5 services
- 8 tenants (public, private, token-protected)
- 50+ content items
- 100+ comments
- Multiple tags per tenant

## Seeder Details

### Identity Database

#### UserSeeder
Creates predefined user accounts for different roles:

- **admin@bocchio.dev** - Admin user
- **owner@bocchio.dev** - Content owner
- **editor@bocchio.dev** - Content editor
- **member@bocchio.dev** - Regular member

All accounts use the password: `password`

---

### Platform Database

#### ServiceSeeder
Creates the core platform services:

- **CMS** (slug: `cms`) - Content Management System
- **Global Direction Explorer** - Geographic exploration tool
- **Tutoring Management System** - Education management platform

#### TenantSeeder
Creates sample tenants for the CMS service:

- **Bocchio Tech Blog** (slug: `bocchio-tech-blog`)
  - Access: Public
  - Owner: owner@bocchio.dev
  - Comments enabled with moderation

- **Bocchio Portfolio** (slug: `bocchio-portfolio`)
  - Access: Private
  - Owner: owner@bocchio.dev
  - Comments disabled

- **API Documentation** (slug: `api-documentation`)
  - Access: Token Protected
  - Owner: admin@bocchio.dev
  - Includes API key for access

Also attaches team members to tenants with appropriate roles.

---

### CMS Database

#### TagSeeder
Creates relevant tags for each tenant:

**Blog Tags:**
- Laravel, PHP, JavaScript, Vue.js, React, Node.js
- Tutorial, Best Practices, DevOps, Testing
- API Development, Database

**Portfolio Tags:**
- Web Development, Mobile Apps, SaaS
- E-Commerce, CMS

**Documentation Tags:**
- Authentication, REST API, GraphQL
- Webhooks, Rate Limiting, Security

#### ContentItemSeeder
Creates realistic content for each tenant:

**Blog Posts** (7 total):
- "Getting Started with Laravel 11"
- "Building RESTful APIs with Laravel"
- "Vue.js and Laravel Integration"
- "Database Optimization Best Practices"
- "Testing Laravel Applications"
- "Deploying Laravel to Production"
- "Advanced React Patterns" (draft)

**Blog Pages** (2 total):
- About
- Contact

**Portfolio Projects** (3 total):
- E-Commerce Platform
- Content Management System
- Mobile Task Manager

**Documentation Articles** (4 total):
- API Authentication
- REST API Reference
- Rate Limiting
- Webhook Integration

All content includes:
- Proper markdown formatting
- Relevant tags
- Appropriate metadata
- Realistic publication dates

#### CommentSeeder
Adds comments to published blog posts:

- **Authenticated comments** from users
- **Guest comments** with author names
- **Approved comments** visible on posts
- **Pending comments** for moderation testing

---

## Idempotency

All seeders use `updateOrCreate()` to ensure they are **idempotent** - you can run them multiple times without creating duplicates. They will update existing records based on unique identifiers:

- **Users**: by email
- **Services**: by slug
- **Tenants**: by public_slug
- **Tags**: by tenant_id + slug
- **Content Items**: by tenant_id + slug

This is particularly useful during development when you need to refresh specific data without wiping everything.

## Testing Credentials

After seeding, you can log in with any of these accounts:

| Email | Password | Role |
|-------|----------|------|
| admin@bocchio.dev | password | Admin |
| owner@bocchio.dev | password | Owner |
| editor@bocchio.dev | password | Editor |
| member@bocchio.dev | password | Member |

## API Access

### Public Tenant
```bash
# Access public blog content (no auth required)
curl https://api.bocchio.dev/api/content/cms/bocchio-tech-blog/posts
```

### Token-Protected Tenant
```bash
# Get the API key from seeder output, then:
curl -H "Authorization: Bearer {api_key}" \
     https://api.bocchio.dev/api/content/cms/api-documentation/posts
```

### Private Tenant
```bash
# Private tenants are not accessible via public API
# Must use authenticated management API
```

## Development Workflow

### Fresh Install
```bash
php artisan migrate:fresh --seed
```

### Add More Data
```bash
# Run factory-based demo seeder for bulk data
php artisan db:seed --class=DemoDataSeeder
```

### Update Specific Data
```bash
# Re-run a specific seeder to update data
php artisan db:seed --class=Database\\Seeders\\Cms\\ContentItemSeeder
```

## Best Practices

1. **Keep seeders simple** - Focus on essential, realistic data
2. **Use factories for volume** - Use `DemoDataSeeder` for bulk testing data
3. **Make them idempotent** - Always use `updateOrCreate()` with unique keys
4. **Document credentials** - Keep track of seeded accounts
5. **Order matters** - Respect foreign key relationships
6. **Test regularly** - Run `migrate:fresh --seed` to validate seeders

## Customization

### Adding New Content

Edit the seeder files to add your own content:

```php
// In ContentItemSeeder.php
protected function seedBlogPosts(Tenant $tenant, User $owner, ?User $editor): int
{
    $posts = [
        [
            'title' => 'Your New Post Title',
            'slug' => 'your-new-post-slug',
            'type' => 'post',
            'excerpt' => 'Your excerpt here',
            'body' => "# Your Content\n\nYour markdown content here...",
            'status' => 'published',
            'published_at' => now(),
            'tags' => ['laravel', 'tutorial'],
        ],
        // ... more posts
    ];

    // ... seeding logic
}
```

### Adding New Tags

Edit `TagSeeder.php`:

```php
$blogTags = [
    ['name' => 'Your Tag', 'slug' => 'your-tag'],
    // ... more tags
];
```

### Adding New Users

Edit `UserSeeder.php`:

```php
User::updateOrCreate(
    ['email' => 'youruser@example.com'],
    [
        'name' => 'Your Name',
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
    ]
);
```

## Troubleshooting

### Foreign Key Errors
Make sure you're running seeders in order:
```bash
php artisan db:seed --class=Database\\Seeders\\Identity\\UserSeeder
php artisan db:seed --class=Database\\Seeders\\Platform\\ServiceSeeder
php artisan db:seed --class=Database\\Seeders\\Platform\\TenantSeeder
# ... etc
```

### Duplicate Entry Errors
This should not happen due to `updateOrCreate()`, but if it does:
```bash
php artisan migrate:fresh --seed
```

### Missing Data
Check that previous seeders ran successfully:
```bash
# Check if services exist
php artisan tinker
>>> App\Services\Platform\Models\Service::count()
```
