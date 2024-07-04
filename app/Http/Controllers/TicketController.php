<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Gate;


class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        // $tickets = Ticket::all();
        // $tickets = Ticket::orderBy('updated_at', 'desc')->get();
        // return Inertia::render('Tickets/Index', ['tickets' => $tickets]);
        $user = auth()->user();

        return Inertia::render('Tickets/Index', [
            // 'tickets' => Ticket::with('user:id')->latest()->get(),
            'tickets' => Ticket::with('user:id')->where('user_id', $user->id)->latest('updated_at')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Tickets/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'title' => 'required|string|max:255',
        //     'description' => 'required|string',
        //     'status' => 'required|string|in:open,in_progress,closed',
        //     'priority' => 'required|string|in:low,medium,high',
        // ]);
        // Ticket::create($request->all());

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string|in:open,in_progress,closed',
            'priority' => 'required|string|in:low,medium,high',
        ]);

        $request->user()->tickets()->create($validated);

        return redirect()->route('tickets.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        return Inertia::render('Tickets/Edit', ['ticket' => $ticket]);
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, Ticket $ticket)
    public function update(Request $request, Ticket $ticket): RedirectResponse
    {
        Gate::authorize('update', $ticket);

        // $ticket->update($request->validate([
        //     'title' => 'required|string|max:255',
        //     'description' => 'required|string',
        //     'status' => 'required|string',
        //     'priority' => 'required|string',
        // ]));

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string',
            'priority' => 'required|string',
        ]);

        $ticket->update($validated);

        return redirect()->route('tickets.index')->with('success', 'Ticket updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(Ticket $ticket)
    public function destroy(Ticket $ticket): RedirectResponse
    {
        Gate::authorize('delete', $ticket);

        $ticket->delete();

        return redirect()->route('tickets.index');
    }
}
