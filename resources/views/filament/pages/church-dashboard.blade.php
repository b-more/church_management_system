{{-- resources/views/filament/pages/church-dashboard.blade.php --}}
<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Welcome Header --}}
        <div class="bg-gradient-to-r from-primary-600 to-primary-800 dark:from-primary-700 dark:to-primary-900 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white">His Kingdom Church</h1>
                    <p class="text-primary-100 mt-2">{{ now()->format('l, F j, Y') }} Dashboard</p>
                </div>
                <div class="text-right hidden md:block">
                    <div class="text-sm text-primary-100">Welcome back</div>
                    <div class="font-semibold text-lg text-white">{{ auth()->user()->name }}</div>
                </div>
            </div>
        </div>

        {{-- Financial Overview --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Financial Overview</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center p-4 bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-lg border border-green-200 dark:border-green-800">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $today_income }}</div>
                    <div class="text-sm text-green-700 dark:text-green-300 mt-1">Today's Income</div>
                </div>
                <div class="text-center p-4 bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-lg border border-red-200 dark:border-red-800">
                    <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $today_expenses }}</div>
                    <div class="text-sm text-red-700 dark:text-red-300 mt-1">Today's Expenses</div>
                </div>
                <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-lg border border-blue-200 dark:border-blue-800">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $monthly_income }}</div>
                    <div class="text-sm text-blue-700 dark:text-blue-300 mt-1">Monthly Income</div>
                </div>
                <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-lg border border-purple-200 dark:border-purple-800">
                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $monthly_expenses }}</div>
                    <div class="text-sm text-purple-700 dark:text-purple-300 mt-1">Monthly Expenses</div>
                </div>
            </div>
        </div>

        {{-- Members & Attendance Overview --}}
        <div class="space-y-6">
            {{-- Members Section --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Membership</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-800 dark:to-slate-700 rounded-lg border border-slate-200 dark:border-slate-600">
                        <div class="text-xl font-bold text-slate-900 dark:text-white">{{ $total_members }}</div>
                        <div class="text-xs text-slate-700 dark:text-slate-300 mt-1">Total Members</div>
                    </div>
                    <div class="text-center p-4 bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/30 dark:to-green-800/30 rounded-lg border border-green-200 dark:border-green-700">
                        <div class="text-xl font-bold text-green-700 dark:text-green-300">{{ $active_members }}</div>
                        <div class="text-xs text-green-600 dark:text-green-400 mt-1">Active Members</div>
                    </div>
                    <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/30 rounded-lg border border-blue-200 dark:border-blue-700">
                        <div class="text-xl font-bold text-blue-700 dark:text-blue-300">{{ $new_members_month }}</div>
                        <div class="text-xs text-blue-600 dark:text-blue-400 mt-1">New This Month</div>
                    </div>
                    <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/30 dark:to-purple-800/30 rounded-lg border border-purple-200 dark:border-purple-700">
                        <div class="text-xl font-bold text-purple-700 dark:text-purple-300">{{ $new_members_week }}</div>
                        <div class="text-xs text-purple-600 dark:text-purple-400 mt-1">New This Week</div>
                    </div>
                </div>
            </div>

            {{-- Attendance Section --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Attendance</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-800 dark:to-slate-700 rounded-lg border border-slate-200 dark:border-slate-600">
                        <div class="text-xl font-bold text-slate-900 dark:text-white">{{ $today_attendance }}</div>
                        <div class="text-xs text-slate-700 dark:text-slate-300 mt-1">Today's Total</div>
                    </div>
                    <div class="text-center p-4 bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/30 dark:to-yellow-800/30 rounded-lg border border-yellow-200 dark:border-yellow-700">
                        <div class="text-xl font-bold text-yellow-700 dark:text-yellow-300">{{ $today_visitors }}</div>
                        <div class="text-xs text-yellow-600 dark:text-yellow-400 mt-1">Today's Visitors</div>
                    </div>
                    <div class="text-center p-4 bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/30 dark:to-red-800/30 rounded-lg border border-red-200 dark:border-red-700">
                        <div class="text-xl font-bold text-red-700 dark:text-red-300">{{ $pending_followups }}</div>
                        <div class="text-xs text-red-600 dark:text-red-400 mt-1">Pending Follow-ups</div>
                    </div>
                    <div class="text-center p-4 bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900/30 dark:to-indigo-800/30 rounded-lg border border-indigo-200 dark:border-indigo-700">
                        <div class="text-xl font-bold text-indigo-700 dark:text-indigo-300">{{ $week_attendance }}</div>
                        <div class="text-xs text-indigo-600 dark:text-indigo-400 mt-1">This Week Total</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Events & Ministry Overview --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Events & Ministry</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-lg">
                    <div class="text-xl font-bold text-orange-600 dark:text-orange-400">{{ $upcoming_events }}</div>
                    <div class="text-sm text-orange-700 dark:text-orange-300">Upcoming Events</div>
                </div>
                <div class="text-center p-4 bg-gradient-to-br from-teal-50 to-teal-100 dark:from-teal-900/20 dark:to-teal-800/20 rounded-lg">
                    <div class="text-xl font-bold text-teal-600 dark:text-teal-400">{{ $events_this_week }}</div>
                    <div class="text-sm text-teal-700 dark:text-teal-300">Events This Week</div>
                </div>
                <div class="text-center p-4 bg-gradient-to-br from-pink-50 to-pink-100 dark:from-pink-900/20 dark:to-pink-800/20 rounded-lg">
                    <div class="text-xl font-bold text-pink-600 dark:text-pink-400">{{ $active_cell_groups }}</div>
                    <div class="text-sm text-pink-700 dark:text-pink-300">Active Cell Groups</div>
                </div>
                <div class="text-center p-4 bg-gradient-to-br from-cyan-50 to-cyan-100 dark:from-cyan-900/20 dark:to-cyan-800/20 rounded-lg">
                    <div class="text-xl font-bold text-cyan-600 dark:text-cyan-400">{{ $total_branches }}</div>
                    <div class="text-sm text-cyan-700 dark:text-cyan-300">Total Branches</div>
                </div>
            </div>
        </div>

        {{-- Events & Ministry Overview --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Events & Ministry</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/30 dark:to-orange-800/30 rounded-lg border border-orange-200 dark:border-orange-700">
                    <div class="text-xl font-bold text-orange-700 dark:text-orange-300">{{ $upcoming_events }}</div>
                    <div class="text-xs text-orange-600 dark:text-orange-400 mt-1">Upcoming Events</div>
                </div>
                <div class="text-center p-4 bg-gradient-to-br from-teal-50 to-teal-100 dark:from-teal-900/30 dark:to-teal-800/30 rounded-lg border border-teal-200 dark:border-teal-700">
                    <div class="text-xl font-bold text-teal-700 dark:text-teal-300">{{ $events_this_week }}</div>
                    <div class="text-xs text-teal-600 dark:text-teal-400 mt-1">Events This Week</div>
                </div>
                <div class="text-center p-4 bg-gradient-to-br from-pink-50 to-pink-100 dark:from-pink-900/30 dark:to-pink-800/30 rounded-lg border border-pink-200 dark:border-pink-700">
                    <div class="text-xl font-bold text-pink-700 dark:text-pink-300">{{ $active_cell_groups }}</div>
                    <div class="text-xs text-pink-600 dark:text-pink-400 mt-1">Active Cell Groups</div>
                </div>
                <div class="text-center p-4 bg-gradient-to-br from-cyan-50 to-cyan-100 dark:from-cyan-900/30 dark:to-cyan-800/30 rounded-lg border border-cyan-200 dark:border-cyan-700">
                    <div class="text-xl font-bold text-cyan-700 dark:text-cyan-300">{{ $total_branches }}</div>
                    <div class="text-xs text-cyan-600 dark:text-cyan-400 mt-1">Total Branches</div>
                </div>
            </div>
        </div>

        {{-- Recent Transactions Table --}}
        {{-- <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Transactions</h3>
                <a href="/admin/transactions" class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300">
                    View All →
                </a>
            </div>
            {{ $this->table }}
        </div> --}}

        {{-- Quick Stats Summary --}}
        <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Summary</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-center">
                <div>
                    <div class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                        {{ number_format((($active_members / max($total_members, 1)) * 100), 1) }}%
                    </div>
                    <div class="text-xs text-gray-600 dark:text-gray-400">Active Rate</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                        {{ $today_attendance > 0 ? number_format((($today_visitors / $today_attendance) * 100), 1) : 0 }}%
                    </div>
                    <div class="text-xs text-gray-600 dark:text-gray-400">Visitor Rate</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                        {{ $active_cell_groups > 0 ? number_format(($active_members / $active_cell_groups), 1) : 0 }}
                    </div>
                    <div class="text-xs text-gray-600 dark:text-gray-400">Avg per Cell</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-orange-600 dark:text-orange-400">
                        {{ $pending_followups }}
                    </div>
                    <div class="text-xs text-gray-600 dark:text-gray-400">Follow-ups</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Enhanced Styles for Mobile & Dark Mode --}}
    <style>
        /* Ensure smooth transitions for dark mode */
        * {
            transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
        }

        /* Mobile-first responsive design */
        @media (max-width: 640px) {
            .grid {
                grid-template-columns: 1fr !important;
                gap: 1rem !important;
            }

            .text-xl {
                font-size: 1.125rem !important;
            }

            .text-xs {
                font-size: 0.75rem !important;
            }
        }

        /* Tablet responsive adjustments */
        @media (min-width: 641px) and (max-width: 1024px) {
            .lg\:grid-cols-4 {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            }
        }

        /* Enhanced card styling */
        .rounded-lg {
            border-radius: 0.75rem;
            backdrop-filter: blur(10px);
        }

        /* Better text contrast */
        .text-slate-900 {
            color: rgb(15 23 42) !important;
        }

        .dark .text-slate-900 {
            color: rgb(248 250 252) !important;
        }

        .text-slate-700 {
            color: rgb(51 65 85) !important;
        }

        .dark .text-slate-700 {
            color: rgb(203 213 225) !important;
        }

        /* Card hover effects */
        .bg-gradient-to-br:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .dark .bg-gradient-to-br:hover {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
        }

        /* Table styling improvements */
        .fi-ta-table {
            border-radius: 0.75rem;
            overflow: hidden;
        }

        /* Badge improvements */
        .fi-badge {
            font-weight: 500;
            font-size: 0.75rem;
        }

        /* Improved spacing for mobile */
        @media (max-width: 640px) {
            .space-y-6 > * + * {
                margin-top: 1.5rem !important;
            }

            .p-6 {
                padding: 1rem !important;
            }

            .p-4 {
                padding: 0.75rem !important;
            }
        }

        /* Better focus states for accessibility */
        *:focus {
            outline: 2px solid rgb(59 130 246 / 0.5);
            outline-offset: 2px;
            border-radius: 0.375rem;
        }

        /* Dark mode specific improvements */
        .dark .bg-slate-800 {
            background-color: rgb(30 41 59 / 0.8) !important;
        }

        .dark .bg-slate-700 {
            background-color: rgb(51 65 85 / 0.6) !important;
        }

        /* Gradient improvements for dark mode */
        .dark .from-green-900\/30 {
            --tw-gradient-from: rgb(20 83 45 / 0.4) !important;
        }

        .dark .to-green-800\/30 {
            --tw-gradient-to: rgb(22 101 52 / 0.4) !important;
        }

        .dark .from-blue-900\/30 {
            --tw-gradient-from: rgb(30 58 138 / 0.4) !important;
        }

        .dark .to-blue-800\/30 {
            --tw-gradient-to: rgb(30 64 175 / 0.4) !important;
        }

        .dark .from-purple-900\/30 {
            --tw-gradient-from: rgb(88 28 135 / 0.4) !important;
        }

        .dark .to-purple-800\/30 {
            --tw-gradient-to: rgb(107 33 168 / 0.4) !important;
        }

        .dark .from-yellow-900\/30 {
            --tw-gradient-from: rgb(113 63 18 / 0.4) !important;
        }

        .dark .to-yellow-800\/30 {
            --tw-gradient-to: rgb(133 77 14 / 0.4) !important;
        }

        .dark .from-red-900\/30 {
            --tw-gradient-from: rgb(127 29 29 / 0.4) !important;
        }

        .dark .to-red-800\/30 {
            --tw-gradient-to: rgb(153 27 27 / 0.4) !important;
        }

        .dark .from-indigo-900\/30 {
            --tw-gradient-from: rgb(49 46 129 / 0.4) !important;
        }

        .dark .to-indigo-800\/30 {
            --tw-gradient-to: rgb(55 48 163 / 0.4) !important;
        }
    </style>
</x-filament-panels::page>
