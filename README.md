# Voltaro

B2B energy marketplace where **suppliers** publish tariffs, **brokers** quote
buyer RFQs, and **buyers** sign contracts that get invoiced monthly from meter
readings.

This repository is a Laravel 11 + Inertia + Vue 3 scaffold built to be
extended by an AI coding agent (Devin). The key Devin-facing docs are:

- `AGENTS.md` — rules and conventions Devin must follow.
- `ARCHITECTURE.md` — domain, modules, relationships, auth model.
- `TASKS.md` — a prioritized backlog of small, testable tasks.

## Quick start (Docker / Sail)

```bash
cp .env.example .env
composer install
npm ci
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
npm run dev
```

Then open http://localhost and sign in as `admin@voltaro.test`
(password = whatever you set after `php artisan tinker`, or register a new
account and assign the `admin` role manually).

## Quick start (no Docker)

```bash
cp .env.example .env
# flip DB_CONNECTION=sqlite and comment out DB_HOST/PORT/… in .env
touch database/database.sqlite
composer install
npm ci
php artisan key:generate
php artisan migrate --seed
npm run dev
php artisan serve
```

## Stack

- Laravel 11 (PHP 8.3+)
- Inertia 2 + Vue 3 + Tailwind via Laravel Breeze
- Spatie Permission (roles: `admin`, `supplier`, `broker`, `buyer`)
- Laravel Horizon (Redis queues)
- Pest v3 (tests) + Laravel Pint (code style)
- Laravel Sail (Docker: MySQL, Redis, Mailpit)

## Useful commands

```bash
./vendor/bin/pest              # run tests
./vendor/bin/pint              # auto-fix code style
./vendor/bin/pint --test       # check only (CI)
php artisan migrate:fresh --seed
php artisan horizon            # run queue workers in dev
```

## Reference vertical slice

The Suppliers resource is fully implemented end-to-end and is the **pattern
Devin should copy** for every other entity:

- `app/Models/Supplier.php`
- `app/Http/Controllers/SupplierController.php`
- `app/Http/Requests/{Store,Update}SupplierRequest.php`
- `app/Policies/SupplierPolicy.php`
- `database/factories/SupplierFactory.php`
- `resources/js/Pages/Suppliers/{Index,Create,Edit,Show}.vue`
- `tests/Feature/SupplierTest.php`

See `ARCHITECTURE.md` for the full domain model and `TASKS.md` for the
backlog.
