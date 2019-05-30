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

use App\Coupon;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('coupons','CouponController');

Route::post('/coupons/list', [
    'as'    =>  'coupons.list',
    function() {
        $coupons = Coupon::paginate(100);
        $users = DB::table('users')->pluck('name','id')->all();

        return view('coupons.list', compact(['coupons','users']));
    }
]);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');
