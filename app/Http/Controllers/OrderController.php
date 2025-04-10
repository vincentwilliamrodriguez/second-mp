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

        dd($validated['quantity']);

        return redirect()->route('orders.index')
            ->with('message', 'Added to cart successfully.');
    }

    public function update(Request $request, Order $order) {
        $validated = $request->validate([
            'status' => 'required|in:accepted,cancelled',
        ]);

        dd($validated['status']);
    }

    public function destroy(Order $order) {
        dd($order);
    }
}
