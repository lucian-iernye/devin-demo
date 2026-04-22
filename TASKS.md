# TASKS.md — Voltaro backlog

Devin: work top-down. One commit per checked task, following the rules in
`AGENTS.md`. The Suppliers slice is the canonical reference.

Legend: `P0` = blocker, `P1` = next, `P2` = nice-to-have.

## Phase 1 — Fill out the CRUD skeletons

- [ ] **P0 brokers(crud):** Build the Broker slice mirroring Supplier
      (controller, requests, policy, Inertia pages, tests).
- [ ] **P0 buyers(crud):** Same for Buyer.
- [ ] **P0 tariffs(crud):** Nested under suppliers. Only the owning
      supplier (or admin) can create/edit their tariffs. Index page
      filters by `active`, `type`, `green_percentage >= X`.
- [ ] **P0 factories:** Fill in stub factories for Broker, Buyer, Rfq,
      Quote, Contract, Invoice, MeterReading so tests can use them.
- [ ] **P1 dashboard(role-aware):** `/dashboard` should show a different
      widget set per role (admin: platform KPIs; supplier: active tariffs
      + upcoming renewals; broker: pipeline; buyer: open RFQs + active
      contracts).

## Phase 2 — RFQ → Quote → Contract flow

- [ ] **P0 rfqs(crud):** Buyers create RFQs. Index shows buyer's own RFQs.
      A separate index (`/rfqs/open`) shows all `status=open` RFQs to
      brokers.
- [ ] **P0 quotes(submit):** A broker viewing an open RFQ can submit a
      quote (pick one of their accessible tariffs, set commission rate,
      add notes). Status transitions: draft → submitted → accepted/rejected.
- [ ] **P0 quotes(buyer-review):** Buyer viewing their RFQ sees all
      submitted quotes and can accept exactly one.
- [ ] **P0 contracts(from-quote):** Accepting a quote creates a Contract
      in `pending_signature`, closes the RFQ (`status=awarded`), and
      rejects the other quotes.
- [ ] **P1 contracts(sign):** Buyer can "sign" a contract (sets
      `signed_at`, `status=active`). Must be inside `[starts_on, ends_on]`.
- [ ] **P1 contracts(terminate):** Admin or buyer can terminate an active
      contract (status=terminated). Validation: cannot terminate one
      that has unpaid invoices.

## Phase 3 — Metering & billing

- [ ] **P1 meter-readings(ingest):** Form for brokers/admins to record a
      MeterReading against a contract. Derive `kwh_period` from previous
      cumulative reading.
- [ ] **P1 invoices(generate):** Artisan command
      `invoices:generate {--month=YYYY-MM}` that creates one Invoice per
      active contract using the MeterReadings in that window.
- [ ] **P1 invoices(index + show):** Role-scoped (buyer sees theirs,
      broker sees theirs, admin sees all).
- [ ] **P2 invoices(mark-paid):** Admin action. Sets `paid_at`,
      `status=paid`.

## Phase 4 — Ops polish

- [ ] **P1 horizon(gate):** Gate `/horizon` to `admin` role in
      `HorizonServiceProvider::gate()`.
- [ ] **P2 notifications:** Mail notification to broker when an RFQ is
      published in their region; to buyer when a quote is submitted;
      to buyer when an invoice is issued. Use Mailpit in dev.
- [ ] **P2 search:** Full-text search on tariffs (name + region).
- [ ] **P2 audit:** Log status transitions on RFQ/Quote/Contract/Invoice
      with `spatie/laravel-activitylog` (install when you reach this task).

## House-keeping (always open)

- [ ] Keep `ARCHITECTURE.md` in sync when you add a new table or role.
- [ ] Keep this file tidy: tick what you ship, add discovered TODOs at
      the bottom with `P?` so we can prioritise later.
