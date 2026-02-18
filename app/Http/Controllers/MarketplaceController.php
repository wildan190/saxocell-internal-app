<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class MarketplaceController extends Controller
{
    public function index(Request $request, $slug)
    {
        $store = Store::where('slug', $slug)->firstOrFail();
        
        // Get search and filter parameters
        $search = $request->input('search');
        $category = $request->input('category');
        
        // Get products available in this store
        $query = Product::whereHas('storeInventory', function ($q) use ($store) {
            $q->where('store_id', $store->id)
              ->where('quantity', '>', 0)
              ->where('is_active', true);
        });
        
        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ILIKE', '%' . $search . '%')
                  ->orWhere('description_1', 'ILIKE', '%' . $search . '%')
                  ->orWhere('sku', 'ILIKE', '%' . $search . '%');
            });
        }
        
        // Apply category filter
        if ($category) {
            $query->where('category', $category);
        }
        
        $products = $query->with(['storeInventory' => function ($q) use ($store) {
            $q->where('store_id', $store->id);
        }])->latest()->get();
        
        // Get all available categories for filter dropdown
        $categories = Product::whereHas('storeInventory', function ($q) use ($store) {
            $q->where('store_id', $store->id)
              ->where('quantity', '>', 0)
              ->where('is_active', true);
        })->distinct()->pluck('category')->filter()->sort()->values();

        return view('marketplace.index', compact('store', 'products', 'categories', 'search', 'category'));
    }

    public function show($slug, Product $product)
    {
        $store = Store::where('slug', $slug)->firstOrFail();
        
        // Load inventory specific to this store
        $inventory = $product->storeInventory()->where('store_id', $store->id)->first();
        $quantity = $inventory ? $inventory->quantity : 0;

        return view('marketplace.show', compact('store', 'product', 'quantity'));
    }

    public function addToCart(Request $request, $slug, Product $product)
    {
        $store = Store::where('slug', $slug)->firstOrFail();
        $quantity = $request->input('quantity', 1);

        $cart = Session::get('cart_' . $store->id, []);
        
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $cart[$product->id] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
                'image' => $product->image
            ];
        }

        Session::put('cart_' . $store->id, $cart);

        return redirect()->route('marketplace.cart', $slug)->with('success', 'Product added to cart');
    }

    public function viewCart($slug)
    {
        $store = Store::where('slug', $slug)->firstOrFail();
        $cart = Session::get('cart_' . $store->id, []);
        $total = array_reduce($cart, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        return view('marketplace.cart', compact('store', 'cart', 'total'));
    }

    public function removeFromCart($slug, $id)
    {
        $store = Store::where('slug', $slug)->firstOrFail();
        $cart = Session::get('cart_' . $store->id, []);
        
        if (isset($cart[$id])) {
            unset($cart[$id]);
            Session::put('cart_' . $store->id, $cart);
        }

        return redirect()->back()->with('success', 'Item removed from cart');
    }

    public function checkout($slug)
    {
        $store = Store::where('slug', $slug)->firstOrFail();
        $cart = Session::get('cart_' . $store->id, []);
        
        if (empty($cart)) {
            return redirect()->route('marketplace.index', $slug)->with('error', 'Your cart is empty');
        }

        $total = array_reduce($cart, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        return view('marketplace.checkout', compact('store', 'cart', 'total'));
    }

    public function storeOrder(Request $request, $slug)
    {
        $store = Store::where('slug', $slug)->firstOrFail();
        $cart = Session::get('cart_' . $store->id, []);
        
        if (empty($cart)) {
            return redirect()->route('marketplace.index', $slug)->with('error', 'Cart is empty');
        }

        $validated = $request->validate([
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            // Total calculation and race condition check
            $totalAmount = 0;

            foreach ($cart as $productId => $item) {
                // Lock stock for update to prevent race condition
                $inventory = StoreInventory::where('store_id', $store->id)
                    ->where('product_id', $productId)
                    ->lockForUpdate()
                    ->first();

                if (!$inventory || $inventory->quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product: " . $item['name']);
                }

                // Decrement stock immediately (Race Condition Handling)
                $inventory->quantity -= $item['quantity'];
                $inventory->save();

                $totalAmount += $item['price'] * $item['quantity'];
            }

            // Create Order
            $order = Order::create([
                'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
                'store_id' => $store->id,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'total_amount' => $totalAmount,
                'status' => 'pending_payment',
            ]);

            // Create Order Items
            foreach ($cart as $productId => $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
            }

            DB::commit();

            // Clear Cart
            Session::forget('cart_' . $store->id);

            return redirect()->route('marketplace.payment', ['slug' => $store->slug, 'order' => $order->id])
                ->with('success', 'Order placed successfully! Please upload payment proof.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Order failed: ' . $e->getMessage());
        }
    }

    public function payment($slug, Order $order)
    {
        $store = Store::where('slug', $slug)->firstOrFail();
        if ($order->store_id !== $store->id) abort(404);
        
        return view('marketplace.payment', compact('store', 'order'));
    }

    public function uploadPayment(Request $request, $slug, Order $order)
    {
        $store = Store::where('slug', $slug)->firstOrFail();
        if ($order->store_id !== $store->id) abort(404);

        $request->validate([
            'payment_proof' => 'required|image|max:2048',
        ]);

        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
            
            $order->update([
                'payment_proof_path' => $path,
                'status' => 'pending_confirmation',
            ]);
        }

        return redirect()->route('marketplace.payment', ['slug' => $store->slug, 'order' => $order->id])
            ->with('success', 'Payment proof uploaded! Waiting for store confirmation.');
    }
}
