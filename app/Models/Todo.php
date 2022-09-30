<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'task_priority', 'due_at', 'user_id', 'date_completed'];
    protected $casts = [
        'due_at' => 'datetime:Y-m-d',
    ];
}
