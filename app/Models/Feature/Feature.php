<?php

namespace App\Models\Feature;

use App\Services\Period;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

class Feature extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CentralConnection;

    /**
     * The connection name for the model.
     *
     * @var string
     */

    protected $casts = [
        'plan_id' => 'integer',
        'value' => 'string',
        'resettable_period' => 'integer',
        'resettable_interval' => 'string',
        'deleted_at' => 'datetime',
    ];


    /**
     * The plan feature may have many subscription usage.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usage(): HasMany
    {
        return $this->hasMany(FeatureUsage::class);
    }

    /**
     * Get feature's reset date.
     *
     * @param string $dateFrom
     *
     */
    public function getResetDate(Carbon $dateFrom)
    {
        $period = new Period($this->resettable_interval, $this->resettable_period, $dateFrom ?? now());

        return $period->getEndDate();
    }
}
