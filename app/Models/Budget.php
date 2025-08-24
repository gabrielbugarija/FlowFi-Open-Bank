<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{

    protected $fillable = [
    'user_id',
    'category_id',
    'amount',
    'period',
];


    public function user() {
    return $this->belongsTo(User::class);
}





public function Expenses() {
    return $this->belongsTo(Expenses::class);
}

}
