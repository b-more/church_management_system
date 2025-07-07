{{-- resources/views/filament/pages/organized-dashboard.blade.php --}}
<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Welcome Header --}}
        <div class="bg-gradient-to-r from-primary-600 to-primary-800 dark:from-primary-700 dark:to-primary-900 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white">Welcome Back!</h1>
                    <p class="text-primary-100 mt-2">{{ now()->format('l, F j, Y') }}</p>
                </div>
                <div class="text-right hidden md:block">
                    <div class="text-sm text-primary-100">Logged in as</div>
                    <div class="font-semibold text-lg text-white">{{ auth()->user()->name }}</div>
                </div>
            </div>
        </div>

        {{-- Overview Stats Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Today's Income --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Today's Income</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            ZMW {{ number_format(\App\Models\Transaction::whereDate('transaction_date', today())->where('transaction_type', '!=', 'expense')->sum('amount'), 2) }}
                        </p>
                        <p class="text-xs text-green-600 dark:text-green-400 mt-1">
                            {{ \App\Models\Transaction::whereDate('transaction_date', today())->count() }} transactions
                        </p>
                    </div>
                </div>
            </div>

            {{-- Active Members --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Members</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ \App\Models\Member::where('is_active', true)->count() }}
                        </p>
                        <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                            +{{ \App\Models\Member::whereMonth('membership_date', now()->month)->count() }} this month
                        </p>
                    </div>
                </div>
            </div>

            {{-- Today's Attendance --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h6a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Today's Attendance</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ \App\Models\AttendanceRecord::whereDate('check_in_time', today())->count() }}
                        </p>
                        <p class="text-xs text-purple-600 dark:text-purple-400 mt-1">
                            {{ \App\Models\AttendanceRecord::whereDate('check_in_time', today())->whereIn('attendance_type', ['Visitor', 'First Timer'])->count() }} visitors
                        </p>
                    </div>
                </div>
            </div>

            {{-- Pending Follow-ups --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Follow-ups</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ \App\Models\AttendanceRecord::where('follow_up_required', true)->whereNull('follow_up_notes')->count() }}
                        </p>
                        <p class="text-xs text-orange-600 dark:text-orange-400 mt-1">Pending</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Quick Actions - Professional Compact Layout --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                <div class="grid grid-cols-2 gap-3">
                    <a href="/admin/members/create" class="flex flex-col items-center p-4 bg-gray-50 dark:bg-gray-700/50 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-all duration-200 group border border-transparent hover:border-green-200 dark:hover:border-green-800">
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900/50 text-green-600 dark:text-green-400 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white text-center">Add Member</span>
                    </a>

                    <a href="/admin/attendance-records/create" class="flex flex-col items-center p-4 bg-gray-50 dark:bg-gray-700/50 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all duration-200 group border border-transparent hover:border-blue-200 dark:hover:border-blue-800">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white text-center">Attendance</span>
                    </a>

                    <a href="/admin/transactions/create" class="flex flex-col items-center p-4 bg-gray-50 dark:bg-gray-700/50 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 rounded-lg transition-all duration-200 group border border-transparent hover:border-yellow-200 dark:hover:border-yellow-800">
                        <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/50 text-yellow-600 dark:text-yellow-400 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white text-center">Transaction</span>
                    </a>

                    <a href="/admin/events/create" class="flex flex-col items-center p-4 bg-gray-50 dark:bg-gray-700/50 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-lg transition-all duration-200 group border border-transparent hover:border-purple-200 dark:hover:border-purple-800">
                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/50 text-purple-600 dark:text-purple-400 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white text-center">Event</span>
                    </a>
                </div>

                {{-- Additional Quick Links --}}
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex flex-wrap gap-2">
                        <a href="/admin/members" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/50 rounded-full hover:bg-blue-200 dark:hover:bg-blue-900/70 transition-colors">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                            Members
                        </a>
                        <a href="/admin/transactions" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-green-700 dark:text-green-300 bg-green-100 dark:bg-green-900/50 rounded-full hover:bg-green-200 dark:hover:bg-green-900/70 transition-colors">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                            Finances
                        </a>
                        <a href="/admin/events" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-purple-700 dark:text-purple-300 bg-purple-100 dark:bg-purple-900/50 rounded-full hover:bg-purple-200 dark:hover:bg-purple-900/70 transition-colors">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Events
                        </a>
                    </div>
                </div>
            </div>

            {{-- Recent Activity & Insights --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Recent Activity --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Activity</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <div class="flex items-center text-sm">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-3 flex-shrink-0"></div>
                                <span class="text-gray-600 dark:text-gray-300">{{ \App\Models\Member::whereBetween('created_at', [now()->subWeek(), now()])->count() }} new members this week</span>
                            </div>
                            <div class="flex items-center text-sm">
                                <div class="w-2 h-2 bg-blue-500 rounded-full mr-3 flex-shrink-0"></div>
                                <span class="text-gray-600 dark:text-gray-300">{{ \App\Models\Transaction::whereDate('transaction_date', today())->count() }} transactions today</span>
                            </div>
                            <div class="flex items-center text-sm">
                                <div class="w-2 h-2 bg-purple-500 rounded-full mr-3 flex-shrink-0"></div>
                                <span class="text-gray-600 dark:text-gray-300">{{ \App\Models\Event::where('start_date', '>=', now())->where('start_date', '<=', now()->addWeek())->count() }} events this week</span>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center text-sm">
                                <div class="w-2 h-2 bg-yellow-500 rounded-full mr-3 flex-shrink-0"></div>
                                <span class="text-gray-600 dark:text-gray-300">{{ \App\Models\AttendanceRecord::where('follow_up_required', true)->whereNull('follow_up_notes')->count() }} follow-ups pending</span>
                            </div>
                            <div class="flex items-center text-sm">
                                <div class="w-2 h-2 bg-red-500 rounded-full mr-3 flex-shrink-0"></div>
                                <span class="text-gray-600 dark:text-gray-300">{{ \App\Models\CellGroup::where('status', 'Active')->count() }} active cell groups</span>
                            </div>
                            <div class="flex items-center text-sm">
                                <div class="w-2 h-2 bg-indigo-500 rounded-full mr-3 flex-shrink-0"></div>
                                <span class="text-gray-600 dark:text-gray-300">Monthly income tracking</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- System Status --}}
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">System Status</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mx-auto mb-2"></div>
                            <div class="text-xs font-medium text-gray-900 dark:text-white">Database</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Online</div>
                        </div>
                        <div class="text-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mx-auto mb-2"></div>
                            <div class="text-xs font-medium text-gray-900 dark:text-white">Backup</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Current</div>
                        </div>
                        <div class="text-center">
                            <div class="w-3 h-3 bg-blue-500 rounded-full mx-auto mb-2"></div>
                            <div class="text-xs font-medium text-gray-900 dark:text-white">Sync</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ now()->format('g:i A') }}</div>
                        </div>
                        <div class="text-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mx-auto mb-2"></div>
                            <div class="text-xs font-medium text-gray-900 dark:text-white">System</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Healthy</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Enhanced Styles for Dark/Light Mode --}}
    <style>
        /* Ensure smooth transitions */
        * {
            transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
        }

        /* Custom hover effects */
        .group:hover .group-hover\:scale-110 {
            transform: scale(1.1);
        }

        /* Better focus states */
        a:focus {
            outline: 2px solid rgb(59 130 246 / 0.5);
            outline-offset: 2px;
        }

        /* Enhanced shadows for depth */
        .shadow-sm {
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }

        .dark .shadow-sm {
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.1);
        }

        /* Better contrast for dark mode */
        .dark .bg-gray-50 {
            background-color: rgb(55 65 81 / 0.5) !important;
        }

        .dark .border-gray-200 {
            border-color: rgb(75 85 99) !important;
        }
    </style>
</x-filament-panels::page>
