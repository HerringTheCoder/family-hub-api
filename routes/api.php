<?php
use Illuminate\Http\Request;
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
    Route::get('signup/activate/{token}', 'AuthController@signupActivate');
    Route::get('signup/activate/member/{token}', 'UserController@activate');
  
    Route::group([
        'middleware' => 'auth:api'
    ], function() {
        Route::post('member/add', 'UserController@store');
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

