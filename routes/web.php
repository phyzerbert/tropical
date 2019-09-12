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

Route::get('/home', 'HomeController@index')->name('home');


Route::any('/payment/index/{type}/{id}', 'PaymentController@index')->name('payment.index');
Route::post('/payment/create', 'PaymentController@create')->name('payment.create');
Route::post('/payment/edit', 'PaymentController@edit')->name('payment.edit');
Route::get('/payment/delete/{id}', 'PaymentController@delete')->name('payment.delete');

Route::any('/product/index', 'ProductController@index')->name('product.index');
Route::post('/get_products', 'ProductController@getProducts')->name('product.get');
Route::post('/product/create', 'ProductController@create')->name('product.create');
Route::post('/product/edit', 'ProductController@edit')->name('product.edit');
Route::get('/product/delete/{id}', 'ProductController@delete')->name('product.delete');
Route::post('/product/produce_create', 'ProductController@produce_create')->name('product.produce_create');

Route::any('/supplier/index', 'SupplierController@index')->name('supplier.index');
Route::post('/supplier/create', 'SupplierController@create')->name('supplier.create');
Route::post('/supplier/ajax_create', 'SupplierController@ajax_create')->name('supplier.ajax_create');
Route::post('/supplier/edit', 'SupplierController@edit')->name('supplier.edit');
Route::get('/supplier/report/{id}', 'SupplierController@report')->name('supplier.report');
Route::get('/supplier/delete/{id}', 'SupplierController@delete')->name('supplier.delete');

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
Route::get('/proforma/container/{id}', 'ProformaController@container')->name('proforma.container');

Route::any('/shipment/index', 'ShipmentController@index')->name('shipment.index');
Route::get('/shipment/delete/{id}', 'ShipmentController@delete')->name('shipment.delete');
Route::get('/shipment/receive/{id}', 'ShipmentController@receive')->name('shipment.receive');
Route::get('/shipment/detail/{id}', 'ShipmentController@detail')->name('shipment.detail');
Route::post('/shipment/save_receive', 'ShipmentController@save_receive')->name('shipment.save_receive');

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
Route::post('get_items', 'VueController@get_items');
Route::post('get_proforma', 'VueController@get_proforma');
Route::post('get_shipment', 'VueController@get_shipment');
Route::post('get_container', 'VueController@get_container');
Route::post('get_received_quantity', 'VueController@get_received_quantity');
Route::post('get_autocomplete_products', 'VueController@get_autocomplete_products');

Route::any('/search', 'HomeController@search')->name('search');
Route::post('/set_pagesize', 'HomeController@set_pagesize')->name('set_pagesize');

Route::get('/get_mac', function(){
    dump(shell_exec('php -v'));
    $ipAddress=$_SERVER['REMOTE_ADDR'];
    // $ipAddress="192.168.0.24";
    $macAddr=false;

    #run the external command, break output into lines
    $arp=`arp -a $ipAddress`;
    dump($arp);
    $lines=explode("\n", $arp);
    #look for the output line describing our IP address
    foreach($lines as $line)
    {
        $cols=preg_split('/\s+/', trim($line));
        if ($cols[0]==$ipAddress)
        {
            $macAddr=$cols[1];
        }
    }
    dump($macAddr);
    

});