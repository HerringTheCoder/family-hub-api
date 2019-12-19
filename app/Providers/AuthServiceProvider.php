<?php

namespace App\Providers;


use Laravel\Passport\Passport;
use Illuminate\Http\Request;
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
        //\App\Model::class => \App\Policies\ModelPolicy::class,
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
        // Gate::define('edit-news', function ($news) {
        //     if(Auth::user()->isAdmin()){
        //         return true;
        //     }
        //     return Auth::user()->id == $news->author_id;
        //     });

        // Gate::define('update-news', function ($user, $news) {
        //     if($user->isAdmin()){
        //         return true;
        //     }

        //     return $user->id == $news->author_id;
        //     });

        // Gate::define('delete-news', function ($user, $news) {
        //     if($user->isAdmin()){
        //         return true;
        //     }
            
        //     return $user->id == $news->author_id;
        //     });


        // //Gallery
        // Gate::define('delete-photo', function ($user, $photo) {
        //     if($user->isAdmin()){
        //         return true;
        //     }
            
        //     return $user->id == $photo->author_id;
        //     });



        // //Relations
        // Gate::define('edit-relation', function ($user, $relation) {
           
        //     if(($user->id == $relation->partner_1_id) || ($user->id == $relation->partner_2_id) || ($user->id == $relation->parent_id)){
        //         return true;
        //     }else{
        //         return false;
        //     }

        //     });

        // Passport::routes();
    }
}