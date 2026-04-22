# AGENTS.md — rules for Devin

This file tells Devin (and any other AI coding agent) how to work in this
repo. **Read it before every task.**

## 1. Before you change code

1. Read `ARCHITECTURE.md` to understand the domain.
2. Read `TASKS.md` and pick the highest-priority unchecked task.
3. Look at the **Suppliers** vertical slice — it is the reference pattern
   for everything else:
   - `app/Models/Supplier.php`
   - `app/Http/Controllers/SupplierController.php`
   - `app/Http/Requests/{Store,Update}SupplierRequest.php`
   - `app/Policies/SupplierPolicy.php`
   - `database/factories/SupplierFactory.php`
   - `resources/js/Pages/Suppliers/{Index,Create,Edit,Show,Partials/SupplierForm}.vue`
   - `tests/Feature/SupplierTest.php`

## 2. Definition of done

A task is done only when **all** of these are true:

- [ ] `./vendor/bin/pest` is green (add tests for the new behavior).
- [ ] `./vendor/bin/pint --test` is clean (run `./vendor/bin/pint` to fix).
- [ ] `npm run build` succeeds (no broken Inertia page).
- [ ] New routes, if any, are behind `auth` middleware and covered by a
      policy.
- [ ] `TASKS.md` checkbox is ticked in the same commit.
- [ ] Commit message follows `<type>(<scope>): <summary>` (e.g.
      `feat(rfq): list & create RFQs for buyers`).

## 3. Code conventions

### PHP

- Laravel 11 idioms only. Use `HasMiddleware` contract on controllers,
  not the legacy `__construct` middleware calls.
- Controllers stay thin. Extract to `app/Actions/...` or `app/Services/...`
  once a method exceeds ~30 lines of business logic.
- Always use `FormRequest`s for validation. `authorize()` should call
  `can('<permission>')`; ownership checks live in the Policy.
- Every resource has a Policy registered by convention (`App\Policies\<Model>Policy`).
  Use `$this->authorize(...)` in controller methods.
- Use typed properties, return types, and `readonly` where appropriate.
- No facades in tests when a helper function exists (e.g. `actingAs`,
  `get`, `post` from Pest).

### Vue / Inertia

- Use `<script setup>`, composition API only.
- Pages go under `resources/js/Pages/<Resource>/`. Shared form pieces
  go under `Partials/`.
- Use the existing `AuthenticatedLayout`, Breeze components
  (`PrimaryButton`, `TextInput`, `InputLabel`, `InputError`), and
  Tailwind utility classes. Do **not** pull in a new UI library.
- Forms use `useForm` from `@inertiajs/vue3`.

### Database

- Migrations: plural, snake_case table names.
  `create_<plural>_table`, `Schema::create('<plural>', ...)`.
- Prefer `foreignId(...)->constrained()->cascadeOnDelete()` (or
  `nullOnDelete()` where it makes sense).
- Money: `decimal(12, 2)` for totals, `decimal(8, 4)` for per-kWh prices,
  `decimal(5, 4)` for commission rates.
- Energy volumes in **kWh**, stored as `unsignedBigInteger`.
- Every new migration ships with a factory that produces valid data.

### Tests

- Pest v3. One file per resource: `tests/Feature/<Resource>Test.php`.
- Always seed `RolesAndPermissionsSeeder` in `beforeEach`.
- For each controller action, cover at minimum:
  - unauthenticated → redirect to login,
  - wrong role → 403,
  - right role → 200/201/302 + DB/Inertia assertion.

## 4. What Devin must NOT do

- Don't rename or delete the Suppliers slice — it's the reference.
- Don't introduce a new CSS framework, state-management library, or
  testing framework.
- Don't commit `.env`, `storage/*`, `node_modules`, `vendor`, or build
  artifacts. They're already gitignored; keep it that way.
- Don't disable or skip failing tests. Fix them.
- Don't edit files under `vendor/` or `node_modules/`.

## 5. Running things

```bash
# Tests
./vendor/bin/pest
./vendor/bin/pest --filter <Name>

# Style
./vendor/bin/pint
./vendor/bin/pint --test

# Frontend
npm run dev        # HMR during development
npm run build      # production build (required for server-rendered tests)

# Database
php artisan migrate:fresh --seed
```

## 6. When stuck

If a task is ambiguous, leave a short comment in `TASKS.md` describing
the ambiguity and move on to the next task. Never fabricate requirements.
