<?php


function hospital()
{
    return tenant();
}

function auth_doctor()
{
    return auth(config("login.types.doctor.guard"))->user();
}

function auth_nurse()
{
    return auth(config("login.types.nurse.guard"))->user();
}

function auth_employee()
{
    return auth(config("login.types.employee.guard"))->user();
}


function getConnectionName()
{
    return config('tenancy.database.central_connection');
}
