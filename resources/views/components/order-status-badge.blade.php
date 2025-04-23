@props(['order'])

@php
$status = $order->overall_status;
$statusColor = match ($status) {
    'pending' => 'bg-yellow-200',
    'in_progress' => 'bg-blue-200',
    'completed' => 'bg-green-300',
};
@endphp

<span class='w-max h-min rounded-full px-2 py-1 text-xs {{ $statusColor }}'> {{ ucwords(str_replace('_', ' ', $status)) }} </span>
