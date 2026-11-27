<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::orderBy('sort_order', 'asc')->get();
        return view('admin.modules.pages', compact('pages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'slug' => 'nullable|string|max:200|unique:pages,slug',
            'template' => 'required|string',
            'excerpt' => 'nullable|string',
            'status' => 'required|in:published,draft',
            'seo_title' => 'nullable|string|max:200',
            'seo_description' => 'nullable|string',
        ]);

        $slug = $validated['slug'] ? Str::slug($validated['slug']) : Str::slug($validated['title']);

        $page = Page::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'template' => $validated['template'],
            'excerpt' => $validated['excerpt'],
            'status' => $validated['status'],
            'seo_title' => $validated['seo_title'] ?: $validated['title'],
            'seo_description' => $validated['seo_description'] ?: $validated['excerpt'],
            'published_at' => $validated['status'] === 'published' ? now() : null,
            'sort_order' => (Page::max('sort_order') ?? 0) + 1,
        ]);

        ActivityLog::log('created', 'pages', "Created page '{$page->title}'.");

        return redirect('/admin/pages')->with('success', "Page '{$page->title}' created successfully.");
    }

    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'slug' => 'required|string|max:200|unique:pages,slug,' . $id,
            'template' => 'required|string',
            'excerpt' => 'nullable|string',
            'status' => 'required|in:published,draft',
            'seo_title' => 'nullable|string|max:200',
            'seo_description' => 'nullable|string',
        ]);

        $page->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['slug']),
            'template' => $validated['template'],
            'excerpt' => $validated['excerpt'],
            'status' => $validated['status'],
            'seo_title' => $validated['seo_title'],
            'seo_description' => $validated['seo_description'],
            'published_at' => $validated['status'] === 'published' && !$page->published_at ? now() : $page->published_at,
        ]);

        ActivityLog::log('updated', 'pages', "Updated page '{$page->title}'.");

        return redirect('/admin/pages')->with('success', "Page '{$page->title}' updated successfully.");
    }

    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        $title = $page->title;
        $page->delete();

        ActivityLog::log('deleted', 'pages', "Deleted page '{$title}'.");

        return redirect('/admin/pages')->with('success', "Page '{$title}' deleted.");
    }
}
