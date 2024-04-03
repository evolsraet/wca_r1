<?php

namespace App\Providers;


use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Spatie\Permission\Models\Permission;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        // 'App\Models\Auction' => 'App\Policies\AuctionPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->registerUserAccessToGates();
    }

    protected function registerUserAccessToGates()
    {
        try {
            foreach (Permission::pluck('name') as $permission) {
                Gate::define($permission, function ($user) use ($permission) {
                    return $user->roles()->whereHas('permissions', function ($q) use ($permission) {
                        $q->where('name', $permission);
                    })->count() > 0;
                });
            }
        } catch (\Exception $e) {
            // VSCODE 에서 주기적으로 읽어 오류를 뱉으므로, 정지
            // info('registerUserAccessToGates: Database not found or not yet migrated. Ignoring user permissions while booting app.');
        }
    }
}
