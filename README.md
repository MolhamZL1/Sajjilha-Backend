# Sajjilha API

Sajjilha is now an API-only backend built with Laravel 10 and JWT authentication. The Blade UI, Vite assets, and Breeze scaffolding were removed so the project can serve pure JSON responses for mobile apps or SPAs.

## Features

- JWT-based registration, login, logout, refresh, and profile endpoints.
- Debt, payment, notification, and statement resources under `routes/api.php` protected by `auth:api` middleware.
- Password verification via email and mobile codes, plus API-driven password reset (`POST /api/auth/password/forgot` and `POST /api/auth/password/reset`).
- Aggregated totals and statements for each client along with notification tracking.

## Getting Started

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Use your preferred HTTP client (Postman, Hoppscotch, etc.) to hit the `/api` routes. Every authenticated request must include the `Authorization: Bearer {token}` header obtained from the login endpoint.

## Docker

A slim single-stage `Dockerfile` is provided for production images. Build and run it with:

```bash
docker build -t sajjilha-api .
docker run -p 8000:80 --env-file .env sajjilha-api
```

## Testing

```bash
php artisan test
```

Add your own HTTP tests to cover the API contracts that matter to your consumers.
