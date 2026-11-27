@extends('admin.layouts.app')

@section('title', 'Security Settings & Audit Logs')
@section('header_title', 'System Security, Session Controls & Audit Logs')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-xl font-extrabold text-brand-navy">Security & Audit Logs</h1>
        <p class="text-xs text-slate-500 mt-0.5">Review administrator access logs, CSRF protection policies, session security and system activity.</p>
    </div>

    <!-- Security Badges -->
    <div class="grid sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs">
            <span class="text-xs font-semibold text-slate-500">CSRF Defense</span>
            <div class="flex items-center gap-2 mt-1">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <span class="font-bold text-sm text-slate-800">Active</span>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs">
            <span class="text-xs font-semibold text-slate-500">Brute-Force Throttling</span>
            <div class="flex items-center gap-2 mt-1">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <span class="font-bold text-sm text-slate-800">5 req/min</span>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs">
            <span class="text-xs font-semibold text-slate-500">Password Hashing</span>
            <div class="flex items-center gap-2 mt-1">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <span class="font-bold text-sm text-slate-800">Bcrypt / Argon2</span>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs">
            <span class="text-xs font-semibold text-slate-500">Installer Lock</span>
            <div class="flex items-center gap-2 mt-1">
                <span class="w-2.5 h-2.5 rounded-full {{ file_exists(storage_path('installed.lock')) ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                <span class="font-bold text-sm text-slate-800">{{ file_exists(storage_path('installed.lock')) ? 'Permanent Lock' : 'Unlocked' }}</span>
            </div>
        </div>
    </div>

    <!-- Audit Log Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Security Audit Trail</h3>
            <span class="text-xs text-slate-400">Total Recorded Actions: {{ $logs->total() }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[10px] border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4 font-bold">Admin User</th>
                        <th class="py-3 px-4 font-bold">Action</th>
                        <th class="py-3 px-4 font-bold">Module</th>
                        <th class="py-3 px-4 font-bold">Description</th>
                        <th class="py-3 px-4 font-bold">IP Address</th>
                        <th class="py-3 px-4 font-bold">Timestamp</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $l)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3 px-4 font-bold text-slate-800">{{ $l->user->name ?? 'System / Anonymous' }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-slate-100 text-slate-700 uppercase">
                                    {{ $l->action }}
                                </span>
                            </td>
                            <td class="py-3 px-4 font-semibold text-brand-blue">{{ $l->module }}</td>
                            <td class="py-3 px-4 text-slate-600 max-w-sm truncate">{{ $l->description }}</td>
                            <td class="py-3 px-4 font-mono text-slate-400 text-[11px]">{{ $l->ip_address }}</td>
                            <td class="py-3 px-4 text-slate-400">{{ \Carbon\Carbon::parse($l->created_at)->format('M d, H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">No security audit entries recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
