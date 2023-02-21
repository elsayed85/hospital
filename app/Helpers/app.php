<?php


function hospital()
{
    return tenant();
}


function getConnectionName()
{
    return config('tenancy.database.central_connection');
}
