<?php
use Illuminate\Http\Request;
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
    Route::get('signup/activate/{token}', 'AuthController@signupActivate');
    Route::get('signup/activate/member/{token}', 'MemberController@activate');
    Route::get('spam/check', 'AuthController@spamChecker');
  
    Route::group([
        'middleware' => 'auth:api'
    ], function() {


        Route::post('member/add', 'MemberController@store');
        Route::post('member/add/deceased', 'MemberController@storeDeceased');
        Route::get('member/edit', 'MemberController@edit');
        Route::put('member/update', 'MemberController@update');
        Route::post('member/update/avatar', 'MemberController@avatar');
        Route::get('member/all', 'MemberController@index');

        Route::get('news/all', 'NewsController@index');
        Route::post('news/add', 'NewsController@store');
        Route::get('news/edit', 'NewsController@edit');
        Route::put('news/update', 'NewsController@update');
        Route::delete('news/delete', 'NewsController@delete');

        Route::get('gallery/all', 'GalleryController@index');
        Route::post('gallery/add', 'GalleryController@store');
        // Route::get('gallery/edit/{id}', 'GalleryController@edit');
        // Route::post('gallery/update/{id}', 'GalleryController@update');
        Route::delete('gallery/delete', 'GalleryController@delete');

        Route::get('relation/all', 'RelationController@index');
        Route::get('tree', 'RelationController@tree');
        Route::post('relation/add', 'RelationController@store');
        Route::get('relation/edit', 'RelationController@edit');
        Route::put('relation/update', 'RelationController@update');
        Route::delete('relation/delete', 'RelationController@delete');

        Route::get('pivot/get', 'PivotController@index');

        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });

     Route::group([
         'middleware' => ['auth:api','is_admin','prefix','check_prefix']
     ], function() {
        Route::post('admin/news/all', 'NewsController@index');
        Route::post('admin/news/edit', 'NewsController@edit');
        Route::put('admin/news/update', 'NewsController@update');
        Route::delete('admin/news/delete', 'NewsController@delete');
         
        Route::post('admin/gallery/all', 'GalleryController@store');
        Route::delete('admin/gallery/delete', 'GalleryController@delete');
     });

     Route::group([
        'middleware' => ['auth:api','is_admin']
    ], function() {
        Route::get('user/all', 'UserController@index');
        Route::put('user/update', 'UserController@update');
        Route::post('user/active', 'UserController@active');
        Route::post('user/deactive', 'UserController@delete');

        Route::get('family/all', 'FamilyController@index');
        //Route::post('family/add', 'FamilyController@store');
        Route::get('family/edit', 'FamilyController@edit');
        Route::put('family/update', 'FamilyController@update');
    });

    Route::group([    
        'namespace' => 'Auth',    
        'middleware' => 'api',    
        'prefix' => 'password'
    ], function () {    
        Route::post('create', 'PasswordResetController@create');
        Route::get('find/{token}', 'PasswordResetController@find');
        Route::post('reset', 'PasswordResetController@reset');
    });

    Route::fallback(function(){
        return response()->json([
            'message' => 'Page Not Found. If error persists, contact with admin'], 404);
    });
});

