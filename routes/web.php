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
// use App\Http\Controllers\Customer\Auth\LoginController;
// use App\Http\Controllers\Customer\HomeController;
// use Illuminate\Support\Facades\Route;


// Authentication Routes...
// Route::get('ahihi', function ()
// {
	
// });
Route::get('home','Customer\HomeController@index')->middleware('verified.phone');
Route::get('login', 'Customer\Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Customer\Auth\LoginController@login');
Route::post('logout', 'Customer\Auth\LoginController@logout')->name('logout');
Route::middleware('auth')->group(function (){
	Route::get('cart', 'CartController@showCart')->name('show.cart');
	Route::post('checkout', 'CartController@showCheckout')->name('show.checkout');
	Route::post('order/create', 'OrderController@create')->name('order.create');
	Route::get('manage', 'OrderController@history')->name('customer.manage');
	Route::get('order-details/{id}', 'OrderController@show')->name('order.details');
    //Route::get('/', 'Customer\HomeController@index')->name('home');
});
// Registration Routes...
Route::get('register', 'Customer\Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Customer\Auth\RegisterController@register');

// Phone Verification Routes...
Route::get('verify-otp', 'Customer\Auth\VerificationController@showOtpForm')->name('verification.otp');
Route::post('verify-otp', 'Customer\Auth\VerificationController@verifyOtp');

// Email Verification Routes...
// Route::get('email/verify', 'Customer\Auth\VerificationController@show')->name('verification.notice');
// Route::get('email/verify/{id}/{hash}', 'Customer\Auth\VerificationController@verify')->name('verification.verify');
// Route::post('email/resend', 'Customer\Auth\VerificationController@resend')->name('verification.resend');
Route::get('password/reset', 'Customer\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/otp', 'Customer\Auth\ForgotPasswordController@verifyOtp')->name('password.otp');
Route::get('password/reset/{token}', 'Customer\Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Customer\Auth\ResetPasswordController@reset')->name('password.update');


//Group : Login page
//Auth::routes(); 

/*
	admin/
	admin/dashboard
	admin/categories
	admin/users/
	admin/plants/
 */
// Route::group(['prefix' => 'admin','middleware' => ['auth'], 'as' => 'admin.'], function () {

// 	Route::get('/', 'AdminController@index')->name('index')
// 		->middleware('permission:'.config('permission.web.AdminController@index'));

//     Route::get('/dashboard', 'AdminController@index')->name('index')
//     	->middleware('permission:'.config('permission.web.AdminController@index'));

// 	Route::get('/categories', 'AdminController@category')->name('categories.index')
// 		->middleware('permission:'.config('permission.web.AdminController@category'));

// 	Route::get('/plants', 'AdminController@plant')->name('plants.index')
// 		->middleware('permission:'.config('permission.web.AdminController@plant'));

// 	Route::get('/users', 'AdminController@user')->name('users.index')
// 		->middleware('permission:'.config('permission.web.AdminController@user'));

//     Route::get('/permissions', 'AdminController@permission')->name('permissions.index')
//     	->middleware('permission:'.config('permission.web.AdminController@permission'));
    
// });
// /*
// 	user/
// 	user/dashboard
// 	user/profile
//  */
// Route::group(['prefix' => 'user','middleware' => 'auth'], function () {
// 	Route::view('/', 'user.dashboard')->name('user_dashboard');
//     Route::view('/dashboard', 'user.dashboard')->name('user_dashboard');
//     Route::view('/profile', 'user.profile')->name('user_profile');
//     Route::view('/security', 'user.security')->name('user_security');
//     Route::post('/avt', 'UserController@changeAvt')->name('user_avt');
//     Route::post('/changepassword', 'UserController@changePassword');
//     Route::post('/resettoken', 'UserController@resetToken');
// });
/*
	/
	/{category_slug}
	/{category_slug}/{plant_slug}
 */

Route::get('search/{keyword?}','CoreController@search')->name('search');

Route::get('advanced-search-form','CoreController@showFormSearch')->name('search.form');

Route::get('advanced-search','CoreController@advancedSearch')->name('search.advanced');

Route::get('/', 'CoreController@home')->name('home');
Route::post('carts/add', 'CartController@store');
Route::post('carts', 'CartController@index');
Route::post('carts/change-quantity','CartController@changeQty');
Route::post('carts/remove-item','CartController@removeItem');

//Route::get('/controllers', 'HomeController@controllers')->name('home');
//Route::get('/categories', 'CoreController@categories')->name('categories');
Route::get('/{category_slug}', 'CoreController@products')->name('products');
Route::get('/{category_slug}/{plant_slug}', 'CoreController@product_details')->name('product.details');
