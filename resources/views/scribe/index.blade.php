<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Multi-Tenant Platform API Documentation</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "http://localhost:8000";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.5.0.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.5.0.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                                    <ul id="tocify-subheader-introduction" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="overview">
                                <a href="#overview">Overview</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="base-url">
                                <a href="#base-url">Base URL</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="authentication">
                                <a href="#authentication">Authentication</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="tenant-isolation">
                                <a href="#tenant-isolation">Tenant Isolation</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
                                    <ul id="tocify-subheader-authenticating-requests" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="getting-a-token">
                                <a href="#getting-a-token">Getting a Token</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="using-the-token">
                                <a href="#using-the-token">Using the Token</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="tenant-context-header">
                                <a href="#tenant-context-header">Tenant Context Header</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="content-delivery-api">
                                <a href="#content-delivery-api">Content Delivery API</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="token-management">
                                <a href="#token-management">Token Management</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-platform-management" class="tocify-header">
                <li class="tocify-item level-1" data-unique="platform-management">
                    <a href="#platform-management">Platform Management</a>
                </li>
                                    <ul id="tocify-subheader-platform-management" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="platform-management-GETapi-manage-services">
                                <a href="#platform-management-GETapi-manage-services">List all services</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="platform-management-GETapi-manage-tenants">
                                <a href="#platform-management-GETapi-manage-tenants">List your tenants</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="platform-management-POSTapi-manage-tenants">
                                <a href="#platform-management-POSTapi-manage-tenants">Create a tenant</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="platform-management-GETapi-manage-tenants--id-">
                                <a href="#platform-management-GETapi-manage-tenants--id-">Get tenant details</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="platform-management-PUTapi-manage-tenants--id-">
                                <a href="#platform-management-PUTapi-manage-tenants--id-">Update a tenant</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="platform-management-DELETEapi-manage-tenants--id-">
                                <a href="#platform-management-DELETEapi-manage-tenants--id-">Delete a tenant</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-cms-management" class="tocify-header">
                <li class="tocify-item level-1" data-unique="cms-management">
                    <a href="#cms-management">CMS - Management</a>
                </li>
                                    <ul id="tocify-subheader-cms-management" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="cms-management-GETapi-manage-cms-content">
                                <a href="#cms-management-GETapi-manage-cms-content">List content items</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="cms-management-POSTapi-manage-cms-content">
                                <a href="#cms-management-POSTapi-manage-cms-content">Create content item</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="cms-management-GETapi-manage-cms-content--id-">
                                <a href="#cms-management-GETapi-manage-cms-content--id-">Get content item</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="cms-management-PUTapi-manage-cms-content--id-">
                                <a href="#cms-management-PUTapi-manage-cms-content--id-">Update content item</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="cms-management-DELETEapi-manage-cms-content--id-">
                                <a href="#cms-management-DELETEapi-manage-cms-content--id-">Delete content item</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="cms-management-GETapi-manage-cms-comments">
                                <a href="#cms-management-GETapi-manage-cms-comments">List comments</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="cms-management-POSTapi-manage-cms-comments--comment_id--approve">
                                <a href="#cms-management-POSTapi-manage-cms-comments--comment_id--approve">Approve comment</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="cms-management-POSTapi-manage-cms-comments--comment_id--reject">
                                <a href="#cms-management-POSTapi-manage-cms-comments--comment_id--reject">Reject comment</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="cms-management-DELETEapi-manage-cms-comments--comment_id-">
                                <a href="#cms-management-DELETEapi-manage-cms-comments--comment_id-">Delete comment</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="cms-management-GETapi-manage-cms-tags">
                                <a href="#cms-management-GETapi-manage-cms-tags">List tags</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="cms-management-POSTapi-manage-cms-tags">
                                <a href="#cms-management-POSTapi-manage-cms-tags">Create tag</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="cms-management-GETapi-manage-cms-tags--id-">
                                <a href="#cms-management-GETapi-manage-cms-tags--id-">Get tag with content</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="cms-management-PUTapi-manage-cms-tags--id-">
                                <a href="#cms-management-PUTapi-manage-cms-tags--id-">Update tag</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="cms-management-DELETEapi-manage-cms-tags--id-">
                                <a href="#cms-management-DELETEapi-manage-cms-tags--id-">Delete tag</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-cms-content-delivery" class="tocify-header">
                <li class="tocify-item level-1" data-unique="cms-content-delivery">
                    <a href="#cms-content-delivery">CMS - Content Delivery</a>
                </li>
                                    <ul id="tocify-subheader-cms-content-delivery" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="cms-content-delivery-GETapi-content-cms--tenant_slug-">
                                <a href="#cms-content-delivery-GETapi-content-cms--tenant_slug-">Get tenant info</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="cms-content-delivery-GETapi-content-cms--tenant_slug--posts">
                                <a href="#cms-content-delivery-GETapi-content-cms--tenant_slug--posts">List published posts</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="cms-content-delivery-GETapi-content-cms--tenant_slug--posts--post_slug-">
                                <a href="#cms-content-delivery-GETapi-content-cms--tenant_slug--posts--post_slug-">Get a published post</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="cms-content-delivery-GETapi-content-cms--tenant_slug--pages">
                                <a href="#cms-content-delivery-GETapi-content-cms--tenant_slug--pages">List published pages</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="cms-content-delivery-GETapi-content-cms--tenant_slug--pages--page_slug-">
                                <a href="#cms-content-delivery-GETapi-content-cms--tenant_slug--pages--page_slug-">Get a published page</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="cms-content-delivery-GETapi-content-cms--tenant_slug--projects">
                                <a href="#cms-content-delivery-GETapi-content-cms--tenant_slug--projects">List published projects</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="cms-content-delivery-GETapi-content-cms--tenant_slug--tags">
                                <a href="#cms-content-delivery-GETapi-content-cms--tenant_slug--tags">List tags with published content</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="cms-content-delivery-GETapi-content-cms--tenant_slug--tags--tag_slug-">
                                <a href="#cms-content-delivery-GETapi-content-cms--tenant_slug--tags--tag_slug-">Get content by tag</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                    <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: January 21, 2026</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<p>A multi-service, multi-tenant platform with identity management, tenant isolation, and CMS capabilities. This API enables you to manage services, tenants, and content across isolated tenant contexts.</p>
<aside>
    <strong>Base URL</strong>: <code>http://localhost:8000</code>
