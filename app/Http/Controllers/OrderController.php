<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index() {
        $orders = Order::all();
        return view('orders.index', compact('orders'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        return redirect()->route('orders.index')
            ->with('message', 'Added to cart successfully.');
    }
}
