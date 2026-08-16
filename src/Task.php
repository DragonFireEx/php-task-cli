<?php

declare(strict_types=1);

namespace App;

final class Task
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $status
    ) {
    }

    public static function fromCsvRow(array $row): self
    {
        if (count($row) < 3) {
            throw new \InvalidArgumentException('Nieprawidłowy wiersz CSV — oczekiwano 3 kolumn (id, title, status).');
        }

        return new self(
            id: (int) $row[0],
            title: $row[1],
            status: $row[2]
        );
    }

    public function toCsvRow(): array
    {
        return [$this->id, $this->title, $this->status];
    }
}