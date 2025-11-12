<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Export extends Model
{
    //

    protected $casts = [
        'meta' => 'array',
    ];

    protected $fillable = [
        'user_id',
        'status',
        'meta',
        'file_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
