<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'question',
        'lesson_id'
    ];

    public function answers(){
        return $this->hasMany(Answer::class, 'question_id', 'id');
    }
}
