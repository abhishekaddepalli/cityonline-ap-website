<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::with('user')->latest()->paginate(30);
        return view('admin.modules.security', compact('logs'));
    }
}
