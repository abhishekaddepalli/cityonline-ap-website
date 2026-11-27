<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\HomepageSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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
            'subtitle' => 'nullable|string|max:500',
            'badge_text' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'sort_order' => 'required|numeric',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,webp,svg,gif|max:8192',
            'image_url' => 'nullable|string|max:500',
            'cta_label' => 'nullable|string|max:100',
            'cta_url' => 'nullable|string|max:255',
            'stat_1_num' => 'nullable|string|max:50',
            'stat_1_lbl' => 'nullable|string|max:100',
            'stat_2_num' => 'nullable|string|max:50',
            'stat_2_lbl' => 'nullable|string|max:100',
            'stat_3_num' => 'nullable|string|max:50',
            'stat_3_lbl' => 'nullable|string|max:100',
        ]);

        $content = is_array($section->content) ? $section->content : [];

        // Handle direct image file upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $uploadDir = public_path('uploads/sections');
            if (!File::isDirectory($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true, true);
            }

            $filename = 'sec_' . $section->section_key . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);

            // Clean previous uploaded image file if local
            if (isset($content['image_url']) && Str::startsWith($content['image_url'], '/uploads/sections/')) {
                $oldFile = public_path(ltrim($content['image_url'], '/'));
                if (File::exists($oldFile)) {
                    @unlink($oldFile);
                }
            }

            $content['image_url'] = '/uploads/sections/' . $filename;
        } elseif ($request->filled('image_url')) {
            $content['image_url'] = $request->input('image_url');
        }

        // Section CTA links
        if ($request->filled('cta_label')) {
            $content['primary_cta_label'] = $request->input('cta_label');
        }
        if ($request->filled('cta_url')) {
            $content['primary_cta_url'] = $request->input('cta_url');
        }

        // About section stats
        if ($section->section_key === 'about') {
            $stats = [];
            if ($request->filled('stat_1_num')) {
                $stats[] = ['number' => $request->input('stat_1_num'), 'label' => $request->input('stat_1_lbl', 'years in Kakinada')];
            }
            if ($request->filled('stat_2_num')) {
                $stats[] = ['number' => $request->input('stat_2_num'), 'label' => $request->input('stat_2_lbl', 'localities covered')];
            }
            if ($request->filled('stat_3_num')) {
                $stats[] = ['number' => $request->input('stat_3_num'), 'label' => $request->input('stat_3_lbl', 'fault response')];
            }
            if (!empty($stats)) {
                $content['stats'] = $stats;
            }
        }

        $section->update([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'],
            'badge_text' => $validated['badge_text'],
            'description' => $validated['description'],
            'sort_order' => $validated['sort_order'],
            'content' => $content,
            'is_active' => $request->boolean('is_active'),
        ]);

        ActivityLog::log('updated', 'sections', "Updated homepage section '{$section->name}' and visual assets.");

        return redirect('/admin/sections')->with('success', "Section '{$section->name}' updated successfully.");
    }
}
