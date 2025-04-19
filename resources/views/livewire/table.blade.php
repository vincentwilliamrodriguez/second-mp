{{-- This is the new table component that uses Livewire and with a sorting feature --}}

<div class="overflow-x-auto bg-white rounded-lg shadow border border-gray-200">
    <table class="min-w-full divide-y divide-blue-200">
        <thead class="bg-blue-700">
            <tr>
                @foreach($columns as $column)
                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-white uppercase tracking-wider"
                        @if(isset($widths[$column])) style="min-width: {{ $widths[$column] }}; max-width: {{ $widths[$column] }}" @endif>

                        {{ $column }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-blue-100">
            @if(!empty($items))
                @foreach ($items as $row => $item)
                    <tr class="hover:bg-blue-50">
                        @foreach ($columns as $col => $column)
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700 first:font-medium first:text-blue-900 first:bg-blue-50">
                                {!! $slots[$row . '_' . $col] ?? '' !!}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="{{ count($columns) }}" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                        No data available
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
