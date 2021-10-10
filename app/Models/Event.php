<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'calendar_id',
        'user_id',
        'title',
        'description',
        'category',
        'date'
    ];
}
