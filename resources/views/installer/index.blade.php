<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation Wizard — City Online AP</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            blue: '#073B91',
                            navy: '#05265F',
                            secondary: '#1765C0',
                            orange: '#F36B21',
                            orangeHover: '#E85A12',
                            lightBg: '#F5F8FF',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        .step-active { border-color: #F36B21; color: #F36B21; background: #FFF5EF; }
        .step-done { border-color: #073B91; background: #073B91; color: white; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen flex flex-col justify-between antialiased">
    <!-- Header -->
    <header class="bg-brand-navy border-b border-white/10 py-4 px-6 shadow-md">
        <div class="max-w-5xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="bg-white px-3 py-1.5 rounded-lg shadow-sm flex items-center gap-2">
                    <span class="text-brand-blue font-extrabold text-xl tracking-tight">CITY</span>
                    <span class="text-brand-orange font-extrabold text-xl tracking-tight">ONLINE</span>
                    <span class="text-brand-blue font-bold text-sm bg-blue-50 px-1.5 py-0.5 rounded">AP</span>
                </div>
                <span class="hidden sm:inline-block text-white/60 text-xs font-semibold uppercase tracking-widest pl-2 border-l border-white/20">Installation Wizard</span>
            </div>
            <div class="text-xs text-white/70">
                PHP {{ $requirements['php_version'] }} · cPanel Production Mode
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-1 max-w-4xl w-full mx-auto p-4 sm:p-8">
        <!-- Progress Steps -->
        <div class="mb-8 overflow-x-auto pb-2">
            <div class="flex items-center justify-between min-w-[620px]">
                <div class="flex flex-col items-center flex-1 step-indicator" id="step-ind-1">
                    <span class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm step-done" id="badge-1">1</span>
                    <span class="text-xs font-semibold mt-2 text-slate-600">Welcome</span>
                </div>
                <div class="h-0.5 bg-slate-200 flex-1 -mt-4"></div>
                <div class="flex flex-col items-center flex-1 step-indicator" id="step-ind-2">
                    <span class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm border-2 border-slate-300 bg-white" id="badge-2">2</span>
                    <span class="text-xs font-semibold mt-2 text-slate-400">Server</span>
                </div>
                <div class="h-0.5 bg-slate-200 flex-1 -mt-4"></div>
                <div class="flex flex-col items-center flex-1 step-indicator" id="step-ind-3">
                    <span class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm border-2 border-slate-300 bg-white" id="badge-3">3</span>
                    <span class="text-xs font-semibold mt-2 text-slate-400">Database</span>
                </div>
                <div class="h-0.5 bg-slate-200 flex-1 -mt-4"></div>
                <div class="flex flex-col items-center flex-1 step-indicator" id="step-ind-4">
                    <span class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm border-2 border-slate-300 bg-white" id="badge-4">4</span>
                    <span class="text-xs font-semibold mt-2 text-slate-400">Admin</span>
                </div>
                <div class="h-0.5 bg-slate-200 flex-1 -mt-4"></div>
                <div class="flex flex-col items-center flex-1 step-indicator" id="step-ind-5">
                    <span class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm border-2 border-slate-300 bg-white" id="badge-5">5</span>
                    <span class="text-xs font-semibold mt-2 text-slate-400">Company</span>
                </div>
                <div class="h-0.5 bg-slate-200 flex-1 -mt-4"></div>
                <div class="flex flex-col items-center flex-1 step-indicator" id="step-ind-6">
                    <span class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm border-2 border-slate-300 bg-white" id="badge-6">6</span>
                    <span class="text-xs font-semibold mt-2 text-slate-400">Finish</span>
                </div>
            </div>
        </div>

        <!-- Wizard Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
            <!-- Step 1: Welcome -->
            <div id="step-1" class="p-6 sm:p-10">
                <div class="text-center max-w-xl mx-auto">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-50 text-brand-blue mb-4 shadow-sm">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-brand-navy">Welcome to City Online AP Website Installation</h1>
                    <p class="mt-3 text-slate-600 text-sm sm:text-base">Let's configure your broadband website, dynamic CMS database, and administration dashboard in a few simple steps.</p>
                </div>

                <div class="mt-8 grid sm:grid-cols-3 gap-4 max-w-2xl mx-auto">
                    <div class="p-4 rounded-xl border border-slate-100 bg-slate-50 text-center">
                        <span class="block text-brand-orange font-bold text-lg mb-1">01</span>
                        <h4 class="font-bold text-sm text-slate-800">Environment</h4>
                        <p class="text-xs text-slate-500 mt-1">Automated PHP & directory checks</p>
                    </div>
                    <div class="p-4 rounded-xl border border-slate-100 bg-slate-50 text-center">
                        <span class="block text-brand-orange font-bold text-lg mb-1">02</span>
                        <h4 class="font-bold text-sm text-slate-800">Database</h4>
                        <p class="text-xs text-slate-500 mt-1">MySQL/SQLite auto-migration</p>
                    </div>
                    <div class="p-4 rounded-xl border border-slate-100 bg-slate-50 text-center">
                        <span class="block text-brand-orange font-bold text-lg mb-1">03</span>
                        <h4 class="font-bold text-sm text-slate-800">Admin Setup</h4>
                        <p class="text-xs text-slate-500 mt-1">Owner credentials & company data</p>
                    </div>
                </div>

                <div class="mt-10 flex justify-center">
                    <button type="button" onclick="goToStep(2)" class="px-8 py-3.5 rounded-xl font-bold text-white bg-brand-orange hover:bg-brand-orangeHover transition shadow-lg shadow-orange-500/20 flex items-center gap-2">
                        Start Installation
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>

            <!-- Step 2: Server Requirements -->
            <div id="step-2" class="p-6 sm:p-10 hidden">
                <h2 class="text-xl sm:text-2xl font-bold text-brand-navy">Server Requirements Check</h2>
                <p class="text-sm text-slate-600 mt-1">Verifying your server meets the operational prerequisites for cPanel and PHP 8.2+.</p>

                <div class="mt-6 space-y-4">
                    <!-- PHP Version -->
                    <div class="flex items-center justify-between p-3.5 rounded-xl border {{ $requirements['php_passed'] ? 'bg-emerald-50/50 border-emerald-200' : 'bg-red-50 border-red-200' }}">
                        <div>
                            <span class="font-bold text-sm text-slate-800">PHP Version (>= 8.2)</span>
                            <span class="block text-xs text-slate-500">Current version: {{ $requirements['php_version'] }}</span>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $requirements['php_passed'] ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                            {{ $requirements['php_passed'] ? 'Passed' : 'Failed' }}
                        </span>
                    </div>

                    <!-- Extensions -->
                    <div class="border border-slate-200 rounded-xl p-4">
                        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">PHP Extensions</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 text-xs">
                            @foreach($requirements['extensions'] as $name => $passed)
                                <div class="flex items-center gap-2 p-2 rounded-lg bg-slate-50 border border-slate-100">
                                    <span class="w-2 h-2 rounded-full {{ $passed ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                    <span class="font-medium {{ $passed ? 'text-slate-700' : 'text-red-700 font-bold' }}">{{ $name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Directories -->
                    <div class="border border-slate-200 rounded-xl p-4">
                        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Writable Directory Permissions</h3>
                        <div class="grid sm:grid-cols-2 gap-2 text-xs">
                            @foreach($requirements['directories'] as $dir => $passed)
                                <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-100">
                                    <span class="font-mono text-slate-700">{{ $dir }}</span>
                                    <span class="font-semibold {{ $passed ? 'text-emerald-600' : 'text-red-600' }}">{{ $passed ? 'Writable' : 'Not Writable' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-between items-center">
                    <button type="button" onclick="goToStep(1)" class="px-5 py-2.5 rounded-xl font-semibold text-slate-600 border border-slate-300 hover:bg-slate-50">Back</button>
                    @if($allPassed)
                        <button type="button" onclick="goToStep(3)" class="px-6 py-2.5 rounded-xl font-bold text-white bg-brand-orange hover:bg-brand-orangeHover shadow-md">Continue to Database</button>
                    @else
                        <button type="button" disabled class="px-6 py-2.5 rounded-xl font-bold text-white bg-slate-400 cursor-not-allowed">Fix Requirements to Proceed</button>
                    @endif
                </div>
            </div>

            <!-- Step 3: Database Configuration -->
            <div id="step-3" class="p-6 sm:p-10 hidden">
                <h2 class="text-xl sm:text-2xl font-bold text-brand-navy">Database Configuration</h2>
                <p class="text-sm text-slate-600 mt-1">Configure your MySQL database (created via cPanel) or select SQLite for local development.</p>

                <div class="mt-6 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Database Driver</label>
                        <select id="db_connection" onchange="toggleDbFields()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 outline-none">
                            <option value="mysql" selected>MySQL / MariaDB (Standard cPanel)</option>
                            <option value="sqlite">SQLite (File-based)</option>
                        </select>
                    </div>

                    <div id="mysql_fields" class="space-y-4">
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Database Host</label>
                                <input type="text" id="db_host" value="127.0.0.1" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-blue outline-none" placeholder="localhost or 127.0.0.1">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Database Port</label>
                                <input type="number" id="db_port" value="3306" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-blue outline-none" placeholder="3306">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Database Name</label>
                            <input type="text" id="db_database" value="cityonline_db" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-blue outline-none" placeholder="e.g. cpaneluser_cityonline">
                            <span class="text-xs text-slate-400 mt-1 block">In cPanel, database names often start with your cPanel username prefix (e.g. cityuser_db).</span>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Database Username</label>
                                <input type="text" id="db_username" value="root" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-blue outline-none" placeholder="DB Username">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Database Password</label>
                                <input type="password" id="db_password" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-blue outline-none" placeholder="DB Password">
                            </div>
                        </div>
                    </div>

                    <div id="sqlite_fields" class="hidden">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">SQLite Database Path</label>
                        <input type="text" id="sqlite_database" value="database/database.sqlite" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-mono focus:border-brand-blue outline-none">
                    </div>

                    <!-- Connection Feedback Box -->
                    <div id="db_test_result" class="hidden p-3 rounded-xl text-xs font-semibold"></div>

                    <div>
                        <button type="button" onclick="testDatabaseConnection()" class="px-4 py-2 rounded-lg text-xs font-bold bg-slate-100 hover:bg-slate-200 text-brand-navy border border-slate-300 transition">
                            Test Connection
                        </button>
                    </div>
                </div>

                <div class="mt-8 flex justify-between items-center">
                    <button type="button" onclick="goToStep(2)" class="px-5 py-2.5 rounded-xl font-semibold text-slate-600 border border-slate-300 hover:bg-slate-50">Back</button>
                    <button type="button" onclick="validateStep3()" class="px-6 py-2.5 rounded-xl font-bold text-white bg-brand-orange hover:bg-brand-orangeHover shadow-md">Continue to Admin Setup</button>
                </div>
            </div>

            <!-- Step 4: Administrator Account -->
            <div id="step-4" class="p-6 sm:p-10 hidden">
                <h2 class="text-xl sm:text-2xl font-bold text-brand-navy">Create Administrator Account</h2>
                <p class="text-sm text-slate-600 mt-1">This user will be granted the Super Administrator role with full dashboard privileges.</p>

                <div class="mt-6 space-y-4">
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Full Name</label>
                            <input type="text" id="admin_name" value="City Online Administrator" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-blue outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Username</label>
                            <input type="text" id="admin_username" value="admin" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-blue outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Administrator Email</label>
                        <input type="email" id="admin_email" value="admin@cityonlineap.com" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-blue outline-none">
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Password</label>
                            <input type="password" id="admin_password" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-blue outline-none" placeholder="Min. 8 characters">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Confirm Password</label>
                            <input type="password" id="admin_password_confirmation" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-blue outline-none" placeholder="Repeat password">
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-between items-center">
                    <button type="button" onclick="goToStep(3)" class="px-5 py-2.5 rounded-xl font-semibold text-slate-600 border border-slate-300 hover:bg-slate-50">Back</button>
                    <button type="button" onclick="validateStep4()" class="px-6 py-2.5 rounded-xl font-bold text-white bg-brand-orange hover:bg-brand-orangeHover shadow-md">Continue to Company Details</button>
                </div>
            </div>

            <!-- Step 5: Company Configuration -->
            <div id="step-5" class="p-6 sm:p-10 hidden">
                <h2 class="text-xl sm:text-2xl font-bold text-brand-navy">Company & Website Information</h2>
                <p class="text-sm text-slate-600 mt-1">Pre-filled with official City Online AP details. You can adjust these anytime in the Admin Dashboard.</p>

                <div class="mt-6 space-y-4">
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Company Legal Name</label>
                            <input type="text" id="company_name" value="City Online AP Pvt. Ltd." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-blue outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Website Brand Title</label>
                            <input type="text" id="website_name" value="City Online AP" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-blue outline-none">
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Website URL</label>
                            <input type="url" id="website_url" value="https://cityonlineap.com" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-blue outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Company Email</label>
                            <input type="email" id="company_email" value="citybroadbandkkd@gmail.com" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-blue outline-none">
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">New Connections</label>
                            <input type="text" id="phone_new" value="+91 97009 28928" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-blue outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Support Phone</label>
                            <input type="text" id="phone_support" value="+91 8844 6668999" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-blue outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">WhatsApp</label>
                            <input type="text" id="whatsapp" value="+91 97009 28928" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-blue outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Office Address</label>
                        <textarea id="office_address" rows="2" class="w-full px-4 py-2 rounded-xl border border-slate-300 text-sm focus:border-brand-blue outline-none">City Broadband Services, #34-1-6, Temple Street, Opp. Joyalukas Jewellers, Kakinada 533001, Andhra Pradesh</textarea>
                    </div>

                    <div class="grid sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Default Language</label>
                            <select id="default_language" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 text-sm">
                                <option value="en" selected>English</option>
                                <option value="te">Telugu (తెలుగు)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Timezone</label>
                            <input type="text" id="timezone" value="Asia/Kolkata" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Currency</label>
                            <input type="text" id="currency" value="₹" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 text-sm">
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-between items-center">
                    <button type="button" onclick="goToStep(4)" class="px-5 py-2.5 rounded-xl font-semibold text-slate-600 border border-slate-300 hover:bg-slate-50">Back</button>
                    <button type="button" onclick="executeFinalInstallation()" id="install-btn" class="px-8 py-3 rounded-xl font-bold text-white bg-brand-orange hover:bg-brand-orangeHover shadow-lg shadow-orange-500/20 flex items-center gap-2">
                        Install & Launch Website
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </button>
                </div>
            </div>

            <!-- Step 6: Installation Progress / Completion -->
            <div id="step-6" class="p-6 sm:p-10 hidden">
                <div id="installing-spinner" class="text-center py-8">
                    <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-brand-orange border-t-transparent mb-4"></div>
                    <h3 class="text-lg font-bold text-brand-navy">Installing City Online AP Website...</h3>
                    <p class="text-sm text-slate-500 mt-1">Executing database migrations, seeding verified broadband plans, creating admin account, and securing the installation.</p>
                </div>

                <div id="install-complete" class="hidden text-center py-6">
                    <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-brand-navy">Your website is ready!</h2>
                    <p class="text-slate-600 text-sm sm:text-base mt-2 max-w-md mx-auto">
                        Installation completed successfully. The installer has been permanently locked for security. You can now visit your website or access the admin panel.
                    </p>

                    <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="/" class="px-6 py-3 rounded-xl font-bold text-brand-navy border border-slate-300 bg-white hover:bg-slate-50 transition shadow-sm">
                            Visit Website
                        </a>
                        <a href="/admin/login" class="px-6 py-3 rounded-xl font-bold text-white bg-brand-orange hover:bg-brand-orangeHover transition shadow-lg shadow-orange-500/20">
                            Open Admin Dashboard
                        </a>
                    </div>
                </div>

                <div id="install-error" class="hidden p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm mt-4"></div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-6 text-center text-xs text-slate-400 border-t border-slate-200 bg-white">
        © {{ date('Y') }} City Online AP Pvt. Ltd. · Designed for Standard cPanel Hosting
    </footer>

    <script>
        function goToStep(step) {
            for (let i = 1; i <= 6; i++) {
                const el = document.getElementById('step-' + i);
                const badge = document.getElementById('badge-' + i);
                if (el) el.classList.add('hidden');
                if (badge) {
                    badge.classList.remove('step-active', 'step-done');
                    if (i < step) {
                        badge.classList.add('step-done');
                    } else if (i === step) {
                        badge.classList.add('step-active');
                    }
                }
            }
            const current = document.getElementById('step-' + step);
            if (current) current.classList.remove('hidden');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function toggleDbFields() {
            const conn = document.getElementById('db_connection').value;
            if (conn === 'sqlite') {
                document.getElementById('mysql_fields').classList.add('hidden');
                document.getElementById('sqlite_fields').classList.remove('hidden');
            } else {
                document.getElementById('mysql_fields').classList.remove('hidden');
                document.getElementById('sqlite_fields').classList.add('hidden');
            }
        }

        async function testDatabaseConnection() {
            const resultBox = document.getElementById('db_test_result');
            resultBox.classList.remove('hidden', 'bg-emerald-50', 'text-emerald-700', 'border-emerald-200', 'bg-red-50', 'text-red-700', 'border-red-200');
            resultBox.innerHTML = 'Testing connection...';
            resultBox.classList.add('bg-slate-100', 'text-slate-700', 'border', 'border-slate-200');

            const conn = document.getElementById('db_connection').value;
            const payload = {
                db_connection: conn,
                db_host: document.getElementById('db_host').value,
                db_port: document.getElementById('db_port').value,
                db_database: conn === 'sqlite' ? document.getElementById('sqlite_database').value : document.getElementById('db_database').value,
                db_username: document.getElementById('db_username').value,
                db_password: document.getElementById('db_password').value,
            };

            try {
                const res = await fetch('/install/test-db', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                resultBox.classList.remove('bg-slate-100', 'text-slate-700', 'border-slate-200');
                if (res.ok && data.success) {
                    resultBox.classList.add('bg-emerald-50', 'text-emerald-700', 'border', 'border-emerald-200');
                    resultBox.innerHTML = '✓ ' + data.message;
                } else {
                    resultBox.classList.add('bg-red-50', 'text-red-700', 'border', 'border-red-200');
                    resultBox.innerHTML = '✗ ' + (data.message || 'Connection failed.');
                }
            } catch (err) {
                resultBox.classList.remove('bg-slate-100', 'text-slate-700', 'border-slate-200');
                resultBox.classList.add('bg-red-50', 'text-red-700', 'border', 'border-red-200');
                resultBox.innerHTML = '✗ Network error testing database.';
            }
        }

        function validateStep3() {
            const conn = document.getElementById('db_connection').value;
            const dbName = conn === 'sqlite' ? document.getElementById('sqlite_database').value : document.getElementById('db_database').value;
            if (!dbName.trim()) {
                alert('Please provide a database name.');
                return;
            }
            goToStep(4);
        }

        function validateStep4() {
            const email = document.getElementById('admin_email').value;
            const pass = document.getElementById('admin_password').value;
            const confirm = document.getElementById('admin_password_confirmation').value;

            if (!email || !email.includes('@')) {
                alert('Please enter a valid administrator email.');
                return;
            }
            if (pass.length < 8) {
                alert('Password must be at least 8 characters long.');
                return;
            }
            if (pass !== confirm) {
                alert('Passwords do not match. Please re-enter.');
                return;
            }
            goToStep(5);
        }

        async function executeFinalInstallation() {
            const btn = document.getElementById('install-btn');
            btn.disabled = true;

            goToStep(6);
            document.getElementById('installing-spinner').classList.remove('hidden');
            document.getElementById('install-complete').classList.add('hidden');
            document.getElementById('install-error').classList.add('hidden');

            const conn = document.getElementById('db_connection').value;
            const payload = {
                db_connection: conn,
                db_host: document.getElementById('db_host').value,
                db_port: document.getElementById('db_port').value,
                db_database: conn === 'sqlite' ? document.getElementById('sqlite_database').value : document.getElementById('db_database').value,
                db_username: document.getElementById('db_username').value,
                db_password: document.getElementById('db_password').value,
                admin_name: document.getElementById('admin_name').value,
                admin_username: document.getElementById('admin_username').value,
                admin_email: document.getElementById('admin_email').value,
                admin_password: document.getElementById('admin_password').value,
                admin_password_confirmation: document.getElementById('admin_password_confirmation').value,
                company_name: document.getElementById('company_name').value,
                website_name: document.getElementById('website_name').value,
                website_url: document.getElementById('website_url').value,
                company_email: document.getElementById('company_email').value,
                phone_new: document.getElementById('phone_new').value,
                phone_support: document.getElementById('phone_support').value,
                phone_office: document.getElementById('phone_office').value,
                whatsapp: document.getElementById('whatsapp').value,
                office_address: document.getElementById('office_address').value,
                default_language: document.getElementById('default_language').value,
                timezone: document.getElementById('timezone').value,
                currency: document.getElementById('currency').value,
            };

            try {
                const res = await fetch('/install/run', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                document.getElementById('installing-spinner').classList.add('hidden');

                if (res.ok && data.success) {
                    document.getElementById('install-complete').classList.remove('hidden');
                } else {
                    document.getElementById('install-error').classList.remove('hidden');
                    document.getElementById('install-error').innerText = data.message || 'Installation failed.';
                    btn.disabled = false;
                }
            } catch (err) {
                document.getElementById('installing-spinner').classList.add('hidden');
                document.getElementById('install-error').classList.remove('hidden');
                document.getElementById('install-error').innerText = 'A network error occurred while running the installation.';
                btn.disabled = false;
            }
        }
    </script>
</body>
</html>
