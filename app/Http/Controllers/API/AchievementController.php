<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\ResponseController as ResponseController;
use App\Models\PlayerAchievement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AchievementController extends ResponseController
{
    public function myAchievement()
    {
        $player = Auth::id();

        $achievement = PlayerAchievement::firstWhere('user_id', $player);

        if ($achievement == null) {
            return $this->sendError("Player has no achievement", 404);
        }

        return $this->sendSuccess($achievement, "Successfully get player achievements",200);
    }

    public function listLeaderboard()
    {
        $players = DB::table('users')
                ->join('player_statuses', 'users.id', '=', 'player_statuses.user_id')
                ->select('users.name', 'player_statuses.exp')
                ->orderBy('player_statuses.exp', 'desc')
                ->get();
        
        if ($players == null) {
            return $this->sendError("Player is empty", 404);
        }

        return $this->sendSuccess($players, "Successfully list leaderboard player", 200);
    }
}
