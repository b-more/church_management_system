{{-- resources/views/filament/pages/tabbed-dashboard.blade.php --}}
<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Welcome Header --}}
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">His Kingdom Church Dashboard</h1>
                    <p class="text-blue-100 mt-1">{{ now()->format('l, F j, Y') }}</p>
                </div>
                <div class="text-right hidden md:block">
                    <div class="text-sm text-blue-100">Welcome back</div>
                    <div class="font-semibold">{{ auth()->user()->name }}</div>
                </div>
            </div>
        </div>

        {{-- Dashboard Tabs --}}
        <div x-data="{ activeTab: 'overview' }" class="bg-white rounded-xl shadow-sm border border-gray-200">
            {{-- Tab Navigation --}}
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8 px-6" aria-label="Tabs">
                    <button @click="activeTab = 'overview'"
                            :class="activeTab === 'overview' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        Overview
                    </button>
                    <button @click="activeTab = 'finances'"
                            :class="activeTab === 'finances' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        Finances
                    </button>
                    <button @click="activeTab = 'members'"
                            :class="activeTab === 'members' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        Members
                    </button>
                    <button @click="activeTab = 'actions'"
                            :class="activeTab === 'actions' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        Quick Actions
                    </button>
                </nav>
            </div>

            {{-- Tab Content --}}
            <div class="p-6">
                {{-- Overview Tab --}}
                <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="text-center p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-lg">
                            <div class="text-3xl font-bold text-green-600">ZMW 25,430</div>
                            <div class="text-sm text-green-700 mt-1">Today's Income</div>
                            <div class="text-xs text-green-600 mt-2">↗ +12% from yesterday</div>
                        </div>
                        <div class="text-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg">
                            <div class="text-3xl font-bold text-blue-600">347</div>
                            <div class="text-sm text-blue-700 mt-1">Active Members</div>
                            <div class="text-xs text-blue-600 mt-2">+5 this month</div>
                        </div>
                        <div class="text-center p-6 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg">
                            <div class="text-3xl font-bold text-purple-600">89</div>
                            <div class="text-sm text-purple-700 mt-1">Today's Attendance</div>
                            <div class="text-xs text-purple-600 mt-2">15 visitors</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="font-semibold text-gray-900 mb-4">Upcoming Events</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Sunday Service</span>
                                    <span class="text-sm font-medium">Tomorrow, 9:00 AM</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Youth Meeting</span>
                                    <span class="text-sm font-medium">Friday, 6:00 PM</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Bible Study</span>
                                    <span class="text-sm font-medium">Wednesday, 7:00 PM</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="font-semibold text-gray-900 mb-4">Pending Tasks</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Follow-up calls</span>
                                    <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full">12 pending</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Event planning</span>
                                    <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">3 in progress</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Membership reviews</span>
                                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">5 scheduled</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Finances Tab --}}
                <div x-show="activeTab === 'finances'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            @livewire(\App\Filament\Widgets\MonthlyIncomeChart::class)
                        </div>
                        <div>
                            @livewire(\App\Filament\Widgets\FinancialTargetsWidget::class)
                        </div>
                    </div>
                </div>

                {{-- Members Tab --}}
                <div x-show="activeTab === 'members'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="font-semibold text-gray-900 mb-4">Member Statistics</h3>
                            @livewire(\App\Filament\Widgets\MembershipOverviewStats::class)
                        </div>
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="font-semibold text-gray-900 mb-4">Attendance Overview</h3>
                            @livewire(\App\Filament\Widgets\AttendanceOverviewStats::class)
                        </div>
                    </div>
                </div>

                {{-- Quick Actions Tab --}}
                <div x-show="activeTab === 'actions'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="max-w-2xl mx-auto">
                        @livewire(\App\Filament\Widgets\QuickActionsWidget::class)
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Clean widget styling */
        .fi-wi-stats-overview-stat {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
        }
    </style>
</x-filament-panels::page>
