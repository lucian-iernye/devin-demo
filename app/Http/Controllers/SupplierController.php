<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Reference vertical slice: Suppliers CRUD via Inertia + Vue.
 *
 * Devin: model this pattern for Brokers, Buyers, Tariffs, RFQs, Quotes,
 * Contracts, Invoices. Keep controllers thin, validate via FormRequests,
 * authorize via policies + Spatie permissions, render Inertia pages
 * under resources/js/Pages/<Resource>.
 */
class SupplierController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('verified', except: ['index', 'show']),
        ];
    }

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Supplier::class);

        $suppliers = Supplier::query()
            ->when($request->string('q')->toString(), fn ($q, $term) => $q->where('name', 'like', "%{$term}%")
            )
            ->when($request->string('status')->toString(), fn ($q, $status) => $q->where('status', $status)
            )
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Suppliers/Index', [
            'suppliers' => $suppliers,
            'filters' => $request->only(['q', 'status']),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Supplier::class);

        return Inertia::render('Suppliers/Create');
    }

    public function store(StoreSupplierRequest $request): RedirectResponse
    {
        $this->authorize('create', Supplier::class);

        $supplier = Supplier::create($request->validated() + [
            'user_id' => $request->user()->id,
        ]);

        return redirect()
            ->route('suppliers.edit', $supplier)
            ->with('success', 'Supplier created.');
    }

    public function show(Supplier $supplier): Response
    {
        $this->authorize('view', $supplier);

        return Inertia::render('Suppliers/Show', [
            'supplier' => $supplier->load('tariffs'),
        ]);
    }

    public function edit(Supplier $supplier): Response
    {
        $this->authorize('update', $supplier);

        return Inertia::render('Suppliers/Edit', [
            'supplier' => $supplier,
        ]);
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier): RedirectResponse
    {
        $this->authorize('update', $supplier);

        $supplier->update($request->validated());

        return back()->with('success', 'Supplier updated.');
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        $this->authorize('delete', $supplier);

        $supplier->delete();

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier deleted.');
    }
}
