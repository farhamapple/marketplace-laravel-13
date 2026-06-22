# RESTful API — Customer Management — Build Plan

## 1. Requirement Analysis

- [ ] Identify all external consumers (web app, mobile, third-party) and their access patterns
- [x] Define customer data model attributes (name, email, phone, address, DOB, notes, status, etc.)
- [x] Define business rules (unique email, soft-delete, status workflow, required fields)
- [x] Determine CRUD + additional operations (bulk import, search, export, merge duplicates)
- [x] Define rate limits per consumer tier
- [ ] Define SLA (uptime, response time P95 < 200ms)

## 2. System Design

- [x] Choose API architectural style (RESTful, JSON:API or custom conventions)
- [x] Design endpoint naming and structure:
  - `GET /api/v1/customers` — list with pagination, search, filter, sort
  - `POST /api/v1/customers` — create
  - `GET /api/v1/customers/{id}` — get single
  - `PUT /api/v1/customers/{id}` — full update
  - `PATCH /api/v1/customers/{id}` — partial update
  - `DELETE /api/v1/customers/{id}` — soft-delete
  - `POST /api/v1/customers/bulk` — bulk create/update
  - `GET /api/v1/customers/export` — export filtered set
- [x] Design request/response structures (JSON schemas for each endpoint)
- [x] Define standardized error response format (`{ error: { code, message, details } }`)
- [x] Design pagination (cursor-based or offset-based, include `meta` with total/next/prev)
- [x] Design filtering/sorting query parameters (`?search=`, `?status=active`, `?sort=-created_at`)
- [x] Choose API versioning strategy (URL prefix `/v1/` vs header)
- [x] Decide on response envelope or direct data

## 3. Database Design

- [ ] Design `customers` table schema with all required fields + timestamps
- [x] Add soft-deletes column (`deleted_at`)
- [x] Add indexes (email unique, status, created_at, search columns)
- [ ] Design `customer_notes` or `customer_addresses` related tables (if needed)
- [ ] Design audit log table (`customer_audits`) for tracking changes
- [x] Write migration files
- [x] Write seeders for test data

## 4. API Development

- [x] Set up project structure (Laravel: controllers, requests, resources, services, actions)
- [x] Implement `CustomerController` with all endpoints
- [x] Implement Form Request classes for validation per endpoint:
  - `StoreCustomerRequest` (name required, email unique, phone optional, etc.)
  - `UpdateCustomerRequest`
  - `BulkCustomerRequest`
- [x] Implement `CustomerResource` and `CustomerCollection` for consistent JSON responses
- [ ] Implement service layer / action classes for business logic:
  - `CreateCustomerAction`
  - `UpdateCustomerAction`
  - `SearchCustomerAction`
  - `BulkImportAction`
- [x] Implement pagination with query params (`?page=1&per_page=25`)
- [x] Implement search/filter with query params (`?search=jane&status=active&sort=-created_at`)
- [ ] Implement sparse fieldsets (`?fields=customers:id,name,email`)
- [ ] Implement include for related resources (`?include=notes,addresses`)
- [x] Implement proper HTTP status codes (200, 201, 204, 400, 404, 422, 429, 500)
- [x] Implement consistent error handling (Handler class with JSON exceptions)
- [x] Implement API rate limiting (throttle middleware per IP/user/token)
- [x] Implement request logging middleware (method, URL, status, duration, IP)

## 5. Security Implementation

- [x] Implement authentication (Laravel Sanctum or Passport for token/JWT)
- [ ] Implement authorization (Policy/Gates per action):
  - `viewAny`, `view`, `create`, `update`, `delete`, `export`
- [x] Issue API tokens via dedicated endpoint (`POST /api/v1/auth/token`)
- [ ] Add token abilities/scopes (`customers:read`, `customers:write`, `customers:export`)
- [x] Validate token on every request (middleware)
- [x] Implement CORS configuration for allowed origins
- [ ] Add request signing or HMAC for critical mutations (optional, for third-party)
- [x] Sanitize all inputs (strip XSS, reject malformed JSON)
- [x] Add SQL injection protection (use Eloquent/parameterized queries)
- [x] Add CSRF exemption for API routes (stateless)

## 6. Testing

- [x] Write feature tests for every endpoint:
  - `GET /api/v1/customers` — list, pagination, filter, sort, empty results
  - `POST /api/v1/customers` — create success, validation errors, duplicate email
  - `GET /api/v1/customers/{id}` — found, not found
  - `PUT /api/v1/customers/{id}` — update, validation
  - `PATCH /api/v1/customers/{id}` — partial update
  - `DELETE /api/v1/customers/{id}` — soft-delete, not found
  - `POST /api/v1/customers/bulk` — success, partial failures
  - `GET /api/v1/customers/export` — CSV/XLSX download
- [x] Write authentication tests: unauthenticated requests, invalid token, expired token
- [ ] Write authorization tests: user without scope, wrong scope
- [ ] Write rate limiting tests: exceed limit returns 429
- [ ] Write validation tests: missing fields, wrong types, boundary values
- [ ] Write edge case tests: long strings, Unicode, SQL injection attempts
- [ ] Write performance tests: 100 concurrent requests, response time assertions
- [ ] Set up CI pipeline to run tests on push/PR

## 7. Documentation

- [x] Write API reference (OpenAPI/Swagger spec or dedicated doc site):
  - Endpoint descriptions, methods, paths
  - Request/response examples for each endpoint
  - Error response catalog (all possible errors per endpoint)
  - Authentication instructions
- [ ] Write getting-started guide (how to obtain token, first request)
- [ ] Write changelog / migration guide for breaking changes
- [ ] Auto-generate docs from code annotations (e.g., Scribe for Laravel)
- [ ] Include Postman collection or Insomnia export

## 8. Deployment

- [x] Add environment configs (`API_RATE_LIMIT`, `API_VERSION`, `JWT_TTL`)
- [ ] Set up API-specific logging channel (separate file from app logs)
- [ ] Configure monitoring (response time percentile alerts, error rate alerts)
- [x] Configure API-specific health check endpoint (`GET /api/v1/health`)
- [ ] Set up database connection pool for read replicas (if needed)
- [ ] Configure cache layer for frequent queries (Redis: customer list with pagination)
- [x] Add deployment migration strategy (zero-downtime if adding columns)
- [ ] Set up domain/subdomain for API (e.g., `api.customers.example.com`)
- [ ] Configure web server (Nginx rate limiting, body size limits, timeouts)
- [ ] Add graceful degradation / fallback responses for downstream failures
- [ ] Plan API sunset / deprecation policy with `Deprecation` and `Sunset` headers
