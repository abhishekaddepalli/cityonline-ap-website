<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\InternetTool;
use Illuminate\Http\Request;

class InternetToolController extends Controller
{
    public function index()
    {
        $tools = InternetTool::orderBy('sort_order', 'asc')->get();
        return view('admin.modules.tools', compact('tools'));
    }

    public function update(Request $request, $id)
    {
        $tool = InternetTool::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'endpoint_url' => 'nullable|string|max:255',
            'external_link' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'required|numeric',
        ]);

        $tool->update(array_merge($validated, ['is_active' => $request->boolean('is_active')]));

        ActivityLog::log('updated', 'tools', "Updated internet diagnostic tool '{$tool->name}'.");

        return redirect('/admin/tools')->with('success', "Tool '{$tool->name}' configuration saved.");
    }
}
