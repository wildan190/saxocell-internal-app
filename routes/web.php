<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    $needsReviewCount = \App\Models\Product::needsReview()->count();
    $productsNeedingReview = \App\Models\Product::needsReview()->latest()->take(5)->get();
    return view('home', compact('needsReviewCount', 'productsNeedingReview'));
})->middleware(['auth'])->name('home');

// Products Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/products/needs-review', [ProductController::class, 'needsReview'])->name('products.needs-review');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});

// Suppliers Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('/suppliers/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');
    Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
});

// Inventory/Stock Management Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/create', [InventoryController::class, 'create'])->name('inventory.create');
    Route::post('/inventory', [InventoryController::class, 'store'])->name('inventory.store');
});

// Procurement - Purchase Orders
Route::middleware(['auth'])->group(function () {
    Route::get('/procurement/purchase-orders', [\App\Http\Controllers\PurchaseOrderController::class, 'index'])->name('purchase-orders.index');
    Route::get('/procurement/purchase-orders/create', [\App\Http\Controllers\PurchaseOrderController::class, 'create'])->name('purchase-orders.create');
    Route::post('/procurement/purchase-orders', [\App\Http\Controllers\PurchaseOrderController::class, 'store'])->name('purchase-orders.store');
    Route::get('/procurement/purchase-orders/{id}', [\App\Http\Controllers\PurchaseOrderController::class, 'show'])->name('purchase-orders.show');
    Route::get('/procurement/purchase-orders/{id}/pdf', [\App\Http\Controllers\PurchaseOrderController::class, 'downloadPdf'])->name('purchase-orders.pdf');
    Route::post('/procurement/purchase-orders/{id}/approve', [\App\Http\Controllers\PurchaseOrderController::class, 'approve'])->name('purchase-orders.approve');
    Route::delete('/procurement/purchase-orders/{id}', [\App\Http\Controllers\PurchaseOrderController::class, 'destroy'])->name('purchase-orders.destroy');
    
    // Procurement - Delivery Orders (Goods Receipt)
    Route::get('/procurement/delivery-orders', [\App\Http\Controllers\DeliveryOrderController::class, 'index'])->name('delivery-orders.index');
    Route::get('/procurement/delivery-orders/create', [\App\Http\Controllers\DeliveryOrderController::class, 'create'])->name('delivery-orders.create');
    Route::post('/procurement/delivery-orders', [\App\Http\Controllers\DeliveryOrderController::class, 'store'])->name('delivery-orders.store');
    Route::get('/procurement/delivery-orders/{id}', [\App\Http\Controllers\DeliveryOrderController::class, 'show'])->name('delivery-orders.show');
    
    // Procurement - Invoices & Matching
    Route::get('/procurement/invoices', [\App\Http\Controllers\InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/procurement/invoices/create', [\App\Http\Controllers\InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/procurement/invoices', [\App\Http\Controllers\InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/procurement/invoices/{id}', [\App\Http\Controllers\InvoiceController::class, 'show'])->name('invoices.show');
});

// Warehouse & Store Management
Route::middleware(['auth'])->group(function () {
    // Warehouses
    Route::resource('warehouses', \App\Http\Controllers\WarehouseController::class);
    
    // Stores
    // Stores
    Route::put('stores/{store}/inventory/{inventory}/toggle-status', [\App\Http\Controllers\StoreController::class, 'toggleInventoryStatus'])->name('stores.inventory.toggle-status');
    Route::resource('stores', \App\Http\Controllers\StoreController::class);
    
    // Stock Transfers
    Route::get('/stock-transfers/create-request', [\App\Http\Controllers\StockTransferController::class, 'createRequest'])->name('stock-transfers.create-request');
    Route::post('/stock-transfers/request', [\App\Http\Controllers\StockTransferController::class, 'storeRequest'])->name('stock-transfers.store-request');
    
    Route::get('/stock-transfers', [\App\Http\Controllers\StockTransferController::class, 'index'])->name('stock-transfers.index');
    Route::get('/stock-transfers/create', [\App\Http\Controllers\StockTransferController::class, 'create'])->name('stock-transfers.create');
    Route::post('/stock-transfers', [\App\Http\Controllers\StockTransferController::class, 'store'])->name('stock-transfers.store');
    Route::get('/stock-transfers/{id}', [\App\Http\Controllers\StockTransferController::class, 'show'])->name('stock-transfers.show');
    
    Route::post('/stock-transfers/{id}/approve', [\App\Http\Controllers\StockTransferController::class, 'approve'])->name('stock-transfers.approve');
    Route::post('/stock-transfers/{id}/reject', [\App\Http\Controllers\StockTransferController::class, 'reject'])->name('stock-transfers.reject');
    Route::post('/stock-transfers/{id}/receive', [\App\Http\Controllers\StockTransferController::class, 'receive'])->name('stock-transfers.receive');
    
    // Stock Opname
    Route::get('/stock-opnames', [\App\Http\Controllers\StockOpnameController::class, 'index'])->name('stock-opnames.index');
    Route::get('/stock-opnames/create', [\App\Http\Controllers\StockOpnameController::class, 'create'])->name('stock-opnames.create');
    Route::post('/stock-opnames', [\App\Http\Controllers\StockOpnameController::class, 'store'])->name('stock-opnames.store');
    Route::get('/stock-opnames/{id}', [\App\Http\Controllers\StockOpnameController::class, 'show'])->name('stock-opnames.show');
    Route::post('/stock-opnames/{id}/finalize', [\App\Http\Controllers\StockOpnameController::class, 'finalize'])->name('stock-opnames.finalize');
});

