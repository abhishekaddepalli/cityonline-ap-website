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
                $dbDir = dirname($dbPath);
                if (!file_exists($dbDir)) {
                    @mkdir($dbDir, 0755, true);
                }
                if (!file_exists($dbPath)) {
                    @touch($dbPath);
                }
                new PDO('sqlite:' . $dbPath);
                return response()->json([
                    'success' => true,
                    'message' => 'SQLite database file is accessible!',
                    'detected_host' => 'sqlite',
                ]);
            }

            $preferredHost = trim($validated['db_host'] ?: 'localhost');
            $dbPort = $validated['db_port'] ?: '3306';
            $dbDatabase = $validated['db_database'];
            $dbUser = $validated['db_username'] ?? '';
            $dbPass = $validated['db_password'] ?? '';

            // Try the user's host first, followed by localhost and 127.0.0.1
            $hostsToTry = array_unique([$preferredHost, 'localhost', '127.0.0.1']);
            $connected = false;
            $workingHost = $preferredHost;
            $lastError = '';

            foreach ($hostsToTry as $host) {
                try {
                    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, $dbPort, $dbDatabase);
                    $pdo = new PDO($dsn, $dbUser, $dbPass, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_TIMEOUT => 4,
                    ]);
                    $pdo->query('SELECT 1');
                    $connected = true;
                    $workingHost = $host;
                    break;
                } catch (\Throwable $e) {
                    $lastError = $e->getMessage();
                }
            }

            if (!$connected) {
                return response()->json([
                    'success' => false,
                    'message' => 'Connection failed: ' . $lastError,
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => 'Database connection successful! You can now proceed to the next step.',
                'detected_host' => $workingHost,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage(),
            ], 422);
        }
    }

    public function install(Request $request)
    {
        if (CheckInstallation::isInstalled()) {
            return response()->json([
                'success' => true,
                'message' => 'Installation is already completed and locked.',
                'redirect_url' => url('/admin/login'),
            ]);
        }

        @set_time_limit(300);
        @ini_set('max_execution_time', '300');
        @ini_set('memory_limit', '256M');

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
            $dbConnection = $validated['db_connection'];
            $dbHost = trim($validated['db_host'] ?: 'localhost');
            $dbPort = $validated['db_port'] ?: '3306';
            $dbDatabase = $validated['db_database'];
            $dbUsername = $validated['db_username'] ?? '';
            $dbPassword = $validated['db_password'] ?? '';

            // 1. FAST PRE-FLIGHT DATABASE VERIFICATION
            if ($dbConnection === 'mysql') {
                $hostsToTry = array_unique([$dbHost, 'localhost', '127.0.0.1']);
                $connected = false;
                $workingHost = $dbHost;
                $lastDbError = '';

                foreach ($hostsToTry as $host) {
                    try {
                        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, $dbPort, $dbDatabase);
                        $pdo = new PDO($dsn, $dbUsername, $dbPassword, [
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                            PDO::ATTR_TIMEOUT => 4,
                        ]);
                        $pdo->query('SELECT 1');
                        $connected = true;
                        $workingHost = $host;
                        break;
                    } catch (\Throwable $ex) {
                        $lastDbError = $ex->getMessage();
                    }
                }

                if (!$connected) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Could not connect to MySQL database: ' . $lastDbError . '. Please double-check your database name, username and password in Step 3.',
                    ], 422);
                }

                $dbHost = $workingHost;
            } else {
                $dbPath = base_path($dbDatabase);
                $dbDir = dirname($dbPath);
                if (!file_exists($dbDir)) {
                    @mkdir($dbDir, 0755, true);
                }
                if (!file_exists($dbPath)) {
                    @touch($dbPath);
                }
            }

            // 2. Safe .env update (no regex backreference bugs with $ passwords)
            $envUpdates = [
                'APP_NAME' => '"' . addslashes($validated['website_name']) . '"',
                'APP_URL' => rtrim($validated['website_url'], '/'),
                'DB_CONNECTION' => $dbConnection,
            ];

            if ($dbConnection === 'mysql') {
                $envUpdates['DB_HOST'] = $dbHost;
                $envUpdates['DB_PORT'] = $dbPort;
                $envUpdates['DB_DATABASE'] = $dbDatabase;
                $envUpdates['DB_USERNAME'] = $dbUsername;
                $envUpdates['DB_PASSWORD'] = '"' . addslashes($dbPassword) . '"';
            } else {
                $envUpdates['DB_DATABASE'] = '"' . $dbDatabase . '"';
            }

            // Ensure APP_KEY exists
            if (empty(config('app.key')) || config('app.key') === 'base64:Xz9Xgeegq5lmzsVudL4XYjsO/NJx93yW3pyFhc3t1H4=') {
                $newKey = 'base64:' . base64_encode(random_bytes(32));
                config(['app.key' => $newKey]);
                $envUpdates['APP_KEY'] = $newKey;
            }

            $this->updateEnvFile($envUpdates);

            // 3. Reconnect DB dynamically for this request
            config([
                'database.default' => $dbConnection,
                'database.connections.' . $dbConnection => array_merge(
                    config('database.connections.' . $dbConnection, []),
                    [
                        'host' => $dbHost,
                        'port' => $dbPort,
                        'database' => $dbConnection === 'sqlite' ? base_path($dbDatabase) : $dbDatabase,
                        'username' => $dbUsername,
                        'password' => $dbPassword,
                        'options' => [
                            PDO::ATTR_TIMEOUT => 10,
                        ],
                    ]
                ),
            ]);
            DB::purge();
            DB::reconnect();

            // 4. Run migrations with --force (safer & faster than migrate:fresh on shared hosts)
            Artisan::call('migrate', ['--force' => true]);

            // 5. Run initial seeders with --force
            Artisan::call('db:seed', ['--force' => true]);

            // 6. Create or update Super Administrator
            $superAdminRole = Role::firstOrCreate(['slug' => 'super-admin'], [
                'name' => 'Super Administrator',
                'description' => 'Full system access and configuration',
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

            // 7. Update Company Settings
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

            // 8. Safely link public storage if symlink is supported
            if (!file_exists(public_path('storage')) && function_exists('symlink')) {
                $target = storage_path('app/public');
                $link = public_path('storage');
                if (file_exists($target)) {
                    @symlink($target, $link);
                }
            }

            // 9. Write permanent installation lock file
            $lockData = json_encode([
                'installed_at' => now()->toIso8601String(),
                'version' => '1.0.0',
                'environment' => app()->environment(),
                'admin_email' => $validated['admin_email'],
                'admin_username' => $validated['admin_username'],
                'site_url' => $validated['website_url'],
            ], JSON_PRETTY_PRINT);

            file_put_contents(storage_path('installed.lock'), $lockData);

            return response()->json([
                'success' => true,
                'message' => 'City Online AP has been installed successfully!',
                'redirect_url' => url('/admin/login'),
                'site_url' => url('/'),
                'admin_email' => $validated['admin_email'],
            ]);
        } catch (\Throwable $e) {
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

        $lines = file($envPath, FILE_IGNORE_NEW_LINES);
        if ($lines === false) {
            $lines = [];
        }

        $updatedKeys = [];
        foreach ($lines as $i => $line) {
            $trimmed = trim($line);
            if (empty($trimmed) || str_starts_with($trimmed, '#')) {
                continue;
            }
            foreach ($data as $key => $value) {
                if (str_starts_with($trimmed, $key . '=')) {
                    $lines[$i] = "{$key}={$value}";
                    $updatedKeys[$key] = true;
                }
            }
        }

        foreach ($data as $key => $value) {
            if (!isset($updatedKeys[$key])) {
                $lines[] = "{$key}={$value}";
            }
        }

        file_put_contents($envPath, implode("\n", $lines) . "\n");
    }
}
