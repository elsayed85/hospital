<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Hospital;
use Illuminate\Database\Seeder;

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
        dd("Seeded the countries!");
        $local_env = env('APP_ENV');
        if ($local_env == "local") {
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
        }
    }
}
