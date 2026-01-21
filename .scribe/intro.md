# Introduction

A multi-service, multi-tenant platform with identity management, tenant isolation, and CMS capabilities. This API enables you to manage services, tenants, and content across isolated tenant contexts.

<aside>
    <strong>Base URL</strong>: <code>http://localhost:8000</code>
</aside>

## Overview

This platform provides a multi-tenant architecture where:

- **Services** are logical groupings of functionality (e.g., CMS, future services)
- **Tenants** are isolated instances of a service, owned and managed by users
- **Two API types** serve different purposes:
  - **Management API** (`/api/manage/*`) - Authenticated CRUD operations for tenant owners/members
  - **Content Delivery API** (`/api/content/*`) - Public/semi-public content access for consumers

## Base URL

```
https://api.bocchio.dev
```

## Authentication

**Management API** requires:
- Bearer token authentication (Laravel Sanctum)
- `X-Tenant-ID` header to specify tenant context

**Content Delivery API** uses:
- Public slug-based tenant resolution (no auth for public tenants)
- Optional bearer token for token-protected tenants

## Tenant Isolation

All data is isolated by tenant. When making requests to the Management API, you must specify which tenant you're operating on via the `X-Tenant-ID` header. The platform ensures you can only access tenants you own or are a member of.

<aside>Code examples are shown on the right. You can switch between programming languages using the tabs.</aside>

