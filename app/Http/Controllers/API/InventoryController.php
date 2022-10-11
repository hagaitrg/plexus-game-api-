<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\ResponseController as ResponseController;
use App\Models\Inventory;
use App\Models\PlayerStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends ResponseController
{
    public function myInventories()
    {
        $player = Auth::id();

        $status = $this->checkStatus($player);
        $inventory = $this->checkInventory($player);

        if ($status == null || $inventory == null) {
            return $this->sendError("Player Status or inventory not found", 404);
        }

        return $this->sendSuccess([$status,$inventory], "Successfully get inventory", 200);
    }

    public function checkStatus($userId)
    {
        $status = PlayerStatus::firstWhere('user_id', $userId);

        if ($status == null) {
            return null;
        }

        return[
            'Player Status' => $status
        ];
    }

    public function checkInventory($userId)
    {
        $inventory = Inventory::where('user_id', $userId)->get();

        if ($inventory == null) {
            return null;
        }

        return [
            'Inventory' => $inventory
        ];
    }
}
