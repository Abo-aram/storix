<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'parent_id',
        'path',
        'is_shared',
        'last_accessed_at',
    ];


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function parent(){
        return $this->belongsTo(Folder::class, 'parent_id');
    }

    public function children(){
        return $this->hasMany(Folder::class, 'parent_id');
    }

    
}
