<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'original_name',
        'stored_name',
        'path',
        'size',
        'extension',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
