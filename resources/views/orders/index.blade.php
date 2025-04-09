<x-tab title="Orders">
    <div class="flex flex-col p-8 w-[90vw] max-w-[1200px]">
        @foreach ($orders as $order)
            <div class="mb-4 flex flex-col">
                @foreach ($order->getAttributes() as $key => $value)
                    <div class="flex gap-1">
                        <strong>{{ $key }}: </strong> {{ $value }}
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</x-tab>
