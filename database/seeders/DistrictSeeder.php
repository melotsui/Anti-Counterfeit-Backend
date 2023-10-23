<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictSeeder extends Seeder
{
    public function run()
    {
        $districts = [
            [
                'district_name' => 'Hong Kong Islands',
                'sub_districts' => [
                    'Eastern', 'Wan Chai', 'Central / Western', 'Southern', 'Islands'
                ]
            ],
            [
                'district_name' => 'Kowloon',
                'sub_districts' => [
                    'Kwun Tong', 'Kowloon City', 'Wong Tai Sin', 'Yau Tsim', 'Mong Kok', 'Sham Shui Po'
                ]
            ],
            [
                'district_name' => 'New Territories',
                'sub_districts' => [
                    'Kwai Tsing', 'North', 'Sai Kung', 'Sha Tin', 'Tai Po', 'Tsuen Wan', 'Tuen Mun', 'Yuen Long'
                ]
            ],
        ];

        foreach ($districts as $district) {
            $districtId = DB::table('districts')->insertGetId([
                'district_name' => $district['district_name'],
            ]);

            $subDistricts = [];
            foreach ($district['sub_districts'] as $subDistrictName) {
                $subDistricts[] = [
                    'sub_district_name' => $subDistrictName,
                    'district_id' => $districtId,
                ];
            }

            DB::table('sub_districts')->insert($subDistricts);
        }
    }

}
