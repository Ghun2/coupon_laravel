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
# 홈 = / 이라면 welcom 뷰를 가져와서 보여준다
Route::get('/', function () {
    return view('welcome');
});

# 컨트롤러 CouponController 라우터로 등록
Route::resource('coupons','CouponController');

# get 방식은 개별적으로 라우팅 설정
Route::get('coupons/index/{id}', 'CouponController@update');
Route::get('coupons/show/{id}', 'CouponController@destroy');

# 차트 컨트롤러 등록 및 라우팅 설정
Route::get('charts', 'ChartController@index');

//Route::post('coupons/list', [
//    'as'    =>  'coupons.list',
//    function() {
//        var_dump("hello");
//        $coupons = Coupon::paginate(100);
//        $users = DB::table('users')->pluck('name','id')->all();
//
//        return view('coupons.list', compact(['coupons','users']));
//    }
//]);


# 로그인 보안
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');
