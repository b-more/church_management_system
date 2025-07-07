{{-- resources/views/filament/widgets/recent-activities.blade.php --}}
<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Recent Activities
        </x-slot>

        @php
            $activities = $this->getRecentActivities();
        @endphp

        <div class="space-y-3 max-h-96 overflow-y-auto">
            @forelse($activities as $activity)
                <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    {{-- Activity Icon --}}
                    <div class="flex-shrink-0 mt-0.5">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center
                            @if($activity['color'] === 'success') bg-green-100 text-green-600
                            @elseif($activity['color'] === 'info') bg-blue-100 text-blue-600
                            @elseif($activity['color'] === 'warning') bg-yellow-100 text-yellow-600
                            @elseif($activity['color'] === 'danger') bg-red-100 text-red-600
                            @else bg-gray-100 text-gray-600
                            @endif">
                            @if($activity['type'] === 'member')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            @elseif($activity['type'] === 'transaction')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                            @elseif($activity['type'] === 'attendance')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            @elseif($activity['type'] === 'event')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @endif
                        </div>
                    </div>

                    {{-- Activity Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $activity['title'] }}
                                </p>
                                <p class="text-xs text-gray-600 mt-1">
                                    {{ $activity['description'] }}
                                </p>
                            </div>
                            <div class="flex-shrink-0 ml-4">
                                <span class="text-xs text-gray-500">
                                    {{ $activity['time']->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    <div class="text-gray-400 mb-2">
                        <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p>No recent activities found</p>
                    <p class="text-xs text-gray-400 mt-1">Activities will appear here as they occur</p>
                </div>
            @endforelse
        </div>

        {{-- Activity Legend --}}
        <div class="mt-4 pt-4 border-t border-gray-200">
            <div class="flex items-center justify-between text-xs text-gray-500">
                <span>Showing activities from the last 7 days</span>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-1">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <span>Members</span>
                    </div>
                    <div class="flex items-center space-x-1">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        <span>Finance</span>
                    </div>
                    <div class="flex items-center space-x-1">
                        <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                        <span>Events</span>
                    </div>
                    <div class="flex items-center space-x-1">
                        <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                        <span>Attendance</span>
                    </div>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
