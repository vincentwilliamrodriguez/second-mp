<?php

namespace App\Livewire;

use App\CartTrait;
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
    use WithFileUploads, CartTrait;

    public Product $product;

    #[Validate]
    public $item = [];
    public $state;

    public $orderQuantity = 1;
    public $maxDescriptionLength = 250;

    public $categories = [];
    public $categoryValues = [];

    public $curCartItem;
    public $curCartCount;

    protected $listeners = ['open', 'resetform'];
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

    public function resetform() {
        $this->reset('state', 'item', 'orderQuantity');
        $this->resetErrorBag();
    }



    public function open($method, $productId = null) {
        $this->state = null;

        if ($productId) {
            $this->product = Product::findOrFail($productId);
        }

        switch ($method) {
            case 'Show':
                $this->authorize('view', $this->product);
                $this->curCartItem = $this->retrieveItemByProductId($this->product->id) ?? ['id' => 'new', 'order_quantity' => 1];
                $this->curCartCount = $this->curCartItem['order_quantity'];
                $this->orderQuantity = $this->curCartCount;

                break;

            case 'Create':
                $this->authorize('create', Product::class);
                break;

            case 'Edit':
                $this->authorize('update', $this->product);
                $this->item = $this->product->only(['name', 'description', 'quantity', 'price', 'category', 'picture']);
                break;

            case 'Delete':
                $this->authorize('delete', $this->product);
                break;
        }

        $this->state = $method;
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
        $cartData = [
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'product_price' => $this->product->price,
            'order_quantity' => $this->orderQuantity,
            'seller_id' => $this->product->seller->id,
        ];

        $wasAdded = $this->addItemToCart($cartData);

        session()->flash('message', $wasAdded ? 'Product added to cart successfully.' : 'Cart updated successfully.');
        $this->redirectRoute('cart');
    }

    private function savePicture() {
        if ($this->isPictureUploaded()) {
            if ($this->product->picture) {
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
