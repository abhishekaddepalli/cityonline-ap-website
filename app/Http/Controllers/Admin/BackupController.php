<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Backup;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function index()
    {
        $backups = Backup::latest()->get();
        return view('admin.modules.backups', compact('backups'));
    }

    public function create()
    {
        try {
            $filename = 'cityonline_backup_' . date('Y-m-d_His') . '.json';
            $backupDir = storage_path('app/backups');
            if (!is_dir($backupDir)) {
                mkdir($backupDir, 0755, true);
            }

            // Export all core tables as structured JSON dump
            $data = [
                'metadata' => [
                    'created_at' => now()->toIso8601String(),
                    'version' => '1.0.0',
                    'app' => 'City Online AP',
                ],
                'settings' => DB::table('settings')->get()->toArray(),
                'broadband_plans' => DB::table('broadband_plans')->get()->toArray(),
                'homepage_sections' => DB::table('homepage_sections')->get()->toArray(),
                'pages' => DB::table('pages')->get()->toArray(),
                'navigation_menus' => DB::table('navigation_menus')->get()->toArray(),
                'navigation_items' => DB::table('navigation_items')->get()->toArray(),
                'custom_links' => DB::table('custom_links')->get()->toArray(),
                'localities' => DB::table('localities')->get()->toArray(),
                'internet_tools' => DB::table('internet_tools')->get()->toArray(),
                'faqs' => DB::table('faqs')->get()->toArray(),
                'enquiries' => DB::table('enquiries')->get()->toArray(),
            ];

            $content = json_encode($data, JSON_PRETTY_PRINT);
            $fullPath = $backupDir . '/' . $filename;
            file_put_contents($fullPath, $content);
            $size = filesize($fullPath);

            $backup = Backup::create([
                'filename' => $filename,
                'disk' => 'backups',
                'size' => $size,
                'type' => 'database',
                'created_at' => now(),
            ]);

            ActivityLog::log('created', 'backup', "Generated database backup '{$filename}' ({$size} bytes).");

            return redirect('/admin/backups')->with('success', "Database backup created successfully ({$filename}).");
        } catch (Exception $e) {
            return redirect('/admin/backups')->with('error', "Backup failed: " . $e->getMessage());
        }
    }

    public function download($id)
    {
        $backup = Backup::findOrFail($id);
        $fullPath = storage_path('app/backups/' . $backup->filename);

        if (!file_exists($fullPath)) {
            return back()->with('error', 'Backup file not found on disk.');
        }

        return response()->download($fullPath, $backup->filename);
    }

    public function destroy($id)
    {
        $backup = Backup::findOrFail($id);
        $fullPath = storage_path('app/backups/' . $backup->filename);

        if (file_exists($fullPath)) {
            unlink($fullPath);
        }

        $backup->delete();

        ActivityLog::log('deleted', 'backup', "Deleted backup '{$backup->filename}'.");

        return redirect('/admin/backups')->with('success', 'Backup deleted.');
    }
}
