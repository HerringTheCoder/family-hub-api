<?php

namespace App\Providers;


use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\Response;
use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \App\News::class => \App\Policies\NewsPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        
    /**
     * News actions, grants
     *
     * 
     */

     //NEWS
        Gate::define('edit-news', function ($user, $news) {
            if($user->isAdmin()){
                return true;
            }
            
            return $user->id == $news->author_id;
            });

        Gate::define('update-news', function ($user, $news) {
            if($user->isAdmin()){
                return true;
            }

            return $user->id == $news->author_id;
            });

        Gate::define('delete-news', function ($user, $news) {
            if($user->isAdmin()){
                return true;
            }
            
            return $user->id == $news->author_id;
            });


        //Gallery
        Gate::define('delete-photo', function ($user, $photo) {
            if($user->isAdmin()){
                return true;
            }
            
            return $user->id == $photo->author_id;
            });

        Passport::routes();
    }
}