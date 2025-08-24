<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{

    protected $fillable = [
    'user_id',
    'name',
];


    public function user() {
    return $this->belongsTo(User::class);
}

public function transactions() {
    return $this->belongsToMany(Transaction::class, 'type_transaction');
}

public function budgets() {
    return $this->hasMany(Budget::class);
}

}
