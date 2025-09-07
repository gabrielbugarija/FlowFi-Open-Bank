<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{

    protected $fillable = [
    'user_id',
    'name',
];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        // Pivot table is `expense_type` linking expenses and transactions
        return $this->belongsToMany(Transaction::class, 'expense_type');
    }

}
