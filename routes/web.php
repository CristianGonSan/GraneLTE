<?php

use Illuminate\Support\Facades\Route;

// Auth
use App\Http\Controllers\Auth\LoginController;

// Core
use App\Http\Controllers\AccountController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\MediaController;

// Admin
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;

// Exports
use App\Http\Controllers\Exports\ExportController;

// Inventory
use App\Http\Controllers\Inventory\CategoryController;
use App\Http\Controllers\Inventory\DashboardController;
use App\Http\Controllers\Inventory\RawMaterialBatchController;
use App\Http\Controllers\Inventory\RawMaterialController;
use App\Http\Controllers\Inventory\RawMaterialMovementController;
use App\Http\Controllers\Inventory\RawMaterialStockController;
use App\Http\Controllers\Inventory\ResponsibleController;
use App\Http\Controllers\Inventory\SupplierController;
use App\Http\Controllers\Inventory\UnitController;
use App\Http\Controllers\Inventory\WarehouseController;

// Documents
use App\Http\Controllers\Inventory\RawMaterialDocuments\AdjustmentController;
use App\Http\Controllers\Inventory\RawMaterialDocuments\DocumentController;
use App\Http\Controllers\Inventory\RawMaterialDocuments\IssueController;
use App\Http\Controllers\Inventory\RawMaterialDocuments\ReceiptController;
use App\Http\Controllers\Inventory\RawMaterialDocuments\TransferController;

// Lookups
use App\Http\Controllers\Lookups\CategoryLookup;
use App\Http\Controllers\Lookups\RawMaterialLookup;
use App\Http\Controllers\Lookups\ResponsibleLookup;
use App\Http\Controllers\Lookups\SupplierLookup;
use App\Http\Controllers\Lookups\UnitLookup;
use App\Http\Controllers\Lookups\UserLookup;
use App\Http\Controllers\Lookups\WarehouseLookup;

/*
|--------------------------------------------------------------------------
| Redirects
|--------------------------------------------------------------------------
*/

Route::redirect('/', 'dashboard')
    ->name('root');
Route::redirect('/home', 'dashboard')
    ->name('home');

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/

Route::get('disabled', fn() => view('auth.disabled'))
    ->name('auth.disabled');

Route::get('login',     [LoginController::class, 'showLoginForm'])
    ->name('login');
