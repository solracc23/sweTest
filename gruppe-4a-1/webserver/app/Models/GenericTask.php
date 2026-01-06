<?php

namespace App\Models;

use JsonSerializable;

class GenericTask implements JsonSerializable
{
    public function __construct(
        public readonly string $subjectName,
        public readonly int $taskId,
        public readonly ?int $week,
        public readonly string $path,
        public readonly string $text,
        public readonly string $category_name,
    ) {}

    /**
     * Erstellt eine GenericTask aus einem Task-Model
     */
    public static function fromTask(Task $task): ?self
    {
        $data = is_array($task->content) ? $task->content : json_decode($task->content, true);
        if (!$data || !isset($data['path'])) {
            return null;
        }

        return new self(
            subjectName: $task->subjectName,
            taskId: $task->taskID,
            week: $task->week,
            path: $data['path'] ?? '',
            text: $data['text'] ?? '',
            category_name: $task->category_name,
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'subjectName' => $this->subjectName,
            'taskId' => $this->taskId,
            'week' => $this->week,
            'path' => $this->path,
            'text' => $this->text,
            'category_name' => $this->category_name,
        ];
    }
}
