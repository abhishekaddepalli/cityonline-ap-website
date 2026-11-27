<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\HomepageSection;
use Illuminate\Http\Request;

class HomepageSectionController extends Controller
{
    public function index()
    {
        $sections = HomepageSection::orderBy('sort_order', 'asc')->get();
        return view('admin.modules.sections', compact('sections'));
    }

    public function update(Request $request, $id)
    {
        $section = HomepageSection::findOrFail($id);

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'badge_text' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'sort_order' => 'required|numeric',
            'is_active' => 'nullable|boolean',
        ]);

        $section->update([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'],
            'badge_text' => $validated['badge_text'],
            'description' => $validated['description'],
            'sort_order' => $validated['sort_order'],
            'is_active' => $request->boolean('is_active'),
        ]);

        ActivityLog::log('updated', 'sections', "Updated homepage section '{$section->name}'.");

        return redirect('/admin/sections')->with('success', "Section '{$section->name}' updated successfully.");
    }
}
