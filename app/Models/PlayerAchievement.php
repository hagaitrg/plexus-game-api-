<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerAchievement extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'silver_star', 'gold_star'];

    public $timestamps = false;

    public function user()
    {   
        return $this->belongsTo(User::class);
    }
}
