<?php

namespace App\Models;

use JsonSerializable;

/**
 * Repräsentiert eine Mathe-Aufgabe für die 5. Klasse
 * Die Aufgabe kann Lücken enthalten, die vom Schüler ausgefüllt werden müssen
 */
class MathTask implements JsonSerializable
{
    private ?string $description;
    private string $expression;
    private array $tokens;
    private int $gapIndex;
    private string $correctAnswer;
    private ?int $week;
    private ?int $id;

    public function __construct(?string $description, string $expression, array $tokens, int $gapIndex, ?int $week = null, ?int $id = null)
    {
        $this->description = $description;
        $this->expression = $expression;
        $this->tokens = $tokens;
        $this->gapIndex = $gapIndex;
        $this->correctAnswer = $tokens[$gapIndex] ?? '';
        $this->week = $week;
        $this->id = $id;
    }

    public static function fromRequest(array $data): self
    {
        $description = $data['description'] ?? null;
        $expression = $data['expression'] ?? '';
        $tokens = $data['tokens'] ?? [];
        $gapIndex = (int)($data['gap_index'] ?? 0);
        $week = isset($data['week']) ? (int)$data['week'] : null;
        $id = isset($data['id']) ? (int)$data['id'] : null;

        return new self($description, $expression, $tokens, $gapIndex, $week, $id);
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'expression' => $this->expression,
            'tokens' => $this->tokens,
            'gap_index' => $this->gapIndex,
            'correct_answer' => $this->correctAnswer,
            'week' => $this->week,
        ];
    }

    public function getDisplayExpression(): string
    {
        $displayTokens = $this->tokens;
        if (isset($displayTokens[$this->gapIndex])) {
            $displayTokens[$this->gapIndex] = '___';
        }
        return implode(' ', $displayTokens);
    }

    // Getter-Methoden
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getExpression(): string
    {
        return $this->expression;
    }

    public function getTokens(): array
    {
        return $this->tokens;
    }

    public function getGapIndex(): int
    {
        return $this->gapIndex;
    }

    public function getCorrectAnswer(): string
    {
        return $this->correctAnswer;
    }

    public function getWeek(): ?int
    {
        return $this->week;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gibt die Tokens vor der Lücke zurück
     */
    public function getTokensBeforeGap(): array
    {
        return array_slice($this->tokens, 0, $this->gapIndex);
    }

    /**
     * Gibt die Tokens nach der Lücke zurück
     */
    public function getTokensAfterGap(): array
    {
        return array_slice($this->tokens, $this->gapIndex + 1);
    }
}

