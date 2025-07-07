{{-- resources/views/filament/widgets/financial-targets.blade.php --}}
<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Financial Targets - {{ $this->getFinancialTargets()['month'] }}
        </x-slot>

        @php
            $data = $this->getFinancialTargets();
            $targets = $data['targets'];
            $overall = $data['overall'];
        @endphp

        {{-- Overall Progress --}}
        <div class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg">
            <div class="flex items-center justify-between mb-2">
                <h4 class="text-lg font-semibold text-gray-900">Overall Progress</h4>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    @if($overall['status']['color'] === 'success') bg-green-100 text-green-800
                    @elseif($overall['status']['color'] === 'warning') bg-yellow-100 text-yellow-800
                    @else bg-red-100 text-red-800
                    @endif">
                    {{ $overall['status']['label'] }}
                </span>
            </div>
            <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                <span>ZMW {{ number_format($overall['actual'], 2) }} of ZMW {{ number_format($overall['target'], 2) }}</span>
                <span class="font-semibold">{{ $overall['percentage'] }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="h-3 rounded-full transition-all duration-300
                    @if($overall['percentage'] >= 100) bg-green-500
                    @elseif($overall['percentage'] >= 80) bg-blue-500
                    @elseif($overall['percentage'] >= 60) bg-yellow-500
                    @else bg-red-500
                    @endif"
                    style="width: {{ min(100, $overall['percentage']) }}%">
                </div>
            </div>
        </div>

        {{-- Individual Targets --}}
        <div class="space-y-4">
            @foreach($targets as $target)
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center justify-between mb-2">
                        <h5 class="font-medium text-gray-900">{{ $target['type'] }}</h5>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            @if($target['status']['color'] === 'success') bg-green-100 text-green-800
                            @elseif($target['status']['color'] === 'warning') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ $target['status']['label'] }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                        <span>ZMW {{ number_format($target['actual'], 2) }} / ZMW {{ number_format($target['target'], 2) }}</span>
                        <span class="font-semibold">{{ $target['percentage'] }}%</span>
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                        <div class="h-2 rounded-full transition-all duration-300
                            @if($target['percentage'] >= 100) bg-green-500
                            @elseif($target['percentage'] >= 80) bg-blue-500
                            @elseif($target['percentage'] >= 60) bg-yellow-500
                            @else bg-red-500
                            @endif"
                            style="width: {{ min(100, $target['percentage']) }}%">
                        </div>
                    </div>

                    @if($target['remaining'] > 0)
                        <div class="text-xs text-gray-500">
                            ZMW {{ number_format($target['remaining'], 2) }} remaining to reach target
                        </div>
                    @else
                        <div class="text-xs text-green-600 font-medium">
                            🎉 Target exceeded by ZMW {{ number_format(abs($target['remaining']), 2) }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Target Settings Note --}}
        <div class="mt-4 p-3 bg-yellow-50 rounded-lg">
            <div class="flex items-center">
                <svg class="w-4 h-4 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="text-xs text-yellow-700">
                    Monthly targets can be configured in system settings
                </span>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
