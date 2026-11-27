<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\SupportTicket;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $query = SupportTicket::latest();

        if ($status) {
            $query->where('status', $status);
        }

        $tickets = $query->paginate(20)->withQueryString();

        return view('admin.modules.support', compact('tickets', 'status'));
    }

    public function update(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,waiting_customer,resolved,closed',
            'priority' => 'required|in:low,medium,high,urgent',
            'admin_notes' => 'nullable|string',
        ]);

        $updates = $validated;
        if ($validated['status'] === 'resolved' && !$ticket->resolved_at) {
            $updates['resolved_at'] = now();
        }

        $ticket->update($updates);

        ActivityLog::log('updated', 'support', "Updated support ticket #{$ticket->ticket_number}.");

        return back()->with('success', 'Support ticket updated successfully.');
    }
}
