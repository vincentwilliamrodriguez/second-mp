<?php

namespace App\Livewire;

use App\Models\Product;
use Illuminate\Support\Number;
use Livewire\Attributes\Renderless;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ProductsIndex extends Component {
    use WithPagination, WithoutUrlPagination;

    public $search = '';

    public $sortBy = '';
    public $sortOrder = '';
    public $sortValues = [
        'created_at' => [
            'Created',
            'clock',
        ],
        'updated_at' => [
            'Modified',
            'arrow-path',
        ],
        'name' => [
            'Name',
            'case-sensitive',
        ],
        'price' => [
            'Price',
            'banknotes',
        ],
        'quantity' => [
            'Quantity',
            'cube',
        ],
    ];

    public $category = '';
    public $categoryValues = [
        'Books' => [
            'Books',
            'book-open'
        ],
        'Clothing' => [
            'Clothing',
            'shirt',
        ],
        'Electronics' => [
            'Electronics',
            'bolt',
        ],
        'Furniture' => [
            'Furniture',
            'armchair',
        ],
        'Hardware' => [
            'Hardware',
            'cpu-chip',
        ],
        'Health' => [
            'Health',
            'activity',
        ],
        'Hobbies' => [
            'Hobbies',
            'puzzle-piece',
        ],
        'Other' => [
            'Other',
            'ellipsis-horizontal',
        ],
    ];


    public $minPrice = '';
    public $maxPrice = '';

    public function rules() {
        return [
            'minPrice' => ['decimal:0,2', 'min:0', 'max:10000000'],
            'maxPrice' => ['decimal:0,2', 'min:0', 'max:10000000']
        ];
    }

    public function render() {
        $products = $this->fetchProducts()->paginate(8);

        return view('livewire.products-index', compact('products'));
    }

    public function fetchProducts() {
        $user = auth()->user();

        $products = $user->hasRole('seller')
            ? $user->products()
            : Product::query();


        if ($this->search) {
            $products = $products->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
                    ->orWhereHas('seller', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('username', 'like', '%' . $this->search . '%');
                    });
            });
        }

        if ($this->sortBy) {
            $products = $products->orderBy($this->sortBy, $this->sortOrder);
        } else {
            $products = $products->orderBy('updated_at', 'DESC');
        }

        if ($this->category) {
            $products = $products->where('category', $this->category);
        }

        $products = $products->where('price', '>=', $this->minPrice ? (float) $this->minPrice : 0)
                             ->where('price', '<=', $this->maxPrice ? (float) $this->maxPrice : 10000000);

        return $products;
    }

    public function updated($property, $value) {
        if (in_array($property, ['category', 'search', 'sortBy', 'sortOrder'])) {
            $this->resetPage();
        }

        if (in_array($property, ['minPrice', 'maxPrice']) && ($value !== '')) {
            $this->$property = Number::clamp((float) $value, 0, 10000000);

            if ($property === 'maxPrice') {
                $this->maxPrice = Number::clamp((float) $value, $this->minPrice, 10000000);
            }
        }
    }


    public function isProductsEmpty() {
        return Product::first() === null;
    }

    public function getSortText() {
        return ($this->sortBy !== '')
            ? $this->sortValues[$this->sortBy][0]
            : "Sort by";
    }
}
