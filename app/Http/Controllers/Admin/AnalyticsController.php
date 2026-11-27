<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Enquiry;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $ga4 = Setting::get('ga4_measurement_id', '');
        $gtm = Setting::get('gtm_container_id', '');
        $metaPixel = Setting::get('meta_pixel_id', '');

        // Plan clicks & lead conversion stats from actual DB
        $plansCount = Enquiry::select('interested_plan', DB::raw('count(*) as total'))
            ->whereNotNull('interested_plan')
            ->groupBy('interested_plan')
            ->get();

        $localityCount = Enquiry::select('locality', DB::raw('count(*) as total'))
            ->whereNotNull('locality')
            ->groupBy('locality')
            ->orderBy('total', 'desc')
            ->take(8)
            ->get();

        return view('admin.modules.analytics', compact('ga4', 'gtm', 'metaPixel', 'plansCount', 'localityCount'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'ga4_measurement_id' => 'nullable|string',
            'gtm_container_id' => 'nullable|string',
            'meta_pixel_id' => 'nullable|string',
        ]);

        foreach ($validated as $k => $v) {
            Setting::set($k, $v, 'analytics', 'string', true);
        }

        ActivityLog::log('updated', 'analytics', 'Updated analytics and tracking tag IDs.');

        return redirect('/admin/analytics')->with('success', 'Analytics IDs saved successfully.');
    }
}
