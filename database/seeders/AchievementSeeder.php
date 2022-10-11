<?php

namespace Database\Seeders;

use App\Models\PlayerStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $player_status = PlayerStatus::all();
        foreach ($player_status as $status) {
            DB::table('player_achievements')->insert([
                'user_id' => $status->user_id,
            ]);

            if ($status->silver >= 100 && $status->silver < 200) {
                DB::table('player_achievements')
                    ->where('user_id',$status->user_id)
                    ->update(['silver_star' => 1]);
            }else if($status->silver >= 200 && $status->silver < 400){
                DB::table('player_achievements')
                ->where('user_id',$status->user_id)
                ->update(['silver_star' => 2]);
            }else if($status->silver >= 400){
                DB::table('player_achievements')
                    ->where('user_id',$status->user_id)
                    ->update(['silver_star' => 3]);
            }

            if ($status->gold >= 100 && $status->gold < 200) {
                DB::table('player_achievements')
                    ->where('user_id',$status->user_id)
                    ->update(['gold_star' => 1]);
            }else if($status->gold >= 200 && $status->gold < 400){
                DB::table('player_achievements')
                    ->where('user_id',$status->user_id)
                    ->update(['gold_star' => 1]);
            }else if($status->gold >= 400){
                DB::table('player_achievements')
                    ->where('user_id',$status->user_id)
                    ->update(['gold_star' => 1]);
            }
        }
    }
}
