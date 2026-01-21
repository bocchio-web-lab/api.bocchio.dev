# Multi-Service Multi-Tenant Platform - Architecture Documentation

## Overview

This Laravel 12 application implements a **multi-service, multi-tenant platform** with a unified identity system and isolated service databases. The architecture is designed to be modular, scalable, and extensible.

## Architecture Principles

### Database Architecture

The platform uses **three types of databases**:

1. **identity_db** (Default Connection)
   - Stores user authentication data
   - Managed by Laravel Sanctum + Fortify
   - Tables: `users`, `personal_access_tokens`

2. **platform_db** (Central Management)
   - Stores platform configuration and tenant information
   - Tables: `services`, `tenants`, `tenant_user`
   - Acts as the "glue" between identity and services

3. **Service-Specific Databases** (e.g., `cms_db`, `game_db`)
   - One database per service
   - All tables scoped by `tenant_id`
   - Complete isolation between services

### Services & Tenants

- **Service**: A type of application (e.g., CMS, Game Backend)
- **Tenant**: A user-owned instance of a service
- **Tenant Access Levels**:
  - `private`: No public access
  - `public`: Open access via content API
  - `token_protected`: Requires API key

## API Structure

### Management API (Authenticated)

**Prefix:** `/api/manage/`

**Authentication:** `auth:sanctum` + `tenant.context:{service}` middleware

**Headers Required:**
- `Authorization: Bearer {token}`
- `X-Tenant-ID: {tenant_id}`

**Examples:**
```
POST   /api/manage/cms/posts
GET    /api/manage/cms/dashboard
PUT    /api/manage/cms/posts/{id}
DELETE /api/manage/cms/posts/{id}
```

### Content Delivery API (Public/Semi-Public)

**Prefix:** `/api/content/{service}/{tenant_slug}/`

**Authentication:** `tenant.public_access:{service}` middleware

**Access Control:**
- `private`: Returns 403
- `public`: No authentication needed
- `token_protected`: Requires `Authorization: Bearer {public_api_key}`

**Examples:**
```
GET /api/content/cms/my-blog/posts
GET /api/content/cms/client-site/posts/{slug}
GET /api/content/cms/portfolio/pages
```

## Core Components

### Platform Models

#### Service Model
```php
App\Services\Platform\Models\Service

Properties:
- id, name, slug, description, is_active
- timestamps

Relationships:
- hasMany(Tenant)
```

#### Tenant Model
```php
App\Services\Platform\Models\Tenant

Properties:
- id, service_id, owner_id, name, public_slug
- access_level (enum: private|public|token_protected)
- public_api_key, settings (json)
- timestamps

Relationships:
- belongsTo(Service)
- belongsTo(User, 'owner_id')
- belongsToMany(User) via tenant_user pivot
```

#### User Model
```php
App\Models\User

Relationships:
- ownedTenants(): hasMany(Tenant, 'owner_id')
- tenants(): belongsToMany(Tenant) via platform_db.tenant_user
```

### Middleware

#### EnsureTenantContext
**Alias:** `tenant.context:{service}`

**Purpose:** Validate and set tenant context for management API

**Process:**
1. Read `X-Tenant-ID` header
2. Verify user is authenticated
3. Check user is member or owner of tenant
4. Verify tenant belongs to service
5. Set `app('current_tenant_id')` and `app('current_tenant')`

#### EnsurePublicTenantAccess
**Alias:** `tenant.public_access:{service}`

**Purpose:** Validate and set tenant context for content API

**Process:**
1. Read `{tenant_slug}` from URL
2. Find tenant by `public_slug` and service
3. Check `access_level`
   - `private` → 403
   - `public` → allow
   - `token_protected` → verify bearer token
4. Set `app('current_tenant_id')` and `app('current_tenant')`

### Global Tenant Scoping

All service models should use the `BelongsToTenant` trait to automatically scope queries:

```php
use App\Services\Platform\Traits\BelongsToTenant;

class Post extends Model
{
    use BelongsToTenant;

    protected $connection = 'cms_db';
    // ...
}
```

**What it does:**
- Automatically filters queries by `tenant_id`
- Sets `tenant_id` on model creation
- Prevents cross-tenant data leakage

**Usage:**
```php
// Automatically scoped to current tenant
$posts = Post::all();

// Bypass scoping (use with caution)
$allPosts = Post::withoutTenantScope()->get();
```

## Directory Structure

```
app/
├── Services/
│   ├── Platform/
│   │   ├── Models/
│   │   │   ├── Service.php
│   │   │   └── Tenant.php
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   │   ├── ServiceController.php
│   │   │   │   └── TenantController.php
│   │   │   └── Middleware/
│   │   │       ├── EnsureTenantContext.php
│   │   │       └── EnsurePublicTenantAccess.php
│   │   ├── Enums/
│   │   │   └── TenantAccessLevel.php
│   │   └── Traits/
│   │       ├── BelongsToTenant.php
│   │       └── TenantScope.php
│   └── Cms/
│       ├── Models/ (future)
│       └── Http/
│           └── Controllers/
│               ├── CmsManagementController.php
│               └── CmsContentController.php
├── Console/
│   └── Commands/
│       └── SetupDatabases.php
└── Models/
    └── User.php

database/
├── migrations/
│   ├── platform/
│   │   ├── 2025_01_21_000001_create_services_table.php
│   │   ├── 2025_01_21_000002_create_tenants_table.php
│   │   └── 2025_01_21_000003_create_tenant_user_table.php
│   └── (identity migrations)
└── seeders/
    ├── Platform/
    │   └── ServiceSeeder.php
    └── DatabaseSeeder.php

routes/
├── api.php
└── services/
    ├── manage_cms.php
    └── content_cms.php
```

