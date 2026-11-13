<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder {
    public function run(): void {
        $roomsByBuilding = [

            'ICT' => [
                ['LQ-103', 'Lab 1'],
                ['LQ-104', 'Lab 2'],
                ['LQ-207', 'Lab 3'],
            ],

            'HSB' => [
                ['LQ-105', 'FOOD LAB'],
                ['LQ-106', null],
                ['LQ-107', null],
                ['LQ-108', null],
                ['LQ-203', null],
                ['LQ-204', null],
                ['LQ-205', 'PHYS LAB'],
                ['LQ-206', 'CHEM LAB'],
            ],

            'EAB' => [
                ['LQ-109', null],
                ['LQ-110', null],
                ['LQ-113', null],
                ['LQ-114', null],
                ['LQ-115', null],
                ['LQ-116', null],
                ['LQ-208', 'DRAFT LAB'],
                ['LQ-209', 'CEA FUNC. RM.'],
                ['LQ-210', null],
                ['LQ-211', null],
            ],

            'NAN' => [
                ['LQ-120', null],
                ['LQ-121', null],
                ['LQ-122', null],
                ['LQ-216', 'SPEECH LAB'],
                ['LQ-217', 'KEY. LAB'],
                ['LQ-218', 'SIMULATION RM./AVR'],
            ],

            'PAEB' => [
                ['LQ-117', null],
                ['LQ-118', null],
                ['LQ-119', 'EDTECH'],
                ['LQ-212', null],
                ['LQ-213', null],
                ['LQ-214', null],
                ['LQ-215', null],
            ],

            'HML' => [
                ['LQ-100', 'KITCHEN LAB'],
                ['LQ-101', 'BEVERAGE LAB'],
                ['LQ-102', 'TISSUE LAB'],
            ],
        ];

        foreach ($roomsByBuilding as $buildingCode => $rooms) {

            $building = Building::where(
                'building_code',
                $buildingCode
            )->firstOrFail();

            foreach ($rooms as [$roomCode, $roomName]) {

                Room::updateOrCreate(
                    [
                        'building_id' => $building->id,
                        'room_code' => $roomCode,
                    ],
                    [
                        'room_name' => $roomName,
                        'description' => null,
                        'status' => 'Active',
                    ]
                );
            }
        }
    }
}