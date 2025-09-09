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

    protected static function booted(): void
    {
        $update = fn (Transaction $transaction) => $transaction->account?->updateBalance();

        static::created($update);
        static::updated($update);
        static::deleted($update);
    }



    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function expenses()
    {
        return $this->belongsToMany(Expenses::class, 'expense_type');
    }

}
