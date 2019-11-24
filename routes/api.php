<?php
use Illuminate\Http\Request;
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
    Route::get('signup/activate/{token}', 'AuthController@signupActivate');
    Route::get('signup/activate/member/{token}', 'MemberController@activate');
  
    Route::group([
        'middleware' => 'auth:api'
    ], function() {
        //Route::post('member/add', 'MemberController@store');
        Route::post('member/update', 'MemberController@update');
        Route::get('member/all', 'MemberController@index');

        Route::post('news/add', 'NewsController@store');
        Route::get('news/edit/{id}', 'NewsController@edit');
        Route::put('news/update/{id}', 'NewsController@update');

        Route::post('gallery/add', 'GalleryController@store');
        Route::get('gallery/edit/{id}', 'GalleryController@edit');
        Route::put('gallery/update/{id}', 'GalleryController@update');

        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });

    Route::group([
        'middleware' => ['auth:api','required']
    ], function() {
        Route::post('member/add', 'MemberController@store');
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
});