</aside>
<h2 id="overview">Overview</h2>
<p>This platform provides a multi-tenant architecture where:</p>
<ul>
<li><strong>Services</strong> are logical groupings of functionality (e.g., CMS, future services)</li>
<li><strong>Tenants</strong> are isolated instances of a service, owned and managed by users</li>
<li><strong>Two API types</strong> serve different purposes:
<ul>
<li><strong>Management API</strong> (<code>/api/manage/*</code>) - Authenticated CRUD operations for tenant owners/members</li>
<li><strong>Content Delivery API</strong> (<code>/api/content/*</code>) - Public/semi-public content access for consumers</li>
</ul></li>
</ul>
<h2 id="base-url">Base URL</h2>
<pre><code>https://api.bocchio.dev</code></pre>
<h2 id="authentication">Authentication</h2>
<p><strong>Management API</strong> requires:</p>
<ul>
<li>Bearer token authentication (Laravel Sanctum)</li>
<li><code>X-Tenant-ID</code> header to specify tenant context</li>
</ul>
<p><strong>Content Delivery API</strong> uses:</p>
<ul>
<li>Public slug-based tenant resolution (no auth for public tenants)</li>
<li>Optional bearer token for token-protected tenants</li>
</ul>
<h2 id="tenant-isolation">Tenant Isolation</h2>
<p>All data is isolated by tenant. When making requests to the Management API, you must specify which tenant you're operating on via the <code>X-Tenant-ID</code> header. The platform ensures you can only access tenants you own or are a member of.</p>
<aside>Code examples are shown on the right. You can switch between programming languages using the tabs.</aside>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>This API uses <strong>Laravel Sanctum</strong> for token-based authentication on the Management API.</p>
<h2 id="getting-a-token">Getting a Token</h2>
<p>To obtain an authentication token, you'll need to authenticate using Laravel Fortify's login endpoint:</p>
<pre><code class="language-bash">POST /login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "your-password"
}</code></pre>
<p>The response will include a bearer token:</p>
<pre><code class="language-json">{
  "token": "1|abc123def456..."
}</code></pre>
<h2 id="using-the-token">Using the Token</h2>
<p>To authenticate requests to the <strong>Management API</strong>, include an <strong><code>Authorization</code></strong> header with the value <strong><code>"Bearer {YOUR_AUTH_TOKEN}"</code></strong>.</p>
<p>All authenticated endpoints are marked with a <code>requires authentication</code> badge in the documentation below.</p>
<h3 id="example-authenticated-request">Example Authenticated Request</h3>
<pre><code class="language-bash">GET /api/manage/services
Authorization: Bearer 1|abc123def456...</code></pre>
<h2 id="tenant-context-header">Tenant Context Header</h2>
<p>For CMS Management endpoints (under <code>/api/manage/cms/*</code>), you must also include the <code>X-Tenant-ID</code> header to specify which tenant you're operating on:</p>
<pre><code class="language-bash">POST /api/manage/cms/content
Authorization: Bearer 1|abc123def456...
X-Tenant-ID: 1
Content-Type: application/json

{
  "type": "post",
  "title": "My First Post",
  "body": "Content..."
}</code></pre>
<h2 id="content-delivery-api">Content Delivery API</h2>
<p>The <strong>Content Delivery API</strong> (<code>/api/content/*</code>) does NOT use bearer token authentication by default. Instead:</p>
<ul>
<li><strong>Public tenants</strong>: No authentication required</li>
<li><strong>Private tenants</strong>: Completely inaccessible via this API</li>
<li><strong>Token-protected tenants</strong>: Use <code>Authorization: Bearer {tenant_public_api_key}</code></li>
</ul>
<p>You can get a tenant's <code>public_api_key</code> from the tenant details endpoint (<code>GET /api/manage/tenants/{tenant}</code>).</p>
<h2 id="token-management">Token Management</h2>
<p>Tokens can be revoked by logging out:</p>
<pre><code class="language-bash">POST /logout
Authorization: Bearer 1|abc123def456...</code></pre>

        <h1 id="platform-management">Platform Management</h1>

    <p>APIs for managing platform services and tenants. These endpoints require authentication
and allow users to list available services and manage their tenants.</p>

                                <h2 id="platform-management-GETapi-manage-services">List all services</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Returns a list of all active services available on the platform.
Services are logical groupings of functionality (e.g., CMS).
You'll need a service ID when creating a tenant.</p>

<span id="example-requests-GETapi-manage-services">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/manage/services" \
    --header "Authorization: {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/manage/services"
);

const headers = {
    "Authorization": "{YOUR_AUTH_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-manage-services">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;CMS&quot;,
            &quot;slug&quot;: &quot;cms&quot;,
            &quot;description&quot;: &quot;Content Management System&quot;,
            &quot;is_active&quot;: true,
            &quot;created_at&quot;: &quot;2025-01-20T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-01-20T10:00:00.000000Z&quot;
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-manage-services" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-manage-services"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-manage-services"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-manage-services" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-manage-services">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-manage-services" data-method="GET"
      data-path="api/manage/services"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-manage-services', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-manage-services"
                    onclick="tryItOut('GETapi-manage-services');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-manage-services"
                    onclick="cancelTryOut('GETapi-manage-services');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-manage-services"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/manage/services</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-manage-services"
               value="{YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>{YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-manage-services"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-manage-services"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="platform-management-GETapi-manage-tenants">List your tenants</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Returns all tenants that the authenticated user owns or is a member of.
This includes both tenants you created and tenants you've been invited to.</p>

<span id="example-requests-GETapi-manage-tenants">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/manage/tenants" \
    --header "Authorization: {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/manage/tenants"
);

const headers = {
    "Authorization": "{YOUR_AUTH_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-manage-tenants">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;My CMS&quot;,
            &quot;service_id&quot;: 1,
            &quot;owner_id&quot;: 1,
            &quot;public_slug&quot;: &quot;my-cms-abc123&quot;,
            &quot;access_level&quot;: &quot;public&quot;,
            &quot;public_api_key&quot;: &quot;pk_1234567890abcdef&quot;,
            &quot;settings&quot;: {},
            &quot;created_at&quot;: &quot;2025-01-20T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-01-20T10:00:00.000000Z&quot;,
            &quot;service&quot;: {
                &quot;id&quot;: 1,
                &quot;name&quot;: &quot;CMS&quot;,
                &quot;slug&quot;: &quot;cms&quot;,
                &quot;description&quot;: &quot;Content Management System&quot;,
                &quot;is_active&quot;: true
            }
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-manage-tenants" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-manage-tenants"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-manage-tenants"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-manage-tenants" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-manage-tenants">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-manage-tenants" data-method="GET"
      data-path="api/manage/tenants"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-manage-tenants', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-manage-tenants"
                    onclick="tryItOut('GETapi-manage-tenants');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-manage-tenants"
                    onclick="cancelTryOut('GETapi-manage-tenants');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-manage-tenants"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/manage/tenants</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-manage-tenants"
               value="{YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>{YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-manage-tenants"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-manage-tenants"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="platform-management-POSTapi-manage-tenants">Create a tenant</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Creates a new tenant for a specific service. You become the owner and are
automatically added as an admin member. The tenant will have a unique public
slug used for public content access.</p>

<span id="example-requests-POSTapi-manage-tenants">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/manage/tenants" \
    --header "Authorization: {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"My Blog\",
    \"service_id\": 1,
    \"public_slug\": \"my-blog\",
    \"access_level\": \"public\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/manage/tenants"
);

const headers = {
    "Authorization": "{YOUR_AUTH_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "My Blog",
    "service_id": 1,
    "public_slug": "my-blog",
    "access_level": "public"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-manage-tenants">
            <blockquote>
            <p>Example response (201):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 2,
        &quot;name&quot;: &quot;My Blog&quot;,
        &quot;service_id&quot;: 1,
        &quot;owner_id&quot;: 1,
        &quot;public_slug&quot;: &quot;my-blog&quot;,
        &quot;access_level&quot;: &quot;public&quot;,
        &quot;public_api_key&quot;: &quot;pk_abcdefghijklmnopqrstuvwxyz123456789&quot;,
        &quot;settings&quot;: {},
        &quot;created_at&quot;: &quot;2025-01-22T10:00:00.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-01-22T10:00:00.000000Z&quot;,
        &quot;service&quot;: {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;CMS&quot;,
            &quot;slug&quot;: &quot;cms&quot;
        }
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-manage-tenants" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-manage-tenants"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-manage-tenants"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-manage-tenants" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-manage-tenants">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-manage-tenants" data-method="POST"
      data-path="api/manage/tenants"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-manage-tenants', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-manage-tenants"
                    onclick="tryItOut('POSTapi-manage-tenants');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-manage-tenants"
                    onclick="cancelTryOut('POSTapi-manage-tenants');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-manage-tenants"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/manage/tenants</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-manage-tenants"
               value="{YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>{YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-manage-tenants"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-manage-tenants"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-manage-tenants"
               value="My Blog"
               data-component="body">
    <br>
<p>The display name of the tenant. Example: <code>My Blog</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>service_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="service_id"                data-endpoint="POSTapi-manage-tenants"
               value="1"
               data-component="body">
    <br>
<p>The ID of the service (get from /api/manage/services). Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>public_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="public_slug"                data-endpoint="POSTapi-manage-tenants"
               value="my-blog"
               data-component="body">
    <br>
<p>Optional custom public slug (must be unique). If not provided, one will be auto-generated. Example: <code>my-blog</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>access_level</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="access_level"                data-endpoint="POSTapi-manage-tenants"
               value="public"
               data-component="body">
    <br>
<p>Access level for public content API. One of: public, private, token_protected. Default is private. Example: <code>public</code></p>
        </div>
        </form>

                    <h2 id="platform-management-GETapi-manage-tenants--id-">Get tenant details</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Returns detailed information about a specific tenant, including its members.
You must be the owner or a member to access this endpoint.</p>

<span id="example-requests-GETapi-manage-tenants--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/manage/tenants/1" \
    --header "Authorization: {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/manage/tenants/1"
);

const headers = {
    "Authorization": "{YOUR_AUTH_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-manage-tenants--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;My CMS&quot;,
        &quot;service_id&quot;: 1,
        &quot;owner_id&quot;: 1,
        &quot;public_slug&quot;: &quot;my-cms-abc123&quot;,
        &quot;access_level&quot;: &quot;public&quot;,
        &quot;public_api_key&quot;: &quot;pk_1234567890abcdef&quot;,
        &quot;settings&quot;: {},
        &quot;created_at&quot;: &quot;2025-01-20T10:00:00.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-01-20T10:00:00.000000Z&quot;,
        &quot;service&quot;: {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;CMS&quot;,
            &quot;slug&quot;: &quot;cms&quot;
        },
        &quot;users&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;name&quot;: &quot;John Doe&quot;,
                &quot;email&quot;: &quot;john@example.com&quot;,
                &quot;pivot&quot;: {
                    &quot;role&quot;: &quot;admin&quot;
                }
            }
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;You do not have access to this tenant.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-manage-tenants--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-manage-tenants--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-manage-tenants--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-manage-tenants--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-manage-tenants--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-manage-tenants--id-" data-method="GET"
      data-path="api/manage/tenants/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-manage-tenants--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-manage-tenants--id-"
                    onclick="tryItOut('GETapi-manage-tenants--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-manage-tenants--id-"
                    onclick="cancelTryOut('GETapi-manage-tenants--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-manage-tenants--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/manage/tenants/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-manage-tenants--id-"
               value="{YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>{YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-manage-tenants--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-manage-tenants--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-manage-tenants--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the tenant. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>tenant</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="tenant"                data-endpoint="GETapi-manage-tenants--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the tenant. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="platform-management-PUTapi-manage-tenants--id-">Update a tenant</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Updates tenant information such as name, public slug, or access level.
Only the tenant owner can perform updates. You can also regenerate the
public API key used for token-protected content access.</p>

<span id="example-requests-PUTapi-manage-tenants--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8000/api/manage/tenants/1" \
    --header "Authorization: {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Updated Blog Name\",
    \"public_slug\": \"updated-blog\",
    \"access_level\": \"token_protected\",
    \"regenerate_api_key\": false
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/manage/tenants/1"
);

const headers = {
    "Authorization": "{YOUR_AUTH_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Updated Blog Name",
    "public_slug": "updated-blog",
    "access_level": "token_protected",
    "regenerate_api_key": false
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-manage-tenants--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Updated Blog Name&quot;,
        &quot;service_id&quot;: 1,
        &quot;owner_id&quot;: 1,
        &quot;public_slug&quot;: &quot;updated-blog&quot;,
        &quot;access_level&quot;: &quot;token_protected&quot;,
        &quot;public_api_key&quot;: &quot;pk_newgeneratedkey1234567890&quot;,
        &quot;settings&quot;: {},
        &quot;created_at&quot;: &quot;2025-01-20T10:00:00.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-01-22T11:00:00.000000Z&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Only the tenant owner can perform this action.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-manage-tenants--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-manage-tenants--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-manage-tenants--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-manage-tenants--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-manage-tenants--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-manage-tenants--id-" data-method="PUT"
      data-path="api/manage/tenants/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-manage-tenants--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-manage-tenants--id-"
                    onclick="tryItOut('PUTapi-manage-tenants--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-manage-tenants--id-"
                    onclick="cancelTryOut('PUTapi-manage-tenants--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-manage-tenants--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/manage/tenants/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/manage/tenants/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-manage-tenants--id-"
               value="{YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>{YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-manage-tenants--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-manage-tenants--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTapi-manage-tenants--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the tenant. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>tenant</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="tenant"                data-endpoint="PUTapi-manage-tenants--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the tenant. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-manage-tenants--id-"
               value="Updated Blog Name"
               data-component="body">
    <br>
<p>The display name. Example: <code>Updated Blog Name</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>public_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="public_slug"                data-endpoint="PUTapi-manage-tenants--id-"
               value="updated-blog"
               data-component="body">
    <br>
<p>Custom public slug (must be unique). Example: <code>updated-blog</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>access_level</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="access_level"                data-endpoint="PUTapi-manage-tenants--id-"
               value="token_protected"
               data-component="body">
    <br>
<p>Access level: public, private, or token_protected. Example: <code>token_protected</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>regenerate_api_key</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="PUTapi-manage-tenants--id-" style="display: none">
            <input type="radio" name="regenerate_api_key"
                   value="true"
                   data-endpoint="PUTapi-manage-tenants--id-"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="PUTapi-manage-tenants--id-" style="display: none">
            <input type="radio" name="regenerate_api_key"
                   value="false"
                   data-endpoint="PUTapi-manage-tenants--id-"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Set to true to generate a new public API key. Example: <code>false</code></p>
        </div>
        </form>

                    <h2 id="platform-management-DELETEapi-manage-tenants--id-">Delete a tenant</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Permanently deletes a tenant and all associated data. This action cannot be undone.
Only the tenant owner can delete a tenant.</p>

<span id="example-requests-DELETEapi-manage-tenants--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8000/api/manage/tenants/1" \
    --header "Authorization: {YOUR_AUTH_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/manage/tenants/1"
);

const headers = {
    "Authorization": "{YOUR_AUTH_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-manage-tenants--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Tenant deleted successfully&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (403):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Only the tenant owner can delete this tenant.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-manage-tenants--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-manage-tenants--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-manage-tenants--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-manage-tenants--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-manage-tenants--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-manage-tenants--id-" data-method="DELETE"
      data-path="api/manage/tenants/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-manage-tenants--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-manage-tenants--id-"
                    onclick="tryItOut('DELETEapi-manage-tenants--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-manage-tenants--id-"
                    onclick="cancelTryOut('DELETEapi-manage-tenants--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-manage-tenants--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/manage/tenants/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="DELETEapi-manage-tenants--id-"
               value="{YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>{YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-manage-tenants--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-manage-tenants--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="DELETEapi-manage-tenants--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the tenant. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>tenant</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="tenant"                data-endpoint="DELETEapi-manage-tenants--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the tenant. Example: <code>1</code></p>
            </div>
                    </form>

                <h1 id="cms-management">CMS - Management</h1>

    <p>Manage content items (posts, pages, projects) within a CMS tenant.
All endpoints require authentication and the <code>X-Tenant-ID</code> header.
Content is isolated by tenant - you can only manage content in tenants you own or are a member of.</p>

                                <h2 id="cms-management-GETapi-manage-cms-content">List content items</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Returns a paginated list of content items in the current tenant.
You can filter by type (post, page, project) and status (draft, published, archived).</p>

<span id="example-requests-GETapi-manage-cms-content">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/manage/cms/content?type=post&amp;status=published" \
    --header "Authorization: {YOUR_AUTH_TOKEN}" \
    --header "X-Tenant-ID: required The ID of the tenant context. Example: 1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/manage/cms/content"
);

const params = {
    "type": "post",
    "status": "published",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "{YOUR_AUTH_TOKEN}",
    "X-Tenant-ID": "required The ID of the tenant context. Example: 1",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-manage-cms-content">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;current_page&quot;: 1,
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;tenant_id&quot;: 1,
            &quot;type&quot;: &quot;post&quot;,
            &quot;title&quot;: &quot;My First Blog Post&quot;,
            &quot;slug&quot;: &quot;my-first-blog-post&quot;,
            &quot;excerpt&quot;: &quot;This is a short excerpt&quot;,
            &quot;body&quot;: &quot;Full content goes here...&quot;,
            &quot;status&quot;: &quot;published&quot;,
            &quot;author_id&quot;: 1,
            &quot;published_at&quot;: &quot;2025-01-22T10:00:00.000000Z&quot;,
            &quot;meta&quot;: {},
            &quot;created_at&quot;: &quot;2025-01-22T09:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-01-22T10:00:00.000000Z&quot;,
            &quot;author&quot;: {
                &quot;id&quot;: 1,
                &quot;name&quot;: &quot;John Doe&quot;,
                &quot;email&quot;: &quot;john@example.com&quot;
            },
            &quot;tags&quot;: [
                {
                    &quot;id&quot;: 1,
                    &quot;name&quot;: &quot;Laravel&quot;,
                    &quot;slug&quot;: &quot;laravel&quot;
                }
            ]
        }
    ],
    &quot;per_page&quot;: 15,
    &quot;total&quot;: 25
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-manage-cms-content" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-manage-cms-content"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-manage-cms-content"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-manage-cms-content" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-manage-cms-content">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-manage-cms-content" data-method="GET"
      data-path="api/manage/cms/content"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-manage-cms-content', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-manage-cms-content"
                    onclick="tryItOut('GETapi-manage-cms-content');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-manage-cms-content"
                    onclick="cancelTryOut('GETapi-manage-cms-content');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-manage-cms-content"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/manage/cms/content</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-manage-cms-content"
               value="{YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>{YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-Tenant-ID</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-Tenant-ID"                data-endpoint="GETapi-manage-cms-content"
               value="required The ID of the tenant context. Example: 1"
               data-component="header">
    <br>
<p>Example: <code>required The ID of the tenant context. Example: 1</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-manage-cms-content"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-manage-cms-content"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="type"                data-endpoint="GETapi-manage-cms-content"
               value="post"
               data-component="query">
    <br>
<p>Filter by content type: post, page, or project. Example: <code>post</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="GETapi-manage-cms-content"
               value="published"
               data-component="query">
    <br>
<p>Filter by status: draft, published, or archived. Example: <code>published</code></p>
            </div>
                </form>

                    <h2 id="cms-management-POSTapi-manage-cms-content">Create content item</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Creates a new content item (post, page, or project). The authenticated user
becomes the author. You can optionally attach tags and set the status.
If no slug is provided, one will be auto-generated from the title.</p>

<span id="example-requests-POSTapi-manage-cms-content">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/manage/cms/content" \
    --header "Authorization: {YOUR_AUTH_TOKEN}" \
    --header "X-Tenant-ID: required The ID of the tenant context. Example: 1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"type\": \"post\",
    \"title\": \"Getting Started with Laravel\",
    \"slug\": \"getting-started-with-laravel\",
    \"excerpt\": \"Learn the basics of Laravel\",
    \"body\": \"# Getting Started\\\\n\\\\nLaravel is amazing...\",
    \"status\": \"published\",
    \"published_at\": \"2025-01-22T10:00:00Z\",
    \"meta\": {
        \"featured\": true
    },
    \"tags\": [
        1,
        2,
        3
    ]
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/manage/cms/content"
);

const headers = {
    "Authorization": "{YOUR_AUTH_TOKEN}",
    "X-Tenant-ID": "required The ID of the tenant context. Example: 1",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "type": "post",
    "title": "Getting Started with Laravel",
    "slug": "getting-started-with-laravel",
    "excerpt": "Learn the basics of Laravel",
    "body": "# Getting Started\\n\\nLaravel is amazing...",
    "status": "published",
    "published_at": "2025-01-22T10:00:00Z",
    "meta": {
        "featured": true
    },
    "tags": [
        1,
        2,
        3
    ]
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-manage-cms-content">
            <blockquote>
            <p>Example response (201):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 5,
        &quot;tenant_id&quot;: 1,
        &quot;type&quot;: &quot;post&quot;,
        &quot;title&quot;: &quot;Getting Started with Laravel&quot;,
        &quot;slug&quot;: &quot;getting-started-with-laravel&quot;,
        &quot;excerpt&quot;: &quot;Learn the basics of Laravel&quot;,
        &quot;body&quot;: &quot;# Getting Started...&quot;,
        &quot;status&quot;: &quot;published&quot;,
        &quot;author_id&quot;: 1,
        &quot;published_at&quot;: &quot;2025-01-22T10:00:00.000000Z&quot;,
        &quot;meta&quot;: {
            &quot;featured&quot;: true
        },
        &quot;created_at&quot;: &quot;2025-01-22T09:30:00.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-01-22T09:30:00.000000Z&quot;,
        &quot;author&quot;: {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;John Doe&quot;,
            &quot;email&quot;: &quot;john@example.com&quot;
        },
        &quot;tags&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;name&quot;: &quot;Laravel&quot;,
                &quot;slug&quot;: &quot;laravel&quot;
            },
            {
                &quot;id&quot;: 2,
                &quot;name&quot;: &quot;PHP&quot;,
                &quot;slug&quot;: &quot;php&quot;
            }
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-manage-cms-content" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-manage-cms-content"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-manage-cms-content"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-manage-cms-content" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-manage-cms-content">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-manage-cms-content" data-method="POST"
      data-path="api/manage/cms/content"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-manage-cms-content', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-manage-cms-content"
                    onclick="tryItOut('POSTapi-manage-cms-content');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-manage-cms-content"
                    onclick="cancelTryOut('POSTapi-manage-cms-content');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-manage-cms-content"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/manage/cms/content</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-manage-cms-content"
               value="{YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>{YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-Tenant-ID</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-Tenant-ID"                data-endpoint="POSTapi-manage-cms-content"
               value="required The ID of the tenant context. Example: 1"
               data-component="header">
    <br>
<p>Example: <code>required The ID of the tenant context. Example: 1</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-manage-cms-content"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-manage-cms-content"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="type"                data-endpoint="POSTapi-manage-cms-content"
               value="post"
               data-component="body">
    <br>
<p>Type of content: post, page, or project. Example: <code>post</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>title</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="title"                data-endpoint="POSTapi-manage-cms-content"
               value="Getting Started with Laravel"
               data-component="body">
    <br>
<p>The title of the content. Example: <code>Getting Started with Laravel</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="slug"                data-endpoint="POSTapi-manage-cms-content"
               value="getting-started-with-laravel"
               data-component="body">
    <br>
<p>Optional URL-friendly slug. Auto-generated if not provided. Example: <code>getting-started-with-laravel</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>excerpt</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="excerpt"                data-endpoint="POSTapi-manage-cms-content"
               value="Learn the basics of Laravel"
               data-component="body">
    <br>
<p>Short summary or excerpt. Example: <code>Learn the basics of Laravel</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>body</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="body"                data-endpoint="POSTapi-manage-cms-content"
               value="# Getting Started\n\nLaravel is amazing..."
               data-component="body">
    <br>
<p>The full content body (HTML/Markdown). Example: <code># Getting Started\n\nLaravel is amazing...</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="POSTapi-manage-cms-content"
               value="published"
               data-component="body">
    <br>
<p>Status: draft, published, or archived. Default is draft. Example: <code>published</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>published_at</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="published_at"                data-endpoint="POSTapi-manage-cms-content"
               value="2025-01-22T10:00:00Z"
               data-component="body">
    <br>
<p>Publish date in ISO format. Example: <code>2025-01-22T10:00:00Z</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>meta</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="meta"                data-endpoint="POSTapi-manage-cms-content"
               value=""
               data-component="body">
    <br>
<p>Custom metadata as key-value pairs.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>tags</code></b>&nbsp;&nbsp;
<small>integer[]</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="tags[0]"                data-endpoint="POSTapi-manage-cms-content"
               data-component="body">
        <input type="number" style="display: none"
               name="tags[1]"                data-endpoint="POSTapi-manage-cms-content"
               data-component="body">
    <br>
<p>Array of tag IDs to attach.</p>
        </div>
        </form>

                    <h2 id="cms-management-GETapi-manage-cms-content--id-">Get content item</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Returns detailed information about a specific content item, including
author details, tags, and comments.</p>

<span id="example-requests-GETapi-manage-cms-content--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/manage/cms/content/1" \
    --header "Authorization: {YOUR_AUTH_TOKEN}" \
    --header "X-Tenant-ID: required The ID of the tenant context. Example: 1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/manage/cms/content/1"
);

const headers = {
    "Authorization": "{YOUR_AUTH_TOKEN}",
    "X-Tenant-ID": "required The ID of the tenant context. Example: 1",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-manage-cms-content--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;tenant_id&quot;: 1,
        &quot;type&quot;: &quot;post&quot;,
        &quot;title&quot;: &quot;My First Blog Post&quot;,
        &quot;slug&quot;: &quot;my-first-blog-post&quot;,
        &quot;excerpt&quot;: &quot;This is a short excerpt&quot;,
        &quot;body&quot;: &quot;Full content goes here...&quot;,
        &quot;status&quot;: &quot;published&quot;,
        &quot;author_id&quot;: 1,
        &quot;published_at&quot;: &quot;2025-01-22T10:00:00.000000Z&quot;,
        &quot;meta&quot;: {},
        &quot;created_at&quot;: &quot;2025-01-22T09:00:00.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-01-22T10:00:00.000000Z&quot;,
        &quot;author&quot;: {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;John Doe&quot;,
            &quot;email&quot;: &quot;john@example.com&quot;
        },
        &quot;tags&quot;: [],
        &quot;comments&quot;: []
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-manage-cms-content--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-manage-cms-content--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-manage-cms-content--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-manage-cms-content--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-manage-cms-content--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-manage-cms-content--id-" data-method="GET"
      data-path="api/manage/cms/content/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-manage-cms-content--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-manage-cms-content--id-"
                    onclick="tryItOut('GETapi-manage-cms-content--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-manage-cms-content--id-"
                    onclick="cancelTryOut('GETapi-manage-cms-content--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-manage-cms-content--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/manage/cms/content/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-manage-cms-content--id-"
               value="{YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>{YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-Tenant-ID</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-Tenant-ID"                data-endpoint="GETapi-manage-cms-content--id-"
               value="required The ID of the tenant context. Example: 1"
               data-component="header">
    <br>
<p>Example: <code>required The ID of the tenant context. Example: 1</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-manage-cms-content--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-manage-cms-content--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-manage-cms-content--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the content. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>contentItem</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="contentItem"                data-endpoint="GETapi-manage-cms-content--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the content item. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="cms-management-PUTapi-manage-cms-content--id-">Update content item</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Updates an existing content item. Only the author or tenant owner can update content.
You can update any field including title, body, status, and tags.</p>

<span id="example-requests-PUTapi-manage-cms-content--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8000/api/manage/cms/content/1" \
    --header "Authorization: {YOUR_AUTH_TOKEN}" \
    --header "X-Tenant-ID: required The ID of the tenant context. Example: 1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"title\": \"Updated Title\",
    \"slug\": \"updated-title\",
    \"excerpt\": \"Updated excerpt\",
    \"body\": \"Updated content...\",
    \"status\": \"published\",
    \"published_at\": \"2025-01-22T10:00:00Z\",
    \"meta\": {
        \"featured\": false
    },
    \"tags\": [
        1,
        3
    ]
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/manage/cms/content/1"
);

const headers = {
    "Authorization": "{YOUR_AUTH_TOKEN}",
    "X-Tenant-ID": "required The ID of the tenant context. Example: 1",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "title": "Updated Title",
    "slug": "updated-title",
    "excerpt": "Updated excerpt",
    "body": "Updated content...",
    "status": "published",
    "published_at": "2025-01-22T10:00:00Z",
    "meta": {
        "featured": false
    },
    "tags": [
        1,
        3
    ]
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-manage-cms-content--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;title&quot;: &quot;Updated Title&quot;,
        &quot;slug&quot;: &quot;updated-title&quot;,
        &quot;status&quot;: &quot;published&quot;,
        &quot;updated_at&quot;: &quot;2025-01-22T11:00:00.000000Z&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;You do not have permission to update this content.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-manage-cms-content--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-manage-cms-content--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-manage-cms-content--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-manage-cms-content--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-manage-cms-content--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-manage-cms-content--id-" data-method="PUT"
      data-path="api/manage/cms/content/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-manage-cms-content--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-manage-cms-content--id-"
                    onclick="tryItOut('PUTapi-manage-cms-content--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-manage-cms-content--id-"
                    onclick="cancelTryOut('PUTapi-manage-cms-content--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-manage-cms-content--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/manage/cms/content/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/manage/cms/content/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-manage-cms-content--id-"
               value="{YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>{YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-Tenant-ID</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-Tenant-ID"                data-endpoint="PUTapi-manage-cms-content--id-"
               value="required The ID of the tenant context. Example: 1"
               data-component="header">
    <br>
<p>Example: <code>required The ID of the tenant context. Example: 1</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-manage-cms-content--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-manage-cms-content--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTapi-manage-cms-content--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the content. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>contentItem</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="contentItem"                data-endpoint="PUTapi-manage-cms-content--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the content item. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>title</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="title"                data-endpoint="PUTapi-manage-cms-content--id-"
               value="Updated Title"
               data-component="body">
    <br>
<p>The title. Example: <code>Updated Title</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="slug"                data-endpoint="PUTapi-manage-cms-content--id-"
               value="updated-title"
               data-component="body">
    <br>
<p>URL-friendly slug. Example: <code>updated-title</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>excerpt</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="excerpt"                data-endpoint="PUTapi-manage-cms-content--id-"
               value="Updated excerpt"
               data-component="body">
    <br>
<p>Short summary. Example: <code>Updated excerpt</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>body</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="body"                data-endpoint="PUTapi-manage-cms-content--id-"
               value="Updated content..."
               data-component="body">
    <br>
<p>The content body. Example: <code>Updated content...</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="PUTapi-manage-cms-content--id-"
               value="published"
               data-component="body">
    <br>
<p>Status: draft, published, or archived. Example: <code>published</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>published_at</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="published_at"                data-endpoint="PUTapi-manage-cms-content--id-"
               value="2025-01-22T10:00:00Z"
               data-component="body">
    <br>
<p>Publish date. Example: <code>2025-01-22T10:00:00Z</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>meta</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="meta"                data-endpoint="PUTapi-manage-cms-content--id-"
               value=""
               data-component="body">
    <br>
<p>Custom metadata.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>tags</code></b>&nbsp;&nbsp;
<small>integer[]</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="tags[0]"                data-endpoint="PUTapi-manage-cms-content--id-"
               data-component="body">
        <input type="number" style="display: none"
               name="tags[1]"                data-endpoint="PUTapi-manage-cms-content--id-"
               data-component="body">
    <br>
<p>Tag IDs to attach.</p>
        </div>
        </form>

                    <h2 id="cms-management-DELETEapi-manage-cms-content--id-">Delete content item</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Permanently deletes a content item. Only the author or tenant owner can delete content.
This action cannot be undone.</p>

<span id="example-requests-DELETEapi-manage-cms-content--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8000/api/manage/cms/content/1" \
    --header "Authorization: {YOUR_AUTH_TOKEN}" \
    --header "X-Tenant-ID: required The ID of the tenant context. Example: 1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/manage/cms/content/1"
);

const headers = {
    "Authorization": "{YOUR_AUTH_TOKEN}",
    "X-Tenant-ID": "required The ID of the tenant context. Example: 1",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-manage-cms-content--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Content item deleted successfully&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (403):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;You do not have permission to delete this content.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-manage-cms-content--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-manage-cms-content--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-manage-cms-content--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-manage-cms-content--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-manage-cms-content--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-manage-cms-content--id-" data-method="DELETE"
      data-path="api/manage/cms/content/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-manage-cms-content--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-manage-cms-content--id-"
                    onclick="tryItOut('DELETEapi-manage-cms-content--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-manage-cms-content--id-"
                    onclick="cancelTryOut('DELETEapi-manage-cms-content--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-manage-cms-content--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/manage/cms/content/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="DELETEapi-manage-cms-content--id-"
               value="{YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>{YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-Tenant-ID</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-Tenant-ID"                data-endpoint="DELETEapi-manage-cms-content--id-"
               value="required The ID of the tenant context. Example: 1"
               data-component="header">
    <br>
<p>Example: <code>required The ID of the tenant context. Example: 1</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-manage-cms-content--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-manage-cms-content--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="DELETEapi-manage-cms-content--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the content. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>contentItem</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="contentItem"                data-endpoint="DELETEapi-manage-cms-content--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the content item. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="cms-management-GETapi-manage-cms-comments">List comments</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Returns a paginated list of comments with optional filtering by approval status.
Includes related content item and author information.</p>

<span id="example-requests-GETapi-manage-cms-comments">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/manage/cms/comments?approved=" \
    --header "Authorization: {YOUR_AUTH_TOKEN}" \
    --header "X-Tenant-ID: required The ID of the tenant context. Example: 1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/manage/cms/comments"
);

const params = {
    "approved": "0",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "{YOUR_AUTH_TOKEN}",
    "X-Tenant-ID": "required The ID of the tenant context. Example: 1",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-manage-cms-comments">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;current_page&quot;: 1,
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;content_item_id&quot;: 1,
            &quot;author_id&quot;: 2,
            &quot;body&quot;: &quot;Great article!&quot;,
            &quot;approved&quot;: true,
            &quot;created_at&quot;: &quot;2025-01-22T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-01-22T10:00:00.000000Z&quot;,
            &quot;content_item&quot;: {
                &quot;id&quot;: 1,
                &quot;title&quot;: &quot;My First Blog Post&quot;,
                &quot;slug&quot;: &quot;my-first-blog-post&quot;
            },
            &quot;author&quot;: {
                &quot;id&quot;: 2,
                &quot;name&quot;: &quot;Jane Smith&quot;,
                &quot;email&quot;: &quot;jane@example.com&quot;
            }
        }
    ],
    &quot;per_page&quot;: 20,
    &quot;total&quot;: 45
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-manage-cms-comments" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-manage-cms-comments"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-manage-cms-comments"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-manage-cms-comments" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-manage-cms-comments">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-manage-cms-comments" data-method="GET"
      data-path="api/manage/cms/comments"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-manage-cms-comments', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-manage-cms-comments"
                    onclick="tryItOut('GETapi-manage-cms-comments');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-manage-cms-comments"
                    onclick="cancelTryOut('GETapi-manage-cms-comments');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-manage-cms-comments"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/manage/cms/comments</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-manage-cms-comments"
               value="{YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>{YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-Tenant-ID</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-Tenant-ID"                data-endpoint="GETapi-manage-cms-comments"
               value="required The ID of the tenant context. Example: 1"
               data-component="header">
    <br>
<p>Example: <code>required The ID of the tenant context. Example: 1</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-manage-cms-comments"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-manage-cms-comments"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>approved</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="GETapi-manage-cms-comments" style="display: none">
            <input type="radio" name="approved"
                   value="1"
                   data-endpoint="GETapi-manage-cms-comments"
                   data-component="query"             >
            <code>true</code>
        </label>
        <label data-endpoint="GETapi-manage-cms-comments" style="display: none">
            <input type="radio" name="approved"
                   value="0"
                   data-endpoint="GETapi-manage-cms-comments"
                   data-component="query"             >
            <code>false</code>
        </label>
    <br>
<p>Filter by approval status. Example: <code>false</code></p>
            </div>
                </form>

                    <h2 id="cms-management-POSTapi-manage-cms-comments--comment_id--approve">Approve comment</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Approves a comment, making it visible on the public content API.</p>

<span id="example-requests-POSTapi-manage-cms-comments--comment_id--approve">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/manage/cms/comments/16/approve" \
    --header "Authorization: {YOUR_AUTH_TOKEN}" \
    --header "X-Tenant-ID: required The ID of the tenant context. Example: 1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/manage/cms/comments/16/approve"
);

const headers = {
    "Authorization": "{YOUR_AUTH_TOKEN}",
    "X-Tenant-ID": "required The ID of the tenant context. Example: 1",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-manage-cms-comments--comment_id--approve">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;approved&quot;: true,
        &quot;updated_at&quot;: &quot;2025-01-22T11:00:00.000000Z&quot;
    },
    &quot;message&quot;: &quot;Comment approved successfully&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-manage-cms-comments--comment_id--approve" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-manage-cms-comments--comment_id--approve"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-manage-cms-comments--comment_id--approve"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-manage-cms-comments--comment_id--approve" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-manage-cms-comments--comment_id--approve">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-manage-cms-comments--comment_id--approve" data-method="POST"
      data-path="api/manage/cms/comments/{comment_id}/approve"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-manage-cms-comments--comment_id--approve', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-manage-cms-comments--comment_id--approve"
                    onclick="tryItOut('POSTapi-manage-cms-comments--comment_id--approve');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-manage-cms-comments--comment_id--approve"
                    onclick="cancelTryOut('POSTapi-manage-cms-comments--comment_id--approve');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-manage-cms-comments--comment_id--approve"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/manage/cms/comments/{comment_id}/approve</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-manage-cms-comments--comment_id--approve"
               value="{YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>{YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-Tenant-ID</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-Tenant-ID"                data-endpoint="POSTapi-manage-cms-comments--comment_id--approve"
               value="required The ID of the tenant context. Example: 1"
               data-component="header">
    <br>
<p>Example: <code>required The ID of the tenant context. Example: 1</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-manage-cms-comments--comment_id--approve"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-manage-cms-comments--comment_id--approve"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>comment_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="comment_id"                data-endpoint="POSTapi-manage-cms-comments--comment_id--approve"
               value="16"
               data-component="url">
    <br>
<p>The ID of the comment. Example: <code>16</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>comment</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="comment"                data-endpoint="POSTapi-manage-cms-comments--comment_id--approve"
               value="1"
               data-component="url">
    <br>
<p>The ID of the comment. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="cms-management-POSTapi-manage-cms-comments--comment_id--reject">Reject comment</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Rejects a comment, hiding it from the public content API.</p>

<span id="example-requests-POSTapi-manage-cms-comments--comment_id--reject">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/manage/cms/comments/16/reject" \
    --header "Authorization: {YOUR_AUTH_TOKEN}" \
    --header "X-Tenant-ID: required The ID of the tenant context. Example: 1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/manage/cms/comments/16/reject"
);

const headers = {
    "Authorization": "{YOUR_AUTH_TOKEN}",
    "X-Tenant-ID": "required The ID of the tenant context. Example: 1",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-manage-cms-comments--comment_id--reject">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;approved&quot;: false,
        &quot;updated_at&quot;: &quot;2025-01-22T11:00:00.000000Z&quot;
    },
    &quot;message&quot;: &quot;Comment rejected successfully&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-manage-cms-comments--comment_id--reject" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-manage-cms-comments--comment_id--reject"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-manage-cms-comments--comment_id--reject"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-manage-cms-comments--comment_id--reject" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-manage-cms-comments--comment_id--reject">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-manage-cms-comments--comment_id--reject" data-method="POST"
      data-path="api/manage/cms/comments/{comment_id}/reject"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-manage-cms-comments--comment_id--reject', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-manage-cms-comments--comment_id--reject"
                    onclick="tryItOut('POSTapi-manage-cms-comments--comment_id--reject');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-manage-cms-comments--comment_id--reject"
                    onclick="cancelTryOut('POSTapi-manage-cms-comments--comment_id--reject');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-manage-cms-comments--comment_id--reject"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/manage/cms/comments/{comment_id}/reject</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-manage-cms-comments--comment_id--reject"
               value="{YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>{YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-Tenant-ID</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-Tenant-ID"                data-endpoint="POSTapi-manage-cms-comments--comment_id--reject"
               value="required The ID of the tenant context. Example: 1"
               data-component="header">
    <br>
<p>Example: <code>required The ID of the tenant context. Example: 1</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-manage-cms-comments--comment_id--reject"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-manage-cms-comments--comment_id--reject"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>comment_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="comment_id"                data-endpoint="POSTapi-manage-cms-comments--comment_id--reject"
               value="16"
               data-component="url">
    <br>
<p>The ID of the comment. Example: <code>16</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>comment</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="comment"                data-endpoint="POSTapi-manage-cms-comments--comment_id--reject"
               value="1"
               data-component="url">
    <br>
<p>The ID of the comment. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="cms-management-DELETEapi-manage-cms-comments--comment_id-">Delete comment</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Permanently deletes a comment. This action cannot be undone.</p>

<span id="example-requests-DELETEapi-manage-cms-comments--comment_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8000/api/manage/cms/comments/16" \
    --header "Authorization: {YOUR_AUTH_TOKEN}" \
    --header "X-Tenant-ID: required The ID of the tenant context. Example: 1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/manage/cms/comments/16"
);

const headers = {
    "Authorization": "{YOUR_AUTH_TOKEN}",
    "X-Tenant-ID": "required The ID of the tenant context. Example: 1",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-manage-cms-comments--comment_id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Comment deleted successfully&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-manage-cms-comments--comment_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-manage-cms-comments--comment_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-manage-cms-comments--comment_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-manage-cms-comments--comment_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-manage-cms-comments--comment_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-manage-cms-comments--comment_id-" data-method="DELETE"
      data-path="api/manage/cms/comments/{comment_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-manage-cms-comments--comment_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-manage-cms-comments--comment_id-"
                    onclick="tryItOut('DELETEapi-manage-cms-comments--comment_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-manage-cms-comments--comment_id-"
                    onclick="cancelTryOut('DELETEapi-manage-cms-comments--comment_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-manage-cms-comments--comment_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/manage/cms/comments/{comment_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="DELETEapi-manage-cms-comments--comment_id-"
               value="{YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>{YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-Tenant-ID</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-Tenant-ID"                data-endpoint="DELETEapi-manage-cms-comments--comment_id-"
               value="required The ID of the tenant context. Example: 1"
               data-component="header">
    <br>
<p>Example: <code>required The ID of the tenant context. Example: 1</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-manage-cms-comments--comment_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-manage-cms-comments--comment_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>comment_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="comment_id"                data-endpoint="DELETEapi-manage-cms-comments--comment_id-"
               value="16"
               data-component="url">
    <br>
<p>The ID of the comment. Example: <code>16</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>comment</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="comment"                data-endpoint="DELETEapi-manage-cms-comments--comment_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the comment. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="cms-management-GETapi-manage-cms-tags">List tags</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Returns all tags in the current tenant with content item counts.</p>

<span id="example-requests-GETapi-manage-cms-tags">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/manage/cms/tags" \
    --header "Authorization: {YOUR_AUTH_TOKEN}" \
    --header "X-Tenant-ID: required The ID of the tenant context. Example: 1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/manage/cms/tags"
);

const headers = {
    "Authorization": "{YOUR_AUTH_TOKEN}",
    "X-Tenant-ID": "required The ID of the tenant context. Example: 1",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-manage-cms-tags">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;tenant_id&quot;: 1,
            &quot;name&quot;: &quot;Laravel&quot;,
            &quot;slug&quot;: &quot;laravel&quot;,
            &quot;created_at&quot;: &quot;2025-01-20T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-01-20T10:00:00.000000Z&quot;,
            &quot;content_items_count&quot;: 12
        },
        {
            &quot;id&quot;: 2,
            &quot;tenant_id&quot;: 1,
            &quot;name&quot;: &quot;PHP&quot;,
            &quot;slug&quot;: &quot;php&quot;,
            &quot;created_at&quot;: &quot;2025-01-20T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-01-20T10:00:00.000000Z&quot;,
            &quot;content_items_count&quot;: 8
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-manage-cms-tags" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-manage-cms-tags"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-manage-cms-tags"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-manage-cms-tags" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-manage-cms-tags">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-manage-cms-tags" data-method="GET"
      data-path="api/manage/cms/tags"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-manage-cms-tags', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-manage-cms-tags"
                    onclick="tryItOut('GETapi-manage-cms-tags');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-manage-cms-tags"
                    onclick="cancelTryOut('GETapi-manage-cms-tags');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-manage-cms-tags"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/manage/cms/tags</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-manage-cms-tags"
               value="{YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>{YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-Tenant-ID</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-Tenant-ID"                data-endpoint="GETapi-manage-cms-tags"
               value="required The ID of the tenant context. Example: 1"
               data-component="header">
    <br>
<p>Example: <code>required The ID of the tenant context. Example: 1</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-manage-cms-tags"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-manage-cms-tags"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="cms-management-POSTapi-manage-cms-tags">Create tag</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Creates a new tag. If no slug is provided, one will be auto-generated from the name.</p>

<span id="example-requests-POSTapi-manage-cms-tags">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/manage/cms/tags" \
    --header "Authorization: {YOUR_AUTH_TOKEN}" \
    --header "X-Tenant-ID: required The ID of the tenant context. Example: 1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Vue.js\",
    \"slug\": \"vuejs\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/manage/cms/tags"
);

const headers = {
    "Authorization": "{YOUR_AUTH_TOKEN}",
    "X-Tenant-ID": "required The ID of the tenant context. Example: 1",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Vue.js",
    "slug": "vuejs"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-manage-cms-tags">
            <blockquote>
            <p>Example response (201):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 3,
        &quot;tenant_id&quot;: 1,
        &quot;name&quot;: &quot;Vue.js&quot;,
        &quot;slug&quot;: &quot;vuejs&quot;,
        &quot;created_at&quot;: &quot;2025-01-22T10:00:00.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-01-22T10:00:00.000000Z&quot;
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-manage-cms-tags" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-manage-cms-tags"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-manage-cms-tags"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-manage-cms-tags" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-manage-cms-tags">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-manage-cms-tags" data-method="POST"
      data-path="api/manage/cms/tags"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-manage-cms-tags', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-manage-cms-tags"
                    onclick="tryItOut('POSTapi-manage-cms-tags');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-manage-cms-tags"
                    onclick="cancelTryOut('POSTapi-manage-cms-tags');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-manage-cms-tags"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/manage/cms/tags</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-manage-cms-tags"
               value="{YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>{YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-Tenant-ID</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-Tenant-ID"                data-endpoint="POSTapi-manage-cms-tags"
               value="required The ID of the tenant context. Example: 1"
               data-component="header">
    <br>
<p>Example: <code>required The ID of the tenant context. Example: 1</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-manage-cms-tags"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-manage-cms-tags"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-manage-cms-tags"
               value="Vue.js"
               data-component="body">
    <br>
<p>The tag name. Example: <code>Vue.js</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="slug"                data-endpoint="POSTapi-manage-cms-tags"
               value="vuejs"
               data-component="body">
    <br>
<p>Optional URL-friendly slug. Example: <code>vuejs</code></p>
        </div>
        </form>

                    <h2 id="cms-management-GETapi-manage-cms-tags--id-">Get tag with content</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Returns a specific tag with all associated content items.</p>

<span id="example-requests-GETapi-manage-cms-tags--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/manage/cms/tags/16" \
    --header "Authorization: {YOUR_AUTH_TOKEN}" \
    --header "X-Tenant-ID: required The ID of the tenant context. Example: 1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/manage/cms/tags/16"
);

const headers = {
    "Authorization": "{YOUR_AUTH_TOKEN}",
    "X-Tenant-ID": "required The ID of the tenant context. Example: 1",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-manage-cms-tags--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;tenant_id&quot;: 1,
        &quot;name&quot;: &quot;Laravel&quot;,
        &quot;slug&quot;: &quot;laravel&quot;,
        &quot;created_at&quot;: &quot;2025-01-20T10:00:00.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-01-20T10:00:00.000000Z&quot;,
        &quot;content_items&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;title&quot;: &quot;Getting Started with Laravel&quot;,
                &quot;slug&quot;: &quot;getting-started-with-laravel&quot;,
                &quot;type&quot;: &quot;post&quot;,
                &quot;status&quot;: &quot;published&quot;
            }
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-manage-cms-tags--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-manage-cms-tags--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-manage-cms-tags--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-manage-cms-tags--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-manage-cms-tags--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-manage-cms-tags--id-" data-method="GET"
      data-path="api/manage/cms/tags/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-manage-cms-tags--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-manage-cms-tags--id-"
                    onclick="tryItOut('GETapi-manage-cms-tags--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-manage-cms-tags--id-"
                    onclick="cancelTryOut('GETapi-manage-cms-tags--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-manage-cms-tags--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/manage/cms/tags/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-manage-cms-tags--id-"
               value="{YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>{YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-Tenant-ID</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-Tenant-ID"                data-endpoint="GETapi-manage-cms-tags--id-"
               value="required The ID of the tenant context. Example: 1"
               data-component="header">
    <br>
<p>Example: <code>required The ID of the tenant context. Example: 1</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-manage-cms-tags--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-manage-cms-tags--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-manage-cms-tags--id-"
               value="16"
               data-component="url">
    <br>
<p>The ID of the tag. Example: <code>16</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>tag</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="tag"                data-endpoint="GETapi-manage-cms-tags--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the tag. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="cms-management-PUTapi-manage-cms-tags--id-">Update tag</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Updates a tag's name or slug.</p>

<span id="example-requests-PUTapi-manage-cms-tags--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8000/api/manage/cms/tags/16" \
    --header "Authorization: {YOUR_AUTH_TOKEN}" \
    --header "X-Tenant-ID: required The ID of the tenant context. Example: 1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Laravel 11\",
    \"slug\": \"laravel-11\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/manage/cms/tags/16"
);

const headers = {
    "Authorization": "{YOUR_AUTH_TOKEN}",
    "X-Tenant-ID": "required The ID of the tenant context. Example: 1",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Laravel 11",
    "slug": "laravel-11"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-manage-cms-tags--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Laravel 11&quot;,
        &quot;slug&quot;: &quot;laravel-11&quot;,
        &quot;updated_at&quot;: &quot;2025-01-22T11:00:00.000000Z&quot;
    }
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-manage-cms-tags--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-manage-cms-tags--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-manage-cms-tags--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-manage-cms-tags--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-manage-cms-tags--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-manage-cms-tags--id-" data-method="PUT"
      data-path="api/manage/cms/tags/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-manage-cms-tags--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-manage-cms-tags--id-"
                    onclick="tryItOut('PUTapi-manage-cms-tags--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-manage-cms-tags--id-"
                    onclick="cancelTryOut('PUTapi-manage-cms-tags--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-manage-cms-tags--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/manage/cms/tags/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/manage/cms/tags/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-manage-cms-tags--id-"
               value="{YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>{YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-Tenant-ID</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-Tenant-ID"                data-endpoint="PUTapi-manage-cms-tags--id-"
               value="required The ID of the tenant context. Example: 1"
               data-component="header">
    <br>
<p>Example: <code>required The ID of the tenant context. Example: 1</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-manage-cms-tags--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-manage-cms-tags--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTapi-manage-cms-tags--id-"
               value="16"
               data-component="url">
    <br>
<p>The ID of the tag. Example: <code>16</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>tag</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="tag"                data-endpoint="PUTapi-manage-cms-tags--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the tag. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-manage-cms-tags--id-"
               value="Laravel 11"
               data-component="body">
    <br>
<p>The tag name. Example: <code>Laravel 11</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="slug"                data-endpoint="PUTapi-manage-cms-tags--id-"
               value="laravel-11"
               data-component="body">
    <br>
<p>URL-friendly slug. Example: <code>laravel-11</code></p>
        </div>
        </form>

                    <h2 id="cms-management-DELETEapi-manage-cms-tags--id-">Delete tag</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Permanently deletes a tag. Content items associated with this tag will remain
but will no longer be tagged with it.</p>

<span id="example-requests-DELETEapi-manage-cms-tags--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8000/api/manage/cms/tags/16" \
    --header "Authorization: {YOUR_AUTH_TOKEN}" \
    --header "X-Tenant-ID: required The ID of the tenant context. Example: 1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/manage/cms/tags/16"
);

const headers = {
    "Authorization": "{YOUR_AUTH_TOKEN}",
    "X-Tenant-ID": "required The ID of the tenant context. Example: 1",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-manage-cms-tags--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Tag deleted successfully&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-manage-cms-tags--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-manage-cms-tags--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-manage-cms-tags--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-manage-cms-tags--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-manage-cms-tags--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-manage-cms-tags--id-" data-method="DELETE"
      data-path="api/manage/cms/tags/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-manage-cms-tags--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-manage-cms-tags--id-"
                    onclick="tryItOut('DELETEapi-manage-cms-tags--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-manage-cms-tags--id-"
                    onclick="cancelTryOut('DELETEapi-manage-cms-tags--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-manage-cms-tags--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/manage/cms/tags/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="DELETEapi-manage-cms-tags--id-"
               value="{YOUR_AUTH_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>{YOUR_AUTH_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-Tenant-ID</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-Tenant-ID"                data-endpoint="DELETEapi-manage-cms-tags--id-"
               value="required The ID of the tenant context. Example: 1"
               data-component="header">
    <br>
<p>Example: <code>required The ID of the tenant context. Example: 1</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-manage-cms-tags--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-manage-cms-tags--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="DELETEapi-manage-cms-tags--id-"
               value="16"
               data-component="url">
    <br>
<p>The ID of the tag. Example: <code>16</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>tag</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="tag"                data-endpoint="DELETEapi-manage-cms-tags--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the tag. Example: <code>1</code></p>
            </div>
                    </form>

                <h1 id="cms-content-delivery">CMS - Content Delivery</h1>

    <p>Public content delivery API for consuming published CMS content.
Access is controlled by tenant access level (public, private, or token_protected).
No authentication required for public tenants. Use the tenant's public slug in the URL.</p>
<h2>Tenant Access Levels</h2>
<ul>
<li><strong>public</strong>: Anyone can access content, no authentication needed</li>
<li><strong>private</strong>: Content is completely inaccessible via this API</li>
<li><strong>token_protected</strong>: Requires <code>Authorization: Bearer {tenant_public_api_key}</code> header</li>
</ul>
<h2>URL Structure</h2>
<p>All endpoints use the pattern: <code>/api/content/cms/{tenant_slug}/...</code>
where <code>{tenant_slug}</code> is the tenant's public slug (e.g., &quot;my-blog-abc123&quot;).</p>

                                <h2 id="cms-content-delivery-GETapi-content-cms--tenant_slug-">Get tenant info</h2>

<p>
</p>

<p>Returns basic information about the tenant. Useful for verifying tenant
slug and checking access.</p>

<span id="example-requests-GETapi-content-cms--tenant_slug-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/content/cms/my-blog-abc123" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/content/cms/my-blog-abc123"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-content-cms--tenant_slug-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;tenant&quot;: {
            &quot;name&quot;: &quot;My Blog&quot;,
            &quot;slug&quot;: &quot;my-blog-abc123&quot;
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (403):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Access to this resource is private.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-content-cms--tenant_slug-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-content-cms--tenant_slug-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-content-cms--tenant_slug-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-content-cms--tenant_slug-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-content-cms--tenant_slug-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-content-cms--tenant_slug-" data-method="GET"
      data-path="api/content/cms/{tenant_slug}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-content-cms--tenant_slug-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-content-cms--tenant_slug-"
                    onclick="tryItOut('GETapi-content-cms--tenant_slug-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-content-cms--tenant_slug-"
                    onclick="cancelTryOut('GETapi-content-cms--tenant_slug-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-content-cms--tenant_slug-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/content/cms/{tenant_slug}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-content-cms--tenant_slug-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-content-cms--tenant_slug-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>tenant_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="tenant_slug"                data-endpoint="GETapi-content-cms--tenant_slug-"
               value="my-blog-abc123"
               data-component="url">
    <br>
<p>The public slug of the tenant. Example: <code>my-blog-abc123</code></p>
            </div>
                    </form>

                    <h2 id="cms-content-delivery-GETapi-content-cms--tenant_slug--posts">List published posts</h2>

<p>
</p>

<p>Returns a paginated list of published blog posts, ordered by publish date (newest first).
Only published content is returned; drafts and archived posts are excluded.</p>

<span id="example-requests-GETapi-content-cms--tenant_slug--posts">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/content/cms/my-blog-abc123/posts" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/content/cms/my-blog-abc123/posts"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-content-cms--tenant_slug--posts">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;current_page&quot;: 1,
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;type&quot;: &quot;post&quot;,
            &quot;title&quot;: &quot;Getting Started with Laravel&quot;,
            &quot;slug&quot;: &quot;getting-started-with-laravel&quot;,
            &quot;excerpt&quot;: &quot;Learn the basics of Laravel&quot;,
            &quot;body&quot;: &quot;# Getting Started...&quot;,
            &quot;status&quot;: &quot;published&quot;,
            &quot;published_at&quot;: &quot;2025-01-22T10:00:00.000000Z&quot;,
            &quot;created_at&quot;: &quot;2025-01-22T09:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-01-22T10:00:00.000000Z&quot;,
            &quot;tags&quot;: [
                {
                    &quot;id&quot;: 1,
                    &quot;name&quot;: &quot;Laravel&quot;,
                    &quot;slug&quot;: &quot;laravel&quot;
                }
            ]
        }
    ],
    &quot;per_page&quot;: 15,
    &quot;total&quot;: 42
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-content-cms--tenant_slug--posts" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-content-cms--tenant_slug--posts"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-content-cms--tenant_slug--posts"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-content-cms--tenant_slug--posts" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-content-cms--tenant_slug--posts">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-content-cms--tenant_slug--posts" data-method="GET"
      data-path="api/content/cms/{tenant_slug}/posts"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-content-cms--tenant_slug--posts', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-content-cms--tenant_slug--posts"
                    onclick="tryItOut('GETapi-content-cms--tenant_slug--posts');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-content-cms--tenant_slug--posts"
                    onclick="cancelTryOut('GETapi-content-cms--tenant_slug--posts');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-content-cms--tenant_slug--posts"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/content/cms/{tenant_slug}/posts</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-content-cms--tenant_slug--posts"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-content-cms--tenant_slug--posts"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>tenant_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="tenant_slug"                data-endpoint="GETapi-content-cms--tenant_slug--posts"
               value="my-blog-abc123"
               data-component="url">
    <br>
<p>The public slug of the tenant. Example: <code>my-blog-abc123</code></p>
            </div>
                    </form>

                    <h2 id="cms-content-delivery-GETapi-content-cms--tenant_slug--posts--post_slug-">Get a published post</h2>

<p>
</p>

<p>Returns a single published post by its slug, including tags and approved comments.
Comments are ordered by creation date (newest first).</p>

<span id="example-requests-GETapi-content-cms--tenant_slug--posts--post_slug-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/content/cms/my-blog-abc123/posts/getting-started-with-laravel" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/content/cms/my-blog-abc123/posts/getting-started-with-laravel"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-content-cms--tenant_slug--posts--post_slug-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;type&quot;: &quot;post&quot;,
        &quot;title&quot;: &quot;Getting Started with Laravel&quot;,
        &quot;slug&quot;: &quot;getting-started-with-laravel&quot;,
        &quot;excerpt&quot;: &quot;Learn the basics of Laravel&quot;,
        &quot;body&quot;: &quot;# Getting Started\n\nLaravel is...&quot;,
        &quot;status&quot;: &quot;published&quot;,
        &quot;published_at&quot;: &quot;2025-01-22T10:00:00.000000Z&quot;,
        &quot;meta&quot;: {},
        &quot;created_at&quot;: &quot;2025-01-22T09:00:00.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-01-22T10:00:00.000000Z&quot;,
        &quot;tags&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;name&quot;: &quot;Laravel&quot;,
                &quot;slug&quot;: &quot;laravel&quot;
            }
        ],
        &quot;comments&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;body&quot;: &quot;Great article!&quot;,
                &quot;approved&quot;: true,
                &quot;created_at&quot;: &quot;2025-01-22T11:00:00.000000Z&quot;
            }
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No query results for model [ContentItem].&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-content-cms--tenant_slug--posts--post_slug-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-content-cms--tenant_slug--posts--post_slug-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-content-cms--tenant_slug--posts--post_slug-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-content-cms--tenant_slug--posts--post_slug-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-content-cms--tenant_slug--posts--post_slug-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-content-cms--tenant_slug--posts--post_slug-" data-method="GET"
      data-path="api/content/cms/{tenant_slug}/posts/{post_slug}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-content-cms--tenant_slug--posts--post_slug-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-content-cms--tenant_slug--posts--post_slug-"
                    onclick="tryItOut('GETapi-content-cms--tenant_slug--posts--post_slug-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-content-cms--tenant_slug--posts--post_slug-"
                    onclick="cancelTryOut('GETapi-content-cms--tenant_slug--posts--post_slug-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-content-cms--tenant_slug--posts--post_slug-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/content/cms/{tenant_slug}/posts/{post_slug}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-content-cms--tenant_slug--posts--post_slug-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-content-cms--tenant_slug--posts--post_slug-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>tenant_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="tenant_slug"                data-endpoint="GETapi-content-cms--tenant_slug--posts--post_slug-"
               value="my-blog-abc123"
               data-component="url">
    <br>
<p>The public slug of the tenant. Example: <code>my-blog-abc123</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>post_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="post_slug"                data-endpoint="GETapi-content-cms--tenant_slug--posts--post_slug-"
               value="getting-started-with-laravel"
               data-component="url">
    <br>
<p>The slug of the post. Example: <code>getting-started-with-laravel</code></p>
            </div>
                    </form>

                    <h2 id="cms-content-delivery-GETapi-content-cms--tenant_slug--pages">List published pages</h2>

<p>
</p>

<p>Returns all published pages, ordered alphabetically by title.
Pages are typically static content like &quot;About&quot; or &quot;Contact&quot;.</p>

<span id="example-requests-GETapi-content-cms--tenant_slug--pages">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/content/cms/my-blog-abc123/pages" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/content/cms/my-blog-abc123/pages"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-content-cms--tenant_slug--pages">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 2,
            &quot;type&quot;: &quot;page&quot;,
            &quot;title&quot;: &quot;About&quot;,
            &quot;slug&quot;: &quot;about&quot;,
            &quot;body&quot;: &quot;About us...&quot;,
            &quot;status&quot;: &quot;published&quot;,
            &quot;published_at&quot;: &quot;2025-01-20T10:00:00.000000Z&quot;
        },
        {
            &quot;id&quot;: 3,
            &quot;type&quot;: &quot;page&quot;,
            &quot;title&quot;: &quot;Contact&quot;,
            &quot;slug&quot;: &quot;contact&quot;,
            &quot;body&quot;: &quot;Contact information...&quot;,
            &quot;status&quot;: &quot;published&quot;,
            &quot;published_at&quot;: &quot;2025-01-20T10:00:00.000000Z&quot;
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-content-cms--tenant_slug--pages" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-content-cms--tenant_slug--pages"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-content-cms--tenant_slug--pages"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-content-cms--tenant_slug--pages" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-content-cms--tenant_slug--pages">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-content-cms--tenant_slug--pages" data-method="GET"
      data-path="api/content/cms/{tenant_slug}/pages"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-content-cms--tenant_slug--pages', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-content-cms--tenant_slug--pages"
                    onclick="tryItOut('GETapi-content-cms--tenant_slug--pages');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-content-cms--tenant_slug--pages"
                    onclick="cancelTryOut('GETapi-content-cms--tenant_slug--pages');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-content-cms--tenant_slug--pages"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/content/cms/{tenant_slug}/pages</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-content-cms--tenant_slug--pages"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-content-cms--tenant_slug--pages"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>tenant_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="tenant_slug"                data-endpoint="GETapi-content-cms--tenant_slug--pages"
               value="my-blog-abc123"
               data-component="url">
    <br>
<p>The public slug of the tenant. Example: <code>my-blog-abc123</code></p>
            </div>
                    </form>

                    <h2 id="cms-content-delivery-GETapi-content-cms--tenant_slug--pages--page_slug-">Get a published page</h2>

<p>
</p>

<p>Returns a single published page by its slug.</p>

<span id="example-requests-GETapi-content-cms--tenant_slug--pages--page_slug-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/content/cms/my-blog-abc123/pages/about" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/content/cms/my-blog-abc123/pages/about"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-content-cms--tenant_slug--pages--page_slug-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 2,
        &quot;type&quot;: &quot;page&quot;,
        &quot;title&quot;: &quot;About&quot;,
        &quot;slug&quot;: &quot;about&quot;,
        &quot;body&quot;: &quot;About us content...&quot;,
        &quot;status&quot;: &quot;published&quot;,
        &quot;published_at&quot;: &quot;2025-01-20T10:00:00.000000Z&quot;,
        &quot;meta&quot;: {},
        &quot;created_at&quot;: &quot;2025-01-20T09:00:00.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-01-20T10:00:00.000000Z&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No query results for model [ContentItem].&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-content-cms--tenant_slug--pages--page_slug-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-content-cms--tenant_slug--pages--page_slug-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-content-cms--tenant_slug--pages--page_slug-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-content-cms--tenant_slug--pages--page_slug-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-content-cms--tenant_slug--pages--page_slug-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-content-cms--tenant_slug--pages--page_slug-" data-method="GET"
      data-path="api/content/cms/{tenant_slug}/pages/{page_slug}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-content-cms--tenant_slug--pages--page_slug-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-content-cms--tenant_slug--pages--page_slug-"
                    onclick="tryItOut('GETapi-content-cms--tenant_slug--pages--page_slug-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-content-cms--tenant_slug--pages--page_slug-"
                    onclick="cancelTryOut('GETapi-content-cms--tenant_slug--pages--page_slug-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-content-cms--tenant_slug--pages--page_slug-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/content/cms/{tenant_slug}/pages/{page_slug}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-content-cms--tenant_slug--pages--page_slug-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-content-cms--tenant_slug--pages--page_slug-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>tenant_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="tenant_slug"                data-endpoint="GETapi-content-cms--tenant_slug--pages--page_slug-"
               value="my-blog-abc123"
               data-component="url">
    <br>
<p>The public slug of the tenant. Example: <code>my-blog-abc123</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>page_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="page_slug"                data-endpoint="GETapi-content-cms--tenant_slug--pages--page_slug-"
               value="about"
               data-component="url">
    <br>
<p>The slug of the page. Example: <code>about</code></p>
            </div>
                    </form>

                    <h2 id="cms-content-delivery-GETapi-content-cms--tenant_slug--projects">List published projects</h2>

<p>
</p>

<p>Returns all published projects with tags, ordered by publish date (newest first).
Projects are portfolio items or case studies.</p>

<span id="example-requests-GETapi-content-cms--tenant_slug--projects">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/content/cms/my-blog-abc123/projects" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/content/cms/my-blog-abc123/projects"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-content-cms--tenant_slug--projects">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 4,
            &quot;type&quot;: &quot;project&quot;,
            &quot;title&quot;: &quot;E-Commerce Platform&quot;,
            &quot;slug&quot;: &quot;ecommerce-platform&quot;,
            &quot;excerpt&quot;: &quot;A full-featured online store&quot;,
            &quot;body&quot;: &quot;Project details...&quot;,
            &quot;status&quot;: &quot;published&quot;,
            &quot;published_at&quot;: &quot;2025-01-21T10:00:00.000000Z&quot;,
            &quot;tags&quot;: [
                {
                    &quot;id&quot;: 2,
                    &quot;name&quot;: &quot;Laravel&quot;,
                    &quot;slug&quot;: &quot;laravel&quot;
                },
                {
                    &quot;id&quot;: 3,
                    &quot;name&quot;: &quot;Vue.js&quot;,
                    &quot;slug&quot;: &quot;vuejs&quot;
                }
            ]
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-content-cms--tenant_slug--projects" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-content-cms--tenant_slug--projects"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-content-cms--tenant_slug--projects"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-content-cms--tenant_slug--projects" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-content-cms--tenant_slug--projects">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-content-cms--tenant_slug--projects" data-method="GET"
      data-path="api/content/cms/{tenant_slug}/projects"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-content-cms--tenant_slug--projects', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-content-cms--tenant_slug--projects"
                    onclick="tryItOut('GETapi-content-cms--tenant_slug--projects');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-content-cms--tenant_slug--projects"
                    onclick="cancelTryOut('GETapi-content-cms--tenant_slug--projects');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-content-cms--tenant_slug--projects"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/content/cms/{tenant_slug}/projects</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-content-cms--tenant_slug--projects"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-content-cms--tenant_slug--projects"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>tenant_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="tenant_slug"                data-endpoint="GETapi-content-cms--tenant_slug--projects"
               value="my-blog-abc123"
               data-component="url">
    <br>
<p>The public slug of the tenant. Example: <code>my-blog-abc123</code></p>
            </div>
                    </form>

                    <h2 id="cms-content-delivery-GETapi-content-cms--tenant_slug--tags">List tags with published content</h2>

<p>
</p>

<p>Returns all tags that have at least one published content item,
with a count of published items per tag. Ordered alphabetically.</p>

<span id="example-requests-GETapi-content-cms--tenant_slug--tags">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/content/cms/my-blog-abc123/tags" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/content/cms/my-blog-abc123/tags"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-content-cms--tenant_slug--tags">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Laravel&quot;,
            &quot;slug&quot;: &quot;laravel&quot;,
            &quot;created_at&quot;: &quot;2025-01-20T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-01-20T10:00:00.000000Z&quot;,
            &quot;content_items_count&quot;: 8
        },
        {
            &quot;id&quot;: 2,
            &quot;name&quot;: &quot;PHP&quot;,
            &quot;slug&quot;: &quot;php&quot;,
            &quot;created_at&quot;: &quot;2025-01-20T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-01-20T10:00:00.000000Z&quot;,
            &quot;content_items_count&quot;: 5
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-content-cms--tenant_slug--tags" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-content-cms--tenant_slug--tags"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-content-cms--tenant_slug--tags"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-content-cms--tenant_slug--tags" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-content-cms--tenant_slug--tags">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-content-cms--tenant_slug--tags" data-method="GET"
      data-path="api/content/cms/{tenant_slug}/tags"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-content-cms--tenant_slug--tags', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-content-cms--tenant_slug--tags"
                    onclick="tryItOut('GETapi-content-cms--tenant_slug--tags');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-content-cms--tenant_slug--tags"
                    onclick="cancelTryOut('GETapi-content-cms--tenant_slug--tags');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-content-cms--tenant_slug--tags"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/content/cms/{tenant_slug}/tags</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-content-cms--tenant_slug--tags"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-content-cms--tenant_slug--tags"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>tenant_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="tenant_slug"                data-endpoint="GETapi-content-cms--tenant_slug--tags"
               value="my-blog-abc123"
               data-component="url">
    <br>
<p>The public slug of the tenant. Example: <code>my-blog-abc123</code></p>
            </div>
                    </form>

                    <h2 id="cms-content-delivery-GETapi-content-cms--tenant_slug--tags--tag_slug-">Get content by tag</h2>

<p>
</p>

<p>Returns published content items for a specific tag, with pagination.
Ordered by publish date (newest first).</p>

<span id="example-requests-GETapi-content-cms--tenant_slug--tags--tag_slug-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/content/cms/my-blog-abc123/tags/laravel" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/content/cms/my-blog-abc123/tags/laravel"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-content-cms--tenant_slug--tags--tag_slug-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;tag&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Laravel&quot;,
        &quot;slug&quot;: &quot;laravel&quot;,
        &quot;created_at&quot;: &quot;2025-01-20T10:00:00.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-01-20T10:00:00.000000Z&quot;
    },
    &quot;content_items&quot;: {
        &quot;current_page&quot;: 1,
        &quot;data&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;type&quot;: &quot;post&quot;,
                &quot;title&quot;: &quot;Getting Started with Laravel&quot;,
                &quot;slug&quot;: &quot;getting-started-with-laravel&quot;,
                &quot;excerpt&quot;: &quot;Learn the basics&quot;,
                &quot;status&quot;: &quot;published&quot;,
                &quot;published_at&quot;: &quot;2025-01-22T10:00:00.000000Z&quot;,
                &quot;tags&quot;: [
                    {
                        &quot;id&quot;: 1,
                        &quot;name&quot;: &quot;Laravel&quot;,
                        &quot;slug&quot;: &quot;laravel&quot;
                    }
                ]
            }
        ],
        &quot;per_page&quot;: 15,
        &quot;total&quot;: 8
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No query results for model [Tag].&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-content-cms--tenant_slug--tags--tag_slug-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-content-cms--tenant_slug--tags--tag_slug-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-content-cms--tenant_slug--tags--tag_slug-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-content-cms--tenant_slug--tags--tag_slug-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-content-cms--tenant_slug--tags--tag_slug-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-content-cms--tenant_slug--tags--tag_slug-" data-method="GET"
      data-path="api/content/cms/{tenant_slug}/tags/{tag_slug}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-content-cms--tenant_slug--tags--tag_slug-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-content-cms--tenant_slug--tags--tag_slug-"
                    onclick="tryItOut('GETapi-content-cms--tenant_slug--tags--tag_slug-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-content-cms--tenant_slug--tags--tag_slug-"
                    onclick="cancelTryOut('GETapi-content-cms--tenant_slug--tags--tag_slug-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-content-cms--tenant_slug--tags--tag_slug-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/content/cms/{tenant_slug}/tags/{tag_slug}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-content-cms--tenant_slug--tags--tag_slug-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-content-cms--tenant_slug--tags--tag_slug-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>tenant_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="tenant_slug"                data-endpoint="GETapi-content-cms--tenant_slug--tags--tag_slug-"
               value="my-blog-abc123"
               data-component="url">
    <br>
<p>The public slug of the tenant. Example: <code>my-blog-abc123</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>tag_slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="tag_slug"                data-endpoint="GETapi-content-cms--tenant_slug--tags--tag_slug-"
               value="laravel"
               data-component="url">
    <br>
<p>The slug of the tag. Example: <code>laravel</code></p>
            </div>
                    </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                            </div>
            </div>
</div>
</body>
</html>
