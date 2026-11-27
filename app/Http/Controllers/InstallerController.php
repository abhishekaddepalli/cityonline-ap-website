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

    public function executeStep(string $step, Request $request)
    {
        if (CheckInstallation::isInstalled()) {
            return response()->json([
                'success' => true,
                'message' => 'Installation is already completed.',
                'redirect_url' => url('/admin/login'),
                'site_url' => url('/'),
            ]);
        }

        @set_time_limit(180);
        @ini_set('max_execution_time', '180');
        @ini_set('memory_limit', '256M');

        try {
            return match ($step) {
                'config' => $this->stepConfig($request),
                'migrate' => $this->stepMigrate($request),
                'seed' => $this->stepSeed($request),
                'finish' => $this->stepFinish($request),
                default => response()->json(['success' => false, 'message' => "Unknown step: {$step}"], 400),
            };
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => "Error in step [{$step}]: " . $e->getMessage(),
            ], 500);
        }
    }

    public function install(Request $request)
    {
        if (CheckInstallation::isInstalled()) {
            return response()->json([
                'success' => true,
                'message' => 'Installation is already completed and locked.',
                'redirect_url' => url('/admin/login'),
                'site_url' => url('/'),
            ]);
        }

        @set_time_limit(300);
        @ini_set('max_execution_time', '300');
        @ini_set('memory_limit', '256M');

        try {
            $r1 = $this->stepConfig($request);
            $d1 = json_decode($r1->getContent(), true);
            if (!($d1['success'] ?? false)) return $r1;

            $r2 = $this->stepMigrate($request);
            $d2 = json_decode($r2->getContent(), true);
            if (!($d2['success'] ?? false)) return $r2;

            $r3 = $this->stepSeed($request);
            $d3 = json_decode($r3->getContent(), true);
            if (!($d3['success'] ?? false)) return $r3;

            return $this->stepFinish($request);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Installation error: ' . $e->getMessage(),
            ], 500);
        }
    }

    protected function stepConfig(Request $request)
    {
        $validated = $request->validate([
            'db_connection' => 'required|in:mysql,sqlite',
            'db_host' => 'nullable|string',
            'db_port' => 'nullable|numeric',
            'db_database' => 'required|string',
            'db_username' => 'nullable|string',
            'db_password' => 'nullable|string',
            'website_name' => 'required|string|max:150',
            'website_url' => 'required|url',
        ]);

        $dbConnection = $validated['db_connection'];
        $dbHost = trim($validated['db_host'] ?: 'localhost');
        $dbPort = $validated['db_port'] ?: '3306';
        $dbDatabase = $validated['db_database'];
        $dbUsername = $validated['db_username'] ?? '';
        $dbPassword = $validated['db_password'] ?? '';
        $socketPath = null;

        if ($dbConnection === 'mysql') {
            // Check common cPanel socket paths first
            $possibleSockets = ['/var/lib/mysql/mysql.sock', '/tmp/mysql.sock', '/run/mysqld/mysqld.sock'];
            foreach ($possibleSockets as $sock) {
                if (file_exists($sock)) {
                    $socketPath = $sock;
                    break;
                }
            }

            $hostsToTry = array_unique([$dbHost, 'localhost', '127.0.0.1']);
            $connected = false;
            $workingHost = $dbHost;
            $lastDbError = '';

            if ($socketPath) {
                try {
                    $dsn = "mysql:unix_socket={$socketPath};dbname={$dbDatabase};charset=utf8mb4";
                    $pdo = new PDO($dsn, $dbUsername, $dbPassword, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_TIMEOUT => 3,
                    ]);
                    $pdo->query('SELECT 1');
                    $connected = true;
                    $workingHost = 'localhost';
                } catch (\Throwable $ex) {
                    $lastDbError = $ex->getMessage();
                }
            }

            if (!$connected) {
                foreach ($hostsToTry as $host) {
                    try {
                        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, $dbPort, $dbDatabase);
                        $pdo = new PDO($dsn, $dbUsername, $dbPassword, [
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                            PDO::ATTR_TIMEOUT => 3,
                        ]);
                        $pdo->query('SELECT 1');
                        $connected = true;
                        $workingHost = $host;
                        break;
                    } catch (\Throwable $ex) {
                        $lastDbError = $ex->getMessage();
                    }
                }
            }

            if (!$connected) {
                return response()->json([
                    'success' => false,
                    'message' => 'Could not connect to MySQL: ' . $lastDbError . '. Please verify database name, user, and password in Step 3.',
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

        // Save into .env safely
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
            if ($socketPath) {
                $envUpdates['DB_SOCKET'] = $socketPath;
            }
        } else {
            $envUpdates['DB_DATABASE'] = '"' . $dbDatabase . '"';
        }

        if (empty(config('app.key')) || config('app.key') === 'base64:Xz9Xgeegq5lmzsVudL4XYjsO/NJx93yW3pyFhc3t1H4=') {
            $newKey = 'base64:' . base64_encode(random_bytes(32));
            config(['app.key' => $newKey]);
            $envUpdates['APP_KEY'] = $newKey;
        }

        $this->updateEnvFile($envUpdates);

        // Save in session for subsequent steps
        session([
            'inst_db_conn' => $dbConnection,
            'inst_db_host' => $dbHost,
            'inst_db_port' => $dbPort,
            'inst_db_name' => $dbDatabase,
            'inst_db_user' => $dbUsername,
            'inst_db_pass' => $dbPassword,
            'inst_db_sock' => $socketPath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Configuration saved and database connection verified.',
            'working_host' => $dbHost,
        ]);
    }

    protected function stepMigrate(Request $request)
    {
        $dbConn = $request->input('db_connection') ?: session('inst_db_conn', config('database.default'));
        $dbHost = $request->input('db_host') ?: session('inst_db_host', 'localhost');
        $dbPort = $request->input('db_port') ?: session('inst_db_port', 3306);
        $dbDatabase = $request->input('db_database') ?: session('inst_db_name', config('database.connections.mysql.database'));
        $dbUsername = $request->input('db_username') ?: session('inst_db_user', config('database.connections.mysql.username'));
        $dbPassword = $request->input('db_password') ?: session('inst_db_pass', config('database.connections.mysql.password'));
        $dbSocket = session('inst_db_sock');

        $this->configureDatabaseConnection($dbConn, $dbHost, $dbPort, $dbDatabase, $dbUsername, $dbPassword, $dbSocket);

        // Clean tables if any exist to prevent duplicate table/key collision
        try {
            \Illuminate\Support\Facades\Schema::dropAllTables();
        } catch (\Throwable) {
            // Ignore if empty
        }

        // Run migrations
        $exitCode = Artisan::call('migrate', ['--force' => true]);
        if ($exitCode !== 0) {
            throw new \RuntimeException('Migration failed: ' . Artisan::output());
        }

        return response()->json([
            'success' => true,
            'message' => 'Database tables and schemas created successfully.',
        ]);
    }

    protected function stepSeed(Request $request)
    {
        $dbConn = $request->input('db_connection') ?: session('inst_db_conn', config('database.default'));
        $dbHost = $request->input('db_host') ?: session('inst_db_host', 'localhost');
        $dbPort = $request->input('db_port') ?: session('inst_db_port', 3306);
        $dbDatabase = $request->input('db_database') ?: session('inst_db_name', config('database.connections.mysql.database'));
        $dbUsername = $request->input('db_username') ?: session('inst_db_user', config('database.connections.mysql.username'));
        $dbPassword = $request->input('db_password') ?: session('inst_db_pass', config('database.connections.mysql.password'));
        $dbSocket = session('inst_db_sock');

        $this->configureDatabaseConnection($dbConn, $dbHost, $dbPort, $dbDatabase, $dbUsername, $dbPassword, $dbSocket);

        // 1. Run seeders
        $exitCode = Artisan::call('db:seed', ['--force' => true]);
        if ($exitCode !== 0) {
            throw new \RuntimeException('Seeding failed: ' . Artisan::output());
        }

        // 2. Super Administrator
        $superAdminRole = Role::firstOrCreate(['slug' => 'super-admin'], [
            'name' => 'Super Administrator',
            'description' => 'Full system access and configuration',
            'permissions' => ['*'],
        ]);

        if ($request->filled('admin_email') && $request->filled('admin_password')) {
            User::updateOrCreate(
                ['email' => $request->input('admin_email')],
                [
                    'name' => $request->input('admin_name', 'Administrator'),
                    'username' => $request->input('admin_username', 'admin'),
                    'password' => Hash::make($request->input('admin_password')),
                    'role_id' => $superAdminRole->id,
                    'is_active' => true,
                ]
            );
        }

        // 3. Company Settings
        if ($request->filled('company_name')) {
            Setting::set('company_name', $request->input('company_name'), 'company', 'string', true);
            Setting::set('display_name', $request->input('website_name', 'City Online AP'), 'company', 'string', true);
            Setting::set('email_support', $request->input('company_email', 'citybroadbandkkd@gmail.com'), 'contact', 'string', true);
            Setting::set('phone_new_connections', $request->input('phone_new', '+91 97009 28928'), 'contact', 'string', true);
            Setting::set('phone_support', $request->input('phone_support', '+91 8844 6668999'), 'contact', 'string', true);
            Setting::set('phone_office', $request->input('phone_office', ''), 'contact', 'string', true);
            Setting::set('whatsapp_number', preg_replace('/[^0-9]/', '', $request->input('whatsapp', '919700928928')), 'contact', 'string', true);
            Setting::set('address_line1', $request->input('office_address', 'Main Road, Peddapuram'), 'company', 'string', true);
            Setting::set('address_city', $request->input('office_city', 'Peddapuram 533437, Andhra Pradesh'), 'company', 'string', true);
            Setting::set('target_location', 'Peddapuram & Surroundings', 'company', 'string', true);
            Setting::set('default_language', $request->input('default_language', 'en'), 'general', 'string', true);
            Setting::set('timezone', $request->input('timezone', 'Asia/Kolkata'), 'general', 'string', true);
            Setting::set('currency_symbol', $request->input('currency', '₹'), 'general', 'string', true);
        }

        return response()->json([
            'success' => true,
            'message' => 'Broadband plans, localities seeded and Super Administrator created.',
        ]);
    }

    protected function stepFinish(Request $request)
    {
        // Public storage symlink if supported
        if (!file_exists(public_path('storage')) && function_exists('symlink')) {
            $target = storage_path('app/public');
            $link = public_path('storage');
            if (file_exists($target)) {
                @symlink($target, $link);
            }
        }

        // Write permanent installation lock file
        $lockData = json_encode([
            'installed_at' => now()->toIso8601String(),
            'version' => '1.0.0',
            'environment' => app()->environment(),
            'admin_email' => $request->input('admin_email', 'admin@cityonlineap.com'),
            'admin_username' => $request->input('admin_username', 'admin'),
            'site_url' => $request->input('website_url', url('/')),
        ], JSON_PRETTY_PRINT);

        file_put_contents(storage_path('installed.lock'), $lockData);

        return response()->json([
            'success' => true,
            'message' => 'City Online AP has been installed successfully!',
            'redirect_url' => url('/admin/login'),
            'site_url' => url('/'),
            'admin_email' => $request->input('admin_email'),
        ]);
    }

    protected function configureDatabaseConnection($dbConn, $dbHost, $dbPort, $dbDatabase, $dbUsername, $dbPassword, $dbSocket = null): void
    {
        config([
            'database.default' => $dbConn,
            'database.connections.' . $dbConn => array_merge(
                config('database.connections.' . $dbConn, []),
                [
                    'host' => $dbHost,
                    'port' => $dbPort,
                    'database' => $dbConn === 'sqlite' ? base_path($dbDatabase) : $dbDatabase,
                    'username' => $dbUsername,
                    'password' => $dbPassword,
                    'unix_socket' => $dbSocket ?: '',
                    'options' => [
                        PDO::ATTR_TIMEOUT => 5,
                    ],
                ]
            ),
        ]);
        DB::purge();
        DB::reconnect();
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
