<?php

namespace Modules\Nurse\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class Nurse extends Authenticatable
{
    use HasFactory, HasRoles;

    protected $fillable = [];

    protected $guard = 'nurse';

    protected static function newFactory()
    {
        return \Modules\Nurse\Database\factories\NurseFactory::new();
    }
}