Route::post('login',    [LoginController::class, 'login']);
Route::post('logout',   [LoginController::class, 'logout'])
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'check.user.active'])->group(function () {

    // Core
    Route::get('dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
    Route::get('info',      [InfoController::class, 'index'])
        ->name('info');
    Route::get('account',   [AccountController::class, 'show'])
        ->name('account');

    /*
    |--------------------------------------------------------------------------
    | Admin
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->group(function () {

        // Users
        Route::get('users', [UserController::class, 'index'])
            ->name('users.index')
            ->middleware('permission:users.view');
        Route::get('users/create', [UserController::class, 'create'])
            ->name('users.create')
            ->middleware('permission:users.create');
        Route::get('users/{user}', [UserController::class, 'show'])
            ->name('users.show')
            ->middleware('permission:users.view');
        Route::get('users/{user}/edit', [UserController::class, 'edit'])
            ->name('users.edit')
            ->middleware('permission:users.edit');

        // Roles
        Route::get('roles', [RoleController::class, 'index'])
            ->name('roles.index')
            ->middleware('permission:roles.view');
        Route::get('roles/create', [RoleController::class, 'create'])
            ->name('roles.create')
            ->middleware('permission:roles.create');
        Route::get('roles/{role}', [RoleController::class, 'show'])
            ->name('roles.show')
            ->middleware('permission:roles.view');
        Route::get('roles/{role}/edit', [RoleController::class, 'edit'])
            ->name('roles.edit')
            ->middleware('permission:roles.edit');
    });

    /*
    |--------------------------------------------------------------------------
    | Exports
    |--------------------------------------------------------------------------
    */
    Route::get('exports', [ExportController::class, 'index'])
        ->name('exports.index')
        ->middleware('permission:reports.export');

    /*
    |--------------------------------------------------------------------------
    | Catálogos
    |--------------------------------------------------------------------------
    */

    // Units
    Route::get('units', [UnitController::class, 'index'])
        ->name('units.index')
        ->middleware('permission:units.view');
    Route::get('units/create', [UnitController::class, 'create'])
        ->name('units.create')
        ->middleware('permission:units.create');
    Route::get('units/{unit}', [UnitController::class, 'show'])
        ->name('units.show')
        ->middleware('permission:units.view');
    Route::get('units/{unit}/edit', [UnitController::class, 'edit'])
        ->name('units.edit')
        ->middleware('permission:units.edit');

    // Categories
    Route::get('categories', [CategoryController::class, 'index'])
        ->name('categories.index')
        ->middleware('permission:categories.view');
    Route::get('categories/create', [CategoryController::class, 'create'])
        ->name('categories.create')
        ->middleware('permission:categories.create');
    Route::get('categories/{category}', [CategoryController::class, 'show'])
        ->name('categories.show')
        ->middleware('permission:categories.view');
    Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])
        ->name('categories.edit')
        ->middleware('permission:categories.edit');

    // Warehouses
    Route::get('warehouses', [WarehouseController::class, 'index'])
        ->name('warehouses.index')
        ->middleware('permission:warehouses.view');
    Route::get('warehouses/create', [WarehouseController::class, 'create'])
        ->name('warehouses.create')
        ->middleware('permission:warehouses.create');
    Route::get('warehouses/{warehouse}', [WarehouseController::class, 'show'])
        ->name('warehouses.show')
        ->middleware('permission:warehouses.view');
    Route::get('warehouses/{warehouse}/edit', [WarehouseController::class, 'edit'])
        ->name('warehouses.edit')
        ->middleware('permission:warehouses.edit');

    // Suppliers
    Route::get('suppliers', [SupplierController::class, 'index'])
        ->name('suppliers.index')
        ->middleware('permission:suppliers.view');
    Route::get('suppliers/create', [SupplierController::class, 'create'])
        ->name('suppliers.create')
        ->middleware('permission:suppliers.create');
    Route::get('suppliers/{supplier}', [SupplierController::class, 'show'])
        ->name('suppliers.show')
        ->middleware('permission:suppliers.view');
    Route::get('suppliers/{supplier}/edit', [SupplierController::class, 'edit'])
        ->name('suppliers.edit')
        ->middleware('permission:suppliers.edit');

    // Responsibles
    Route::get('responsibles', [ResponsibleController::class, 'index'])
        ->name('responsibles.index')
        ->middleware('permission:responsibles.view');
    Route::get('responsibles/create', [ResponsibleController::class, 'create'])
        ->name('responsibles.create')
        ->middleware('permission:responsibles.create');
    Route::get('responsibles/{responsible}', [ResponsibleController::class, 'show'])
        ->name('responsibles.show')
        ->middleware('permission:responsibles.view');
    Route::get('responsibles/{responsible}/edit', [ResponsibleController::class, 'edit'])
        ->name('responsibles.edit')
        ->middleware('permission:responsibles.edit');

    // Raw Materials
    Route::get('raw-materials', [RawMaterialController::class, 'index'])
        ->name('raw-materials.index')
        ->middleware('permission:raw-materials.view');
    Route::get('raw-materials/create', [RawMaterialController::class, 'create'])
        ->name('raw-materials.create')
        ->middleware('permission:raw-materials.create');
    Route::get('raw-materials/{raw_material}', [RawMaterialController::class, 'show'])
        ->name('raw-materials.show')
        ->middleware('permission:raw-materials.view');
    Route::get('raw-materials/{raw_material}/edit', [RawMaterialController::class, 'edit'])
        ->name('raw-materials.edit')
        ->middleware('permission:raw-materials.edit');

    /*
    |--------------------------------------------------------------------------
    | Documentos de materia prima
    |--------------------------------------------------------------------------
    */
    Route::prefix('raw-material-documents')->name('raw-material-documents.')->group(function () {

        Route::get('/', [DocumentController::class, 'index'])
            ->name('index')
            ->middleware('permission:raw-material-documents.view');

        // Receipts
        Route::prefix('receipts')->name('receipts.')->group(function () {
            Route::get('create', [ReceiptController::class, 'create'])
                ->name('create')
                ->middleware('permission:raw-material-documents.create');

            Route::middleware('check.document.type:receipt')->group(function () {
                Route::get('{document}', [ReceiptController::class, 'show'])
                    ->name('show')
                    ->middleware('permission:raw-material-documents.view');

                Route::get('{document}/edit', [ReceiptController::class, 'edit'])
                    ->name('edit')
                    ->middleware([
                        'check.document.editable',
                        'permission:raw-material-documents.edit'
                    ]);
            });
        });

        // Issues
        Route::prefix('issues')->name('issues.')->group(function () {
            Route::get('create', [IssueController::class, 'create'])
                ->name('create')
                ->middleware('permission:raw-material-documents.create');

            Route::middleware('check.document.type:issue')->group(function () {
                Route::get('{document}', [IssueController::class, 'show'])
                    ->name('show')
                    ->middleware('permission:raw-material-documents.view');

                Route::get('{document}/edit', [IssueController::class, 'edit'])
                    ->name('edit')
                    ->middleware([
                        'check.document.editable',
                        'permission:raw-material-documents.edit'
                    ]);
            });
        });

        // Transfers
        Route::prefix('transfers')->name('transfers.')->group(function () {
            Route::get('create', [TransferController::class, 'create'])
                ->name('create')
                ->middleware('permission:raw-material-documents.create');

            Route::middleware('check.document.type:transfer')->group(function () {
                Route::get('{document}', [TransferController::class, 'show'])
                    ->name('show')
                    ->middleware('permission:raw-material-documents.view');

                Route::get('{document}/edit', [TransferController::class, 'edit'])
                    ->name('edit')
                    ->middleware([
                        'check.document.editable',
                        'permission:raw-material-documents.edit'
                    ]);
            });
        });

        // Adjustments
        Route::prefix('adjustments')->name('adjustments.')->group(function () {
            Route::get('create', [AdjustmentController::class, 'create'])
                ->name('create')
                ->middleware('permission:raw-material-documents.create');

            Route::middleware('check.document.type:adjustment')->group(function () {
                Route::get('{document}', [AdjustmentController::class, 'show'])
                    ->name('show')
                    ->middleware('permission:raw-material-documents.view');

                Route::get('{document}/edit', [AdjustmentController::class, 'edit'])
                    ->name('edit')
                    ->middleware([
                        'check.document.editable',
                        'permission:raw-material-documents.edit'
                    ]);
            });
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Movimientos, lotes y stock de materia prima
    |--------------------------------------------------------------------------
    */
    Route::get('raw-material-movements', [RawMaterialMovementController::class, 'index'])
        ->name('raw-material-movements.index')
        ->middleware('permission:raw-material-movements.view');

    Route::middleware('permission:raw-material-batches.view')->group(function () {
        Route::get('raw-material-batches', [RawMaterialBatchController::class, 'index'])
            ->name('raw-material-batches.index');
        Route::get('raw-material-batches/{id}', [RawMaterialBatchController::class, 'show'])
            ->name('raw-material-batches.show');
    });

    Route::get('raw-material-stocks', [RawMaterialStockController::class, 'index'])
        ->name('raw-material-stocks.index')
        ->middleware('permission:raw-material-stocks.view');

    /*
    |--------------------------------------------------------------------------
    | Lookups (Select2)
    |--------------------------------------------------------------------------
    */
    Route::prefix('lookups')->name('lookups.')->group(function () {
        Route::get('units/select2', [UnitLookup::class, 'select2'])
            ->name('units.select2');
        Route::get('categories/select2', [CategoryLookup::class, 'select2'])
            ->name('categories.select2');
        Route::get('suppliers/select2', [SupplierLookup::class, 'select2'])
            ->name('suppliers.select2');
        Route::get('responsibles/select2', [ResponsibleLookup::class, 'select2'])
            ->name('responsibles.select2');
        Route::get('users/select2', [UserLookup::class, 'select2'])
            ->name('users.select2');
        Route::get('warehouses/select2', [WarehouseLookup::class, 'select2'])
            ->name('warehouses.select2');
        Route::get('raw-materials/select2', [RawMaterialLookup::class, 'select2'])
            ->name('raw-materials.select2');
    });

    /*
    |--------------------------------------------------------------------------
    | Media
    |--------------------------------------------------------------------------
    */
    Route::prefix('media')->name('media.')->group(function () {
        Route::get('{media}/show', [MediaController::class, 'show'])
            ->name('show')
            ->middleware('permission:media.view');
        Route::get('{media}/download', [MediaController::class, 'download'])
            ->name('download')
            ->middleware('permission:media.view');
    });
});
