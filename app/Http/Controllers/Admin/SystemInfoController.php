<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class SystemInfoController extends Controller
{
    public function index()
    {
        $phpVersion = PHP_VERSION;
        $laravelVersion = app()->version();
        $dbDriver = config('database.default');
        $serverSoftware = $_SERVER['SERVER_SOFTWARE'] ?? php_uname('s') . ' ' . php_uname('r');

        $storageUsage = $this->getFolderSize(storage_path());

        return view('admin.modules.system', compact(
            'phpVersion', 'laravelVersion', 'dbDriver', 'serverSoftware', 'storageUsage'
        ));
    }

    public function clearCache()
    {
        Artisan::call('optimize:clear');
        ActivityLog::log('cleared', 'system', 'Cleared all application, config, view and route caches.');

        return back()->with('success', 'Application cache, view cache, and route cache cleared successfully.');
    }

    protected function getFolderSize($dir): string
    {
        $size = 0;
        if (is_dir($dir)) {
            foreach (glob(rtrim($dir, '/').'/*', GLOB_NOSORT) as $each) {
                $size += is_file($each) ? filesize($each) : 0;
            }
        }
        return round($size / 1024 / 1024, 2) . ' MB';
    }
}
