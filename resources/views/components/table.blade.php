@props([
    'items' => [],
    'columns' => [],
    'widths' => []
])

<div class="overflow-x-auto bg-white rounded-lg shadow">
    <table class="min-w-full divide-y divide-blue-200">
        <thead class="bg-blue-700">
            <tr>
                @foreach($columns as $key => $column)
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider"
                        @if(isset($widths[$key])) style="max-width: {{ $widths[$key] }}" @endif>

                        {{ $key }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-blue-100">
            @forelse($items as $item)
                <tr class="hover:bg-blue-50">
                    @foreach($columns as $key => $callback)
                        @if($loop->first)
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-900 bg-blue-50">
                                {!! $callback($item) !!}
                            </td>
                        @else
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {!! $callback($item) !!}
                            </td>
                        @endif
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
