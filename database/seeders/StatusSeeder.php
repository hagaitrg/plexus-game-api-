<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        foreach ($users as $user) {
            DB::table('player_statuses')->insert([
                'user_id' => $user->id,
                'coin' => mt_rand(0000000000,9999999999),
                'silver' => mt_rand(0,1000),
                'gold' => mt_rand(0,1000),
                'exp' => mt_rand(0000000000,9999999999),
                'created_at' => now(),
            ]);
        }
    }
}
