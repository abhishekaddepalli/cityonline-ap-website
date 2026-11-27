<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CheckInstallation;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PDO;

class InstallerController extends Controller
{
    public function index()
    {
        if (CheckInstallation::isInstalled()) {
            return redirect('/admin');
        }

        $step = (int) session('installer_step', 1);
        $requirements = $this->checkRequirements();
        $allPassed = $requirements['all_passed'];

        return view('installer.index', compact('step', 'requirements', 'allPassed'));
    }

    public function checkRequirements(): array
    {
        $phpVersion = PHP_VERSION;
        $phpRequired = '8.2.0';
        $phpPassed = version_compare($phpVersion, $phpRequired, '>=');

        $requiredExtensions = [
            'openssl' => 'OpenSSL',
            'pdo' => 'PDO',
            'mbstring' => 'Mbstring',
            'tokenizer' => 'Tokenizer',
            'xml' => 'XML',
            'ctype' => 'Ctype',
            'json' => 'JSON',
            'fileinfo' => 'Fileinfo',
            'curl' => 'cURL',
        ];

        $extensionResults = [];
        $extensionsPassed = true;

        foreach ($requiredExtensions as $ext => $label) {
            $loaded = extension_loaded($ext);
            $extensionResults[$label] = $loaded;
            if (!$loaded) {
                $extensionsPassed = false;
            }
        }

        // Check either GD or Imagick
        $hasGdOrImagick = extension_loaded('gd') || extension_loaded('imagick');
        $extensionResults['GD or Imagick'] = $hasGdOrImagick;
        if (!$hasGdOrImagick) {
            $extensionsPassed = false;
        }

        // Database driver (pdo_mysql or pdo_sqlite)
        $hasDbDriver = extension_loaded('pdo_mysql') || extension_loaded('pdo_sqlite');
        $extensionResults['PDO MySQL (or SQLite)'] = $hasDbDriver;
        if (!$hasDbDriver) {
            $extensionsPassed = false;
        }

        // Writable Directories
        $directories = [
            'storage/app' => storage_path('app'),
            'storage/framework' => storage_path('framework'),
            'storage/logs' => storage_path('logs'),
            'bootstrap/cache' => base_path('bootstrap/cache'),
        ];

        $directoryResults = [];
        $directoriesPassed = true;

        foreach ($directories as $label => $path) {
            $writable = is_writable($path) || (is_dir($path) && is_writable($path));
            $directoryResults[$label] = $writable;
            if (!$writable) {
                $directoriesPassed = false;
            }
        }

        $allPassed = $phpPassed && $extensionsPassed && $directoriesPassed;

        return [
            'php_version' => $phpVersion,
            'php_passed' => $phpPassed,
            'extensions' => $extensionResults,
            'directories' => $directoryResults,
            'all_passed' => $allPassed,
        ];
    }

    public function testDatabase(Request $request)
    {
        $validated = $request->validate([
            'db_connection' => 'required|in:mysql,sqlite',
            'db_host' => 'nullable|string',
            'db_port' => 'nullable|numeric',
            'db_database' => 'required|string',
            'db_username' => 'nullable|string',
            'db_password' => 'nullable|string',
        ]);

        try {
            if ($validated['db_connection'] === 'sqlite') {
                $dbPath = base_path($validated['db_database']);
                if (!file_exists($dbPath)) {
                    touch($dbPath);
                }
                new PDO('sqlite:'.$dbPath);
            } else {
                $dsn = sprintf(
                    'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                    $validated['db_host'] ?? '127.0.0.1',
                    $validated['db_port'] ?? '3306',
                    $validated['db_database']
                );
                $pdo = new PDO($dsn, $validated['db_username'] ?? 'root', $validated['db_password'] ?? '', [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_TIMEOUT => 5,
                ]);
                $pdo->query('SELECT 1');
            }

            return response()->json([
                'success' => true,
                'message' => 'Database connection successful! You can now proceed to the next step.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage(),
            ], 422);
        }
    }

