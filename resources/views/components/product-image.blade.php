@props([
    'product',
    'classes' => '',
    'placeholderSize' => 'size-28'
])

<div class="overflow-hidden bg-gradient-to-br from-blue-50 to-gray-100 {{ $classes }}">
    @if(isset($product->picture) && Storage::disk('public')->exists($product->picture))
        <img class="w-full h-full object-cover"
                src="{{ Storage::url($product->picture) }}"
                alt="{{ $product->name }}">
    @else
        <div class="w-full h-full flex items-center justify-center">
            <div class="text-center p-4 flex flex-col justify-center">
                <flux:icon.photo class="text-gray-200 {{ $placeholderSize }}" variant='solid'/>
            </div>
        </div>
    @endif
</div>
