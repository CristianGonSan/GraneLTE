<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\InfoController;

use App\Http\Controllers\Auth\{
    LoginController,
};

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Exports\ExportController;
use App\Http\Controllers\Inventory\{
    CategoryController,
    DashboardController,
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
    AdjustmentController,
    DocumentController,
    IssueController,
    ReceiptController,
    TransferController
};

use App\Http\Controllers\Lookups\{
    CategoryLookup,
    RawMaterialLookup,
    ResponsibleLookup,
    SupplierLookup,
    UnitLookup,
    UserLookup,
    WarehouseLookup
};
use App\Http\Controllers\MediaController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() =>  redirect()->route('dashboard'))->name('root');
Route::get('/home', fn() => redirect()->route('dashboard'))->name('home');

Route::get('/disabled', fn() => view('auth.disabled'))->name('auth.disabled');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'check.user.active'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('exports')->name('exports.')->group(function () {
        Route::get('/', [ExportController::class, 'index'])->name('index');
    });

    Route::get('info', [InfoController::class, 'index'])->name('info');

    Route::get('account', [AccountController::class, 'show'])->name('account');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class)->only(
            ['index', 'show', 'create', 'edit']
        );
        Route::resource('roles', RoleController::class)->only(
            ['index', 'show', 'create', 'edit']
        );
    });

    Route::resource('units', UnitController::class)->only(
        ['index', 'show', 'create', 'edit']
    );
    Route::resource('categories', CategoryController::class)->only(
        ['index', 'show', 'create', 'edit']
    );
    Route::resource('warehouses', WarehouseController::class)->only(
        ['index', 'show', 'create', 'edit']
    );
    Route::resource('suppliers', SupplierController::class)->only(
        ['index', 'show', 'create', 'edit']
    );
    Route::resource('responsibles', ResponsibleController::class)->only(
        ['index', 'show', 'create', 'edit']
    );
    Route::resource('raw-materials', RawMaterialController::class)->only(
        ['index', 'show', 'create', 'edit']
    );

    Route::prefix('raw-material-documents')->name('raw-material-documents.')->group(function () {
        Route::get('/', [DocumentController::class, 'index'])->name('index');

        Route::prefix('receipts')->name('receipts.')->group(function () {
            Route::get('create', [ReceiptController::class, 'create'])->name('create');

            Route::middleware('check.document.type:receipt')->group(function () {
                Route::get('{document}/', [ReceiptController::class, 'show'])->name('show');
                Route::get('{document}/edit', [ReceiptController::class, 'edit'])
                    ->middleware('check.document.editable')->name('edit');
            });
        });

        Route::prefix('issues')->name('issues.')->group(function () {
            Route::get('create', [IssueController::class, 'create'])->name('create');

            Route::middleware('check.document.type:issue')->group(function () {
                Route::get('{document}/', [IssueController::class, 'show'])->name('show');
                Route::get('{document}/edit', [IssueController::class, 'edit'])
                    ->middleware('check.document.editable')->name('edit');
            });
        });

        Route::prefix('transfers')->name('transfers.')->group(function () {
            Route::get('create', [TransferController::class, 'create'])->name('create');

            Route::middleware('check.document.type:transfer')->group(function () {
                Route::get('{document}/', [TransferController::class, 'show'])->name('show');
                Route::get('{document}/edit', [TransferController::class, 'edit'])
                    ->middleware('check.document.editable')->name('edit');
            });
        });

        Route::prefix('adjustments')->name('adjustments.')->group(function () {
            Route::get('create', [AdjustmentController::class, 'create'])->name('create');

            Route::middleware('check.document.type:adjustment')->group(function () {
                Route::get('{document}/', [AdjustmentController::class, 'show'])->name('show');
                Route::get('{document}/edit', [AdjustmentController::class, 'edit'])
                    ->middleware('check.document.editable')->name('edit');
            });
        });
    });

    Route::get('raw-material-movements/index', [RawMaterialMovementController::class, 'index'])->name('raw-material-movements.index');
    Route::get('raw-material-batches/index', [RawMaterialBatchController::class, 'index'])->name('raw-material-batches.index');
    Route::get('raw-material-batches/{id}/show', [RawMaterialBatchController::class, 'show'])->name('raw-material-batches.show');
    Route::get('raw-material-stocks/index', [RawMaterialStockController::class, 'index'])->name('raw-material-stocks.index');

    Route::prefix('lookups')->name('lookups.')->group(function () {
        Route::get('units/select2', [UnitLookup::class, 'select2'])->name('units.select2');
        Route::get('categories/select2', [CategoryLookup::class, 'select2'])->name('categories.select2');
        Route::get('suppliers/select2', [SupplierLookup::class, 'select2'])->name('suppliers.select2');
        Route::get('responsibles/select2', [ResponsibleLookup::class, 'select2'])->name('responsibles.select2');
        Route::get('users/select2', [UserLookup::class, 'select2'])->name('users.select2');
        Route::get('warehouses/select2', [WarehouseLookup::class, 'select2'])->name('warehouses.select2');
        Route::get('raw-materials/select2', [RawMaterialLookup::class, 'select2'])->name('raw-materials.select2');
    });

    Route::prefix('media')->name('media.')->group(function () {
        Route::get('{media}/show', [MediaController::class, 'show'])->name('show');
        Route::get('{media}/download', [MediaController::class, 'download'])->name('download');
    });
});
