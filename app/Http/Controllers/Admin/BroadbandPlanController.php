<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\BroadbandPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BroadbandPlanController extends Controller
{
    public function index()
    {
        $plans = BroadbandPlan::orderBy('sort_order', 'asc')->get();
        return view('admin.modules.plans', compact('plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'speed_mbps' => 'required|numeric|min:1',
            'upload_speed_mbps' => 'nullable|numeric|min:1',
            'price_monthly' => 'required|numeric|min:0',
            'base_price' => 'nullable|string|max:100',
            'note' => 'nullable|string|max:255',
            'cta_url' => 'nullable|url',
            'cta_label' => 'nullable|string|max:50',
            'is_popular' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'features' => 'nullable|string', // newline separated
        ]);

        $features = array_filter(array_map('trim', explode("\n", $request->input('features', ''))));

        $plan = BroadbandPlan::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'speed_mbps' => $validated['speed_mbps'],
            'upload_speed_mbps' => $validated['upload_speed_mbps'] ?? $validated['speed_mbps'],
            'price_monthly' => $validated['price_monthly'],
            'base_price' => $validated['base_price'] ?? '₹' . $validated['price_monthly'],
            'note' => $validated['note'] ?? '',
            'features' => $features ?: ['Unlimited data, no FUP', 'Same upload & download speed', 'Faults attended in 24 hours'],
            'cta_label' => $validated['cta_label'] ?: 'Get This Plan',
            'cta_url' => $validated['cta_url'] ?: 'https://customer.cityonlineap.com/book-connection?src=site',
            'is_popular' => $request->boolean('is_popular'),
            'badge_text' => $request->boolean('is_popular') ? '★ Most Popular' : null,
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => (BroadbandPlan::max('sort_order') ?? 0) + 1,
        ]);

        ActivityLog::log('created', 'plans', "Created broadband plan '{$plan->name}' ({$plan->speed_mbps} Mbps).");

        return redirect('/admin/plans')->with('success', "Plan '{$plan->name}' created successfully.");
    }

    public function update(Request $request, $id)
    {
        $plan = BroadbandPlan::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'speed_mbps' => 'required|numeric|min:1',
            'upload_speed_mbps' => 'nullable|numeric|min:1',
            'price_monthly' => 'required|numeric|min:0',
            'base_price' => 'nullable|string|max:100',
            'note' => 'nullable|string|max:255',
            'cta_url' => 'nullable|url',
            'cta_label' => 'nullable|string|max:50',
            'is_popular' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'features' => 'nullable|string',
        ]);

        $features = array_filter(array_map('trim', explode("\n", $request->input('features', ''))));

        $plan->update([
            'name' => $validated['name'],
            'speed_mbps' => $validated['speed_mbps'],
            'upload_speed_mbps' => $validated['upload_speed_mbps'] ?? $validated['speed_mbps'],
            'price_monthly' => $validated['price_monthly'],
            'base_price' => $validated['base_price'] ?? $plan->base_price,
            'note' => $validated['note'] ?? $plan->note,
            'features' => $features ?: $plan->features,
            'cta_label' => $validated['cta_label'] ?: $plan->cta_label,
            'cta_url' => $validated['cta_url'] ?: $plan->cta_url,
            'is_popular' => $request->boolean('is_popular'),
            'badge_text' => $request->boolean('is_popular') ? '★ Most Popular' : null,
            'is_active' => $request->boolean('is_active'),
        ]);

        ActivityLog::log('updated', 'plans', "Updated broadband plan '{$plan->name}'.");

        return redirect('/admin/plans')->with('success', "Plan '{$plan->name}' updated successfully.");
    }

    public function destroy($id)
    {
        $plan = BroadbandPlan::findOrFail($id);
        $name = $plan->name;
        $plan->delete();

        ActivityLog::log('deleted', 'plans', "Deleted broadband plan '{$name}'.");

        return redirect('/admin/plans')->with('success', "Plan '{$name}' deleted.");
    }
}
