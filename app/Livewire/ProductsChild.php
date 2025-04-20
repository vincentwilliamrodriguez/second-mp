<?php

namespace App\Livewire;

use App\Models\Product;
use Flux\Flux;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage as FacadesStorage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;


class ProductsChild extends Component {
    use WithFileUploads;

    public Product $product;

    #[Validate]
    public $item = [];
    public $state;

    public $orderQuantity = 1;
    public $maxDescriptionLength = 250;

    public $categories;
    public $categoryValues;

    protected $listeners = ['openShow', 'openCreate', 'openEdit', 'openDelete', 'closeModal'];
    public $validationAttributes = [
        'item.name' => 'Name',
        'item.description' => 'Description',
        'item.category' => 'Category',
        'item.quantity' => 'Quantity',
        'item.price' => 'Price',
        'item.picture' => 'Picture',
    ];


    public function render() {
        return view('livewire.products-child');
    }

    public function closeModal() {
        $this->dispatch('modal-closed-' . $this->id);
        // $this->reset('state', 'item', 'orderQuantity');
        // $this->resetErrorBag();
    }

    public function openShow($productId) {
        $this->state = null;
        $this->product = Product::findOrFail($productId);
        $this->authorize('view', $this->product);
        $this->state = 'Show';
    }

    public function openCreate() {
        $this->state = null;
        $this->authorize('create', Product::class);
        $this->state = 'Create';
    }

    public function openEdit($productId) {
        $this->state = null;
        $this->product = Product::findOrFail($productId);
        $this->authorize('update', $this->product);

        $this->item = $this->product->only(['name', 'description', 'quantity', 'price', 'category', 'picture']);
        $this->state = 'Edit';
    }

    public function openDelete($productId) {
        $this->state = null;
        $this->product = Product::findOrFail($productId);
        $this->authorize('delete', $this->product);

        $this->state = 'Delete';
    }

    public function rules() {
        return [
            'item.name' => 'required|string|max:40',
            'item.description' => 'nullable|string|max:250',
            'item.category' => ['required', Rule::in($this->categories)],
            'item.quantity' => 'required|integer|min:0|max:10000000',
            'item.price' => 'required|numeric|min:0|max:10000000',
            'item.picture' => $this->isPictureUploaded()
                ? 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
                : 'nullable|string',
        ];
    }


    public function updated($property) {
        if (in_array($property, array_keys($this->validationAttributes))) {
            $this->validateOnly($property);
        }
    }

    public function saveProduct() {
        $this->validate();

        switch ($this->state) {
            case 'Create':
                $this->authorize('create', Product::class);
                $this->item['seller_id'] = auth()->id();
                $this->savePicture();

                Product::create($this->item);
                session()->flash('message', 'Product created successfully.');

                break;

            case 'Edit':
                $this->authorize('update', $this->product);
                $this->savePicture();

                $this->product->update($this->item);
                session()->flash('message', 'Product updated successfully.');

                break;
        }

        $this->redirectRoute('products.index');
    }

    public function deleteProduct() {
        $this->authorize('delete', $this->product);
        $this->product->delete();

        if ($this->product->picture) {
            FacadesStorage::disk('public')->delete($this->product->picture);
        }

        session()->flash('message', 'Product deleted successfully.');
        $this->redirectRoute('products.index');
    }

    public function addToCart() {
        $cartKey = 'cart.' . auth()->user()->id;
        $cartData = [
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'product_price' => $this->product->price,
            'order_quantity' => $this->orderQuantity,
        ];

        session()->push($cartKey, $cartData);
        session()->flash('message', 'Product added to cart successfully.');
        $this->redirectRoute('products.index');
    }

    private function savePicture() {
        if ($this->isPictureUploaded()) {
            if (isset($this->product) && $this->product->picture) {
                FacadesStorage::disk('public')->delete($this->product->picture);
            }

            try {
                $path = $this->item['picture']->store('products', 'public');
                $this->item['picture'] = $path;

            } catch (\Throwable $e) {
                dd('File upload error:', $e->getMessage());
            }

        }
    }

    private function isPictureUploaded() {
        $picture = $this->item['picture'] ?? null;
        return is_object($picture) && $picture instanceof TemporaryUploadedFile;
    }
}
