{{-- resources/views/filament/widgets/cell-groups-overview.blade.php --}}
<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Cell Groups Overview
        </x-slot>

        @php
            $data = $this->getCellGroupsData();
            $cellGroups = $data['cell_groups'];
            $stats = $data['stats'];
        @endphp

        {{-- Summary Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['total_groups'] }}</div>
                <div class="text-sm text-gray-600">Active Groups</div>
            </div>
            <div class="bg-green-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-green-600">{{ $stats['total_members'] }}</div>
                <div class="text-sm text-gray-600">Total Members</div>
            </div>
            <div class="bg-yellow-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-yellow-600">{{ number_format($stats['avg_group_size'], 1) }}</div>
                <div class="text-sm text-gray-600">Avg Group Size</div>
            </div>
            <div class="bg-red-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-red-600">{{ $stats['groups_needing_attention'] }}</div>
                <div class="text-sm text-gray-600">Need Attention</div>
            </div>
        </div>

        {{-- Cell Groups List --}}
        <div class="space-y-3 max-h-96 overflow-y-auto">
            @forelse($cellGroups as $group)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex-1">
                        <div class="flex items-center gap-3">
                            <h4 class="font-semibold text-gray-900">{{ $group['name'] }}</h4>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($group['health_status']['color'] === 'success') bg-green-100 text-green-800
                                @elseif($group['health_status']['color'] === 'warning') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ $group['health_status']['status'] }}
                            </span>
                        </div>
                        <div class="text-sm text-gray-600 mt-1">
                            Leader: {{ $group['leader'] }} •
                            {{ $group['member_count'] }} members •
                            @if($group['meeting_day']) {{ $group['meeting_day'] }} @endif
                            @if($group['meeting_time']) at {{ $group['meeting_time'] }} @endif
                        </div>
                        @if($group['location'])
                            <div class="text-xs text-gray-500">📍 {{ $group['location'] }}</div>
                        @endif
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-bold text-gray-900">{{ $group['member_count'] }}</div>
                        <div class="text-xs text-gray-500">members</div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    <div class="text-gray-400 mb-2">
                        <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM9 9a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <p>No active cell groups found</p>
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
