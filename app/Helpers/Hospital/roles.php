<?php

use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Role;

// roles

function role($name, $guard = null)
{
    if (empty($guard)) {
        $guard = auth_guard_name();
    }

    return Role::findByName($name, $guard);
}

function auth_roles(): Collection
{
    return auth_user()->getRoleNames();
}

// check roles

function auth_hasRole($role): bool
{
    return auth_user()->hasRole($role);
}

function auth_hasAnyRole($roles): bool
{
    return auth_user()->hasAnyRole($roles);
}

function auth_hasAllRoles($roles): bool
{
    return auth_user()->hasAllRoles($roles);
}

function auth_hasExactRoles($roles): bool
{
    return auth_user()->hasExactRoles($roles);
}


// assign and remove roles

function auth_assignRole($role)
{
    return auth_user()->assignRole($role);
}

function auth_removeRole($role)
{
    return auth_user()->removeRole($role);
}

function auth_syncRoles($roles)
{
    return auth_user()->syncRoles($roles);
}

// permissions

function auth_permissions(): Collection
{
    return auth_user()->getAllPermissions();
}

function auth_getDirectPermissions(): Collection
{
    return auth_user()->getDirectPermissions();
}

function auth_getPermissionsViaRoles(): Collection
{
    return auth_user()->getPermissionsViaRoles();
}

// check permissions

function auth_hasDirectPermission($permission): bool
{
    return auth_user()->hasDirectPermission($permission);
}

function auth_hasAllDirectPermissions($permissions): bool
{
    return auth_user()->hasAllDirectPermissions($permissions);
}

function auth_hasAnyDirectPermission($permissions): bool
{
    return auth_user()->hasAnyDirectPermission($permissions);
}

// assign and remove permissions

function auth_assignPermission($permission)
{
    return auth_user()->givePermissionTo($permission);
}

function auth_removePermission($permission)
{
    return auth_user()->revokePermissionTo($permission);
}

function auth_syncPermissions($permissions)
{
    return auth_user()->syncPermissions($permissions);
}
