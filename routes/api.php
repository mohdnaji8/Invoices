<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/get_all_invoice', [InvoiceController::class, 'get_all_invoice']);
Route::get('/search_ivoice', [InvoiceController::class, 'search_ivoice']);
Route::get('/create_invoice', [InvoiceController::class, 'create_invoice']);
Route::get('/customers', [CustomerController::class, 'all_customers']);
Route::get('/products', [ProductController::class, 'all_products']);
Route::post('/add_invoice', [InvoiceController::class, 'add_invoice']);
Route::get('/show_invoice/{id}', [InvoiceController::class, 'show_invoice']);
Route::get('/edit_invoice/{id}', [InvoiceController::class, 'edit_invoice']);
Route::get('/delet_invoice_item/{id}', [InvoiceController::class, 'delet_invoice_item']);
Route::post('/update_invoice/{id}', [InvoiceController::class, 'update_invoice']);
Route::get('/delet_invoice/{id}', [InvoiceController::class, 'delet_invoice']);
