<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Locality;
use Illuminate\Http\Request;

class LocalityController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $query = Locality::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('pincode', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('coverage_status', $status);
        }

        $localities = $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc')->paginate(25)->withQueryString();

        $totalCount = Locality::count();
        $availableCount = Locality::where('coverage_status', 'available')->count();
        $expandingCount = Locality::where('coverage_status', 'expanding')->count();
        $plannedCount = Locality::where('coverage_status', 'planned')->count();

        return view('admin.modules.localities', compact(
            'localities', 'search', 'status', 'totalCount', 'availableCount', 'expandingCount', 'plannedCount'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'city' => 'required|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'coverage_status' => 'required|in:available,expanding,planned',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? (Locality::max('sort_order') + 1);

        $loc = Locality::create($validated);

        ActivityLog::log('created', 'localities', "Added new coverage locality '{$loc->name}'.");

        return redirect('/admin/localities')->with('success', "Locality '{$loc->name}' added successfully.");
    }

    public function update(Request $request, $id)
    {
        $locality = Locality::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'city' => 'required|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'coverage_status' => 'required|in:available,expanding,planned',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $locality->update($validated);

        ActivityLog::log('updated', 'localities', "Updated coverage locality '{$locality->name}'.");

        return redirect('/admin/localities')->with('success', "Locality '{$locality->name}' updated successfully.");
    }

    public function toggle($id)
    {
        $locality = Locality::findOrFail($id);
        $locality->is_active = !$locality->is_active;
        $locality->save();

        ActivityLog::log('updated', 'localities', "Toggled coverage status for '{$locality->name}'.");

        return back()->with('success', "Status updated for '{$locality->name}'.");
    }

    public function destroy($id)
    {
        $locality = Locality::findOrFail($id);
        $name = $locality->name;
        $locality->delete();

        ActivityLog::log('deleted', 'localities', "Deleted locality '{$name}'.");

        return redirect('/admin/localities')->with('success', "Locality '{$name}' deleted successfully.");
    }

    public function loadPeddapuram()
    {
        $peddapuramAreas = [
            'Peddapuram Town Center', 'Main Road, Peddapuram', 'Kacheri Center / Court Area',
            'RTC Bus Complex Area', 'Fort Area / Anandashram Road', 'Jagapathi Babu Colony',
            'Maridamma Temple Area', 'ADB Road / Industrial Estate', 'Samalkot Road, Peddapuram',
            'Rayabhupalapatnam', 'Pulimeru', 'G. Ragampeta', 'Vadlamuru', 'Anuru', 'Kandrakota',
            'Kattamuru', 'J. Thimmapuram', 'Chandramampeta', 'Sirivada', 'Ulimeswaram',
            'Tirupati (Peddapuram Mandal)', 'Gorinta', 'Somavaram', 'Samalkot Town',
            'Samalkot Railway Station Area', 'Station Road, Samalkot', 'Jaggamma Gari Peta',
            'Madhavapatnam', 'Hussainpuram', 'Jaggampeta Road', 'Rangampeta Road', 'Pithapuram Road Area',
        ];

        $order = 1;
        foreach ($peddapuramAreas as $area) {
            Locality::updateOrCreate(
                ['name' => $area],
                [
                    'city' => 'Peddapuram & Surroundings',
                    'pincode' => '533437',
                    'coverage_status' => 'available',
                    'sort_order' => $order++,
                    'is_active' => true,
                ]
            );
        }

        ActivityLog::log('seeded', 'localities', 'Loaded 32 Peddapuram and surrounding localities.');

        return redirect('/admin/localities')->with('success', 'Successfully loaded 32 Peddapuram and surrounding coverage localities!');
    }
}
