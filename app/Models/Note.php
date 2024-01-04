<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UuidTrait;

class Note extends Model
{
    use HasFactory, UuidTrait;

    protected $fillable = [
        'title',
        'content',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
