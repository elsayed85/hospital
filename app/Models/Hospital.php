<?php

namespace App\Models;

use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\GeneratesIds;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Hospital extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains, GeneratesIds;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hospitals';

    protected $guarded = [];

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
        ];
    }

    public function domains()
    {
        return $this->hasMany(config('tenancy.domain_model'), 'hospital_id');
    }
}