    public function install(Request $request)
    {
        if (CheckInstallation::isInstalled()) {
            return response()->json(['error' => 'Installation is already locked.'], 403);
        }

        $validated = $request->validate([
            // Database
            'db_connection' => 'required|in:mysql,sqlite',
            'db_host' => 'nullable|string',
            'db_port' => 'nullable|numeric',
            'db_database' => 'required|string',
            'db_username' => 'nullable|string',
            'db_password' => 'nullable|string',

            // Admin User
            'admin_name' => 'required|string|max:100',
            'admin_email' => 'required|email|max:150',
            'admin_username' => 'required|string|alpha_dash|min:3|max:50',
            'admin_password' => 'required|string|min:8|confirmed',

            // Company Config
            'company_name' => 'required|string|max:150',
            'website_name' => 'required|string|max:150',
            'website_url' => 'required|url',
            'company_email' => 'required|email',
            'phone_new' => 'required|string',
            'phone_support' => 'required|string',
            'phone_office' => 'nullable|string',
            'whatsapp' => 'required|string',
            'office_address' => 'required|string',
            'default_language' => 'required|in:en,te',
            'timezone' => 'required|string',
            'currency' => 'required|string',
        ]);

        try {
            // 1. Update .env configuration
            $envUpdates = [
                'APP_NAME' => '"' . addslashes($validated['website_name']) . '"',
                'APP_URL' => rtrim($validated['website_url'], '/'),
                'DB_CONNECTION' => $validated['db_connection'],
            ];

            if ($validated['db_connection'] === 'mysql') {
                $envUpdates['DB_HOST'] = $validated['db_host'] ?? '127.0.0.1';
                $envUpdates['DB_PORT'] = $validated['db_port'] ?? '3306';
                $envUpdates['DB_DATABASE'] = $validated['db_database'];
                $envUpdates['DB_USERNAME'] = $validated['db_username'] ?? 'root';
                $envUpdates['DB_PASSWORD'] = '"' . addslashes($validated['db_password'] ?? '') . '"';
            } else {
                $envUpdates['DB_DATABASE'] = '"' . $validated['db_database'] . '"';
            }

            $this->updateEnvFile($envUpdates);

            // Reconnect DB dynamically for this request
            config([
                'database.default' => $validated['db_connection'],
                'database.connections.' . $validated['db_connection'] => array_merge(
                    config('database.connections.' . $validated['db_connection'], []),
                    [
                        'host' => $validated['db_host'] ?? '127.0.0.1',
                        'port' => $validated['db_port'] ?? '3306',
                        'database' => $validated['db_connection'] === 'sqlite' ? base_path($validated['db_database']) : $validated['db_database'],
                        'username' => $validated['db_username'] ?? 'root',
                        'password' => $validated['db_password'] ?? '',
                    ]
                ),
            ]);
            DB::purge();
            DB::reconnect();

            // 2. Run migrations
            Artisan::call('migrate:fresh', ['--force' => true]);

            // 3. Run initial seeders
            Artisan::call('db:seed', ['--force' => true]);

            // 4. Create or update Super Administrator
            $superAdminRole = Role::firstOrCreate(['slug' => 'super-admin'], [
                'name' => 'Super Administrator',
                'permissions' => ['*'],
            ]);

            User::updateOrCreate(
                ['email' => $validated['admin_email']],
                [
                    'name' => $validated['admin_name'],
                    'username' => $validated['admin_username'],
                    'password' => Hash::make($validated['admin_password']),
                    'role_id' => $superAdminRole->id,
                    'is_active' => true,
                ]
            );

            // 5. Update Company Settings
            Setting::set('company_name', $validated['company_name'], 'company', 'string', true);
            Setting::set('display_name', $validated['website_name'], 'company', 'string', true);
            Setting::set('email_support', $validated['company_email'], 'contact', 'string', true);
            Setting::set('phone_new_connections', $validated['phone_new'], 'contact', 'string', true);
            Setting::set('phone_support', $validated['phone_support'], 'contact', 'string', true);
            Setting::set('phone_office', $validated['phone_office'] ?? '', 'contact', 'string', true);
            Setting::set('whatsapp_number', preg_replace('/[^0-9]/', '', $validated['whatsapp']), 'contact', 'string', true);
            Setting::set('address_line1', $validated['office_address'], 'company', 'string', true);
            Setting::set('default_language', $validated['default_language'], 'general', 'string', true);
            Setting::set('timezone', $validated['timezone'], 'general', 'string', true);
            Setting::set('currency_symbol', $validated['currency'], 'general', 'string', true);

            // 6. Generate app key if needed
            if (empty(config('app.key'))) {
                Artisan::call('key:generate', ['--force' => true]);
            }

            // 7. Automatically create public storage symlink (no terminal needed)
            try {
                Artisan::call('storage:link');
            } catch (\Throwable $symlinkError) {
                $target = storage_path('app/public');
                $link = public_path('storage');
                if (!file_exists($link) && file_exists($target)) {
                    @symlink($target, $link);
                }
            }

            // 8. Write permanent installation lock file
            $lockData = json_encode([
                'installed_at' => now()->toIso8601String(),
                'version' => '1.0.0',
                'environment' => app()->environment(),
                'admin_email' => $validated['admin_email'],
            ], JSON_PRETTY_PRINT);

            file_put_contents(storage_path('installed.lock'), $lockData);

            return response()->json([
                'success' => true,
                'message' => 'City Online AP has been installed successfully!',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Installation error: ' . $e->getMessage(),
            ], 500);
        }
    }

    protected function updateEnvFile(array $data): void
    {
        $envPath = base_path('.env');
        if (!file_exists($envPath)) {
            copy(base_path('.env.example'), $envPath);
        }

        $envContent = file_get_contents($envPath);

        foreach ($data as $key => $value) {
            $pattern = "/^{$key}=.*/m";
            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, "{$key}={$value}", $envContent);
            } else {
                $envContent .= "\n{$key}={$value}";
            }
        }

        file_put_contents($envPath, $envContent);
    }
}
