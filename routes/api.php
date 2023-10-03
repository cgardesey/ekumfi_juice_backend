<?php

use Illuminate\Http\Request;

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
Route::post('/otp/send', 'UserController@sendOtp');
Route::post('/otp/get', 'UserController@getOtp');

Route::post('/otp/change-number/send', 'UserController@changeNumberSendOtp');
Route::post('/otp/change-number/get', 'UserController@changeNumberGetOtp');

Route::post('/otp/vendor/send', 'UserController@sendOtp');
Route::post('/otp/vendor/get', 'UserController@vendorGetOtp');

Route::post('/otp/agent/get', 'UserController@agentGetOtp');
Route::post('/otp/wholesaler/get', 'UserController@wholesalerGetOtp');
Route::post('otp/scoped-agent/get', 'UserController@scopedAgentGetOtp');
Route::post('otp/scoped-wholesaler/get', 'UserController@scopedWholesalerGetOtp');

Route::patch('/update-confirmation-token/{user}', 'UserController@updateConfirmationToken')->middleware('auth:api');

Route::post('/consumer-home-data', 'UserController@fetchConsumerHomeData')->middleware('auth:api');
Route::post('/seller-home-data', 'UserController@fetchSellerHomeData')->middleware('auth:api');
Route::post('/agent-home-data', 'UserController@fetchAgentHomeData')->middleware('auth:api');
Route::post('/wholesaler-home-data', 'UserController@fetchWholesalerHomeData')->middleware('auth:api');

Route::post('/consumer-chat-data', 'UserController@consumerChatData')->middleware('auth:api');
Route::post('/seller-chat-data-with-agent', 'UserController@sellerChatDataWithAgent')->middleware('auth:api');
Route::post('/seller-chat-data-with-consumer', 'UserController@sellerChatDataWithConsumer')->middleware('auth:api');
Route::post('/agent-chat-data-with-seller', 'UserController@agentChatDataWithSeller')->middleware('auth:api');
Route::post('/agent-chat-data-with-wholesaler', 'UserController@agentChatDataWithWholesaler')->middleware('auth:api');
Route::post('/wholesaler-chat-data-with-agent', 'UserController@wholesalerChatDataWithAgent')->middleware('auth:api');
Route::post('/wholesaler-chat-data-with-ekumfi', 'UserController@wholesalerChatDataWithEkumfi')->middleware('auth:api');
Route::post('/ekumfi-chat-data', 'UserController@ekumfiChatData')->middleware('auth:api');

Route::post('/admin-login', 'UserController@adminLogin');

Route::post('/proximity-products', 'SellerProductController@proximityProducts')->middleware('auth:api');
Route::post('/proximity-agent-products', 'AgentProductController@proximityAgentProducts')->middleware('auth:api');

Route::post('/payments/pay', 'PaymentController@pay')->middleware('auth:api');
Route::post('/consumer-pay', 'PaymentController@consumerPay')->middleware('auth:api');
Route::post('/seller-pay', 'PaymentController@sellerPay')->middleware('auth:api');
Route::post('/payments/callback', 'PaymentController@callback')->middleware('auth:api');
Route::get('/consumer-payments', 'PaymentController@consumerPayments')->middleware('auth:api');
Route::get('/seller-payments', 'PaymentController@sellerPayments')->middleware('auth:api');
Route::get('/stock-payments', 'PaymentController@stockPayments')->middleware('auth:api');


Route::post('/group-call', 'UserController@groupCall')->middleware('auth:api');

Route::post('/scoped-cart-products', 'CartProductController@scopedCartProducts')->middleware('auth:api');
Route::post('/scoped-stock-cart-products', 'StockCartProductController@scopedStockCartProducts')->middleware('auth:api');
Route::post('/scoped-wholesaler-cart-products', 'WholesalerCartProductController@scopedWholesalerCartProducts')->middleware('auth:api');
Route::post('/cart-total', 'CartProductController@scopedCartTotal')->middleware('auth:api');
Route::post('/stock-cart-total', 'StockCartProductController@scopedStockCartTotal')->middleware('auth:api');
Route::post('/wholesaler-cart-total', 'WholesalerCartProductController@scopedWholesalerCartTotal')->middleware('auth:api');
Route::post('/scoped-latest-chats', 'ChatController@scopedLatestChats')->middleware('auth:api');
Route::post('/scoped-latest-ekumfi-chats', 'ChatController@scopedLatestEkumfiChats')->middleware('auth:api');
Route::post('/scoped-latest-agent-chats', 'ChatController@scopedLatestAgentChats')->middleware('auth:api');
Route::post('/scoped-latest-wholesaler-chats', 'ChatController@scopedLatestWholesalerChats')->middleware('auth:api');
Route::post('/scoped-carts', 'CartController@scopedCarts')->middleware('auth:api');
Route::post('/scoped-seller-carts', 'CartController@scopedSellerCarts')->middleware('auth:api');
Route::post('/scoped-seller-products', 'SellerProductController@scopedSellerProducts')->middleware('auth:api');
Route::post('/scoped-stock-carts', 'StockCartController@scopedStockCarts')->middleware('auth:api');
Route::post('/scoped-wholesaler-carts', 'WholesalerCartController@scopedWholesalerCarts')->middleware('auth:api');
Route::post('/scoped-consumer-carts', 'CartController@scopedConsumerCarts')->middleware('auth:api');
Route::post('/scoped-chats', 'ChatController@scopedChats')->middleware('auth:api');
Route::get('/scoped-sellers', 'SellerController@scopedSellers')->middleware('auth:api');
Route::post('/seller-order-id', 'UserController@sellerOrderId')->middleware('auth:api');

