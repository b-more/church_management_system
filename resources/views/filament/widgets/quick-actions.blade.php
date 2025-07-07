{{-- resources/views/filament/widgets/quick-actions.blade.php --}}
<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Quick Actions
        </x-slot>

        @php
            $actions = $this->getQuickActions();
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            @foreach($actions as $action)
                <a href="{{ $action['url'] }}"
                   class="group flex items-center p-4 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors duration-150 border-l-4
                          @if($action['color'] === 'success') border-green-500
                          @elseif($action['color'] === 'info') border-blue-500
                          @elseif($action['color'] === 'warning') border-yellow-500
                          @elseif($action['color'] === 'danger') border-red-500
                          @elseif($action['color'] === 'primary') border-indigo-500
                          @else border-gray-500
                          @endif">
                    <div class="flex-shrink-0 mr-4">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center
                                    @if($action['color'] === 'success') bg-green-100 text-green-600
                                    @elseif($action['color'] === 'info') bg-blue-100 text-blue-600
                                    @elseif($action['color'] === 'warning') bg-yellow-100 text-yellow-600
                                    @elseif($action['color'] === 'danger') bg-red-100 text-red-600
                                    @elseif($action['color'] === 'primary') bg-indigo-100 text-indigo-600
                                    @else bg-gray-100 text-gray-600
                                    @endif">
                            @if($action['icon'] === 'heroicon-o-user-plus')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            @elseif($action['icon'] === 'heroicon-o-clipboard-document')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            @elseif($action['icon'] === 'heroicon-o-banknotes')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                            @elseif($action['icon'] === 'heroicon-o-calendar-days')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            @elseif($action['icon'] === 'heroicon-o-chart-bar')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            @elseif($action['icon'] === 'heroicon-o-phone')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            @endif
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-semibold text-gray-900 group-hover:text-gray-700">
                            {{ $action['label'] }}
                        </h4>
                        <p class="text-xs text-gray-600 mt-1">
                            {{ $action['description'] }}
                        </p>
                    </div>
                    <div class="flex-shrink-0 ml-2">
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Emergency Contact Info --}}
        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
            <div class="flex items-center">
                <x-filament::icon
                    icon="heroicon-o-information-circle"
                    class="w-5 h-5 text-blue-600 mr-2"
                />
                <h5 class="text-sm font-medium text-blue-900">Need Help?</h5>
            </div>
            <p class="text-xs text-blue-700 mt-1">
                Contact the system administrator for technical support or training on new features.
            </p>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
