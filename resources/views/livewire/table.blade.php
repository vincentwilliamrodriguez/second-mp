{{-- This is the new table component using Livewire --}}
<div class="overflow-x-auto bg-white rounded-lg shadow border border-gray-200 {{ $customClasses['container'] }}">
    <table class="min-w-full divide-y divide-blue-200 {{ $customClasses['table'] }}">
        <thead class="bg-indigo-600 {{ $customClasses['thead'] }}" x-on:sortchanged="$el.classList.add('pointer-events-none')">
            <tr>
                @foreach($columns as $column)
                    @php
                        $property = $columnsToProperty[$column];
                    @endphp

                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-white uppercase tracking-wider {{ $customClasses['th'] }}"

                        @if(isset($widths[$column]))
                            style="min-width: {{ $widths[$column] }}; max-width: {{ $widths[$column] }}"
                        @endif

                        @if (in_array($column, $columnsWithSorting))
                            :class="'hover:cursor-pointer !px-1.5 !py-1'"
                            x-on:click="$wire.sortOrder = ($wire.sortBy !== '{{ $property }}') ? 'asc' :
                                                          ($wire.sortOrder === 'asc') ? 'desc' :
                                                          ($wire.sortOrder === 'desc') ? '' :
                                                          ($wire.sortOrder === '') ? 'asc' : 'asc';
                                        $wire.sortBy = ($wire.sortOrder !== '') ? '{{ $property }}' : '';
                                        $dispatch('sortchanged', {sortBy: $wire.sortBy, sortOrder: $wire.sortOrder});"
                        @endif
                    >

                        <div class="flex justify-center items-center gap-1 pointer-events-none">
                            @if (in_array($column, $columnsWithSorting))
                                <flux:button variant='ghost' size='sm' class="!p-0" x-cloak
                                    x-show="$wire.sortBy === '{{ $property }}' &&
                                            $wire.sortOrder !== ''"
                                >
                                    <flux:icon.arrow-long-up x-show="$wire.sortOrder === 'asc'" x-cloak class="text-white size-6" />
                                    <flux:icon.arrow-long-down x-show="$wire.sortOrder === 'desc'" x-cloak class="text-white size-6"  />
                                </flux:button>
                            @endif
                            <span class="!select-none">{{ $column }}</span>
                        </div>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-blue-100 {{ $customClasses['tbody'] }}">
            @if(count($items))
                @foreach ($items as $rowIndex => $item)
                    <tr class="hover:bg-blue-50 {{ $customClasses['tr'] }}">
                        @foreach ($columns as $colIndex => $column)
                            @php
                                $cellContent = $cells[$rowIndex][$colIndex];
                                $isLivewire = Str::contains($cellContent, 'livewire:');
                                $componentName = '';
                                $componentKey = '';
                                $componentData = [];

                                if ($isLivewire) {
                                    $component = explode(':', $cellContent)[1] ?? '';
                                    $componentName = preg_match('/^([\w\-]+)/', $component, $matches) ? $matches[1] : '';
                                    $componentKey = $componentName . '$rowIndex' . '$colIndex';
                                    $componentData = $cellData[$rowIndex][$colIndex] ?? [];
                                }

                                if (array_key_exists($column, $columnsWithRowspan)) {
                                    $rowspanValue = $item[$columnsWithRowspan[$column]];
                                    $rowspanClasses = ($rowspanValue === 0) ? 'hidden' : '';
                                } else {
                                    $rowspanClasses = '';
                                    $rowspanValue = null;
                                }
                            @endphp

                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700 first:font-medium first:text-blue-900 first:bg-blue-50 {{ $rowspanClasses }} {{ $customClasses['td'] }}" @if ($rowspanValue) rowspan="{{ $rowspanValue }}" @endif>

                                @if (!$isLivewire)
                                    {!! $cellContent !!}
                                @else
                                    @livewire($componentName, $componentData, key($componentKey))
                                @endif

                            </td>
                        @endforeach
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="{{ count($columns) }}" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center {{ $customClasses['tdNoData'] }}">
                        {{ $noDataText }}
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
