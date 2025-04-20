@props([
    'name',
    'method',
    'data' => [],
])

<div>
    <div wire:click.prevent="$dispatchTo('{{$name}}', '{{$method}}')"
        x-on:click.prevent="$flux.modal('{{$name}}').show(); cloneComponent()"
    >
        {{ $slot }}
    </div>

    @if(isset($data) && count($data) > 0)
        @livewire($name, $data, key($name . '-template'))
    @else
        @livewire($name, key($name . '-template'))
    @endif

    <script>
        function cloneComponent() {
            const componentName = @json($name);
            const item = Livewire.all();
            console.log('awaw', this.modalId, item)
            // const clone = item.cloneNode(true);

            // Optionally, you can modify the cloned component, such as changing content or IDs
            // const newItemId = 'dynamic-item-' + new Date().getTime();
            // clone.id = newItemId;

            // Add the cloned component to the DOM (you can append to a parent element)
            // document.getElementById('container').appendChild(clone);

            // Trigger a Livewire event to handle the clone's data on the backend (if needed)
            // Livewire.emit('itemCloned', newItemId);
        }

        // Function to delete a Livewire component
        function deleteComponent(itemId) {
            const component = document.getElementById(itemId); // Find the component to delete
            if (component) {
                component.remove(); // Remove the component from the DOM

                // Optionally, emit a Livewire event to handle deletion on the server
                Livewire.emit('itemDeleted', itemId);
            }
        }
    </script>
</div>
