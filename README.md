# depot-ms

A Laravel 11 API-backed depot management system for receipts, dispatches, inventory, customers, and depot users.

## Overview

This project is built as an API-first backend using Laravel 11 and Sanctum token authentication. It models depots, tanks, products, tankers, receipts, dispatches, and customers, with a specific approval flow for depot managers.

## Prerequisites

- PHP 8.2+
- Composer
- MySQL 8.0+ (recommended)
- Git

## Recommended Local Environment

1. Install PHP 8.2 or later.
2. Install Composer.
3. Install MySQL 8.0+.
4. Install Git.

## Clone the Repository

```bash
git clone <repository-url> depot-ms
cd depot-ms
```

## Install PHP Dependencies

```bash
composer install
```

## Environment Setup

Copy the example environment file:

```bash
cp .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

## Configure the Database

Create a MySQL database for the application. Example:

```bash
mysql -u root -p -e "CREATE DATABASE depot_ms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

Update the `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=depot_ms
DB_USERNAME=root
DB_PASSWORD=mysql_password
```

## Run Migrations

```bash
php artisan migrate
```

## Seed the Database

The project includes `DatabaseSeeder`, which seeds users, depots, products, customers, tanks, tankers, receipts, and dispatches.

```bash
php artisan db:seed
```

If you want to run both migrate and seed in one command:

```bash
php artisan migrate --seed
```

## Run the Application

```bash
php artisan serve
```

The app will be available at `http://127.0.0.1:8000` by default.

### Postman Setup

You can test the API with Postman using the included collection file: `depot-ms.postman_collection.json`.

The collection uses an environment called **`depot-env`** with the following variables:

- **`base_url`**: `http://127.0.0.1:8000/api`
- **`token`**: (populated after authentication)

To get started:

1. Import the collection into Postman.
2. Create the environment with the above variables.
3. Run the `POST /register` endpoint to create a new user and receive an authentication token.
4. The token will be stored in the `token` environment variable and automatically used in subsequent authenticated requests.

## API Endpoints

### Authentication (Public)

- `POST /api/register` - Register a new user and receive a token
- `POST /api/login` - Login and receive a token

### Authentication (Requires Token)

- `POST /api/logout` - Revoke the current token

### Depots (RESTful Resource)

All endpoints require authentication.

- `GET /api/depots` - List all depots
- `POST /api/depots` - Create a new depot
- `GET /api/depots/{depot}` - Get a specific depot
- `PUT /api/depots/{depot}` - Update a depot
- `DELETE /api/depots/{depot}` - Delete a depot

### Tanks (RESTful Resource)

All endpoints require authentication.

- `GET /api/tanks` - List all tanks
- `POST /api/tanks` - Create a new tank
- `GET /api/tanks/{tank}` - Get a specific tank
- `PUT /api/tanks/{tank}` - Update a tank
- `DELETE /api/tanks/{tank}` - Delete a tank

### Customers (RESTful Resource)

All endpoints require authentication.

- `GET /api/customers` - List all customers
- `POST /api/customers` - Create a new customer
- `GET /api/customers/{customer}` - Get a specific customer
- `PUT /api/customers/{customer}` - Update a customer
- `DELETE /api/customers/{customer}` - Delete a customer

### Tankers (RESTful Resource)

All endpoints require authentication.

- `GET /api/tankers` - List all tankers
- `POST /api/tankers` - Create a new tanker
- `GET /api/tankers/{tanker}` - Get a specific tanker
- `PUT /api/tankers/{tanker}` - Update a tanker
- `DELETE /api/tankers/{tanker}` - Delete a tanker

### Receipts (Custom Actions)

All endpoints require authentication.

- `GET /api/receipts` - List all receipts
- `POST /api/receipts` - Create a new receipt
- `GET /api/receipts/{receipt}` - Get a specific receipt
- `POST /api/receipts/{receipt}/approve` - Approve a receipt (depot manager only)
- `POST /api/receipts/{receipt}/reverse` - Reverse a receipt

### Dispatches (Custom Actions)

All endpoints require authentication.

- `GET /api/dispatches` - List all dispatches
- `POST /api/dispatches` - Create a new dispatch
- `GET /api/dispatches/{dispatch}` - Get a specific dispatch
- `POST /api/dispatches/{dispatch}/approve` - Approve a dispatch (depot manager only)
- `POST /api/dispatches/{dispatch}/cancel` - Cancel a dispatch

### Inventory

All endpoints require authentication.

- `GET /api/depots/{depot}/inventory` - Get inventory summary for a specific depot

## Architecture

The entity relationship diagram is included in `depot-ms ERD.png` and was generated using Mermaid.

The API is implemented with Eloquent eager loading wherever list/detail payloads require related models, avoiding N+1 query issues. Eager-loaded endpoints include:

- `GET /api/depots`, `GET /api/depots/{depot}`
- `GET /api/tanks`, `GET /api/tanks/{tank}`
- `GET /api/receipts`, `GET /api/receipts/{receipt}`, `POST /api/receipts`
- `GET /api/dispatches`, `GET /api/dispatches/{dispatch}`, `POST /api/dispatches`
- `GET /api/customers`, `GET /api/customers/{customer}`
- `GET /api/tankers`, `GET /api/tankers/{tanker}`, `POST /api/tankers`, `PUT /api/tankers/{tanker}`
- `GET /api/depots/{depot}/inventory`

- API-first Laravel application using resource controllers.
- Domain models include `Depot`, `Tank`, `Product`, `Tanker`, `Receipt`, `Dispatch`, `Customer`, and `User`.
- `TankInventoryService` encapsulates inventory operations and ensures business rules like product matching, overflow protection, and sufficient quantity checks.
- Role-based approval is enforced with middleware: `EnsureDepotManager` restricts approvals to users whose role is `depot_manager`.
- Relationships:
    - Users can be attached to depots via many-to-many links.
    - Depots own tanks, receipts, and dispatches.
    - Receipts and dispatches reference tanks, products, tankers, and users for audit tracking.

## Why Sanctum Was Used

Sanctum was chosen because:

- It integrates smoothly with Laravel and requires minimal setup.
- It supports API token authentication for mobile clients or third-party apps.
- It allows simple token creation and revocation via `createToken()` and `currentAccessToken()->delete()`.
- It supports `auth:sanctum` middleware, giving a consistent authentication layer for API routes.

This application uses Sanctum in token mode, which is appropriate for a stateless API and avoids the complexity of session authentication for API consumers.

## Assumptions

- The application is backend-only; there is no built-in frontend shipped in this repo.
- Depot approval flow is limited to the `depot_manager` role.
- Inventory is tracked per tank and product; there is no cross-product conversion.
- Audit metadata fields exist on receipts/dispatches (`created_by`, `approved_by`, `cancelled_by`, etc.).
- Seeded data uses randomized users and depot assignments, so registered accounts may still be needed for predictable testing.

## Known Gaps

- No deployment or production hardening instructions are included.
- Advanced RBAC beyond `depot_manager` is not implemented.
- No frontend/UI package is included by default.
- No explicit rate limiting or API versioning strategy is documented.

## Notes

- If you need a fresh database reset during development:

```bash
php artisan migrate:fresh --seed
```
