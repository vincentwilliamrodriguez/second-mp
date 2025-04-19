{{-- This is the old table component that only uses basic Blade --}}

@props([
    'items' => [],
    'columns' => [],
    'widths' => []
])

<div class="overflow-x-auto bg-white rounded-lg shadow border border-gray-200">
    <table class="min-w-full divide-y divide-blue-200">
        <thead class="bg-blue-700">
            <tr>
                @foreach($columns as $key => $column)
                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-white uppercase tracking-wider"
                        @if(isset($widths[$key])) style="min-width: {{ $widths[$key] }}; max-width: {{ $widths[$key] }}" @endif>

                        {{ $key }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-blue-100">
            @forelse($items as $item)
                <tr class="hover:bg-blue-50">
                    @foreach($columns as $key => $callback)
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700 first:font-medium first:text-blue-900 first:bg-blue-50">
                            {!! $callback($item) !!}
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columns) }}" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                        No data available
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
