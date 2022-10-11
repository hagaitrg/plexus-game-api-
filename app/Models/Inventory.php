<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'weapon'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
