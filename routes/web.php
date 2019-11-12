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

Route::get('/', function () {
    return redirect(route('home'));
});
Route::get('lang/{locale}', 'IndexController@lang')->name('lang');
Auth::routes();

Route::any('/home', 'HomeController@index')->name('home');


Route::any('/payment/index/{type}/{id}', 'PaymentController@index')->name('payment.index');
Route::post('/payment/create', 'PaymentController@create')->name('payment.create');
Route::post('/payment/edit', 'PaymentController@edit')->name('payment.edit');
Route::get('/payment/report/{id}', 'PaymentController@report')->name('payment.report');
Route::get('/payment/delete/{id}', 'PaymentController@delete')->name('payment.delete');

Route::any('/product/index', 'ProductController@index')->name('product.index');
Route::post('/get_products', 'ProductController@getProducts')->name('product.get');
Route::post('/product/create', 'ProductController@create')->name('product.create');
Route::post('/product/edit', 'ProductController@edit')->name('product.edit');
Route::post('/product/produce_create', 'ProductController@produce_create')->name('product.produce_create');
Route::get('/product/stock/{id}', 'ProductController@stock')->name('product.stock');
Route::get('/product/delete/{id}', 'ProductController@delete')->name('product.delete');

Route::any('/invoice/index', 'InvoiceController@index')->name('invoice.index');
Route::get('/invoice/create', 'InvoiceController@create')->name('invoice.create');
Route::post('/invoice/save', 'InvoiceController@save')->name('invoice.save');
Route::get('/invoice/edit/{id}', 'InvoiceController@edit')->name('invoice.edit');
Route::post('/invoice/update', 'InvoiceController@update')->name('invoice.update');
Route::get('/invoice/detail/{id}', 'InvoiceController@detail')->name('invoice.detail');
Route::get('/invoice/delete/{id}', 'InvoiceController@delete')->name('invoice.delete');

Route::any('/proforma/index', 'ProformaController@index')->name('proforma.index');
Route::get('/proforma/create', 'ProformaController@create')->name('proforma.create');
Route::post('/proforma/save', 'ProformaController@save')->name('proforma.save');
Route::get('/proforma/edit/{id}', 'ProformaController@edit')->name('proforma.edit');
Route::post('/proforma/update', 'ProformaController@update')->name('proforma.update');
Route::get('/proforma/detail/{id}', 'ProformaController@detail')->name('proforma.detail');
Route::get('/proforma/delete/{id}', 'ProformaController@delete')->name('proforma.delete');
Route::get('/proforma/submit/{id}', 'ProformaController@submit')->name('proforma.submit');
Route::post('/proforma/save_submit', 'ProformaController@save_submit')->name('proforma.save_submit');
Route::any('/proforma/container/{id}', 'ProformaController@container')->name('proforma.container');

Route::any('/shipment/index', 'ShipmentController@index')->name('shipment.index');
Route::get('/shipment/delete/{id}', 'ShipmentController@delete')->name('shipment.delete');
Route::get('/shipment/receive/{id}', 'ShipmentController@receive')->name('shipment.receive');
Route::get('/shipment/edit/{id}', 'ShipmentController@edit')->name('shipment.edit');
Route::post('/shipment/update', 'ShipmentController@update')->name('shipment.update');
Route::get('/shipment/detail/{id}', 'ShipmentController@detail')->name('shipment.detail');
Route::post('/shipment/save_receive', 'ShipmentController@save_receive')->name('shipment.save_receive');

Route::any('/sale_shipment/index', 'SaleShipmentController@index')->name('sale_shipment.index');
Route::get('/sale_shipment/delete/{id}', 'SaleShipmentController@delete')->name('sale_shipment.delete');
Route::get('/sale_shipment/receive/{id}', 'SaleShipmentController@receive')->name('sale_shipment.receive');
Route::get('/sale_shipment/detail/{id}', 'SaleShipmentController@detail')->name('sale_shipment.detail');
Route::post('/sale_shipment/save_receive', 'SaleShipmentController@save_receive')->name('sale_shipment.save_receive');

Route::any('/payment/index/{type}/{id}', 'PaymentController@index')->name('payment.index');
Route::post('/payment/create', 'PaymentController@create')->name('payment.create');
Route::post('/payment/edit', 'PaymentController@edit')->name('payment.edit');
Route::get('/payment/delete/{id}', 'PaymentController@delete')->name('payment.delete');

Route::any('/container/index', 'ContainerController@index')->name('container.index');
Route::get('/container/create', 'ContainerController@create')->name('container.create');
Route::post('/container/save', 'ContainerController@save')->name('container.save');
Route::get('/container/edit/{id}', 'ContainerController@edit')->name('container.edit');
Route::post('/container/update', 'ContainerController@update')->name('container.update');
Route::get('/container/detail/{id}', 'ContainerController@detail')->name('container.detail');
Route::get('/container/delete/{id}', 'ContainerController@delete')->name('container.delete');
Route::any('/container/bl', 'ContainerController@search_by_bl')->name('container.bl');
Route::any('/container/booking', 'ContainerController@search_by_booking')->name('container.booking');

