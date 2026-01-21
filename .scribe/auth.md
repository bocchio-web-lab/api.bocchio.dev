# Authenticating requests

This API uses **Laravel Sanctum** for token-based authentication on the Management API.

## Getting a Token

To obtain an authentication token, you'll need to authenticate using Laravel Fortify's login endpoint:

```bash
POST /login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "your-password"
}
```

The response will include a bearer token:

```json
{
  "token": "1|abc123def456..."
}
```

## Using the Token

To authenticate requests to the **Management API**, include an **`Authorization`** header with the value **`"Bearer {YOUR_AUTH_TOKEN}"`**.

All authenticated endpoints are marked with a `requires authentication` badge in the documentation below.

### Example Authenticated Request

```bash
GET /api/manage/services
Authorization: Bearer 1|abc123def456...
```

## Tenant Context Header

For CMS Management endpoints (under `/api/manage/cms/*`), you must also include the `X-Tenant-ID` header to specify which tenant you're operating on:

```bash
POST /api/manage/cms/content
Authorization: Bearer 1|abc123def456...
X-Tenant-ID: 1
Content-Type: application/json

{
  "type": "post",
  "title": "My First Post",
  "body": "Content..."
}
```

## Content Delivery API

The **Content Delivery API** (`/api/content/*`) does NOT use bearer token authentication by default. Instead:

- **Public tenants**: No authentication required
- **Private tenants**: Completely inaccessible via this API
- **Token-protected tenants**: Use `Authorization: Bearer {tenant_public_api_key}`

You can get a tenant's `public_api_key` from the tenant details endpoint (`GET /api/manage/tenants/{tenant}`).

## Token Management

Tokens can be revoked by logging out:

```bash
POST /logout
Authorization: Bearer 1|abc123def456...
```


