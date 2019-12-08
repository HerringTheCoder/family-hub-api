<?php

namespace App\Providers;


use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;
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
        Gate::define('edit-news', function ($user, $news) {
            return $user->id == $news->author_id;
            });

        Gate::define('update-news', function ($user, $news) {
            return $user->id == $news->author_id;
            });

        Gate::define('delete-news', function ($user, $news) {
              return $user->id == $news->author_id;
            });

        Passport::routes();
    }
}