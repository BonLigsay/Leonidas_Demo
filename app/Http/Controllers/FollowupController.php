<?php

namespace App\Http\Controllers;

use App\Models\Followup;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class FollowupController extends Controller
{
    public function index(Ticket $ticket)
    {
        $followups = $ticket->followups()->get();
        return Inertia::render('Followups/Index', [
            'followups' => $followups,
            'ticket' => $ticket,
        ]);
    }

    public function show(Followup $followup)
    {
        return Inertia::render('Followups/Show', [
            'followup' => $followup,
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Followup::class);

        if ($request->type === 'solution' ) {
            // Gate::authorize('createSolution', Followup::class);
            if (!Gate::allows('createSolution', Followup::class)) {
                // return redirect()->back()->with('error', 'Unauthorized to create solution follow-ups.');
                return back()->withErrors(['Unauthorized to create solution follow-ups.']);
            }
        }

        if ($request->type === 'comment' ) {
            // Gate::authorize('createComment', Followup::class);
            if (!Gate::allows('createComment', Followup::class)) {
                return redirect()->back()->with('error', 'Unauthorized to create comment follow-ups.');
            }
        }

        $request->validate([
            'content' => 'required|string',
            'type' => 'required|string|in:comment,solution',
            'ticket_id' => 'required|exists:tickets,id',
        ]);

        $followup = Followup::create([
            'content' => $request->content,
            'type' => $request->type,
            'ticket_id' => $request->ticket_id,
            'user_id' => Auth::id(), // or another way to get the user_id
        ]);

        return redirect()->back()->with('success', 'Followup created successfully.');

    }

    public function update(Request $request, Followup $followup)
    {
        Gate::authorize('update', Followup::class);

        $request->validate([
            'content' => 'required|string',
        ]);

        $followup->update([
            'content' => $request->content,
        ]);

        return redirect()->back()->with('message', 'Followup updated successfully.');
    }

    public function destroy(Followup $followup)
    {
        Gate::authorize('delete' ,Followup::class);

        $followup->delete();

        return redirect()->back()->with('message', 'Followup deleted successfully.');
    }
}
