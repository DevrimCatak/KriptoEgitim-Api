<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exchanges extends Model{
    use HasFactory;

    protected $fillable = [
        'id',
        'title',
        'explanation',
        'url',
        'status'
    ];
}
