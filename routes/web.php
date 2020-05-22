<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::match(['get', 'post'], '/', 'IndexController@index');

Route::match(['get', 'post'], '/admin', 'AdminController@login');

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');



Route::group(['middleware' => ['auth']], function(){
    Route::match(['get', 'post'], '/admin/dashboard', 'AdminController@dashboard');
    //Category Route
    Route::match(['get', 'post'], '/admin/add-category', 'CategoryController@addCategory');

    //Product Route
    Route::match(['get', 'post'], '/admin/add-product', 'ProductsController@addProduct');
    Route::match(['get', 'post'], '/admin/view-products', 'ProductsController@viewProducts');
    Route::match(['get', 'post'], '/admin/edit-product/{id}', 'ProductsController@editProduct');
    Route::match(['get', 'post'], '/admin/delete-product/{id}', 'ProductsController@deleteProduct');
    Route::post('/admin/update-product-status', 'ProductsController@updateStatus');
});

Route::get('/logout', 'AdminController@logout');
