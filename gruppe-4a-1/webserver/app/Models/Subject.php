<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subject';
    protected $primaryKey = 'subjectName';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'subjectName',
        'classID',
    ];

    /**
     * Beziehung zu den Kategorien
     */
    public function categories()
    {
        return $this->hasMany(Category::class, 'subject_name', 'subjectName');
    }
}
