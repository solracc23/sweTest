<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentTaskCompleted extends Model
{
    protected $table = 'studentTaskCompleted';
    protected $primaryKey = 'studentTaskID';
    public $timestamps = false;

    protected $fillable = [
        'studentTaskID',
        'userID',
        'taskID',
    ];

    /**
     * Beziehung zum User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userID', 'id');
    }

    /**
     * Beziehung zur Task
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'taskID', 'taskID');
    }
}
