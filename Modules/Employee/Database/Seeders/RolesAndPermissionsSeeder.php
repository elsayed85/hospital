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
        Hospital::all()->runForEach(function () {
            // Reset cached roles and permissions
            app()[PermissionRegistrar::class]->forgetCachedPermissions();

            // Schema::disableForeignKeyConstraints();
            // Role::truncate();
            // Permission::truncate();
            // DB::table('role_has_permissions')->truncate();
            // Schema::enableForeignKeyConstraints();

            $permissions = [
                "admin" => [
                    "show roles",
                    "create roles",
                    "edit roles",
                    "delete roles",

                    "show permissions",
                ],
                "super_admin" => [
                    "show roles",
                    "create roles",
                    "edit roles",
                    "delete roles",
                    "restore roles",

                    "show permissions",
                ]
            ];

            $admin_permissions = [];

            foreach ($permissions['admin'] as $permission) {
                $admin_permissions[] = Permission::firstOrCreate(
                    [
                        'name' => $permission,
                        "guard_name" => config('login.types.employee.guard')
                    ],
                    [
                        'name' => $permission,
                        "guard_name" => config('login.types.employee.guard')
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
                        "guard_name" => config('login.types.employee.guard')
                    ],
                    [
                        'name' => $permission,
                        "guard_name" => config('login.types.employee.guard')
                    ]
                );
            }


            $regular = Role::create([
                'name' => 'regular',
                "guard_name" => config('login.types.employee.guard')
            ]);


            $admin = Role::create([
                'name' => 'admin',
                "guard_name" => config('login.types.employee.guard')
            ]);

            $admin->givePermissionTo($admin_permissions);

            $super_admin = Role::create([
                'name' => 'super admin',
                "guard_name" => config('login.types.employee.guard')
            ]);
            $super_admin->givePermissionTo($all_permissions);
        });
    }
}
