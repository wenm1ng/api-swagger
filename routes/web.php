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
    return view('welcome');
});



Route::group(['prefix' => 'swagger'], function () {
    Route::get('index', 'SwaggerController@getJSON');
    Route::get('my-data', 'SwaggerController@getMyData');
});


Route::group(['prefix' => 'admin'],function(){
	Route::get('goods',function(){
		return view('admin.goods');
	});
	//商品模块
	Route::get('/goods/list','Admin\GoodsController@listGoods');
	Route::get('/goods/test','Admin\GoodsController@test');
	Route::post('/goods/save_goods','Admin\GoodsController@saveGoods');
	Route::post('/goods/delete_goods','Admin\GoodsController@deleteGoods');
	Route::get('/goods/image_list','Admin\GoodsController@listImage');
	Route::post('/goods/save_image','Admin\GoodsController@saveImage');
	Route::post('/goods/delete_image','Admin\GoodsController@deleteImage');
	Route::get('/goods/category_list','Admin\GoodsController@listCategory');
	Route::post('/goods/get_category','Admin\GoodsController@getCategory');
	Route::post('/goods/save_category','Admin\GoodsController@saveCategory');
	Route::post('/goods/delete_category','Admin\GoodsController@deleteCategory');

	//分类模块
	Route::get('category',function(){
		return view('admin.category');
	});

	Route::get('/category/list','Admin\CategoryController@listCategory');
	Route::any('/category/save_category','Admin\CategoryController@saveCategory');
	Route::post('/category/delete_category','Admin\CategoryController@deleteCategory');
	Route::get('/category/son_list','Admin\CategoryController@sonListCategory');
	Route::any('/category/save_son_category','Admin\CategoryController@saveSonCategory');
	Route::post('/category/delete_son_category','Admin\CategoryController@deleteSonCategory');

});

Route::group(['prefix' => 'api','middleware'=>['crossHttp']],function(){
	Route::any('/login','Api\UserController@login');
	Route::any('/subordinates','Api\UserController@subordinates');
	Route::any('/user_info','Api\UserController@userInfo');
	Route::any('/member_list','Api\UserController@getMemberListByStar');
	Route::any('/save_address','Api\UserController@saveAddress');
	Route::any('/get_quantity','Api\UserController@getQuantity');
	Route::any('/goods/list','Api\GoodsController@listGoods');
	Route::any('/goods/detail','Api\GoodsController@detailGoods');
	Route::any('/goods/category','Api\GoodsController@categoryGoods');
	
});

Route::any('/redis_test','SwaggerController@redis_test');	

Route::group(['prefix' => 'sso','middleware' => ['crossHttp']],function(){
	Route::any('/login_erp','Sso\SsoController@getErpStatus');
	Route::any('/login_check','Sso\SsoController@loginCheck');
});
