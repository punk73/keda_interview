<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('see-customer', function(User $user ) {
            return $user->isStaff();
        });
        
        Gate::define('make-reports', function(User $user ) {
            return $user->isCustomer();
        });
        
        Gate::define('see-conversation', function(User $user, $conversation_id ) {
            // return $user->isStaff();
            if($user->isStaff()) {
                return true;
            }

            $tmp = explode('-', $conversation_id);
            if(!$tmp) {
                return false;
            }

            $res = in_array($user->id, $tmp);

            return $res;
        });

        Gate::define('delete-customer', function(User $staff) {
            if(!$staff->isStaff()) {
                return false;
            }

            return true;
        });

        //
        if (!$this->app->routesAreCached()) {
            Passport::routes();
        }
    }
}
