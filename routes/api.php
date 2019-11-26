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

        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });

    // Route::group([
    //     'middleware' => ['auth:api','required']
    // ], function() {
    //     Route::post('member/add', 'MemberController@store');
    // });

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

