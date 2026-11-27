<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    public function index()
    {
        $posts = BlogPost::latest()->paginate(15);
        return view('admin.modules.blog', compact('posts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'slug' => 'nullable|string|max:200|unique:blog_posts,slug',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'category' => 'required|string|max:100',
            'status' => 'required|in:draft,published',
            'seo_title' => 'nullable|string|max:200',
            'seo_description' => 'nullable|string',
        ]);

        $post = BlogPost::create([
            'title' => $validated['title'],
            'slug' => $validated['slug'] ? Str::slug($validated['slug']) : Str::slug($validated['title']),
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'category' => $validated['category'],
            'status' => $validated['status'],
            'author_id' => auth()->id(),
            'published_at' => $validated['status'] === 'published' ? now() : null,
            'seo_title' => $validated['seo_title'] ?: $validated['title'],
            'seo_description' => $validated['seo_description'] ?: $validated['excerpt'],
        ]);

        ActivityLog::log('created', 'blog', "Published article '{$post->title}'.");

        return redirect('/admin/blog')->with('success', 'Article created successfully.');
    }

    public function destroy($id)
    {
        $post = BlogPost::findOrFail($id);
        $post->delete();

        ActivityLog::log('deleted', 'blog', 'Deleted a blog article.');

        return redirect('/admin/blog')->with('success', 'Article deleted.');
    }
}
