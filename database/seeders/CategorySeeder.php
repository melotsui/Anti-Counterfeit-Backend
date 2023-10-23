<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Home Appliances',
            'Electronics and Audio and Video',
            'Food and Drink',
            'Household Products',
            'Childrens Products',
            'Service Industry',
            'Sustainable Consumption',
            'Personal Finance',
            'Health and Beauty',
            'Travel and Leisure',
            'Other',
        ];

        $data = [];
        foreach ($categories as $category) {
            $data[] = [
                'category_name' => $category,
            ];
        }

        DB::table('categories')->insert($data);
    }
}
