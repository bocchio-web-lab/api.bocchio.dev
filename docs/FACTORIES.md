# Database Factories

This project includes comprehensive factories for generating test and demo data across all three databases (identity, platform, and cms).

## Available Factories

### Identity Database

#### UserFactory
Located in `database/factories/UserFactory.php`

```php
// Create a user
User::factory()->create();

// Create an unverified user
User::factory()->unverified()->create();

// Create multiple users
User::factory(10)->create();
```

---

### Platform Database

#### ServiceFactory
Located in `database/factories/Platform/ServiceFactory.php`

```php
// Create a generic service
Service::factory()->create();

// Create a CMS service
Service::factory()->cms()->create();

// Create an E-Commerce service
Service::factory()->ecommerce()->create();

// Create an inactive service
Service::factory()->inactive()->create();
```

#### TenantFactory
Located in `database/factories/Platform/TenantFactory.php`

```php
// Create a tenant with random access level
Tenant::factory()->create();

// Create a public tenant
Tenant::factory()->public()->create();

// Create a private tenant
Tenant::factory()->private()->create();

// Create a token-protected tenant
Tenant::factory()->tokenProtected()->create();

// Create a tenant for a specific service
Tenant::factory()->forService($service)->create();

// Create a tenant owned by a specific user
Tenant::factory()->ownedBy($user)->create();

// Combine states
Tenant::factory()
    ->forService($cmsService)
    ->ownedBy($user)
    ->public()
    ->create();
```

---

### CMS Database

#### ContentItemFactory
Located in `database/factories/Cms/ContentItemFactory.php`

```php
// Create a content item (random type)
ContentItem::factory()->create();

// Create a published post
ContentItem::factory()->post()->published()->create();

// Create a page
ContentItem::factory()->page()->published()->create();

// Create a project
ContentItem::factory()->project()->published()->create();

// Create a draft post
ContentItem::factory()->post()->draft()->create();

// Create an archived post
ContentItem::factory()->post()->archived()->create();

// Create content for a specific tenant
ContentItem::factory()->forTenant($tenant)->create();

// Create content by a specific author
ContentItem::factory()->byAuthor($user)->create();

// Create 20 published blog posts with tags
$posts = ContentItem::factory(20)
    ->forTenant($tenant)
    ->byAuthor($user)
    ->post()
    ->published()
    ->create();

// Attach tags
$posts->each(function ($post) use ($tags) {
    $post->tags()->attach($tags->random(3));
});
```

#### CommentFactory
Located in `database/factories/Cms/CommentFactory.php`

```php
// Create a comment
Comment::factory()->create();

// Create an approved comment
Comment::factory()->approved()->create();

// Create a pending comment
Comment::factory()->pending()->create();

// Create a guest comment
Comment::factory()->guest()->create();

// Create an authenticated user comment
Comment::factory()->authenticated()->create();

// Create a comment for specific content
Comment::factory()->forContent($contentItem)->create();

// Create a comment by a specific user
Comment::factory()->byUser($user)->create();

// Create multiple approved comments for a post
Comment::factory(5)
    ->forContent($post)
    ->approved()
    ->authenticated()
    ->create();
```

#### TagFactory
Located in `database/factories/Cms/TagFactory.php`

```php
// Create a tag
Tag::factory()->create();

// Create a tag for a specific tenant
Tag::factory()->forTenant($tenant)->create();

// Create programming language tags
Tag::factory()->programmingLanguage()->create();

// Create framework tags
Tag::factory()->framework()->create();

// Create category tags
Tag::factory()->category()->create();

// Create multiple tags for a tenant
Tag::factory(10)->forTenant($tenant)->create();
```

---

## Demo Data Seeder

A comprehensive demo data seeder is available that populates all three databases with realistic data.

### Running the Seeder

```bash
php artisan db:seed --class=DemoDataSeeder
```

### What It Creates

- **10 users** across different roles
- **5 services** (CMS, E-Commerce, and others)
- **8 tenants** with different access levels:
  - Public blog tenant
  - Private portfolio tenant
  - Token-protected documentation tenant
- **Tags** for each tenant
- **Content items** (posts, pages, projects)
- **Comments** (approved and pending, authenticated and guest)

### Demo Credentials

After running the seeder, you can log in with:

- **Email:** john@example.com
- **Password:** password

Or:

- **Email:** jane@example.com
- **Password:** password

---

## Usage Examples

### Complete Blog Setup

```php
// Create owner
$owner = User::factory()->create([
    'name' => 'Blog Owner',
    'email' => 'owner@blog.com',
]);

// Create CMS service
$cms = Service::factory()->cms()->create();

// Create public tenant
$tenant = Tenant::factory()
    ->forService($cms)
    ->ownedBy($owner)
    ->public()
    ->create(['name' => 'My Tech Blog']);

// Create tags
$tags = Tag::factory(8)->forTenant($tenant)->create();

// Create published posts
$posts = ContentItem::factory(20)
    ->forTenant($tenant)
    ->byAuthor($owner)
    ->post()
    ->published()
    ->create();

// Attach tags to posts
$posts->each(fn($post) => $post->tags()->attach($tags->random(3)));

// Create comments on posts
$posts->random(10)->each(function ($post) {
    Comment::factory(5)
        ->forContent($post)
        ->approved()
        ->create();
});
```

### Testing Specific Scenarios

```php
// Test draft workflow
$draft = ContentItem::factory()
    ->forTenant($tenant)
    ->byAuthor($user)
    ->post()
    ->draft()
    ->create();

// Publish it
$draft->publish();

// Test comment moderation
$pendingComment = Comment::factory()
    ->forContent($post)
    ->pending()
    ->guest()
    ->create();

// Approve it
$pendingComment->approve();
```

---

## Testing

These factories are perfect for use in tests:

```php
use App\Services\Cms\Models\ContentItem;
use App\Services\Platform\Models\Tenant;

test('user can view published posts', function () {
    $tenant = Tenant::factory()->public()->create();
    $post = ContentItem::factory()
        ->forTenant($tenant)
        ->post()
        ->published()
        ->create();

    $response = $this->get("/api/content/cms/{$tenant->public_slug}/posts/{$post->slug}");

    $response->assertOk();
    $response->assertJson(['data' => ['title' => $post->title]]);
});
```

---

## Factory Relationships

The factories handle cross-database relationships automatically:

```php
// This creates:
// - A user in identity_db
// - A service in platform_db
// - A tenant in platform_db
// - A content item in cms_db
$post = ContentItem::factory()->create();

// You can also pass existing records to avoid creating duplicates
$post = ContentItem::factory()
    ->forTenant($existingTenant)
    ->byAuthor($existingUser)
    ->create();
```

---

## Tips

1. **Use `recycle()`** to reuse models and avoid creating too many related records:
   ```php
   $users = User::factory(5)->create();
   $tenants = Tenant::factory(10)->recycle($users)->create();
   ```

2. **Chain states** for precise control:
   ```php
   ContentItem::factory()
       ->post()
       ->published()
       ->forTenant($tenant)
       ->create();
   ```

3. **Create realistic data** for frontend development by using the `DemoDataSeeder`.

4. **Reset between tests** using `RefreshDatabase` trait in Pest/PHPUnit.
