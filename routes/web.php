<?php

use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\BroadbandPlanController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EnquiryController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\FooterController;
use App\Http\Controllers\Admin\GeneralSettingController;
use App\Http\Controllers\Admin\HomepageSectionController;
use App\Http\Controllers\Admin\InternetToolController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\LinkController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\NavigationController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\SecurityController;
use App\Http\Controllers\Admin\SeoController;
use App\Http\Controllers\Admin\SupportTicketController;
use App\Http\Controllers\Admin\SystemInfoController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Api\PublicApiController;
use App\Http\Controllers\InstallerController;
use App\Models\Redirect;
use Illuminate\Support\Facades\Route;

// Health Check
Route::get('/up', function () {
    return response()->json(['status' => 'healthy', 'service' => 'City Online AP']);
});

// Dynamic XML Sitemap
Route::get('/sitemap.xml', [PublicApiController::class, 'sitemap']);

// Installation Wizard
Route::prefix('install')->group(function () {
    Route::get('/', [InstallerController::class, 'index']);
    Route::post('/test-db', [InstallerController::class, 'testDatabase']);
    Route::post('/run', [InstallerController::class, 'install']);
    Route::post('/step/{step}', [InstallerController::class, 'executeStep']);
});

// Admin Authentication
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login']);
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Admin Dashboard (24 Modules)
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);

    // 1. Dashboard & 20. Analytics
    Route::get('/analytics', [AnalyticsController::class, 'index']);
    Route::put('/analytics', [AnalyticsController::class, 'update']);

    // 2. Website Builder & 4. Homepage Sections
    Route::get('/builder', function () { return redirect('/admin/sections'); });
    Route::get('/sections', [HomepageSectionController::class, 'index']);
    Route::put('/sections/{id}', [HomepageSectionController::class, 'update']);

    // 3. Pages
    Route::get('/pages', [PageController::class, 'index']);
    Route::post('/pages', [PageController::class, 'store']);
    Route::put('/pages/{id}', [PageController::class, 'update']);
    Route::delete('/pages/{id}', [PageController::class, 'destroy']);

    // 5. Header & Navigation
    Route::get('/header', [NavigationController::class, 'index']);
    Route::post('/navigation/items', [NavigationController::class, 'storeItem']);
    Route::put('/navigation/items/{id}', [NavigationController::class, 'updateItem']);
    Route::delete('/navigation/items/{id}', [NavigationController::class, 'destroyItem']);

    // 6. Footer Manager
    Route::get('/footer', [FooterController::class, 'index']);
    Route::put('/footer', [FooterController::class, 'update']);

    // 7. Broadband Plans
    Route::get('/plans', [BroadbandPlanController::class, 'index']);
    Route::post('/plans', [BroadbandPlanController::class, 'store']);
    Route::put('/plans/{id}', [BroadbandPlanController::class, 'update']);
    Route::delete('/plans/{id}', [BroadbandPlanController::class, 'destroy']);

    // 8. Internet Tools
    Route::get('/tools', [InternetToolController::class, 'index']);
    Route::put('/tools/{id}', [InternetToolController::class, 'update']);

    // 9. Media Library
    Route::get('/media', [MediaController::class, 'index']);
    Route::post('/media/upload', [MediaController::class, 'upload']);
    Route::delete('/media/{id}', [MediaController::class, 'destroy']);

    // 10. Menus & Links
    Route::get('/links', [LinkController::class, 'index']);
    Route::put('/links/{id}', [LinkController::class, 'update']);

    // 11. Enquiries
    Route::get('/enquiries', [EnquiryController::class, 'index']);
    Route::put('/enquiries/{id}/status', [EnquiryController::class, 'updateStatus']);
    Route::get('/enquiries/export', [EnquiryController::class, 'exportCsv']);

    // 12. Support Requests
    Route::get('/support', [SupportTicketController::class, 'index']);
    Route::put('/support/{id}', [SupportTicketController::class, 'update']);

    // 13. Chatbot / FAQ
    Route::get('/faqs', [FaqController::class, 'index']);
    Route::post('/faqs', [FaqController::class, 'store']);
    Route::put('/faqs/{id}', [FaqController::class, 'update']);
    Route::delete('/faqs/{id}', [FaqController::class, 'destroy']);

    // 14. SEO Manager
    Route::get('/seo', [SeoController::class, 'index']);
    Route::put('/seo', [SeoController::class, 'update']);
    Route::post('/seo/redirects', [SeoController::class, 'storeRedirect']);
    Route::delete('/seo/redirects/{id}', [SeoController::class, 'destroyRedirect']);

    // 15. Blog / News
    Route::get('/blog', [BlogPostController::class, 'index']);
    Route::post('/blog', [BlogPostController::class, 'store']);
    Route::delete('/blog/{id}', [BlogPostController::class, 'destroy']);

    // 16. Theme & Appearance
    Route::get('/theme', [ThemeController::class, 'index']);
    Route::put('/theme', [ThemeController::class, 'update']);

    // 17. Languages
    Route::get('/languages', [LanguageController::class, 'index']);
    Route::put('/languages', [LanguageController::class, 'update']);

    // 18. Users & Roles
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::post('/users/{id}/toggle', [UserController::class, 'toggleActive']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    // 19. Notifications (Points to enquiries inbox)
    Route::get('/notifications', function () { return redirect('/admin/enquiries'); });

    // 21. Backup & Restore
    Route::get('/backups', [BackupController::class, 'index']);
    Route::post('/backups', [BackupController::class, 'create']);
    Route::get('/backups/{id}/download', [BackupController::class, 'download']);
    Route::delete('/backups/{id}', [BackupController::class, 'destroy']);

    // 22. General Settings
    Route::get('/settings', [GeneralSettingController::class, 'index']);
    Route::put('/settings', [GeneralSettingController::class, 'update']);

    // 23. Security Settings
    Route::get('/security', [SecurityController::class, 'index']);

    // 24. System Info
    Route::get('/system', [SystemInfoController::class, 'index']);
    Route::post('/system/clear-cache', [SystemInfoController::class, 'clearCache']);
});

// Fallback: Check Redirects, then serve Public Frontend SPA
Route::fallback(function () {
    $path = '/' . request()->path();

    // Check if a 301/302 redirect exists
    if (\Illuminate\Support\Facades\Schema::hasTable('redirects')) {
        $redir = Redirect::where('source_url', $path)->where('is_active', true)->first();
        if ($redir) {
            $redir->increment('hits_count');
            return redirect($redir->target_url, $redir->status_code);
        }
    }

    // Serve public frontend
    $distIndex = public_path('index.html');
    if (file_exists($distIndex)) {
        return response()->file($distIndex);
    }

    // Fallback view or JSON
    return response()->json([
        'app' => 'City Online AP Pvt. Ltd.',
        'status' => 'operational',
        'api_status' => 'online',
        'admin_url' => url('/admin'),
    ]);
});
