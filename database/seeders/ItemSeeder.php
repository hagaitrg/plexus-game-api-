<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('items')->insert([
            [
                'id'=>1,
                'name' => 'Dagger of Hunter',
                'stock' => 100,
                'price' => 20000
            ],
            [
                'id'=>2,
                'name' => 'Damascus',
                'stock' => 75,
                'price' => 49000
            ],
            [
                'id'=>3,
                'name' => 'Ice Dragon Sword',
                'stock' => 23,
                'price'=> 100000
            ],
            [
                'id'=>4,
                'name' => 'Black Dragon Bow',
                'stock' => 30,
                'price' => 95000
            ],
            [
                'id'=>5,
                'name' => 'Kratos Sword',
                'stock' => 10,
                'price' => 120000
            ]
        ]);
    }
}
