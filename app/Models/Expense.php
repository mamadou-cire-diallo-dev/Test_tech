<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'spent_at',
        'currency',
        'category',
        'amount',
        'title',
        'user_id',
    ];

    //


    public  function  user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public  function  expensesLogs(){
        return $this->hasMany(ExpenseLog::class);
    }
}