Route::get('/consumers', 'ConsumerController@index')->middleware('auth:api');
Route::get('/consumers/create', 'ConsumerController@create')->middleware('auth:api');
Route::get('/consumers/{consumer}', 'ConsumerController@show')->middleware('auth:api');
Route::post('/consumers', 'ConsumerController@store')->middleware('auth:api');
Route::get('/consumers/{consumer}/edit', 'ConsumerController@edit');
Route::post('/consumers/{consumer}', 'ConsumerController@update');
Route::delete('/consumers/{consumer}', 'ConsumerController@destroy')->middleware('auth:api');

Route::get('/sellers', 'SellerController@index')->middleware('auth:api');
Route::get('/sellers/create', 'SellerController@create')->middleware('auth:api');
Route::get('/sellers/{seller}', 'SellerController@show')->middleware('auth:api');
Route::post('/sellers', 'SellerController@store')->middleware('auth:api');
Route::get('/sellers/{seller}/edit', 'SellerController@edit');
Route::post('/sellers/{seller}', 'SellerController@update');
Route::delete('/sellers/{seller}', 'SellerController@destroy')->middleware('auth:api');

Route::get('/agents', 'AgentController@index')->middleware('auth:api');
Route::get('/agents/create', 'AgentController@create')->middleware('auth:api');
Route::get('/agents/{agent}', 'AgentController@show')->middleware('auth:api');
Route::post('/agents', 'AgentController@store')->middleware('auth:api');
Route::get('/agents/{agent}/edit', 'AgentController@edit');
Route::post('/agents/{agent}', 'AgentController@update');
Route::delete('/agents/{agent}', 'AgentController@destroy')->middleware('auth:api');

Route::get('/wholesalers', 'WholesalerController@index')->middleware('auth:api');
Route::get('/wholesalers/create', 'WholesalerController@create')->middleware('auth:api');
Route::get('/wholesalers/{wholesaler}', 'WholesalerController@show')->middleware('auth:api');
Route::post('/wholesalers', 'WholesalerController@store')->middleware('auth:api');
Route::get('/wholesalers/{wholesaler}/edit', 'WholesalerController@edit');
Route::post('/wholesalers/{wholesaler}', 'WholesalerController@update');
Route::delete('/wholesalers/{wholesaler}', 'WholesalerController@destroy')->middleware('auth:api');

Route::get('/ekumfi-infos', 'EkumfiInfoController@index')->middleware('auth:api');
Route::get('/ekumfi-infos/create', 'EkumfiInfoController@create')->middleware('auth:api');
Route::get('/ekumfi-infos/{ekumfiInfo}', 'EkumfiInfoController@show')->middleware('auth:api');
Route::post('/ekumfi-infos', 'EkumfiInfoController@store')->middleware('auth:api');
Route::get('/ekumfi-infos/{ekumfiInfo}/edit', 'EkumfiInfoController@edit');
Route::post('/ekumfi-infos/{ekumfiInfo}', 'EkumfiInfoController@update');
Route::delete('/ekumfi-infos/{ekumfiInfo}', 'EkumfiInfoController@destroy')->middleware('auth:api');

Route::get('/products', 'ProductController@index')->middleware('auth:api');
Route::get('/products/create', 'ProductController@create')->middleware('auth:api');
Route::get('/products/{product}', 'ProductController@show')->middleware('auth:api');
Route::post('/products', 'ProductController@store')->middleware('auth:api');
Route::get('/products/{product}/edit', 'ProductController@edit');
Route::post('/products/{product}', 'ProductController@update');
Route::delete('/products/{product}', 'ProductController@destroy')->middleware('auth:api');

Route::resource('/faqs', 'FaqController')->middleware('auth:api');
Route::resource('/banners', 'BannerController')->middleware('auth:api');
Route::resource('/seller-products', 'SellerProductController')->middleware('auth:api');
Route::resource('/agent-products', 'AgentProductController')->middleware('auth:api');
Route::resource('/wholesaler-products', 'WholesalerProductController')->middleware('auth:api');
Route::resource('/carts', 'CartController')->middleware('auth:api');
Route::resource('/stock-carts', 'StockCartController')->middleware('auth:api');
Route::resource('/wholesaler-carts', 'WholesalerCartController')->middleware('auth:api');
Route::resource('/cart-products', 'CartProductController')->middleware('auth:api');
Route::resource('/stock-cart-products', 'StockCartProductController')->middleware('auth:api');
Route::resource('/wholesaler-cart-products', 'WholesalerCartProductController')->middleware('auth:api');

Route::resource('/chats', 'ChatController')->middleware('auth:api');
Route::resource('/payments', 'PaymentController')->middleware('auth:api');
