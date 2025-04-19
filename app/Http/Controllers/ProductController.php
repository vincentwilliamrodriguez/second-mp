<?php

// This is the old controller for products


namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage as FacadesStorage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public static $categories = ['Books', 'Clothing', 'Electronics', 'Furniture', 'Hardware', 'Health', 'Hobbies', 'Other'];

    public function index() {
        $user = auth()->user();
        $products = $user->hasRole('seller')
                        ? $user->products()->orderBy('updated_at', 'DESC')->paginate(8)
                        : Product::orderBy('updated_at', 'DESC')->paginate(8);

        return view('products.index', compact('products'));
    }

    public function show(Product $product) {
        return view('products.show', compact('product'));
    }

    public function create() {
        $categories = self::$categories;
        return view('products.create', compact('categories'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:40',
            'description' => 'nullable|string:max:250',
            'category' => ['required', Rule::in(self::$categories)],
            'quantity' => 'required|integer|min:0|max:10000000',
            'price' => 'required|decimal:0,2|min:0|max:10000000',
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
        $categories = self::$categories;
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product) {
        $validated = $request->validate([
            'name' => 'required|string|max:30',
            'description' => 'nullable|string:max:250',
            'category' => ['required', Rule::in(self::$categories)],
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
