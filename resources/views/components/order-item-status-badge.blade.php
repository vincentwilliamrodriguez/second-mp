@props(['item'])

@php
$status = $item->status;
$statusColor = match ($status) {
    'pending' => 'bg-yellow-200',
    'accepted' => 'bg-green-200',
    'shipped' => 'bg-blue-200',
    'delivered' => 'bg-green-700 text-white',
    'cancelled' => 'bg-red-200',
}
@endphp

<span class='w-max h-min rounded-full px-2 py-1 text-xs {{ $statusColor }}'> {{ ucwords(str_replace('_', ' ', $status)) }} </span>
