# Multi-Tenant Architecture

This section explains the core concepts and security model of the platform's multi-tenant architecture.

## What is a Tenant?

A **tenant** is an isolated workspace within a service. Each tenant:

- Has its own completely isolated data
- Is owned by a user who created it
- Can have additional members with different roles
- Has a unique public slug for content access (e.g., `my-blog-abc123`)
- Has configurable access control for the Content Delivery API

Think of a tenant like a separate database namespace - data from one tenant is never visible to another tenant.

## What is a Service?

A **service** is a logical grouping of functionality. Currently available:

- **CMS** - Content Management System (posts, pages, projects, tags, comments)

Future services might include:
- E-commerce platforms
- Analytics dashboards
- Custom application backends

## Tenant Isolation Mechanism

### Database-Level Isolation

The platform uses **global Eloquent scopes** to ensure all queries are automatically filtered by tenant:

```php
// When you query content items with tenant context set
ContentItem::all();  // Automatically filtered to current tenant

// Impossible to accidentally query across tenants
```

### Middleware Enforcement

Two middleware components enforce tenant context:

1. **`tenant.context:{service}`** - For Management API
   - Validates `X-Tenant-ID` header
   - Checks user is owner or member
   - Sets global tenant context

2. **`tenant.public_access:{service}`** - For Content Delivery API
   - Resolves tenant from URL slug
   - Enforces access level (public/private/token-protected)
   - Sets global tenant context

### Multi-Database Architecture

Data is separated across three databases:

```
┌─────────────────────────────────────────────────────────┐
│  identity_db                                             │
│  • users                                                 │
│  • password_resets                                       │
│  • personal_access_tokens (Sanctum)                     │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│  platform_db                                             │
│  • services                                              │
│  • tenants                                               │
│  • tenant_user (pivot table)                            │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│  cms_db                                                  │
│  • content_items (+ tenant_id foreign key)             │
│  • tags (+ tenant_id foreign key)                       │
│  • comments (+ tenant_id foreign key)                   │
│  • content_item_tag (pivot)                             │
└─────────────────────────────────────────────────────────┘
```

This architecture ensures:
- Clear separation of concerns
- Service-specific optimizations
- Independent scaling capabilities

## Tenant Roles and Permissions

### Owner
The user who created the tenant. Has full control:
- ✅ Update tenant settings
- ✅ Delete tenant
- ✅ Manage all content
- ✅ Delete any content
- ✅ Invite/remove members

### Admin
Invited member with elevated privileges:
- ✅ Create content
- ✅ Edit their own content
- ❌ Cannot update tenant settings
- ❌ Cannot delete tenant

### Member
Standard member access:
- ✅ Create content
- ✅ Edit their own content
- ❌ Cannot edit others' content
- ❌ Cannot update tenant settings

## Access Levels for Content Delivery

Each tenant has an `access_level` that controls public content access:

### Public
```
Tenant: access_level = "public"
Request: GET /api/content/cms/my-blog/posts
Authentication: None required
Result: ✅ Returns published content
```

Perfect for:
- Public blogs
- Marketing sites
- Open portfolios

### Private
```
Tenant: access_level = "private"
Request: GET /api/content/cms/internal-docs/posts
Authentication: None provided
Result: ❌ 403 Forbidden
```

Perfect for:
- Internal documentation
- Private knowledge bases
- Staging environments

### Token Protected
```
Tenant: access_level = "token_protected"
         public_api_key = "pk_abc123..."

# Without token
Request: GET /api/content/cms/partners/posts
Result: ❌ 401 Unauthorized

# With token
Request: GET /api/content/cms/partners/posts
         Authorization: Bearer pk_abc123...
Result: ✅ Returns published content
```

Perfect for:
- Partner portals
- Semi-public APIs
- Controlled distribution

## Security Guarantees

The platform provides the following security guarantees:

1. **No Cross-Tenant Data Leakage**
   - Global scopes prevent accidental queries across tenants
   - Database foreign keys use tenant_id
   - Middleware validates tenant access on every request

2. **Permission Enforcement**
   - Only owners can delete tenants
   - Only authors and owners can edit/delete content
   - Member roles are enforced at the controller level

3. **Public API Protection**
   - Private tenants are completely inaccessible
   - Token-protected tenants require valid API key
   - Public tenants only expose published content

4. **Authentication Security**
   - Sanctum provides secure token management
   - Tokens are scoped per user
   - Logout invalidates tokens immediately

## Common Patterns

### Creating Your First Tenant

1. Authenticate to get a token
2. List available services
3. Create a tenant for a service
4. Note the tenant ID for subsequent requests

### Managing Content

1. Set `X-Tenant-ID` header to your tenant's ID
2. Use Management API endpoints to create/edit content
3. Mark content as "published" when ready
4. Content becomes available on Content Delivery API (if tenant is public/token-protected)

### Consuming Content Publicly

1. Use tenant's public slug in the URL
2. No authentication for public tenants
3. Only published content is returned
4. Drafts and archived content remain private

## Error Scenarios

### Missing Tenant Context
```
GET /api/manage/cms/content
Authorization: Bearer token...
(Missing X-Tenant-ID header)

Response: 403 Forbidden
{
  "message": "Tenant ID not specified in header."
}
```

### Wrong Tenant Access
```
GET /api/manage/cms/content
Authorization: Bearer token...
X-Tenant-ID: 999
(Tenant 999 doesn't belong to user)

Response: 403 Forbidden
{
  "message": "You are not a member of this tenant."
}
```

### Private Tenant Public Access
```
GET /api/content/cms/private-tenant/posts

Response: 403 Forbidden
{
  "message": "Access to this resource is private."
}
```

## Best Practices

1. **Always Set Tenant Context**
   - Include `X-Tenant-ID` on all CMS Management requests
   - Store tenant IDs client-side for easy reuse

2. **Use Appropriate Access Levels**
   - Default to `private` for new tenants
   - Only set `public` when content is ready for public consumption
   - Use `token_protected` for controlled external access

3. **Secure API Keys**
   - Store `public_api_key` securely if using token-protected mode
   - Regenerate keys if compromised
   - Never commit keys to version control

4. **Draft Before Publishing**
   - Create content as `draft` first
   - Review and test before setting to `published`
   - Published content is immediately visible on public API

5. **Manage Member Access**
   - Only invite trusted users to tenants
   - Regularly audit tenant members
   - Use appropriate roles for team members
