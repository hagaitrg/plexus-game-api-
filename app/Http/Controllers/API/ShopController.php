<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\ResponseController as ResponseController;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\PlayerStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ShopController extends ResponseController
{
    public function allItem()
    {
        $items = Item::all();
        if ($items == null) {
            return $this->sendError("Item is empty", 404);
        }

        return $this->sendSuccess($items,"Choose a new item!", 200);
    }

    public function buyItem($itemId, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'total' => 'required|integer',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(), 400);       
        }
        

        $item = Item::firstWhere('id', $itemId);

        if ($item->stock == 0 || $item->stock == null) {
            return $this->sendError("Item out of stock!", 404);
        }

        $totalPrice = $item->price * $request->total;

        $player = PlayerStatus::firstWhere('user_id', Auth::id());

        if ($player->coin < $totalPrice) {
            return $this->sendError("You're coin not enough to buy this item!", 400);
        }

        $updateTotal = Inventory::firstWhere([
            'user_id' => Auth::id(),
            'weapon' => $item->name
        ]);

        $inventory = new Inventory();

        if ($updateTotal) {
            $updateTotal->total = $updateTotal->total + $request->total;
            if ($updateTotal->save()) {
                $item->stock = $item->stock - $request->total;
                $player->coin = $player->coin - $totalPrice;
                
                $item->save();
                $player->save();
    
                return $this->sendSuccess($updateTotal, "Succesfully to buy item", 200);
            }else{
                return $this->sendError("Failed to buy item", 400);
            }
        }else{
            $inventory->user_id = Auth::id();
            $inventory->weapon = $item->name;
            $inventory->total = $request->total;

            if ($inventory->save()) {
                $item->stock = $item->stock - $request->total;
                $player->coin = $player->coin - $totalPrice;
                
                $item->save();
                $player->save();
    
                return $this->sendSuccess($inventory, "Succesfully to buy item", 200);
            }else{
                return $this->sendError("Failed to buy item", 400);
            }
        }
    }
}
