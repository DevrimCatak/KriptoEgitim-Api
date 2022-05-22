<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'title',
        'detail',
        'banner',
        'banner_type',
        'course_id',
        'status'
    ];
}
