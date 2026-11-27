<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Enquiry;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $query = Enquiry::latest();

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('mobile_number', 'like', "%{$search}%")
                  ->orWhere('locality', 'like', "%{$search}%");
            });
        }

        $enquiries = $query->paginate(20)->withQueryString();

        return view('admin.modules.enquiries', compact('enquiries', 'status', 'search'));
    }

    public function updateStatus(Request $request, $id)
    {
        $enquiry = Enquiry::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:new,contacted,follow_up,converted,closed',
            'admin_notes' => 'nullable|string',
        ]);

        $enquiry->update($validated);

        ActivityLog::log('updated', 'enquiries', "Updated status of enquiry #{$enquiry->id} to '{$enquiry->status}'.");

        return back()->with('success', 'Enquiry status updated successfully.');
    }

    public function exportCsv()
    {
        $enquiries = Enquiry::latest()->get();
        $filename = "cityonline_enquiries_" . date('Y-m-d_His') . ".csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($enquiries) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Type', 'Customer Name', 'Mobile Number', 'Email', 'Locality', 'Interested Plan', 'Status', 'Date']);

            foreach ($enquiries as $e) {
                fputcsv($file, [
                    $e->id,
                    $e->enquiry_type,
                    $e->customer_name,
                    $e->mobile_number,
                    $e->email,
                    $e->locality,
                    $e->interested_plan,
                    $e->status,
                    $e->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
