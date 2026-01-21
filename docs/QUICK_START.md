# API Quick Start Guide

This quick guide will get you up and running with the Multi-Tenant Platform API in minutes.

## 1. Access the Documentation

Visit the interactive API documentation:
```
http://localhost:8000/docs
```

Or in production:
```
https://api.bocchio.dev/docs
```

## 2. Register & Authenticate

### Register a New User
```bash
POST /register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "SecurePassword123",
  "password_confirmation": "SecurePassword123"
}
```

### Login
```bash
POST /login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "SecurePassword123"
}
```

Response:
```json
{
  "token": "1|abc123def456..."
}
```

Save this token! You'll use it in all authenticated requests.

## 3. List Available Services

```bash
GET /api/manage/services
Authorization: Bearer 1|abc123def456...
```

Response:
```json
{
  "data": [
    {
      "id": 1,
      "name": "CMS",
      "slug": "cms",
      "description": "Content Management System",
      "is_active": true
    }
  ]
}
```

## 4. Create Your First Tenant

```bash
POST /api/manage/tenants
Authorization: Bearer 1|abc123def456...
Content-Type: application/json

{
  "name": "My Blog",
  "service_id": 1,
  "access_level": "public"
}
```

Response:
```json
{
  "data": {
    "id": 1,
    "name": "My Blog",
    "service_id": 1,
    "public_slug": "my-blog-abc123",
    "access_level": "public",
    "public_api_key": "pk_xxxxxxxxxxxx..."
  }
}
```

Save the `id` (for Management API) and `public_slug` (for Content API)!

## 5. Create Your First Post

```bash
POST /api/manage/cms/content
Authorization: Bearer 1|abc123def456...
X-Tenant-ID: 1
Content-Type: application/json

{
  "type": "post",
  "title": "My First Blog Post",
  "body": "This is the content of my first post!",
  "excerpt": "A short summary",
  "status": "published",
  "published_at": "2025-01-22T10:00:00Z"
}
```

Response:
```json
{
  "data": {
    "id": 1,
    "type": "post",
    "title": "My First Blog Post",
    "slug": "my-first-blog-post",
    "status": "published",
    "author_id": 1
  }
}
```

## 6. View Your Content Publicly

Now anyone can access your published content:

```bash
GET /api/content/cms/my-blog-abc123/posts
```

Response:
```json
{
  "data": [
    {
      "id": 1,
      "title": "My First Blog Post",
      "slug": "my-first-blog-post",
      "excerpt": "A short summary",
      "published_at": "2025-01-22T10:00:00.000000Z"
    }
  ]
}
```

## Common Workflows

### Create a Draft Post
```json
{
  "type": "post",
  "title": "Draft Post",
  "body": "Content here...",
  "status": "draft"
}
```
Drafts are NOT visible on the public API!

### Create a Page
```json
{
  "type": "page",
  "title": "About",
  "body": "About us content...",
  "status": "published"
}
```

### Create Tags
```bash
POST /api/manage/cms/tags
Authorization: Bearer TOKEN
X-Tenant-ID: 1

{
  "name": "Laravel"
}
```

### Attach Tags to Content
```json
{
  "title": "Post with Tags",
  "body": "Content...",
  "tags": [1, 2, 3]
}
```

### Make Tenant Private
```bash
PUT /api/manage/tenants/1
Authorization: Bearer TOKEN

{
  "access_level": "private"
}
```
Content is now completely inaccessible via public API!

### Make Tenant Token-Protected
```bash
PUT /api/manage/tenants/1
Authorization: Bearer TOKEN

{
  "access_level": "token_protected"
}
```

Then access with the tenant's API key:
```bash
GET /api/content/cms/my-blog-abc123/posts
Authorization: Bearer pk_tenant_api_key_here
```

## Key Headers

### Management API
```
Authorization: Bearer {your_user_token}
X-Tenant-ID: {tenant_id}
Content-Type: application/json
```

### Public Content API (token-protected tenants)
```
Authorization: Bearer {tenant_public_api_key}
```

## Access Levels Cheat Sheet

| Access Level | Public API Access | Authorization Required |
|-------------|-------------------|----------------------|
| `public` | ✅ Anyone can read | ❌ No |
| `private` | ❌ Completely blocked | N/A |
| `token_protected` | ✅ With valid key | ✅ Yes (tenant API key) |

## Error Codes

| Code | Meaning | Common Cause |
|------|---------|--------------|
| 401 | Unauthorized | Missing or invalid token |
| 403 | Forbidden | Not a member of tenant |
| 404 | Not Found | Resource doesn't exist |
| 422 | Validation Error | Invalid request data |

## Next Steps

1. **Read the full documentation** at `/docs`
2. **Explore the endpoints** in each section
3. **Try the interactive examples** with "Try It Out"
4. **Download the Postman collection** at `/docs.postman`
5. **Review the multi-tenant architecture** guide

## Support

- Full API Docs: http://localhost:8000/docs
- Postman Collection: http://localhost:8000/docs.postman
- OpenAPI Spec: http://localhost:8000/docs.openapi

Happy coding! 🚀
