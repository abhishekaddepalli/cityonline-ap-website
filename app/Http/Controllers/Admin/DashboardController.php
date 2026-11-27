<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\BroadbandPlan;
use App\Models\Enquiry;
use App\Models\Page;
use App\Models\SupportTicket;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEnquiries = Enquiry::count();
        $newConnectionEnquiries = Enquiry::where('enquiry_type', 'new_connection')->count();
        $activePlans = BroadbandPlan::where('is_active', true)->count();
        $publishedPages = Page::where('status', 'published')->count();
        $openTickets = SupportTicket::whereIn('status', ['open', 'in_progress'])->count();
        $resolvedTickets = SupportTicket::where('status', 'resolved')->count();

        $recentEnquiries = Enquiry::latest()->take(6)->get();
        $recentActivities = ActivityLog::with('user')->latest()->take(8)->get();

        // Monthly Enquiry Trends (last 6 months)
        $isSqlite = DB::connection()->getDriverName() === 'sqlite';
        $monthExpr = $isSqlite ? "strftime('%Y-%m', created_at)" : "DATE_FORMAT(created_at, '%Y-%m')";

        $monthlyEnquiries = Enquiry::select(
            DB::raw("{$monthExpr} as month"),
            DB::raw("count(*) as total")
        )
        ->groupBy('month')
        ->orderBy('month', 'desc')
        ->take(6)
        ->get()
        ->reverse();

        $chartLabels = [];
        $chartData = [];
        foreach ($monthlyEnquiries as $me) {
            $chartLabels[] = $me->month;
            $chartData[] = $me->total;
        }

        // Enquiry Types Breakdown
        $enquirySources = Enquiry::select('enquiry_type', DB::raw('count(*) as count'))
            ->groupBy('enquiry_type')
            ->pluck('count', 'enquiry_type')
            ->toArray();

        return view('admin.dashboard', compact(
            'totalEnquiries',
            'newConnectionEnquiries',
            'activePlans',
            'publishedPages',
            'openTickets',
            'resolvedTickets',
            'recentEnquiries',
            'recentActivities',
            'chartLabels',
            'chartData',
            'enquirySources'
        ));
    }
}
