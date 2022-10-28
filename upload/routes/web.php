<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', 'FrontController@index');
Route::group(['middleware' => 'languange'], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/home', 'HomeController@index')->name('home');

    Auth::routes();

    Route::resource('customers', 'CustomerController');
    Route::get('customer/export', 'CustomerController@export')->name('customers.export');
    Route::post('customer/import', 'CustomerController@import')->name('customers.import');

    Route::resource('items', 'ItemController');
    Route::get('item/export', 'ItemController@export')->name('items.export');
    Route::post('item/import', 'ItemController@import')->name('items.import');

    Route::post('item/customcreate', 'ItemController@customCreate')->name('items.customcreate');
    Route::resource('item-kits', 'ItemKitController');
    Route::resource('inventory', 'InventoryController');

    Route::resource('suppliers', 'SupplierController');
    Route::get('supplier/export', 'SupplierController@export')->name('suppliers.export');
    Route::post('supplier/import', 'SupplierController@import')->name('suppliers.import');

    Route::resource('receivings', 'ReceivingController');
    Route::get('receivings/show-invoice/{id}', 'ReceivingController@showInvoice')->name('receivings.show-invoice');

    Route::resource('sales', 'SaleController');
    Route::match(['get', 'post'], 'sales/refund/{id}', 'SaleController@refund')->name('sale.refund');
    Route::match(['get', 'post'], 'sales/edit/{id}', 'SaleController@editSale')->name('sale.edit');
    Route::get('sales/show-invoice/{id}', 'SaleController@showInvoice')->name('sale.show-invoice');
    Route::get('sales/send-email/{sale}', 'SaleController@mailInvoice')->name('sale.mail-invoice');

    Route::get('reports/receivings', 'ReceivingReportController@index')->name('report.receiving');
    Route::get('reports/sales', 'SaleReportController@index')->name('report.sale');
    Route::get('reports/stocks', 'SaleReportController@stockReport')->name('report.stock');

    Route::resource('reports/dailyreport', 'DailyReportController');

    Route::resource('employees', 'EmployeeController');
    Route::post('/employees/assignroles', 'EmployeeController@assignRoles')->name('assign.roles');
    Route::post('/employeerole/create', 'EmployeeController@roleCreate')->name('employeerole.create');
    Route::get('/allpermissions/{role_id?}', 'EmployeeController@permissionList')->name('permissions.list');
    Route::post('permissions/create', 'EmployeeController@createPermission')->name('permissions.create');
    Route::post('permissionrole/create', 'EmployeeController@rolePermissionMapping')->name('permissionrole.create');

    Route::resource('expense', 'ExpenseController');
    Route::resource('expensecategory', 'ExpenseCategoryController');

    // Category
    Route::resource('category', 'CategoryController');
    // Item Attribute
    Route::resource('itemattribute', 'ItemAttributeController');

    Route::resource('supplierpayments', 'SupplierPaymentController');
    Route::resource('receivingpayments', 'ReceivingPaymentController');

    Route::resource('api/item', 'SaleApiController');
    Route::resource('api/recitem', 'ReceivingApiController');
    Route::resource('api/receivingtemp', 'ReceivingTempApiController');

    Route::resource('api/saletemp', 'SaleTempApiController');
    Route::resource('accounts', 'AccountController');
    Route::resource('transactions', 'TransactionController');

    Route::resource('api/itemkittemp', 'ItemKitController');

    Route::get('api/item-kit-temp', 'ItemKitController@itemKitApi')->name('item-kit-temp.index');
    Route::get('api/item-kits', 'ItemKitController@itemKits')->name('item-kits.all');
    Route::get('barcode', 'BarcodeController@index');
    Route::post('/processbarcode', 'BarcodeController@processbarcode');
    Route::post('store-item-kits', 'ItemKitController@storeItemKits')->name('item-kits.save');

    Route::resource('flexiblepossetting', 'FlexiblePosSettingController');
    Route::post('/flexiblepossetting/add-payment-type', 'FlexiblePosSettingController@addPaymentType')->name('flexiblepossetting.payment_type');
    Route::post('/flexiblepossetting/store-settings', 'FlexiblePosSettingController@storeSettings')->name('flexiblepossetting.store_settings');
    Route::get('/flexiblepossetting/update-payment-type/{id}', 'FlexiblePosSettingController@updatePaymentType')->name('flexiblepossetting.payment_type.update');

    Route::resource('salepayments', 'SalePaymentController');
    Route::resource('customerpayments', 'CustomerPaymentController');
});

Route::post('install/environment/saveNewWizard', [
    'as' => 'environmentSaveWizard',
    'uses' => 'NewEnvironmentController@saveEnvWizard'
]);

Route::get('storage-link', function () {
    Artisan::call('storage:link');
    return back();
})->name('storage.link');
