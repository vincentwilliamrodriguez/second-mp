<?php

namespace App;

use App\Models\Product;

trait CartTrait
{
    public $cartKey;

    public function mount() {
        $this->cartKey = 'cart.' . auth()->user()->id;
    }

    public function getUnsortedCart() {
        return session('cart.' . auth()->id(), []);
    }

    public function getSortedCart() {
        $sortedCart = $this->getUnsortedCart();

        if (empty($sortedCart)) {
            return [];
        }


        uasort($sortedCart, function ($itemA, $itemB) {
            return ($itemA['seller_id'] - $itemB['seller_id']);
        });

        $sortedCart = array_values($sortedCart);


        $curSellerIndex = 0;
        $curSellerId = null;

        foreach ($sortedCart as $index => $item) {
            if ($item['seller_id'] !== $curSellerId) {
                $sortedCart[$curSellerIndex]['seller_rowspan'] = $index - $curSellerIndex;

                $curSellerIndex = $index;
                $curSellerId = $item['seller_id'];

            } else {
                $sortedCart[$index]['seller_rowspan'] = 0;
            }
        }

        $sortedCart[$curSellerIndex]['seller_rowspan'] = count($sortedCart) - $curSellerIndex;

        return $sortedCart;
    }

    public function retrieveItemByProductId($productId) {
        $cart = $this->getUnsortedCart();

        foreach ($cart as $item) {
            if ($item['product_id'] === $productId) {
                return $item;
            }
        }

        return null;
    }

    public function addItemToCart($cartData) {
        $existingItem = $this->retrieveItemByProductId($cartData['product_id']);

        if ($existingItem === null) {
            $cartData['id'] = uniqid();
            session()->push($this->cartKey, $cartData);
        } else {
            $this->updateItemInCart($existingItem['id'], $cartData);
        }

        return $existingItem === null;
    }

    public function deleteItemFromCart($cartItemId) {
        $cart = $this->getUnsortedCart();

        $updatedCart = array_filter($cart, function ($item) use ($cartItemId) {
            return $item['id'] !== $cartItemId;
        });

        session()->put($this->cartKey, array_values($updatedCart));
    }

    public function updateItemInCart($cartItemId, $newData) {
        $cart = $this->getUnsortedCart();

        $updatedCart = array_map(function ($item) use ($cartItemId, $newData) {
            if ($item['id'] === $cartItemId) {
                return array_merge($item, $newData);
            }
            return $item;
        }, $cart);

        session()->put($this->cartKey, $updatedCart);
    }


    public function retrieveProduct($cartItem) {
        return Product::where('id', $cartItem['product_id'])->first();
    }
}
