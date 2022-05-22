<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'title',
        'detail',
        'banner',
        'banner_type',
        'status'
    ];

}
