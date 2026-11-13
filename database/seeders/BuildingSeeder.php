<?php

namespace Database\Seeders;

use App\Models\Building;
use Illuminate\Database\Seeder;

class BuildingSeeder extends Seeder {
    public function run(): void {
        $buildings = [
            [
                'building_name' => 'ICT Laboratory',
                'building_code' => 'ICT',
                'description' => 'Information and Communications Technology Laboratory Building.',
                'status' => 'Active',
            ],
            [
                'building_name' => 'Health and Sciences Building',
                'building_code' => 'HSB',
                'description' => 'Health and Sciences Building.',
                'status' => 'Active',
            ],
            [
                'building_name' => 'Engineering and Architecture Building',
                'building_code' => 'EAB',
                'description' => 'Engineering and Architecture Building.',
                'status' => 'Active',
            ],
            [
                'building_name' => 'Nantes Building',
                'building_code' => 'NAN',
                'description' => 'Nantes Building.',
                'status' => 'Active',
            ],
            [
                'building_name' => 'Public Administration and Education Building',
                'building_code' => 'PAEB',
                'description' => 'Public Administration and Education Building.',
                'status' => 'Active',
            ],
            [
                'building_name' => 'Hospitality Management Laboratory',
                'building_code' => 'HML',
                'description' => 'Hospitality Management Laboratory.',
                'status' => 'Active',
            ],
        ];

        foreach ($buildings as $building) {
            Building::updateOrCreate(
                ['building_code' => $building['building_code']],
                $building
            );
        }
    }
}