Route::any('/sale_proforma/index', 'SaleProformaController@index')->name('sale_proforma.index');
Route::get('/sale_proforma/create', 'SaleProformaController@create')->name('sale_proforma.create');
Route::post('/sale_proforma/save', 'SaleProformaController@save')->name('sale_proforma.save');
Route::get('/sale_proforma/edit/{id}', 'SaleProformaController@edit')->name('sale_proforma.edit');
Route::post('/sale_proforma/update', 'SaleProformaController@update')->name('sale_proforma.update');
Route::get('/sale_proforma/detail/{id}', 'SaleProformaController@detail')->name('sale_proforma.detail');
Route::get('/sale_proforma/delete/{id}', 'SaleProformaController@delete')->name('sale_proforma.delete');
Route::get('/sale_proforma/submit/{id}', 'SaleProformaController@submit')->name('sale_proforma.submit');
Route::get('/sale_proforma/report/{id}', 'SaleProformaController@report')->name('sale_proforma.report');
Route::get('/sale_proforma/email/{id}', 'SaleProformaController@email')->name('sale_proforma.email');
Route::post('/sale_proforma/save_submit', 'SaleProformaController@save_submit')->name('sale_proforma.save_submit');
Route::get('/sale_proforma/container/{id}', 'SaleProformaController@container')->name('sale_proforma.container');

Route::any('/sale/index', 'SaleController@index')->name('sale.index');
Route::get('/sale/create', 'SaleController@create')->name('sale.create');
Route::post('/sale/save', 'SaleController@save')->name('sale.save');
Route::get('/sale/edit/{id}', 'SaleController@edit')->name('sale.edit');
Route::post('/sale/update', 'SaleController@update')->name('sale.update');
Route::get('/sale/detail/{id}', 'SaleController@detail')->name('sale.detail');
Route::get('/sale/report/{id}', 'SaleController@report')->name('sale.report');
Route::get('/sale/email/{id}', 'SaleController@email')->name('sale.email');
Route::get('/sale/delete/{id}', 'SaleController@delete')->name('sale.delete');

Route::any('/supplier/index', 'SupplierController@index')->name('supplier.index');
Route::post('/supplier/create', 'SupplierController@create')->name('supplier.create');
Route::post('/supplier/ajax_create', 'SupplierController@ajax_create')->name('supplier.ajax_create');
Route::post('/supplier/edit', 'SupplierController@edit')->name('supplier.edit');
Route::get('/supplier/report/{id}', 'SupplierController@report')->name('supplier.report');
Route::any('/supplier/invoices/{id}', 'SupplierController@invoices')->name('supplier.invoices');
Route::any('/supplier/payments/{id}', 'SupplierController@payments')->name('supplier.payments');
Route::get('/supplier/delete/{id}', 'SupplierController@delete')->name('supplier.delete');

Route::any('/customer/index', 'CustomerController@index')->name('customer.index');
Route::post('/customer/create', 'CustomerController@create')->name('customer.create');
Route::post('/customer/ajax_create', 'CustomerController@ajax_create')->name('customer.ajax_create');
Route::post('/customer/edit', 'CustomerController@edit')->name('customer.edit');
Route::get('/customer/report/{id}', 'CustomerController@report')->name('customer.report');
Route::any('/customer/sales/{id}', 'CustomerController@sales')->name('customer.sales');
Route::any('/customer/payments/{id}', 'CustomerController@payments')->name('customer.payments');
Route::get('/customer/delete/{id}', 'CustomerController@delete')->name('customer.delete');

Route::any('/transaction/index', 'TransactionController@index')->name('transaction.index');
Route::any('/transaction/daily', 'TransactionController@daily')->name('transaction.daily');
Route::get('/transaction/create', 'TransactionController@create')->name('transaction.create');
Route::post('/transaction/save', 'TransactionController@save')->name('transaction.save');
Route::get('/transaction/edit', 'TransactionController@edit')->name('transaction.edit');
Route::post('/transaction/update', 'TransactionController@update')->name('transaction.update');
Route::get('/transaction/delete/{id}', 'TransactionController@delete')->name('transaction.delete');

Route::get('/profile', 'UserController@profile')->name('profile');
Route::post('/updateuser', 'UserController@updateuser')->name('updateuser');
Route::any('/users/index', 'UserController@index')->name('users.index');
Route::post('/user/create', 'UserController@create')->name('user.create');
Route::post('/user/edit', 'UserController@edituser')->name('user.edit');
Route::get('/user/delete/{id}', 'UserController@delete')->name('user.delete');


Route::get('get_products', 'VueController@get_products');
Route::post('get_product', 'VueController@get_product')->name('get_product');
Route::get('get_first_product', 'VueController@get_first_product');
Route::post('get_data', 'VueController@get_data');
Route::post('get_invoice', 'VueController@get_invoice');
Route::post('get_proforma', 'VueController@get_proforma');
Route::post('get_sale', 'VueController@get_sale');
Route::post('get_sale_proforma', 'VueController@get_sale_proforma');
Route::post('get_items', 'VueController@get_items');
Route::post('get_shipment', 'VueController@get_shipment');
Route::post('get_sale_shipment', 'VueController@get_sale_shipment');
Route::post('get_container', 'VueController@get_container');
Route::post('get_received_quantity', 'VueController@get_received_quantity');
Route::post('get_autocomplete_products', 'VueController@get_autocomplete_products');

Route::any('/category/index', 'CategoryController@index')->name('category.index');
Route::post('/category/create', 'CategoryController@create')->name('category.create');
Route::post('/category/edit', 'CategoryController@edit')->name('category.edit');
Route::get('/category/delete/{id}', 'CategoryController@delete')->name('category.delete');

Route::any('/search', 'HomeController@search')->name('search');
Route::post('/set_pagesize', 'HomeController@set_pagesize')->name('set_pagesize');
