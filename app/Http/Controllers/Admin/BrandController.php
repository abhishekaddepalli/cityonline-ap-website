<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        Brand::ensureTableExists();
        $brands = Brand::orderBy('sort_order', 'asc')->get();
        return view('admin.modules.brands', compact('brands'));
    }

    public function store(Request $request)
    {
        Brand::ensureTableExists();

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'website_url' => 'nullable|url|max:255',
            'category' => 'nullable|string|max:100',
            'sort_order' => 'nullable|numeric',
            'is_active' => 'nullable|boolean',
            'logo_url' => 'nullable|string|max:500',
            'logo' => 'nullable|file|mimes:jpeg,png,jpg,webp,svg,gif|max:5120',
        ]);

        $logoPath = $validated['logo_url'] ?? null;

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $uploadDir = public_path('uploads/brands');
            if (!File::isDirectory($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true, true);
            }

            $filename = 'brand_' . time() . '_' . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            $logoPath = '/uploads/brands/' . $filename;
        }

        $brand = Brand::create([
            'name' => $validated['name'],
            'logo_url' => $logoPath,
            'website_url' => $validated['website_url'] ?? null,
            'category' => $validated['category'] ?? 'Partner',
            'sort_order' => $validated['sort_order'] ?? (Brand::max('sort_order') + 1),
            'is_active' => $request->boolean('is_active', true),
        ]);

        ActivityLog::log('created', 'brands', "Added partner brand '{$brand->name}'.");

        return redirect('/admin/brands')->with('success', "Brand '{$brand->name}' added successfully.");
    }

    public function update(Request $request, $id)
    {
        Brand::ensureTableExists();
        $brand = Brand::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'website_url' => 'nullable|url|max:255',
            'category' => 'nullable|string|max:100',
            'sort_order' => 'required|numeric',
            'is_active' => 'nullable|boolean',
            'logo_url' => 'nullable|string|max:500',
            'logo' => 'nullable|file|mimes:jpeg,png,jpg,webp,svg,gif|max:5120',
        ]);

        $logoPath = $brand->logo_url;

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $uploadDir = public_path('uploads/brands');
            if (!File::isDirectory($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true, true);
            }

            $filename = 'brand_' . time() . '_' . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);

            // Clean up old file if stored locally
            if ($brand->logo_url && Str::startsWith($brand->logo_url, '/uploads/brands/')) {
                $oldPath = public_path(ltrim($brand->logo_url, '/'));
                if (File::exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $logoPath = '/uploads/brands/' . $filename;
        } elseif ($request->filled('logo_url')) {
            $logoPath = $request->input('logo_url');
        }

        $brand->update([
            'name' => $validated['name'],
            'logo_url' => $logoPath,
            'website_url' => $validated['website_url'] ?? null,
            'category' => $validated['category'] ?? $brand->category,
            'sort_order' => $validated['sort_order'],
            'is_active' => $request->boolean('is_active'),
        ]);

        ActivityLog::log('updated', 'brands', "Updated brand '{$brand->name}'.");

        return redirect('/admin/brands')->with('success', "Brand '{$brand->name}' updated successfully.");
    }

    public function toggle($id)
    {
        Brand::ensureTableExists();
        $brand = Brand::findOrFail($id);
        $brand->is_active = !$brand->is_active;
        $brand->save();

        ActivityLog::log('updated', 'brands', "Toggled status of brand '{$brand->name}' to " . ($brand->is_active ? 'active' : 'inactive') . '.');

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'is_active' => $brand->is_active]);
        }

        return redirect('/admin/brands')->with('success', "Brand '{$brand->name}' status updated.");
    }

    public function destroy($id)
    {
        Brand::ensureTableExists();
        $brand = Brand::findOrFail($id);
        $name = $brand->name;

        // Clean up file if local
        if ($brand->logo_url && Str::startsWith($brand->logo_url, '/uploads/brands/')) {
            $oldPath = public_path(ltrim($brand->logo_url, '/'));
            if (File::exists($oldPath)) {
                @unlink($oldPath);
            }
        }

        $brand->delete();

        ActivityLog::log('deleted', 'brands', "Deleted brand '{$name}'.");

        return redirect('/admin/brands')->with('success', "Brand '{$name}' deleted successfully.");
    }
}
