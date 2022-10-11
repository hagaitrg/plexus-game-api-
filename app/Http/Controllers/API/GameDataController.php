<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\ResponseController as ResponseController;
use App\Models\PlayerAchievement;
use App\Models\PlayerStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GameDataController extends ResponseController
{
    public function submitGameData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coin' => 'required|numeric|',
            'silver' => 'required|numeric',
            'gold' => 'required|numeric',
            'exp' => 'required|numeric',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(), 400);       
        }

        $user = User::firstWhere('id', Auth::id());
        if ($user == null) {
            return $this->sendError("User not found!", 404);
        }

        $status = PlayerStatus::updateOrCreate(
            [
                "user_id" => $user->id,
            ],
            [
                "coin" => $request->coin,
                "silver" => $request->silver,
                "gold" => $request->gold,
                "exp" => $request->exp,
            ]
        );

        if (!$status) {
            return $this->sendError("Failed submit game data",400);
        }

        $silverStar = $this->checkSilver($status->silver, $user->id);
        $goldStar = $this->checkGold($status->gold, $user->id);

        if ($silverStar != null && $goldStar != null) {
            return $this->sendSuccess([$status,$silverStar, $goldStar], "Game Data Submitted!", 200);
        }

        if ($silverStar != null) {
            return $this->sendSuccess([$status,$silverStar], "Game Data Submitted!", 200);
        }

        if ($goldStar != null) {
            return $this->sendSuccess([$status,$goldStar], "Game Data Submitted!", 200);
        }

        return $this->sendSuccess($status, "Game Data Submitted!", 200);
    }

    public function getMyStatus()
    {
        $userId = Auth::id();

        $status = PlayerStatus::firstWhere('user_id',$userId);

        if ($status == null) {
            return $this->sendError("User not found!", 404);
        }

        return $this->sendSuccess($status, "Succesfully get status", 200);
    }

    public function checkSilver($silver, $userId)
    {
        $star = 0;
        if ($silver >= 100 && $silver < 200) {
            $star = 1;
        }else if($silver >= 200 && $silver < 400){
            $star = 2;
        }else if($silver >= 400){
            $star = 3;
        }

        if ($star!=0) {
            PlayerAchievement::updateOrCreate(
                [
                    'user_id' => $userId,
                ],
                [
                    'silver_star' => $star,
                ]
                );
            return ['Silver Notification'=>"Congratulations you have earned ".$star." Silver Star"];
        }else{
            return null;
        }
    }

    public function checkGold($gold, $userId)
    {
        $star = 0;
        if ($gold >= 100 && $gold < 200) {
            $star = 1;
        }else if($gold >= 200 && $gold < 400){
            $star = 2;
        }else if($gold >= 400){
            $star = 3;
        }

        if ($star!=0) {
            PlayerAchievement::updateOrCreate(
                [
                    'user_id' => $userId,
                ],
                [
                    'gold_star' => $star,
                ]
            );

            return ['Gold Notification'=>"Congratulations you have earned ".$star." Gold Star"];
        }else{
            return null;
        }
    }
}
