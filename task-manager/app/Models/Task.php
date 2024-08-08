<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// INDIVIDUAL TASKS
class Task extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function list()
    {
        return $this->belongsTo(TaskList::class, 'list_id');
    }
}
