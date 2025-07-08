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

        {{-- Sunday Duty Roster Widget --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Sunday Service Assignments</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $next_sunday_date }}</p>
                </div>
                <div class="flex items-center space-x-2">
                    @if($sunday_duty_roster)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Assigned
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            Pending
                        </span>
                    @endif
                    <a href="/admin/duty-rosters" class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300">
                        Manage →
                    </a>
                </div>
            </div>

            @if($sunday_duty_roster)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    {{-- Service Host --}}
                    <div class="flex items-start space-x-3 p-3 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-lg border border-blue-200 dark:border-blue-800">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-blue-800 dark:text-blue-200 uppercase tracking-wide">Service Host</p>
                            <p class="text-sm font-semibold text-blue-900 dark:text-blue-100 mt-1">
                                {{ $sunday_duty_roster->serviceHost ?
                                   $sunday_duty_roster->serviceHost->title . ' ' . $sunday_duty_roster->serviceHost->first_name . ' ' . $sunday_duty_roster->serviceHost->last_name :
                                   'Not Assigned' }}
                            </p>
                        </div>
                    </div>

                    {{-- Worship Leader --}}
                    <div class="flex items-start space-x-3 p-3 bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-lg border border-purple-200 dark:border-purple-800">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v9.114A4.369 4.369 0 005 14c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V7.82l8-1.6v5.894A4.37 4.37 0 0015 12c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V3z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-purple-800 dark:text-purple-200 uppercase tracking-wide">Worship Leader</p>
                            <p class="text-sm font-semibold text-purple-900 dark:text-purple-100 mt-1">
                                {{ $sunday_duty_roster->worshipLeader ?
                                   $sunday_duty_roster->worshipLeader->title . ' ' . $sunday_duty_roster->worshipLeader->first_name . ' ' . $sunday_duty_roster->worshipLeader->last_name :
                                   'Not Assigned' }}
                            </p>
                        </div>
                    </div>

                    {{-- Preacher --}}
                    <div class="flex items-start space-x-3 p-3 bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-lg border border-green-200 dark:border-green-800">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7 4a3 3 0 016 0v4a3 3 0 11-6 0V4zm4 10.93A7.001 7.001 0 0017 8a1 1 0 10-2 0A5 5 0 015 8a1 1 0 00-2 0 7.001 7.001 0 006 6.93V17H6a1 1 0 100 2h8a1 1 0 100-2h-3v-2.07z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-green-800 dark:text-green-200 uppercase tracking-wide">Preacher</p>
                            <p class="text-sm font-semibold text-green-900 dark:text-green-100 mt-1">
                                @if($sunday_duty_roster->preacher_type === 'visiting')
                                    {{ $sunday_duty_roster->visiting_preacher_name ?? 'Not Assigned' }}
                                    <span class="text-xs text-green-600 dark:text-green-400 block">(Visiting)</span>
                                @else
                                    {{ $sunday_duty_roster->preacher ?
                                       $sunday_duty_roster->preacher->title . ' ' . $sunday_duty_roster->preacher->first_name . ' ' . $sunday_duty_roster->preacher->last_name :
                                       'Not Assigned' }}
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- Intercession Leader --}}
                    <div class="flex items-start space-x-3 p-3 bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-yellow-800 dark:text-yellow-200 uppercase tracking-wide">Intercession</p>
                            <p class="text-sm font-semibold text-yellow-900 dark:text-yellow-100 mt-1">
                                {{ $sunday_duty_roster->intercessionLeader ?
                                   $sunday_duty_roster->intercessionLeader->title . ' ' . $sunday_duty_roster->intercessionLeader->first_name . ' ' . $sunday_duty_roster->intercessionLeader->last_name :
                                   'Not Assigned' }}
                            </p>
                        </div>
                    </div>

                    {{-- Sunday School Teacher --}}
                    <div class="flex items-start space-x-3 p-3 bg-gradient-to-br from-pink-50 to-pink-100 dark:from-pink-900/20 dark:to-pink-800/20 rounded-lg border border-pink-200 dark:border-pink-800">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-pink-500 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-pink-800 dark:text-pink-200 uppercase tracking-wide">Sunday School</p>
                            <p class="text-sm font-semibold text-pink-900 dark:text-pink-100 mt-1">
                                {{ $sunday_duty_roster->sundaySchoolTeacher ?
                                   $sunday_duty_roster->sundaySchoolTeacher->title . ' ' . $sunday_duty_roster->sundaySchoolTeacher->first_name . ' ' . $sunday_duty_roster->sundaySchoolTeacher->last_name :
                                   'Not Assigned' }}
                            </p>
                        </div>
                    </div>

                    {{-- Special Song Group --}}
                    <div class="flex items-start space-x-3 p-3 bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900/20 dark:to-indigo-800/20 rounded-lg border border-indigo-200 dark:border-indigo-800">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12a1 1 0 002 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 6.414V12zM2 14a2 2 0 002 2h12a2 2 0 002-2v-2a2 2 0 00-2-2H4a2 2 0 00-2 2v2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-indigo-800 dark:text-indigo-200 uppercase tracking-wide">Special Song</p>
                            <p class="text-sm font-semibold text-indigo-900 dark:text-indigo-100 mt-1">
                                {{ $sunday_duty_roster->special_song_group ?? 'Not Assigned' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Service Times --}}
                <div class="mt-6 p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="text-center">
                                <p class="text-xs text-gray-600 dark:text-gray-400">Service Type</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $sunday_duty_roster->service_type }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-gray-600 dark:text-gray-400">Start Time</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($sunday_duty_roster->service_time)->format('g:i A') }}</p>
                            </div>
                            @if($sunday_duty_roster->end_time)
                            <div class="text-center">
                                <p class="text-xs text-gray-600 dark:text-gray-400">End Time</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($sunday_duty_roster->end_time)->format('g:i A') }}</p>
                            </div>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-600 dark:text-gray-400">Branch</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $sunday_duty_roster->branch->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            @else
                {{-- No Assignments Yet --}}
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No assignments yet</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Create duty roster assignments for this Sunday's service.</p>
                    <div class="mt-6">
                        <a href="/admin/duty-rosters/create" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Create Duty Roster
                        </a>
                    </div>
                </div>
            @endif
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
