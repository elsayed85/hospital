<?php

namespace Modules\Doctor\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class Doctor extends Authenticatable
{
    use HasFactory , HasRoles;

    protected $fillable = [];

    protected $guard = 'doctor';

    protected static function newFactory()
    {
        return \Modules\Doctor\Database\factories\DoctorFactory::new();
    }
}
