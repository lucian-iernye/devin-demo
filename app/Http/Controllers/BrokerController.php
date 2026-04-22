<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBrokerRequest;
use App\Http\Requests\UpdateBrokerRequest;
use App\Models\Broker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Inertia\Response;

class BrokerController extends Controller implements HasMiddleware
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
        $this->authorize('viewAny', Broker::class);

        $brokers = Broker::query()
            ->when($request->string('q')->toString(), fn ($q, $term) => $q->where('name', 'like', "%{$term}%")
            )
            ->when($request->string('status')->toString(), fn ($q, $status) => $q->where('status', $status)
            )
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Brokers/Index', [
            'brokers' => $brokers,
            'filters' => $request->only(['q', 'status']),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Broker::class);

        return Inertia::render('Brokers/Create');
    }

    public function store(StoreBrokerRequest $request): RedirectResponse
    {
        $this->authorize('create', Broker::class);

        $broker = Broker::create($request->validated() + [
            'user_id' => $request->user()->id,
        ]);

        return redirect()
            ->route('brokers.edit', $broker)
            ->with('success', 'Broker created.');
    }

    public function show(Broker $broker): Response
    {
        $this->authorize('view', $broker);

        return Inertia::render('Brokers/Show', [
            'broker' => $broker,
        ]);
    }

    public function edit(Broker $broker): Response
    {
        $this->authorize('update', $broker);

        return Inertia::render('Brokers/Edit', [
            'broker' => $broker,
        ]);
    }

    public function update(UpdateBrokerRequest $request, Broker $broker): RedirectResponse
    {
        $this->authorize('update', $broker);

        $broker->update($request->validated());

        return back()->with('success', 'Broker updated.');
    }

    public function destroy(Broker $broker): RedirectResponse
    {
        $this->authorize('delete', $broker);

        $broker->delete();

        return redirect()
            ->route('brokers.index')
            ->with('success', 'Broker deleted.');
    }
}
