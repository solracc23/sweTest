<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'task';
    protected $primaryKey = 'taskID';
    public $timestamps = false;

    protected $fillable = [
        'subjectName',
        'week',
        'content',
        'category_name',
    ];

    protected $casts = [
        'week' => 'integer',
        'content' => 'array',
    ];


    /**
     * Deserialisiert die MathTask aus dem content
     */
    public function getMathTask(): ?MathTask
    {
        $data = is_array($this->content) ? $this->content : json_decode($this->content, true);
        if (!$data) {
            return null;
        }
        return MathTask::fromRequest([
            'description' => $data['description'] ?? null,
            'expression' => $data['expression'] ?? '',
            'tokens' => $data['tokens'] ?? [],
            'gap_index' => $data['gap_index'] ?? 0,
            'correct_answer' => $data['correct_answer'],
            'week' => $data['week'] ?? null,
            'id' => $this->taskID,
        ]);
    }

    /**
     * Deserialisiert die GenericTask aus dem content
     */
    public function getGenericTask(): ?GenericTask
    {
        return GenericTask::fromTask($this);
    }
}

