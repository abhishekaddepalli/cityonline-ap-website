<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\CustomLink;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function index()
    {
        $links = CustomLink::all();
        return view('admin.modules.links', compact('links'));
    }

    public function update(Request $request, $id)
    {
        $link = CustomLink::findOrFail($id);

        $validated = $request->validate([
            'label' => 'required|string|max:100',
            'url' => 'required|string',
            'target' => 'required|in:_self,_blank',
        ]);

        $link->update($validated);

        ActivityLog::log('updated', 'links', "Updated custom destination link '{$link->label}'.");

        return redirect('/admin/links')->with('success', "Link '{$link->label}' updated successfully.");
    }
}
