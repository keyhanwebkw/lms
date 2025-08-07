<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Keyhanweb\Subsystem\Database\Seeders\RoleSeeder;
use Keyhanweb\Subsystem\Database\Seeders\UserSeeder;
use Keyhanweb\Subsystem\Database\Seeders\CreateManagerSeeder;
use Keyhanweb\Subsystem\Database\Seeders\CitySeeder;
use Keyhanweb\Subsystem\Database\Seeders\CountrySeeder;
use Keyhanweb\Subsystem\Database\Seeders\ProvinceSeeder;

class SubsystemSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(CreateManagerSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(ProvinceSeeder::class);
        $this->call(CitySeeder::class);
        $this->call(RoleSeeder::class);
    }
}
