<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InfoController;

use App\Http\Controllers\Auth\{
    LoginController,
};

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;

use App\Http\Controllers\Inventory\{
    CategoryController,
    RawMaterialBatchController,
    SupplierController,
    UnitController,
    WarehouseController,
    RawMaterialController,
    RawMaterialMovementController,
    RawMaterialStockController,
    ResponsibleController
};

use App\Http\Controllers\Inventory\RawMaterialDocuments\{
    DocumentController,
    IssueController,
    ReceiptController
};

use App\Http\Controllers\Lookups\{
    CategoryLookup,
    RawMaterialLookup,
    ResponsibleLookup,
    SupplierLookup,
    UnitLookup,
    WarehouseLookup
};
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('root');

Route::get('/home', function () {
    return redirect()->route('dashboard');
})->name('home');

Route::get('/disabled', function () {
    return view('auth.disabled');
})->name('auth.disabled');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('info', [InfoController::class, 'index'])->name('info');

    Route::get('account', [AccountController::class, 'show'])->name('account');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class)->only(['index', 'create', 'edit']);
        Route::resource('roles', RoleController::class)->only(['index', 'create', 'edit']);
    });

    Route::resource('units', UnitController::class)->only(['index', 'create', 'edit']);
    Route::resource('categories', CategoryController::class)->only(['index', 'create', 'edit']);
    Route::resource('warehouses', WarehouseController::class)->only(['index', 'create', 'edit']);
    Route::resource('suppliers', SupplierController::class)->only(['index', 'create', 'edit']);
    Route::resource('responsibles', ResponsibleController::class)->only(['index', 'create', 'edit']);
    Route::resource('raw-materials', RawMaterialController::class)->only(['index', 'create', 'edit']);

    Route::prefix('raw-material-documents')->name('raw-material-documents.')->group(function () {
        Route::get('', [DocumentController::class, 'index'])->name('index');

        Route::prefix('receipts')->name('receipts.')->group(function () {
            Route::get('create', [ReceiptController::class, 'create'])->name('create');

            Route::middleware('check.document:receipt')->group(function () {
                Route::get('{document}/show', [ReceiptController::class, 'show'])->name('show');
                Route::get('{document}/edit', [ReceiptController::class, 'edit'])->name('edit');
            });
        });

        Route::prefix('issues')->name('issues.')->group(function () {
            Route::get('create', [IssueController::class, 'create'])->name('create');

            Route::middleware('check.document:issue')->group(function () {
                Route::get('{document}/show', [IssueController::class, 'show'])->name('show');
                Route::get('{document}/edit', [IssueController::class, 'edit'])->name('edit');
            });
        });
    });

    Route::get('raw-material-movements/index', [RawMaterialMovementController::class, 'index'])->name('raw-material-movements.index');
    Route::get('raw-material-batches/index', [RawMaterialBatchController::class, 'index'])->name('raw-material-batches.index');
    Route::get('raw-material-stocks/index', [RawMaterialStockController::class, 'index'])->name('raw-material-stocks.index');

    Route::prefix('lookups')->name('lookups.')->group(function () {
        Route::get('units/select2', [UnitLookup::class, 'select2'])->name('units.select2');
        Route::get('categories/select2', [CategoryLookup::class, 'select2'])->name('categories.select2');
        Route::get('suppliers/select2', [SupplierLookup::class, 'select2'])->name('suppliers.select2');
        Route::get('responsibles/select2', [ResponsibleLookup::class, 'select2'])->name('responsibles.select2');
        Route::get('warehouses/select2', [WarehouseLookup::class, 'select2'])->name('warehouses.select2');
        Route::get('raw-materials/select2', [RawMaterialLookup::class, 'select2'])->name('raw-materials.select2');
    });
});
