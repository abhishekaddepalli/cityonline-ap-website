<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\NavigationItem;
use App\Models\NavigationMenu;
use Illuminate\Http\Request;

class NavigationController extends Controller
{
    public function index()
    {
        $menus = NavigationMenu::with('items')->get();
        return view('admin.modules.navigation', compact('menus'));
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
