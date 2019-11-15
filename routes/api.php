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
        Route::post('member/add', 'MemberController@store');
        Route::post('member/update', 'MemberController@update');
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
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

