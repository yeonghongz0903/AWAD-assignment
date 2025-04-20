<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the user's cart.
     */
    public function index(): View
    {
        $cartItems = auth()->user()->cartItems()->with('product')->get();
        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    /**
     * Add a product to the cart.
     */
    public function store(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock,
        ]);

        $cartItem = CartItem::firstOrNew([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
        ]);

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return redirect()->route('cart.index')
            ->with('success', 'Product added to cart successfully.');
    }

    /**
     * Update the quantity of a cart item.
     */
    public function update(Request $request, CartItem $cartItem): RedirectResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $cartItem->product->stock,
        ]);

        $cartItem->update(['quantity' => $request->quantity]);

        return redirect()->route('cart.index')
            ->with('success', 'Cart updated successfully.');
    }

    /**
     * Remove a product from the cart.
     */
    public function destroy(CartItem $cartItem): RedirectResponse
    {
        $cartItem->delete();

        return redirect()->route('cart.index')
            ->with('success', 'Product removed from cart successfully.');
    }

    /**
     * Process the checkout.
     */
    public function checkout(): RedirectResponse
    {
        $cartItems = auth()->user()->cartItems()->with('product')->get();
        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });
        
        // Check stock before processing
        foreach ($cartItems as $item) {
            if ($item->quantity > $item->product->stock) {
                return redirect()->route('cart.index')
                    ->with('error', 'Insufficient stock for ' . $item->product->name);
            }
        }
        
        // Process the order
        foreach ($cartItems as $item) {
            $item->product->decrement('stock', $item->quantity);
            $item->delete();
        }
    
        return redirect()->route('cart.success')
            ->with('success', 'Order placed successfully!')
            ->with('cartItems', $cartItems)
            ->with('total', $total);
    }  

    public function success(): View
    {
        $cartItems = session()->get('cartItems', collect());
        $total = session()->get('total', 0);
    
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'No order data found. Please try again.');
        }
    
        return view('cart.success', compact('cartItems', 'total'));
    }
}
