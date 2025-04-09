<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage as FacadesStorage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    protected $categories = ['Electronics', 'Books', 'Clothing'];


    public function index() {
        $products = Product::latest()->paginate(5);
        return view('products.index', compact('products'));
    }

    public function show(Product $product) {
        return view('products.show', compact('product'));
    }

    public function create() {
        $categories = $this->categories;
        return view('products.create', compact('categories'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => ['required', Rule::in($this->categories)],
            'quantity' => 'required|integer',
            'price' => 'required|decimal:0,2|min:0',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('picture')) {
            $path = $request->file('picture')->store('products', 'public');
            $validated['picture'] = $path;
        }

        $validated['seller_id'] = auth()->id();

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('message', 'Product created successfully.');
    }

    public function edit(Product $product) {
        $categories = $this->categories;
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => ['required', Rule::in($this->categories)],
            'quantity' => 'required|integer',
            'price' => 'required|decimal:0,2|min:0',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('picture')) {
            if ($product->picture) {
                FacadesStorage::disk('public')->delete($product->picture);
            }

            $path = $request->file('picture')->store('products', 'public');
            $validated['picture'] = $path;
        }

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('message', 'Product updated successfully.');
    }

    public function destroy(Product $product) {
        $product->delete();

        if ($product->picture) {
            FacadesStorage::disk('public')->delete($product->picture);
        }

        return redirect()->route('products.index')->with('message', 'Product deleted successfully.');
    }
}
