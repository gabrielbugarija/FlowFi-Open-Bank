<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $fillable = [
        'user_id',
        'goal_amount',
        'period',
    ];

    protected $appends = [
        'spent',
        'progress',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getSpentAttribute()
    {
        $start = $this->period === 'monthly'
            ? now()->startOfMonth()
            : now()->startOfQuarter();

        return Transaction::whereHas('account', function ($q) {
            $q->where('user_id', $this->user_id);
        })
            ->where('type', 'expense')
            ->whereBetween('date', [$start, now()])
            ->sum('amount');
    }

    public function getProgressAttribute()
    {
        if ($this->goal_amount == 0) {
            return 0;
        }

        return min(100, round(($this->spent / $this->goal_amount) * 100, 2));
    }
}
