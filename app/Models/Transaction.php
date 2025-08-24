<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    protected $fillable = [
    'account_id',
    'type',
    'description',
    'amount',
    'date',
];



    public function account() {
    return $this->belongsTo(Account::class);
}

public function expenses() {
    return $this->belongsToMany(Expenses::class, 'expense_type');
}

}
