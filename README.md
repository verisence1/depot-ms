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

## API Endpoints

You can test the API with Postman using the included collection file: `depot-ms.postman_collection.json`.

Authentication:

- `POST /api/register` - register a new user and receive a token
- `POST /api/login` - login and receive a token
- `POST /api/logout` - revoke the current token

Protected resources (require `Authorization: Bearer <token>`):

- `api/depots`
- `api/tanks`
- `api/receipts`
- `api/dispatches`
- `api/customers`
- `GET /api/depots/{depot}/inventory`

Approval actions (require `Authorization: Bearer <token>`):

- `POST /api/receipts/{receipt}/approve` - depot manager only
- `POST /api/dispatches/{dispatch}/approve` - depot manager only
- `POST /api/receipts/{receipt}/reverse`
- `POST /api/dispatches/{dispatch}/cancel`

## Architecture

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
