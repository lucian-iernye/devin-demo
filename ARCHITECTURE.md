# Voltaro architecture

## Domain

Voltaro is a three-sided B2B energy marketplace.

```
          publishes                    quotes on                signs
Supplier ───────────▶  Tariff ◀──── Broker ───────▶ RFQ ◀───── Buyer
   │                                    │                        │
   │                                    ▼                        │
   │                                 Quote ─── accepted ───▶ Contract
   │                                                            │
   └────────────────────── delivers energy ◀───────────────┐    │
                                                           ▼    ▼
                                                       MeterReading
                                                              │
                                                              ▼
                                                          Invoice
```

### Actors (= User roles, via Spatie Permission)

| Role       | Who                                          | Primary scope |
|------------|----------------------------------------------|---------------|
| `admin`    | Platform operator                            | Everything    |
| `supplier` | Energy producer (solar farm, utility, etc.)  | Own `Supplier` + its `Tariff`s, related `Contract`s & `Invoice`s (read). |
| `broker`   | Intermediary that sells tariffs to buyers    | Own `Broker`, quotes they author, contracts/invoices where `broker_id` matches. |
| `buyer`    | Company buying electricity                    | Own `Buyer`, its `Rfq`s, received `Quote`s, signed `Contract`s, received `Invoice`s. |

A `User` record (Breeze auth) links 1:1 to at most one of `Supplier`,
`Broker`, or `Buyer` via `user_id`. Admins generally don't have a
profile record.

### Core tables

| Table | Purpose | Key columns |
|-------|---------|-------------|
| `users` | Auth identity | — |
| `suppliers` | Supplier company profile | `user_id`, `name`, `country`, `generation_mix` (json), `status` |
| `brokers` | Broker firm profile | `user_id`, `name`, `default_commission_rate`, `status` |
| `buyers` | Buyer company profile | `user_id`, `name`, `vat_number`, `annual_consumption_kwh` |
| `tariffs` | Supplier offering | `supplier_id`, `type` (fixed/variable/indexed), `price_per_kwh`, `green_percentage`, `contract_length_months`, `active` |
| `rfqs` | Buyer request for quote | `buyer_id`, `expected_annual_kwh`, `desired_start_date`, `contract_length_months`, `status` |
| `quotes` | Broker quote on an RFQ | `rfq_id`, `broker_id`, `tariff_id?`, `offered_price_per_kwh`, `commission_rate`, `status` |
| `contracts` | Accepted quote → signed deal | `quote_id`, `buyer_id`, `broker_id`, `supplier_id`, `tariff_id?`, `price_per_kwh`, `commission_rate`, `starts_on`, `ends_on`, `status` |
| `meter_readings` | Consumption over time | `contract_id`, `reading_at`, `kwh_cumulative`, `kwh_period`, `source` |
| `invoices` | Monthly billing | `contract_id`, `buyer_id`, `broker_id`, `period_start/end`, `consumed_kwh`, `amount_energy`, `amount_commission`, `amount_total`, `status` |

`spatie/laravel-permission` provides the `roles`, `permissions`, and
pivot tables. Permission slugs are `<resource>.<view|manage>` — see
`database/seeders/RolesAndPermissionsSeeder.php` for the catalog.

## Lifecycle

1. **Supplier** signs up, completes KYC (→ `suppliers.status = active`),
   publishes **Tariff**s.
2. **Buyer** signs up, publishes an **RFQ** (expected volume, start date,
   contract length, optional green %).
3. **Broker**s browse open RFQs and submit **Quote**s, each referencing
   a Tariff and adding a `commission_rate`.
4. Buyer accepts one Quote → a **Contract** is created with snapshotted
   `price_per_kwh` and `commission_rate`.
5. **MeterReading**s are ingested (manual upload, broker API, or future
   smart-meter integration).
6. A monthly job produces **Invoice**s:
   `amount_energy = consumed_kwh × price_per_kwh`,
   `amount_commission = amount_energy × commission_rate`,
   `amount_total = amount_energy + amount_commission`.

## Layers & conventions

- **Controllers** (`app/Http/Controllers`) are thin. They authorize, call
  validated FormRequests, and render Inertia pages or redirect.
- **FormRequests** (`app/Http/Requests`) own validation + high-level
  authorization (`can('<permission>')`). Fine-grained ownership checks
  live in policies.
- **Policies** (`app/Policies`) are the source of truth for "can this
  user touch this record?". Controllers call `$this->authorize(...)`.
- **Models** (`app/Models`) hold `$fillable`, `$casts`, and relationships.
  No query logic beyond simple scopes.
- **Services / Actions** go in `app/Services` or `app/Actions` once the
  logic outgrows a controller method. Do not put domain logic in models.
- **Jobs** (`app/Jobs`) for async work (invoice generation, API pulls).
  Dispatched onto Horizon-managed Redis queues.
- **Inertia pages** live under `resources/js/Pages/<Resource>/` using
  `AuthenticatedLayout`. Shared form partials go under
  `resources/js/Pages/<Resource>/Partials/`.

## Routing

All authenticated app routes are inside the `auth` middleware group in
`routes/web.php`. Use `Route::resource('<plural>', XxxController::class)`
per entity. Only the Suppliers resource is wired today — Devin adds the
rest (see `TASKS.md`).

## Authentication & authorization

- Authentication: Laravel Breeze (Inertia + Vue).
- Authorization: Spatie roles + per-resource policies. Admin users see
  everything; role-based users see only their own records.

## Queues

- Driver: Redis (via Sail). Horizon is installed; dashboard at
  `/horizon` once protected (TODO: gate by `admin` role).

## Testing

- Pest v3 with `RefreshDatabase`. SQLite `:memory:` per `phpunit.xml`.
- Feature test lives next to the slice (`tests/Feature/<Resource>Test.php`).
- Every new controller action gets at least one happy-path and one
  forbidden-path test.
