# Voltaro

B2B energy marketplace where **suppliers** publish tariffs, **brokers** quote
buyer RFQs, and **buyers** sign contracts that get invoiced monthly from meter
readings.

This repository is a Laravel 11 + Inertia + Vue 3 scaffold built to be
extended by an AI coding agent (Devin). The key Devin-facing docs are:

- `AGENTS.md` — rules and conventions Devin must follow.
- `ARCHITECTURE.md` — domain, modules, relationships, auth model.
- `TASKS.md` — a prioritized backlog of small, testable tasks.

## Quick start (Docker + Sail is the only supported dev setup)

```bash
cp .env.example .env
composer install                       # run once on the host so ./vendor/bin/sail exists
./vendor/bin/sail up -d                # starts MySQL, Redis, Mailpit, and the app container
./vendor/bin/sail composer install     # install PHP deps inside the container
./vendor/bin/sail npm ci
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
./vendor/bin/sail npm run dev
```

Then open http://localhost and sign in as `admin@voltaro.test`
(password = whatever you set via `sail artisan tinker`, or register a new
account and assign the `admin` role manually).

Handy Sail aliases (optional):

```bash
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```

## Database strategy

Voltaro uses **two** database setups on purpose:

| Context                         | Driver                | Where configured |
|---------------------------------|-----------------------|------------------|
| Local dev + production-like run | MySQL 8 (Sail)        | `.env` / `.env.example` |
| Automated tests (Pest / CI)     | SQLite `:memory:`     | `phpunit.xml`    |

Tests always run against SQLite in-memory — fast, isolated, no Docker
required even in CI. Everything else (artisan, Horizon, Inertia pages,
manual QA) runs against MySQL inside Sail. Do **not** switch your local
`.env` to SQLite — MySQL-specific things (enums, `json`, migrations
order) should always be exercised against MySQL.

## Stack

- Laravel 11 (PHP 8.3+)
- Inertia 2 + Vue 3 + Tailwind via Laravel Breeze
- Spatie Permission (roles: `admin`, `supplier`, `broker`, `buyer`)
- Laravel Horizon (Redis queues)
- Pest v3 (tests) + Laravel Pint (code style)
- Laravel Sail (Docker: MySQL, Redis, Mailpit)

## Useful commands

Run these through Sail when they touch the database, queue, or cache
(anything backed by MySQL / Redis). Tests can run either way because
they use SQLite in-memory.

```bash
./vendor/bin/sail artisan migrate:fresh --seed
./vendor/bin/sail artisan horizon          # queue workers
./vendor/bin/sail artisan tinker

./vendor/bin/pest                          # tests (SQLite in-memory, host-side is fine)
./vendor/bin/pint                          # auto-fix code style
./vendor/bin/pint --test                   # check only (CI)
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
