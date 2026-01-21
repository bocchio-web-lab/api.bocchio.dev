# API Testing Reference

## Base URL
```
http://localhost:8000
```

## Authentication Endpoints

### Register User
```http
POST /register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password",
  "password_confirmation": "password"
}
```

### Login
```http
POST /login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password"
}

Response:
{
  "token": "1|abc123def456...",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
  }
}
```

### Get Authenticated User
```http
GET /api/user
Authorization: Bearer {{token}}
```

### Logout
```http
POST /logout
Authorization: Bearer {{token}}
```

---

## Platform Management Endpoints

### List Available Services
```http
GET /api/manage/services
Authorization: Bearer {{token}}

Response:
{
  "data": [
    {
      "id": 1,
      "name": "Content Management System",
      "slug": "cms",
      "description": "A flexible CMS...",
      "is_active": true
    }
  ]
}
```

### List User's Tenants
```http
GET /api/manage/tenants
Authorization: Bearer {{token}}

Response:
{
  "data": [
    {
      "id": 1,
      "service_id": 1,
      "owner_id": 1,
      "name": "My Blog",
      "public_slug": "my-blog",
      "access_level": "public",
      "service": { ... }
    }
  ]
}
```

### Create Tenant
```http
POST /api/manage/tenants
Authorization: Bearer {{token}}
Content-Type: application/json

{
  "name": "My Personal Blog",
  "service_id": 1,
  "public_slug": "personal-blog",
  "access_level": "public"
}

Response:
{
  "data": {
    "id": 1,
    "name": "My Personal Blog",
    "public_slug": "personal-blog",
    "access_level": "public",
    "public_api_key": "pk_abc123...",
    ...
  }
}
```

### Get Tenant Details
```http
GET /api/manage/tenants/{tenant_id}
Authorization: Bearer {{token}}
```

### Update Tenant
```http
PUT /api/manage/tenants/{tenant_id}
Authorization: Bearer {{token}}
Content-Type: application/json

{
  "name": "Updated Blog Name",
  "access_level": "token_protected",
  "regenerate_api_key": true
}
```

### Delete Tenant
```http
DELETE /api/manage/tenants/{tenant_id}
Authorization: Bearer {{token}}
```

---

## CMS Management Endpoints
*Requires: auth:sanctum + X-Tenant-ID header*

### Get Dashboard Stats
```http
GET /api/manage/cms/dashboard
Authorization: Bearer {{token}}
X-Tenant-ID: {{tenant_id}}

Response:
{
  "data": {
    "tenant_id": 1,
    "message": "CMS Management Dashboard",
    "stats": {
      "posts": 0,
      "pages": 0,
      "media": 0
    }
  }
}
```

### List Posts (Management)
```http
GET /api/manage/cms/posts
Authorization: Bearer {{token}}
X-Tenant-ID: {{tenant_id}}
```

### List Pages (Management)
```http
GET /api/manage/cms/pages
Authorization: Bearer {{token}}
X-Tenant-ID: {{tenant_id}}
```

---

## CMS Content Delivery Endpoints
*Public/semi-public access - no Sanctum auth required*

### Get Tenant Info
```http
GET /api/content/cms/{tenant_slug}

# Example
GET /api/content/cms/my-blog

Response:
{
  "data": {
    "tenant": {
      "name": "My Blog",
      "slug": "my-blog"
    },
    "message": "CMS Content API"
  }
}
```

### List Published Posts
```http
GET /api/content/cms/{tenant_slug}/posts

# For public tenant
GET /api/content/cms/my-blog/posts

# For token-protected tenant
GET /api/content/cms/client-site/posts
Authorization: Bearer pk_abc123...
```

### Get Single Post
```http
GET /api/content/cms/{tenant_slug}/posts/{post_slug}

# Example
GET /api/content/cms/my-blog/posts/hello-world
```

### List Published Pages
```http
GET /api/content/cms/{tenant_slug}/pages
```

### Get Single Page
```http
GET /api/content/cms/{tenant_slug}/pages/{page_slug}
```

---

## Access Level Behaviors

### Private Tenant
```http
GET /api/content/cms/private-tenant/posts

Response: 403 Forbidden
{
  "error": "This tenant is private and not accessible via public API"
}
```

### Public Tenant
```http
GET /api/content/cms/public-tenant/posts

Response: 200 OK (no auth required)
```

### Token-Protected Tenant
```http
# Without token
GET /api/content/cms/protected-tenant/posts

Response: 401 Unauthorized

# With valid token
GET /api/content/cms/protected-tenant/posts
Authorization: Bearer pk_valid_token_here

Response: 200 OK
```

---

## Error Responses

### Missing X-Tenant-ID
```http
GET /api/manage/cms/dashboard
Authorization: Bearer {{token}}

Response: 400 Bad Request
{
  "message": "X-Tenant-ID header is missing."
}
```

### Invalid Tenant Access
```http
GET /api/manage/cms/dashboard
Authorization: Bearer {{token}}
X-Tenant-ID: 999

Response: 403 Forbidden
{
  "message": "Forbidden. You do not have access to this tenant or service."
}
```

### Unauthenticated
```http
GET /api/manage/tenants

Response: 401 Unauthorized
{
  "message": "Unauthenticated."
}
```

---

## Testing Workflow Example

1. **Register** a new user
2. **Login** to get token
3. **List services** to see available options
4. **Create tenant** for CMS service
5. **Access management API** with tenant context
6. **Update tenant** to make it public
7. **Test content API** without authentication

---

## Postman Environment Variables

Create these variables in Postman:

- `base_url`: `http://localhost:8000`
- `token`: (Set after login)
- `tenant_id`: (Set after creating tenant)
- `tenant_slug`: (e.g., "my-blog")

---

## cURL Examples

### Complete Flow
```bash
# 1. Register
curl -X POST http://localhost:8000/register \
  -H "Content-Type: application/json" \
  -d '{"name":"John","email":"john@test.com","password":"password","password_confirmation":"password"}'

# 2. Login
TOKEN=$(curl -X POST http://localhost:8000/login \
  -H "Content-Type: application/json" \
  -d '{"email":"john@test.com","password":"password"}' \
  | jq -r '.token')

# 3. Create Tenant
TENANT_ID=$(curl -X POST http://localhost:8000/api/manage/tenants \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"name":"My Blog","service_id":1,"public_slug":"my-blog","access_level":"public"}' \
  | jq -r '.data.id')

# 4. Access Management API
curl http://localhost:8000/api/manage/cms/dashboard \
  -H "Authorization: Bearer $TOKEN" \
  -H "X-Tenant-ID: $TENANT_ID"

# 5. Access Content API
curl http://localhost:8000/api/content/cms/my-blog/posts
```

---

## Notes

- Replace `{{token}}`, `{{tenant_id}}`, and `{{tenant_slug}}` with actual values
- All management endpoints require authentication
- Content endpoints may require authentication based on tenant access level
- API keys (pk_*) are for content API access, not Sanctum tokens
- Sanctum tokens are for user authentication in management API
