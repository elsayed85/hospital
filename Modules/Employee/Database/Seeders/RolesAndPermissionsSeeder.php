<?php

namespace Modules\Employee\Database\Seeders;

use App\Models\Hospital;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $guard = config('login.types.employee.guard');
        Hospital::all()->runForEach(function () use ($guard) {
            // Reset cached roles and permissions
            app()[PermissionRegistrar::class]->forgetCachedPermissions();

            Schema::disableForeignKeyConstraints();
            $roles = Role::where('guard_name', $guard)->get("id")->pluck('id');
            Role::whereIn('id', $roles)->delete();
            $permissions = Permission::where('guard_name', $guard)->get("id")->pluck('id');
            Permission::whereIn('id', $permissions)->delete();
            DB::table('role_has_permissions')
                ->where(function ($q) use ($roles, $permissions) {
                    $q
                        ->whereIn('role_id', $roles->pluck('id'))
                        ->orWhereIn('permission_id', $permissions->pluck('id'));
                })
                ->delete();
            Schema::enableForeignKeyConstraints();

            $permissions = config('employee.permissions');

            $admin_permissions = [];

            foreach ($permissions['admin'] as $permission) {
                $admin_permissions[] = Permission::firstOrCreate(
                    [
                        'name' => $permission,
                        "guard_name" => $guard
                    ],
                    [
                        'name' => $permission,
                        "guard_name" => $guard
                    ]
                );
            }

            $super_admin_permissions = [];
            $super_admin_permissions = array_merge($permissions['admin'], $permissions['super_admin']);
            $super_admin_permissions = array_unique($super_admin_permissions);

            $all_permissions = [];
            foreach ($super_admin_permissions as $permission) {
                $all_permissions[] = Permission::firstOrCreate(
                    [
                        'name' => $permission,
                        "guard_name" => $guard
                    ],
                    [
                        'name' => $permission,
                        "guard_name" => $guard
                    ]
                );
            }


            $regular = Role::create([
                'name' => 'regular',
                "guard_name" => $guard
            ]);


            $admin = Role::create([
                'name' => 'admin',
                "guard_name" => $guard
            ]);

            $admin->givePermissionTo($admin_permissions);

            $super_admin = Role::create([
                'name' => 'super admin',
                "guard_name" => $guard
            ]);
        });
    }
}
