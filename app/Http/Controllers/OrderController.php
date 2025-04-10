<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index() {
        $user = auth()->user();
        $orders =    $user->hasRole('admin') ? Order::latest()->get()
                  : ($user->hasRole('customer') ? $user->orders()->latest()->get()
                  : ($user->hasRole('seller') ? $user->ordersForSeller()->latest()->get() : collect()));

        return view('orders.index', compact('orders'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'product_id' => 'required|uuid',
        ]);

        $validated = array_merge($validated, [
            'customer_id' => auth()->id(),
            'date_placed' => now(),
            'is_placed' => false,
            'status' => 'pending',
        ]);

        Order::create($validated);

        return redirect()->route('orders.index')
            ->with('message', 'Added to cart successfully.');
    }


    public function update(Request $request, Order $order) {
        $validated = $request->validate([
            'status' => 'required|in:completed,cancelled',
        ]);


        $order->status = $validated['status'];

        if ($order->status === 'completed') {
            if ($order->product->quantity < $order->quantity) {
                return redirect()->back()
                    ->with('error', 'Not enough stock to accept this order.')->withInput();
            }

            $order->product->quantity -= $order->quantity;
            $order->product->save();
        }
        
        $order->save();

        return redirect()->route('orders.index')
            ->with('message', 'Order updated successfully.');
    }

    public function placeAll(Request $request) {
        $user = auth()->user();

        $updatedCount = $user->orders()
            ->where('is_placed', false)
            ->update([
                'is_placed' => true,
                'date_placed' => now(),
            ]);

        return redirect()->route('orders.index')
            ->with('message', $updatedCount . ' orders have been placed successfully.');
    }


    public function updateQuantity(Request $request, Order $order)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $order->product->quantity,
        ]);

        $order->update([
            'quantity' => $request->quantity
        ]);

        return redirect()->route('orders.index');
    }

    public function destroy(Order $order) {
        $order->delete();

        return redirect()->route('orders.index')
        ->with('message', 'Order has been removed successfully.');
    }
}
