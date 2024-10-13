<?php

namespace App\Listeners;

use App\Events\RolePermissionsUpdated;
use App\Models\Pengguna;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RevokePermissionsFromUsers
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(RolePermissionsUpdated $event)
    {
        $users = Pengguna::role($event->role->name)->get();

        $rolePermissions = $event->role->permissions->pluck('name');

        foreach ($users as $user) {

            $userPermissions = $user->getAllPermissions()->pluck('name');

            $excessPermissions = $userPermissions->diff($rolePermissions);

            foreach ($excessPermissions as $permission) {
                $user->revokePermissionTo($permission);
            }
        }
    }
}
