<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index() {
        $products = Product::latest()->paginate(5);
        return view('products.index', compact('products'));
    }

    public function show(Product $product) {
        return view('products.show', compact('product'));
    }

    public function create() {
        return view('products.create');
    }

    public function store(Request $request) {

    }

    public function edit(Product $product) {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product) {

    }

    public function destroy(Product $product) {
        $product->delete();
        return redirect()->route('products.index')->with('message', 'Product deleted successfully.');
    }
}
