<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckInstallation
{
    public static function isInstalled(): bool
    {
        return file_exists(storage_path('installed.lock'));
    }

    public function handle(Request $request, Closure $next)
    {
        $isInstalled = static::isInstalled();
        $isInstallRoute = $request->is('install*');

        // If not installed, redirect all web traffic to the installer
        if (!$isInstalled && !$isInstallRoute && !$request->is('up') && !$request->is('api/*') && !$request->is('assets/*') && !$request->is('favicon.png')) {
            return redirect('/install');
        }

        // If installed, permanently lock installer routes
        if ($isInstalled && $isInstallRoute) {
            $dest = auth()->check() ? '/admin' : '/admin/login';
            return redirect($dest)->with('info', 'City Online AP is already installed and locked for security.');
        }

        return $next($request);
    }
}
