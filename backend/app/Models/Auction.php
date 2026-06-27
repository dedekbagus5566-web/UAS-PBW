<?php

namespace App\Models;
use App\Models\User;
use App\Models\Bid;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{

   protected $fillable = [
    'user_id',
    'title',
    'description',
    'price',
    'current_price',
    'category',
    'image_url',
    'status',
    'ends_at'
];
protected $casts = [
    'ends_at' => 'datetime',
];


    public function user()
{
    return $this->belongsTo(User::class);
}
public function winner()
{
    return $this->belongsTo(User::class, 'winner_id');
}

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

}