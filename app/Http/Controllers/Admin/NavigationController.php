<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\NavigationItem;
use App\Models\NavigationMenu;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class NavigationController extends Controller
{
    public function index()
    {
        $menus = NavigationMenu::with('items')->get();
        $headerLogo = Setting::get('header_logo', '/__l5e/assets-v1/9f8be65f-5299-4384-8c94-43db0ccd1033/city-online-logo.png');
        $headerTemplate = Setting::get('header_template', 'modern-glass');
        $headerGlow = Setting::get('enable_header_glow', true);

        return view('admin.modules.navigation', compact('menus', 'headerLogo', 'headerTemplate', 'headerGlow'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'header_logo' => 'nullable|string|max:500',
            'header_logo_file' => 'nullable|file|mimes:jpeg,png,jpg,webp,svg,gif|max:5120',
            'header_template' => 'required|in:modern-glass,floating-island,classic-solid',
            'enable_header_glow' => 'nullable|boolean',
        ]);

        $logoUrl = $request->input('header_logo');

        if ($request->hasFile('header_logo_file')) {
            $file = $request->file('header_logo_file');
            $uploadDir = public_path('uploads/logos');
            if (!File::isDirectory($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true, true);
            }

            $filename = 'header_logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            $logoUrl = '/uploads/logos/' . $filename;
        }

        if ($logoUrl) {
            Setting::set('header_logo', $logoUrl, 'branding', 'string', true);
        }

        Setting::set('header_template', $request->input('header_template', 'modern-glass'), 'branding', 'string', true);
        Setting::set('enable_header_glow', $request->boolean('enable_header_glow'), 'branding', 'boolean', true);

        ActivityLog::log('updated', 'navigation', 'Updated Header Logo and Header layout template.');

        return redirect('/admin/header')->with('success', 'Header logo and template styling updated successfully.');
    }

    public function storeItem(Request $request)
    {
        $validated = $request->validate([
            'menu_id' => 'required|exists:navigation_menus,id',
            'title' => 'required|string|max:100',
            'title_te' => 'nullable|string|max:100',
            'route' => 'nullable|string|max:200',
            'url' => 'nullable|string|max:255',
            'link_type' => 'required|in:internal,external,tel,mailto,whatsapp',
            'target' => 'required|in:_self,_blank',
            'sort_order' => 'required|numeric',
        ]);

        $item = NavigationItem::create(array_merge($validated, ['is_active' => true]));

        ActivityLog::log('created', 'navigation', "Added navigation item '{$item->title}'.");

        return redirect('/admin/header')->with('success', "Menu item '{$item->title}' added successfully.");
    }

    public function updateItem(Request $request, $id)
    {
        $item = NavigationItem::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'title_te' => 'nullable|string|max:100',
            'route' => 'nullable|string|max:200',
            'url' => 'nullable|string|max:255',
            'link_type' => 'required|in:internal,external,tel,mailto,whatsapp',
            'target' => 'required|in:_self,_blank',
            'sort_order' => 'required|numeric',
            'is_active' => 'nullable|boolean',
        ]);

        $item->update(array_merge($validated, ['is_active' => $request->boolean('is_active')]));

        ActivityLog::log('updated', 'navigation', "Updated navigation item '{$item->title}'.");

        return redirect('/admin/header')->with('success', "Menu item '{$item->title}' updated.");
    }

    public function destroyItem($id)
    {
        $item = NavigationItem::findOrFail($id);
        $title = $item->title;
        $item->delete();

        ActivityLog::log('deleted', 'navigation', "Deleted navigation item '{$title}'.");

        return redirect('/admin/header')->with('success', "Menu item '{$title}' removed.");
    }
}
