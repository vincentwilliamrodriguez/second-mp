<?php

namespace App;

use App\Models\Product;

trait CartTrait
{
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

    public function addItemToCart($cartData) {
        $cartKey = 'cart.' . auth()->user()->id;

        session()->push($cartKey, $cartData);
    }

    public function retrieveProduct($cartItem) {
        return Product::where('id', $cartItem['product_id'])->first();
    }
}
