<?php

namespace Modules\Doctor\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Doctor\Database\Seeders\RolesAndPermissionsSeeder;
use Modules\Doctor\Entities\Doctor;
use Spatie\Permission\Models\Role;

class DoctorDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesAndPermissionsSeeder::class);
    }
}
