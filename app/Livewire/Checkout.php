<?php

namespace App\Livewire;

use App\CartTrait;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Checkout extends Component {
    use CartTrait;

    // Shipping Address Fields
    public string $fullName = '';
    public string $phoneNumber = '';
    public string $address = '';
    public string $barangay = '';
    public string $city = '';
    public string $province = '';
    public string $postalCode = '';

    // Delivery Method
    public string $deliveryMethod = 'standard';

    // Payment Method
    public string $paymentMethod = 'cod';

    // Cart Items
    public array $cartItems = [];


    public function mount() {
        // Get user's cart items
        $this->cartItems = $this->getSortedCart();

        // Pre-fill shipping address with user details if available
        $user = Auth::user();
        if ($user) {
            $this->fullName = $user->name ?? '';
            $this->phoneNumber = $user->number ?? '';

            // If the user has previous orders, pre-fill with the latest shipping address
            // $latestOrder = Order::where('user_id', $user->id)
            //     ->latest()
            //     ->first();

            // if ($latestOrder) {
            //     $this->address = $latestOrder->address ?? '';
            //     $this->barangay = $latestOrder->barangay ?? '';
            //     $this->city = $latestOrder->city ?? '';
            //     $this->province = $latestOrder->province ?? '';
            //     $this->postalCode = $latestOrder->postal_code ?? '';
            // }
        }

        // Calculate initial totals
        $this->calculateTotals();
    }

    public function render() {
        return view('livewire.checkout');
    }

    public function calculateTotals() {

        // Set shipping fee based on delivery method
        $this->shippingFee = match ($this->deliveryMethod) {
            'standard' => 20,
            'express' => 30,
            'same_day' => 50,
            default => 80
        };

        $this->updateTotals();
    }

    public function validateShippingAddress() {
        $validated = $this->validate([
            'fullName' => ['required', 'string', 'max:255'],
            'phoneNumber' => ['required', 'string', 'regex:/^(\+63|0)\d{10}$/'],
            'address' => ['required', 'string', 'max:255'],
            'barangay' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'postalCode' => ['required', 'string', 'regex:/^\d{4}$/'],
        ]);

        return true;
    }

    public function validateDeliveryMethod() {
        $this->validate([
            'deliveryMethod' => ['required', 'string', 'in:standard,express,same_day'],
        ]);

        if ($this->deliveryMethod === 'same_day' && !in_array($this->province, ['Metro Manila', 'Manila', 'Maynila', 'NCR', 'National Capital Region', 'Kalakhang Maynila'])) {
            $this->addError('deliveryMethod', 'We\'re sorry, but same-day delivery is only available for Metro Manila addresses for now.');
            return false;
        }

        // Re-calculate totals when delivery method changes
        $this->calculateTotals();

        return true;
    }

    public function updated($propertyName) {
        // Recalculate totals when delivery method is updated
        if ($propertyName === 'deliveryMethod') {
            $this->calculateTotals();
        }
    }

    public function placeOrder() {
        // Validate all sections
        $this->validateShippingAddress();
        $this->validateDeliveryMethod();

        $this->validate([
            'paymentMethod' => ['required', 'string', 'in:cod,e_wallet'],
        ]);

        // Check if cart is not empty
        if (empty($this->cartItems)) {
            session()->flash('error', 'Your cart is empty. Please add items to your cart before checkout.');
            return redirect()->route('cart');
        }

        try {
            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'full_name' => $this->fullName,
                'phone_number' => $this->phoneNumber,
                'address' => $this->address,
                'barangay' => $this->barangay,
                'city' => $this->city,
                'province' => $this->province,
                'postal_code' => $this->postalCode,
                'delivery_method' => $this->deliveryMethod,
                'payment_method' => $this->paymentMethod,
                'subtotal' => $this->subtotal,
                'shipping_fee' => $this->shippingFee,
                'tax' => $this->taxAmount,
                'total_amount' => $this->totalAmount,
                'status' => 'pending'
            ]);

            // Create order items
            // foreach ($this->cartItems as $item) {
            //     OrderItem::create([
            //         'order_id' => $order->id,
            //         'product_id' => $item['product_id'],
            //         'quantity' => $item['order_quantity'],
            //         'price' => $item['product_price'],
            //         'total' => $item['product_price'] * $item['order_quantity']
            //     ]);

            // Update product quantity
            // $product = Product::find($item['product_id']);
            // if ($product) {
            //     $product->quantity -= $item['order_quantity'];
            //     $product->save();
            // }
            // }

            // Clear cart
            $this->clearAllCartItems();

            // Flash success message
            session()->flash('message', 'Order placed successfully! Your order number is #' . $order->id);

            // Redirect to order confirmation page
            return redirect()->route('orders.show', $order);
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while placing your order. Please try again.');
            return false;
        }
    }
}
