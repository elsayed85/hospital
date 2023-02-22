<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Doctor\Entities\Doctor;
use Modules\Employee\Entities\Employee;
use Modules\Nurse\Entities\Nurse;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //Seed the countries
        $this->call(CountriesSeeder::class);
        $this->command->info('Seeded the countries!');

        $local_env = env('APP_ENV');
        if ($local_env == "local") {
            // select databases that start with hospital
            $tenets = DB::select('SHOW DATABASES LIKE "hospital%"');
            foreach ($tenets as $tenet) {
                // drop the databases
                $t = array_values((array) $tenet)[0];
                if ($t != "hospital")
                    DB::statement(DB::raw('DROP DATABASE `' . $t . '`'));
            }

            $this->command->info('Dropped the databases!');

            // Seed the hospitals
            $tenants = [
                'hospital1',
                'hospital2',
                'hospital3',
            ];

            foreach ($tenants as $tenant) {
                $domain = $tenant;
                $hospital = Hospital::create([
                    'name' => $tenant,
                    // 'tenancy_db_username' => 'foo',
                    // 'tenancy_db_password' => 'bar',
                ]);
                $hospital->createDomain($domain);
            }

            Hospital::all()->runForEach(function () {
                Doctor::factory()->count(3)->create();
                Nurse::factory()->count(3)->create();
                Employee::factory()->count(3)->create();
            });

            $this->command->info('Seeded the hospitals (Tennats)!');
        }
    }
}
