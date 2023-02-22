<?php

function hospital()
{
    return tenant();
}

// auth
function auth_guard_name(): string
{
    return auth()->getDefaultDriver();
}

function auth_user($guard = null)
{
    return auth($guard)->user();
}

function current_doctor()
{
    return auth_user(config("login.types.doctor.guard"));
}

function current_nurse()
{
    return auth_user(config("login.types.nurse.guard"));
}

function current_employee()
{
    return auth_user(config("login.types.employee.guard"));
}
