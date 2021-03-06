<?php
use Illuminate\Http\Request;

Route::group([     
    'prefix' => 'password'
], function () {    
    Route::post('create', 'Auth\PasswordResetController@create');
    Route::get('find/{token}', 'Auth\PasswordResetController@find');
    Route::post('reset', 'Auth\PasswordResetController@reset');
});

Route::group([
    'prefix' => 'auth',
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup')->middleware('checkName');
    Route::get('signup/activate/{token}', 'AuthController@signupActivate');
    Route::get('signup/activate/member/{token}', 'MemberController@activate');
    Route::get('spam/check', 'AuthController@spamChecker');
    // Route::group([     
    //     'prefix' => 'password'
    // ], function () {    
    //     Route::get('find/{token}', 'Auth\PasswordResetController@find');
    //     Route::post('reset', 'Auth\PasswordResetController@reset');
    // });

    Route::group([
        'middleware' => 'auth:api'
    ], function() {

        //'throttle:6,1',
        Route::post('member/add', 'MemberController@store')->middleware('familyID','isFounder');
        Route::post('member/add/deceased', 'MemberController@storeDeceased')->middleware('familyID','isFounder');
        Route::get('member/edit', 'MemberController@edit');
        Route::put('member/update', 'MemberController@update');
        Route::post('member/update/avatar', 'MemberController@avatar');
        Route::get('member/all', 'MemberController@index');
        Route::get('member/info', 'MemberController@info');
        Route::get('member/info/one/{id}', 'MemberController@infoOne');

        Route::get('news/all', 'NewsController@index');
        Route::post('news/add', 'NewsController@store');
        Route::get('news/edit', 'NewsController@edit')->middleware('news');
        Route::put('news/update', 'NewsController@update')->middleware('news');
        Route::delete('news/delete', 'NewsController@delete')->middleware('news');

        Route::get('gallery/all', 'GalleryController@index');
        Route::post('gallery/add', 'GalleryController@store');
        // Route::get('gallery/edit/{id}', 'GalleryController@edit');
        // Route::post('gallery/update/{id}', 'GalleryController@update');
        Route::delete('gallery/delete', 'GalleryController@delete')->middleware('gallery');;

        Route::get('relation/all', 'RelationController@index');
        Route::get('tree', 'RelationController@tree');
        Route::get('relation/single', 'RelationController@getSingle');
        Route::post('relation/add', 'RelationController@store')->middleware('familyID','isFounder','isExistMember');
        Route::get('relation/edit', 'RelationController@edit')->middleware('relation');
        Route::put('relation/update', 'RelationController@update')->middleware('relation');

        Route::get('pivot/get', 'PivotController@index');

        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });

    Route::group([
        'middleware' => ['auth:api']
    ], function() {
       Route::delete('relation/delete/all', 'RelationController@delete')->middleware('checkFounder');
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
        
        Route::get('admin/logs/all', 'LogController@index');
    });

 

    Route::fallback(function(){
        return response()->json([
            'message' => 'Page Not Found. If error persists, contact with admin'], 404);
    });
});

