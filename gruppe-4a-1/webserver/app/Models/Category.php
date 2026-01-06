<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';
    protected $primaryKey = 'category_name';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'category_name',
        'subject_name',
        'description',

    ];

    /**
     * Beziehung zum Subject
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_name', 'subjectName');
    }

    /**
     * Alle Kategorien für ein bestimmtes Fach
     */
    public static function forSubject(string $subjectName)
    {
        return self::where('subject_name', $subjectName)->get();
    }
}



