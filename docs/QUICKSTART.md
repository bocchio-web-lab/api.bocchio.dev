# Platform Quick Start Guide

## Initial Setup (First Time)

### 1. Clone and Install
```bash
git clone <repository-url>
cd api.bocchio.dev
composer install
cp .env.example .env
php artisan key:generate
```

### 2. Configure Environment
Edit `.env` file with your database credentials:
```env
DB_CONNECTION=identity_db
IDENTITY_DB_DATABASE=identity
PLATFORM_DB_DATABASE=platform
CMS_DB_DATABASE=cms
```

### 3. Create Databases
```bash
php artisan platform:setup-databases
```

### 4. Run Migrations
```bash
php artisan migrate:fresh
php artisan migrate --database=platform_db --path=database/migrations/platform
php artisan db:seed
```

### 5. Start Server
```bash
php artisan serve
# Server running at http://localhost:8000
```

## Quick Test Flow

### 1. Register a User
```bash
curl -X POST http://localhost:8000/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password",
    "password_confirmation": "password"
  }'
```

### 2. Login
```bash
curl -X POST http://localhost:8000/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password"
  }'
```

Save the token from the response!

### 3. List Available Services
```bash
curl http://localhost:8000/api/manage/services \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### 4. Create a Tenant
```bash
curl -X POST http://localhost:8000/api/manage/tenants \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "My First Blog",
    "service_id": 1,
    "public_slug": "my-blog",
    "access_level": "public"
  }'
```

Save the tenant ID from the response!

### 5. Access Management API
```bash
curl http://localhost:8000/api/manage/cms/dashboard \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "X-Tenant-ID: YOUR_TENANT_ID"
```

### 6. Access Content API (Public)
```bash
curl http://localhost:8000/api/content/cms/my-blog/posts
```

## Common Commands

### Reset Everything
```bash
php artisan migrate:fresh
php artisan migrate --database=platform_db --path=database/migrations/platform
php artisan db:seed
```

### Check Routes
```bash
php artisan route:list
```

### Clear Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

## Troubleshooting

### Database Connection Issues
- Verify MySQL is running
- Check credentials in `.env`
- Ensure databases exist: `php artisan platform:setup-databases`

### Migration Errors
- Run migrations separately per database
- Check for duplicate migration files

### Token Issues
- Generate new token by logging in again
- Verify token is sent in Authorization header

### Tenant Access Denied
- Verify X-Tenant-ID header is set
- Ensure user owns or is member of tenant
- Check tenant belongs to correct service

## Next Steps

1. Read [ARCHITECTURE.md](ARCHITECTURE.md) for detailed documentation
2. Implement CMS content models (Post, Page, Media)
3. Add your own services
4. Deploy to production

## Development Tips

- Use Postman or Insomnia for API testing
- Enable debug mode: `APP_DEBUG=true` in `.env`
- Check logs: `storage/logs/laravel.log`
- Use `php artisan tinker` for quick database queries

---

Happy building! 🚀