## Setup & Installation

### 1. Environment Configuration

Add to your `.env`:

```env
# Identity Database (Default)
DB_CONNECTION=identity_db
IDENTITY_DB_HOST=127.0.0.1
IDENTITY_DB_PORT=3306
IDENTITY_DB_DATABASE=identity
IDENTITY_DB_USERNAME=root
IDENTITY_DB_PASSWORD=

# Platform Database
PLATFORM_DB_HOST=127.0.0.1
PLATFORM_DB_PORT=3306
PLATFORM_DB_DATABASE=platform
PLATFORM_DB_USERNAME=root
PLATFORM_DB_PASSWORD=

# CMS Service Database
CMS_DB_HOST=127.0.0.1
CMS_DB_PORT=3306
CMS_DB_DATABASE=cms
CMS_DB_USERNAME=root
CMS_DB_PASSWORD=
```

### 2. Create Databases

```bash
php artisan platform:setup-databases
```

### 3. Run Migrations

```bash
# Identity database
php artisan migrate:fresh

# Platform database
php artisan migrate --database=platform_db --path=database/migrations/platform

# Seed initial data
php artisan db:seed
```

### 4. Start Development Server

```bash
php artisan serve
```

## API Usage Examples

### 1. User Registration & Authentication

```bash
# Register
POST /register
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password",
  "password_confirmation": "password"
}

# Login
POST /login
{
  "email": "john@example.com",
  "password": "password"
}

# Response includes token
{
  "token": "1|abc123...",
  "user": { ... }
}
```

### 2. Platform Management

```bash
# List available services
GET /api/manage/services
Authorization: Bearer {token}

# Create a tenant
POST /api/manage/tenants
Authorization: Bearer {token}
{
  "name": "My Blog",
  "service_id": 1,
  "access_level": "public"
}

# List user's tenants
GET /api/manage/tenants
Authorization: Bearer {token}

# Update tenant
PUT /api/manage/tenants/{id}
Authorization: Bearer {token}
{
  "name": "Updated Blog Name",
  "access_level": "token_protected"
}
```

### 3. CMS Management API

```bash
# Get dashboard stats
GET /api/manage/cms/dashboard
Authorization: Bearer {token}
X-Tenant-ID: 1

# List posts (when implemented)
GET /api/manage/cms/posts
Authorization: Bearer {token}
X-Tenant-ID: 1
```

### 4. CMS Content API

```bash
# Public tenant
GET /api/content/cms/my-blog/posts

# Token-protected tenant
GET /api/content/cms/client-site/posts
Authorization: Bearer pk_abc123...
```

## Adding a New Service

### 1. Create Service Structure

```bash
app/Services/YourService/
├── Models/
├── Http/
│   └── Controllers/
└── (other directories as needed)
```

### 2. Create Service Database

Update `.env`:
```env
YOUR_SERVICE_DB_DATABASE=your_service
YOUR_SERVICE_DB_USERNAME=root
YOUR_SERVICE_DB_PASSWORD=
```

Add connection to `config/database.php`:
```php
'your_service_db' => [
    'driver' => 'mysql',
    'host' => env('YOUR_SERVICE_DB_HOST', '127.0.0.1'),
    'database' => env('YOUR_SERVICE_DB_DATABASE', 'your_service'),
    // ...
],
```

### 3. Create Migrations

```bash
database/migrations/your_service/
└── 2025_01_21_000001_create_your_tables.php
```

### 4. Create Routes

```bash
routes/services/
├── manage_your_service.php
└── content_your_service.php
```

Register in `routes/api.php`:
```php
Route::prefix('manage/your-service')
    ->middleware(['auth:sanctum', 'tenant.context:your-service'])
    ->group(base_path('routes/services/manage_your_service.php'));

Route::prefix('content/your-service/{tenant_slug}')
    ->middleware(['tenant.public_access:your-service'])
    ->group(base_path('routes/services/content_your_service.php'));
```

### 5. Seed Service Entry

Add to `ServiceSeeder.php`:
```php
[
    'name' => 'Your Service',
    'slug' => 'your-service',
    'description' => 'Description of your service',
    'is_active' => true,
],
```

## Testing

### Manual Testing Checklist

- [ ] User can register and login
- [ ] User can list available services
- [ ] User can create a tenant
- [ ] User can access management API with valid tenant
- [ ] User cannot access management API without tenant context
- [ ] User cannot access another user's tenant
- [ ] Public tenant is accessible via content API
- [ ] Private tenant returns 403 via content API
- [ ] Token-protected tenant requires valid API key
- [ ] Tenant scoping prevents cross-tenant data access

## Security Considerations

1. **Tenant Isolation**: Always use `BelongsToTenant` trait on service models
2. **API Keys**: Store `public_api_key` securely, rotate regularly
3. **Access Control**: Verify user permissions in controllers
4. **Input Validation**: Validate all inputs, especially tenant IDs
5. **Database Credentials**: Use separate credentials per database in production

## Future Enhancements

- [ ] Implement full CMS models (Post, Page, Media)
- [ ] Add Game service implementation
- [ ] Add Rockets service implementation
- [ ] Implement role-based permissions within tenants
- [ ] Add tenant usage analytics
- [ ] Implement rate limiting per tenant
- [ ] Add tenant billing/subscription system
- [ ] WebSocket support for real-time features
- [ ] Multi-region database support

## Support

For questions or issues, please refer to the Laravel documentation or contact the development team.

---

**Version:** 1.0.0
**Last Updated:** January 21, 2026
**Laravel Version:** 12.x
