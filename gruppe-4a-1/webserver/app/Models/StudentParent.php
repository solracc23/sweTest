<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentParent extends Model
{
    protected $table = 'student_parent';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'parentID',
        'studentID',
    ];

    /**
     * Beziehung zum Elternteil
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parentID', 'id');
    }

    /**
     * Beziehung zum Schüler
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'studentID', 'id');
    }
}

