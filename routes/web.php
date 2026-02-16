<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\LauncherController::class, 'index'])->name('root');

Route::get('/home', [\App\Http\Controllers\LauncherController::class, 'index'])
    ->middleware(['auth'])
    ->name('home');

Route::get('/dashboard', function () {
    $needsReviewCount = \App\Models\Product::needsReview()->count();
    $productsNeedingReview = \App\Models\Product::needsReview()->latest()->take(5)->get();
    return view('home', compact('needsReviewCount', 'productsNeedingReview'));
})->middleware(['auth'])->name('dashboard');

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
    Route::post('/procurement/invoices/{id}/approve', [\App\Http\Controllers\InvoiceController::class, 'approve'])->name('invoices.approve');
    Route::post('/procurement/invoices/{id}/approve-and-pay', [\App\Http\Controllers\InvoiceController::class, 'approveAndPay'])->name('invoices.approve_and_pay');
});

// Warehouse & Store Management
Route::middleware(['auth'])->group(function () {
    // Warehouses
    Route::resource('warehouses', \App\Http\Controllers\WarehouseController::class);
    
    // Stores
    // Stores
    Route::put('stores/{store}/inventory/{inventory}/toggle-status', [\App\Http\Controllers\StoreController::class, 'toggleInventoryStatus'])->name('stores.inventory.toggle-status');
    
    // Store Finance Routes
    Route::prefix('stores/{store}')->name('stores.')->group(function() {
        Route::get('income', [\App\Http\Controllers\Store\StoreFinanceController::class, 'createIncome'])->name('income.create');
        Route::post('income', [\App\Http\Controllers\Store\StoreFinanceController::class, 'storeIncome'])->name('income.store');
        Route::get('transfer', [\App\Http\Controllers\Store\StoreFinanceController::class, 'createTransfer'])->name('transfer.create');
        Route::post('transfer', [\App\Http\Controllers\Store\StoreFinanceController::class, 'storeTransfer'])->name('transfer.store');
    });

    Route::resource('stores', \App\Http\Controllers\StoreController::class);
    
    // Warehouse Finance Routes
    Route::prefix('warehouses/{warehouse}')->name('warehouses.')->group(function() {
        Route::get('income', [\App\Http\Controllers\Warehouse\WarehouseFinanceController::class, 'createIncome'])->name('income.create');
        Route::post('income', [\App\Http\Controllers\Warehouse\WarehouseFinanceController::class, 'storeIncome'])->name('income.store');
        Route::get('transfer', [\App\Http\Controllers\Warehouse\WarehouseFinanceController::class, 'createTransfer'])->name('transfer.create');
        Route::post('transfer', [\App\Http\Controllers\Warehouse\WarehouseFinanceController::class, 'storeTransfer'])->name('transfer.store');
    });

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

    // Finance Module
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Finance\FinanceController::class, 'index'])->name('index');
        
        // General Ledger - Chart of Accounts
        Route::resource('accounts', \App\Http\Controllers\Finance\AccountController::class);
        Route::get('accounts/{account}/ledger', [\App\Http\Controllers\Finance\AccountController::class, 'ledger'])->name('accounts.ledger');
        
        // General Ledger - Journal Entries
        Route::resource('journals', \App\Http\Controllers\Finance\JournalController::class);
        
        // Transfers
        Route::get('transfers/create', [\App\Http\Controllers\Finance\TransferController::class, 'create'])->name('transfers.create');
        Route::post('transfers', [\App\Http\Controllers\Finance\TransferController::class, 'store'])->name('transfers.store');

        // Income
        Route::get('income/create', [\App\Http\Controllers\Finance\IncomeController::class, 'create'])->name('income.create');
        Route::post('income', [\App\Http\Controllers\Finance\IncomeController::class, 'store'])->name('income.store');
        
        // Account Payable (Invoices & Payments)
        Route::get('payables', [\App\Http\Controllers\Finance\PaymentController::class, 'payables'])->name('payables');
        Route::get('payables/{invoice}/pay', [\App\Http\Controllers\Finance\PaymentController::class, 'create'])->name('payments.create');
        Route::post('payments', [\App\Http\Controllers\Finance\PaymentController::class, 'store'])->name('payments.store');
        
        // Cash Management
        Route::get('cash', [\App\Http\Controllers\Finance\FinanceController::class, 'cashManagement'])->name('cash');
        
        // Bank Reconciliation
        Route::get('reconciliations', [\App\Http\Controllers\Finance\BankReconciliationController::class, 'index'])->name('reconciliations.index');
        Route::get('reconciliations/create', [\App\Http\Controllers\Finance\BankReconciliationController::class, 'create'])->name('reconciliations.create');
        Route::post('reconciliations', [\App\Http\Controllers\Finance\BankReconciliationController::class, 'store'])->name('reconciliations.store');
        Route::get('reconciliations/{reconciliation}', [\App\Http\Controllers\Finance\BankReconciliationController::class, 'show'])->name('reconciliations.show');
        Route::get('reconciliations/{reconciliation}/edit', [\App\Http\Controllers\Finance\BankReconciliationController::class, 'edit'])->name('reconciliations.edit');
        Route::put('reconciliations/{reconciliation}', [\App\Http\Controllers\Finance\BankReconciliationController::class, 'update'])->name('reconciliations.update');
        Route::delete('reconciliations/{reconciliation}', [\App\Http\Controllers\Finance\BankReconciliationController::class, 'destroy'])->name('reconciliations.destroy');
        Route::post('reconciliations/{reconciliation}/items', [\App\Http\Controllers\Finance\BankReconciliationController::class, 'updateItems'])->name('reconciliations.update-items');
        Route::post('reconciliations/{reconciliation}/finalize', [\App\Http\Controllers\Finance\BankReconciliationController::class, 'finalize'])->name('reconciliations.finalize');
        
        // Financial Reporting
        Route::get('reports', [\App\Http\Controllers\Finance\ReportController::class, 'index'])->name('reports');
        Route::get('reports/profit-loss', [\App\Http\Controllers\Finance\ReportController::class, 'profitAndLoss'])->name('reports.pl');
        Route::get('reports/balance-sheet', [\App\Http\Controllers\Finance\ReportController::class, 'balanceSheet'])->name('reports.bs');
        Route::get('reports/trial-balance', [\App\Http\Controllers\Finance\ReportController::class, 'trialBalance'])->name('reports.tb');
        Route::get('reports/aging', [\App\Http\Controllers\Finance\ReportController::class, 'payablesAging'])->name('reports.aging');
        Route::get('reports/cashflow', [\App\Http\Controllers\Finance\ReportController::class, 'cashflow'])->name('reports.cashflow');
    });

    // HRM Module
    Route::prefix('hrm')->name('hrm.')->group(function () {
        Route::resource('departments', \App\Http\Controllers\HRM\DepartmentController::class);
        
        // Employees & Salary Config
        Route::get('employees/{employee}/salary-config', [\App\Http\Controllers\HRM\EmployeeController::class, 'salaryConfig'])->name('employees.salary-config');
        Route::post('employees/{employee}/salary-config', [\App\Http\Controllers\HRM\EmployeeController::class, 'updateSalaryConfig'])->name('employees.salary-config.update');
        Route::resource('employees', \App\Http\Controllers\HRM\EmployeeController::class);
        
        // Attendance
        Route::get('attendance', [\App\Http\Controllers\HRM\AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('attendance/clock-in', [\App\Http\Controllers\HRM\AttendanceController::class, 'clockIn'])->name('attendance.clock-in');
        Route::post('attendance/clock-out', [\App\Http\Controllers\HRM\AttendanceController::class, 'clockOut'])->name('attendance.clock-out');
        Route::get('attendance/report', [\App\Http\Controllers\HRM\AttendanceController::class, 'report'])->name('attendance.report');

        // Overtime
        Route::get('overtime', [\App\Http\Controllers\HRM\OvertimeController::class, 'index'])->name('overtime.index');
        Route::post('overtime', [\App\Http\Controllers\HRM\OvertimeController::class, 'store'])->name('overtime.store');
        Route::post('overtime/{overtime}/approve', [\App\Http\Controllers\HRM\OvertimeController::class, 'approve'])->name('overtime.approve');
        Route::post('overtime/{overtime}/reject', [\App\Http\Controllers\HRM\OvertimeController::class, 'reject'])->name('overtime.reject');

        // Payroll & Components
        Route::resource('salary-components', \App\Http\Controllers\HRM\SalaryComponentController::class);
        Route::get('payroll', [\App\Http\Controllers\HRM\PayrollController::class, 'index'])->name('payroll.index');
        Route::post('payroll/generate', [\App\Http\Controllers\HRM\PayrollController::class, 'generate'])->name('payroll.generate');
        Route::get('payroll/{payroll}/payslip', [\App\Http\Controllers\HRM\PayrollController::class, 'payslip'])->name('payroll.payslip');
        Route::post('payroll/{payroll}/approve', [\App\Http\Controllers\HRM\PayrollController::class, 'approve'])->name('payroll.approve');

        // Recruitment
        Route::get('recruitment', [\App\Http\Controllers\HRM\RecruitmentController::class, 'index'])->name('recruitment.index');
        Route::resource('jobs', \App\Http\Controllers\HRM\RecruitmentController::class)->except(['index']);
        Route::get('applicants', [\App\Http\Controllers\HRM\RecruitmentController::class, 'applicants'])->name('applicants.index');
        Route::post('applicants/{applicant}/update-status', [\App\Http\Controllers\HRM\RecruitmentController::class, 'updateApplicant'])->name('applicants.update-status');
        
        // KPI
        Route::get('kpi', [\App\Http\Controllers\HRM\KpiController::class, 'index'])->name('kpi.index');
        Route::get('kpi/indicators', [\App\Http\Controllers\HRM\KpiController::class, 'indicators'])->name('kpi.indicators');
        Route::resource('evaluations', \App\Http\Controllers\HRM\KpiController::class)->except(['index']);
        
        // ESS (Employee Self Service)
        Route::get('ess', [\App\Http\Controllers\HRM\EssController::class, 'index'])->name('ess.index');
        Route::get('ess/profile', [\App\Http\Controllers\HRM\EssController::class, 'profile'])->name('ess.profile');
        Route::get('ess/payslips', [\App\Http\Controllers\HRM\EssController::class, 'payslips'])->name('ess.payslips');
        Route::get('ess/attendance', [\App\Http\Controllers\HRM\EssController::class, 'attendance'])->name('ess.attendance');
    });
});


