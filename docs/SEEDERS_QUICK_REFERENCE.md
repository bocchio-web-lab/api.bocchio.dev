# Quick Seeder Reference

## 🌱 Main Command
```bash
php artisan db:seed
# or
php artisan migrate:fresh --seed
```

## 📊 Seeding Flow

```
DatabaseSeeder (main)
│
├─── Identity Database (identity_db)
│    └─── UserSeeder
│         ├─── admin@bocchio.dev
│         ├─── owner@bocchio.dev
│         ├─── editor@bocchio.dev
│         └─── member@bocchio.dev
│
├─── Platform Database (platform_db)
│    ├─── ServiceSeeder
│    │    ├─── CMS (slug: cms)
│    │    ├─── Global Direction Explorer
│    │    └─── Tutoring Management System
│    │
│    └─── TenantSeeder
│         ├─── bocchio-tech-blog (public)
│         ├─── bocchio-portfolio (private)
│         └─── api-documentation (token_protected)
│
└─── CMS Database (cms_db)
     ├─── TagSeeder
     │    ├─── 12 blog tags
     │    ├─── 5 portfolio tags
     │    └─── 6 documentation tags
     │
     ├─── ContentItemSeeder
     │    ├─── 7 blog posts (6 published, 1 draft)
     │    ├─── 2 blog pages (About, Contact)
     │    ├─── 3 portfolio projects
     │    └─── 4 documentation articles
     │
     └─── CommentSeeder
          └─── ~25 comments (approved + pending)
```

## 📝 What Gets Created

| Database | Records | Details |
|----------|---------|---------|
| identity_db | 4 users | admin, owner, editor, member |
| platform_db | 3 services | CMS + 2 others |
| platform_db | 3 tenants | public, private, token-protected |
| cms_db | 23 tags | across 3 tenants |
| cms_db | 16 content items | posts, pages, projects, docs |
| cms_db | ~25 comments | approved + pending |

## 🔑 Test Accounts

| Email | Password | Role |
|-------|----------|------|
| admin@bocchio.dev | password | Admin |
| owner@bocchio.dev | password | Owner |
| editor@bocchio.dev | password | Editor |
| member@bocchio.dev | password | Member |

## 🎯 Tenant Access

| Tenant | Slug | Access | API Key Required |
|--------|------|--------|------------------|
| Bocchio Tech Blog | bocchio-tech-blog | Public | ❌ No |
| Bocchio Portfolio | bocchio-portfolio | Private | ⛔ No Access |
| API Documentation | api-documentation | Token Protected | ✅ Yes |

## 🚀 Quick Commands

```bash
# Seed everything
php artisan migrate:fresh --seed

# Seed only users
php artisan db:seed --class=Database\\Seeders\\Identity\\UserSeeder

# Seed only platform
php artisan db:seed --class=Database\\Seeders\\Platform\\ServiceSeeder
php artisan db:seed --class=Database\\Seeders\\Platform\\TenantSeeder

# Seed only CMS
php artisan db:seed --class=Database\\Seeders\\Cms\\TagSeeder
php artisan db:seed --class=Database\\Seeders\\Cms\\ContentItemSeeder
php artisan db:seed --class=Database\\Seeders\\Cms\\CommentSeeder

# Generate demo data with factories
php artisan db:seed --class=DemoDataSeeder
```

## 🧪 Testing API

```bash
# Public blog - no auth
curl http://localhost:8000/api/content/cms/bocchio-tech-blog/posts

# Get specific post
curl http://localhost:8000/api/content/cms/bocchio-tech-blog/posts/getting-started-with-laravel-11

# Token-protected docs (requires API key from seeder output)
curl -H "Authorization: Bearer {api_key}" \
     http://localhost:8000/api/content/cms/api-documentation/posts

# Authenticated management API
curl -H "Authorization: Bearer {user_token}" \
     http://localhost:8000/api/manage/cms/content
```

## 📦 Seeder Files

```
database/seeders/
├── DatabaseSeeder.php              # Main orchestrator
├── DemoDataSeeder.php              # Factory-based demo data
│
├── Identity/
│   └── UserSeeder.php              # 4 users
│
├── Platform/
│   ├── ServiceSeeder.php           # 3 services
│   └── TenantSeeder.php            # 3 tenants
│
└── Cms/
    ├── TagSeeder.php               # 23 tags
    ├── ContentItemSeeder.php       # 16 content items
    └── CommentSeeder.php           # ~25 comments
```

## 💡 Tips

- All seeders are **idempotent** - run them multiple times safely
- Use `updateOrCreate()` to avoid duplicates
- Order matters: Users → Services → Tenants → Tags → Content → Comments
- For bulk testing data, use `DemoDataSeeder` instead
- Check [SEEDERS.md](SEEDERS.md) for detailed documentation
