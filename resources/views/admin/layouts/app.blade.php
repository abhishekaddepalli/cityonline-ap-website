<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') — City Online AP</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.15); border-radius: 9999px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.25); }
    </style>
    @yield('styles')
</head>
<body class="bg-slate-100 text-slate-800 font-sans min-h-screen flex antialiased">
    <!-- Sidebar -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-brand-navy text-white flex flex-col transition-transform -translate-x-full lg:translate-x-0 shadow-xl">
        <!-- Sidebar Header with Original Logo -->
        <div class="h-16 px-5 border-b border-white/10 flex items-center justify-between">
            <a href="/admin" class="flex items-center gap-2">
                <div class="bg-white px-2.5 py-1 rounded-lg flex items-center gap-1.5 shadow-sm">
                    <span class="text-brand-blue font-extrabold text-base tracking-tight">CITY</span>
                    <span class="text-brand-orange font-extrabold text-base tracking-tight">ONLINE</span>
                    <span class="text-brand-blue font-bold text-xs bg-blue-50 px-1 py-0.2 rounded">AP</span>
                </div>
            </a>
            <button type="button" onclick="toggleSidebar()" class="lg:hidden text-white/70 hover:text-white p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Navigation Modules (27 Modules in Clean Sequential Order) -->
        <nav class="flex-1 overflow-y-auto custom-scrollbar px-3 py-4 space-y-6 text-xs">
            <!-- Group 1: Overview -->
            <div>
                <p class="px-3 text-[10px] font-bold uppercase tracking-wider text-white/40 mb-2">Overview</p>
                <div class="space-y-1">
                    <a href="/admin" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->is('admin') ? 'bg-brand-orange text-white font-bold shadow-md shadow-orange-500/20' : 'text-white/75 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>1. Dashboard</span>
                    </a>
                    <a href="/admin/analytics" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->is('admin/analytics*') ? 'bg-brand-orange text-white font-bold shadow-md shadow-orange-500/20' : 'text-white/75 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        <span>2. Analytics &amp; Traffic</span>
                    </a>
                </div>
            </div>

            <!-- Group 2: ISP Services & Leads -->
            <div>
                <p class="px-3 text-[10px] font-bold uppercase tracking-wider text-white/40 mb-2">ISP Services &amp; Leads</p>
                <div class="space-y-1">
                    <a href="/admin/plans" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->is('admin/plans*') ? 'bg-brand-orange text-white font-bold' : 'text-white/75 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>3. Broadband Plans</span>
                    </a>
                    <a href="/admin/localities" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->is('admin/localities*') ? 'bg-brand-orange text-white font-bold' : 'text-white/75 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>4. Coverage &amp; Localities</span>
                    </a>
                    <a href="/admin/enquiries" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->is('admin/enquiries*') ? 'bg-brand-orange text-white font-bold' : 'text-white/75 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                        <span>5. Customer Enquiries &amp; Leads</span>
                    </a>
                    <a href="/admin/support" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->is('admin/support*') ? 'bg-brand-orange text-white font-bold' : 'text-white/75 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <span>6. Support Requests</span>
                    </a>
                </div>
            </div>

            <!-- Group 3: Website CMS & Page Builder -->
            <div>
                <p class="px-3 text-[10px] font-bold uppercase tracking-wider text-white/40 mb-2">Website &amp; CMS</p>
                <div class="space-y-1">
                    <a href="/admin/builder" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->is('admin/builder*') ? 'bg-brand-orange text-white font-bold' : 'text-white/75 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                        <span>7. Website Builder</span>
                    </a>
                    <a href="/admin/pages" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->is('admin/pages*') ? 'bg-brand-orange text-white font-bold' : 'text-white/75 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>8. Pages</span>
                    </a>
                    <a href="/admin/sections" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->is('admin/sections*') ? 'bg-brand-orange text-white font-bold' : 'text-white/75 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <span>9. Homepage Sections</span>
                    </a>
                    <a href="/admin/popups" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->is('admin/popups*') ? 'bg-brand-orange text-white font-bold' : 'text-white/75 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                        <span>10. Special Offers &amp; Popups</span>
                    </a>
                    <a href="/admin/brands" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->is('admin/brands*') ? 'bg-brand-orange text-white font-bold' : 'text-white/75 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span>11. Trusted Brands</span>
                    </a>
                    <a href="/admin/blog" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->is('admin/blog*') ? 'bg-brand-orange text-white font-bold' : 'text-white/75 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        <span>12. Blog &amp; News</span>
                    </a>
                    <a href="/admin/media" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->is('admin/media*') ? 'bg-brand-orange text-white font-bold' : 'text-white/75 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>13. Media Library</span>
                    </a>
                    <a href="/admin/tools" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->is('admin/tools*') ? 'bg-brand-orange text-white font-bold' : 'text-white/75 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>14. Internet Tools</span>
                    </a>
                </div>
            </div>

            <!-- Group 4: Design & Menus -->
            <div>
                <p class="px-3 text-[10px] font-bold uppercase tracking-wider text-white/40 mb-2">Design &amp; Menus</p>
                <div class="space-y-1">
                    <a href="/admin/header" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->is('admin/header*') ? 'bg-brand-orange text-white font-bold' : 'text-white/75 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                        <span>15. Header &amp; Navigation</span>
                    </a>
                    <a href="/admin/footer" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->is('admin/footer*') ? 'bg-brand-orange text-white font-bold' : 'text-white/75 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                        <span>16. Footer Manager</span>
                    </a>
                    <a href="/admin/links" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->is('admin/links*') ? 'bg-brand-orange text-white font-bold' : 'text-white/75 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        <span>17. Menus &amp; Custom Links</span>
                    </a>
                    <a href="/admin/theme" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->is('admin/theme*') ? 'bg-brand-orange text-white font-bold' : 'text-white/75 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4 5 5 0 013-4.5V11a7 7 0 1114 0v1.5A5 5 0 0121 17a4 4 0 01-4 4H7z"/></svg>
                        <span>18. Theme &amp; Appearance</span>
                    </a>
                    <a href="/admin/languages" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->is('admin/languages*') ? 'bg-brand-orange text-white font-bold' : 'text-white/75 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                        <span>19. Languages</span>
                    </a>
                </div>
            </div>

            <!-- Group 5: AI & Communications -->
            <div>
                <p class="px-3 text-[10px] font-bold uppercase tracking-wider text-white/40 mb-2">AI &amp; Communications</p>
                <div class="space-y-1">
                    <a href="/admin/faqs" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->is('admin/faqs*') ? 'bg-brand-orange text-white font-bold' : 'text-white/75 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        <span>20. Gemini AI Chatbot &amp; FAQs</span>
                    </a>
                    <a href="/admin/notifications" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->is('admin/notifications*') ? 'bg-brand-orange text-white font-bold' : 'text-white/75 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <span>21. Notifications</span>
                    </a>
                </div>
            </div>

            <!-- Group 6: Administration & Security -->
            <div>
                <p class="px-3 text-[10px] font-bold uppercase tracking-wider text-white/40 mb-2">Settings &amp; System</p>
                <div class="space-y-1">
                    <a href="/admin/seo" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->is('admin/seo*') ? 'bg-brand-orange text-white font-bold' : 'text-white/75 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span>22. SEO Manager</span>
                    </a>
                    <a href="/admin/settings" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->is('admin/settings*') ? 'bg-brand-orange text-white font-bold' : 'text-white/75 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>23. General &amp; WhatsApp Settings</span>
                    </a>
                    <a href="/admin/users" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->is('admin/users*') ? 'bg-brand-orange text-white font-bold' : 'text-white/75 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <span>24. Users &amp; Roles</span>
                    </a>
                    <a href="/admin/backups" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->is('admin/backups*') ? 'bg-brand-orange text-white font-bold' : 'text-white/75 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        <span>25. Backup &amp; Restore</span>
                    </a>
                    <a href="/admin/security" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->is('admin/security*') ? 'bg-brand-orange text-white font-bold' : 'text-white/75 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span>26. Security Settings</span>
                    </a>
                    <a href="/admin/system" class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->is('admin/system*') ? 'bg-brand-orange text-white font-bold' : 'text-white/75 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>27. System Info</span>
                    </a>
                </div>
            </div>
        </nav>

        <!-- Sidebar Footer -->
        <div class="p-3 border-t border-white/10 flex items-center justify-between text-xs">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                <span class="text-white/70 font-medium truncate max-w-[120px]">{{ auth()->user()->name ?? 'Administrator' }}</span>
            </div>
            <form action="/admin/logout" method="POST">
                @csrf
                <button type="submit" title="Logout" class="text-white/60 hover:text-white p-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col lg:pl-64 min-w-0">
        <!-- Top Navbar -->
        <header class="h-16 bg-white border-b border-slate-200 px-4 sm:px-6 flex items-center justify-between sticky top-0 z-30 shadow-xs">
            <div class="flex items-center gap-3">
                <button type="button" onclick="toggleSidebar()" class="lg:hidden p-1.5 rounded-lg text-slate-500 hover:bg-slate-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div class="text-xs sm:text-sm font-bold text-brand-navy flex items-center gap-2">
                    <span>@yield('header_title', 'Dashboard')</span>
                </div>
            </div>

            <!-- Right Actions -->
            <div class="flex items-center gap-3">
                <a href="/" target="_blank" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-brand-blue bg-blue-50 hover:bg-blue-100 transition">
                    <span>View Public Website</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
                <div class="h-4 w-px bg-slate-200 hidden sm:block"></div>
                <div class="text-xs font-semibold text-slate-500 hidden md:block">
                    Peddapuram (IST): <span id="live-clock">--:--:--</span>
                </div>
                <!-- Profile Menu -->
                <div class="flex items-center gap-2 pl-2">
                    <div class="w-8 h-8 rounded-full bg-brand-blue text-white flex items-center justify-center font-bold text-xs uppercase shadow-sm">
                        {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Workspace -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm font-semibold flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">&times;</button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script>
        function toggleSidebar() {
            const sb = document.getElementById('sidebar');
            sb.classList.toggle('-translate-x-full');
        }

        // Live Clock (Asia/Kolkata)
        function updateClock() {
            const now = new Date();
            const str = now.toLocaleTimeString('en-US', { timeZone: 'Asia/Kolkata', hour12: true });
            const el = document.getElementById('live-clock');
            if (el) el.innerText = str;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
    @yield('scripts')
</body>
</html>