// Store Order Management Routes (Admin)
Route::middleware(['auth'])->prefix('stores/{store}/orders')->name('stores.orders.')->group(function () {
    Route::get('/', [\App\Http\Controllers\StoreOrderController::class, 'index'])->name('index');
    Route::get('/{order}', [\App\Http\Controllers\StoreOrderController::class, 'show'])->name('show');
    Route::post('/{order}/confirm', [\App\Http\Controllers\StoreOrderController::class, 'confirm'])->name('confirm');
    Route::post('/{order}/reject', [\App\Http\Controllers\StoreOrderController::class, 'reject'])->name('reject');
});

// Marketplace Public Routes
Route::prefix('shop/{slug}')->name('marketplace.')->group(function () {
    Route::get('/', [\App\Http\Controllers\MarketplaceController::class, 'index'])->name('index');
    Route::get('/cart', [\App\Http\Controllers\MarketplaceController::class, 'viewCart'])->name('cart');
    Route::get('/checkout', [\App\Http\Controllers\MarketplaceController::class, 'checkout'])->name('checkout');
    Route::post('/order', [\App\Http\Controllers\MarketplaceController::class, 'storeOrder'])->name('order.store');
    Route::get('/order/{order}/payment', [\App\Http\Controllers\MarketplaceController::class, 'payment'])->name('payment');
    Route::post('/order/{order}/payment', [\App\Http\Controllers\MarketplaceController::class, 'uploadPayment'])->name('payment.upload');
    Route::post('/cart/add/{product}', [\App\Http\Controllers\MarketplaceController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/remove/{id}', [\App\Http\Controllers\MarketplaceController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('/product/{product}', [\App\Http\Controllers\MarketplaceController::class, 'show'])->name('product.show');
});

