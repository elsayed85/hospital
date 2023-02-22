<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Employee\Database\factories\EmployeeFactory;
use Spatie\Permission\Traits\HasRoles;

class Employee extends Authenticatable
{
    use HasFactory , HasRoles;

    protected $fillable = [];

    protected $guard = 'employee';

    protected static function newFactory()
    {
        return EmployeeFactory::new();
    }
}
