<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// THIS IS FOR THE LIST
class TaskList extends Model
{
    use HasFactory;

    public function tasks()
    {
        return $this->hasMany(Task::class, 'list_id');
    }

    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }
}
