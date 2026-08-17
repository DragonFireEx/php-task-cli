<?php

declare(strict_types=1);

namespace App;

final class TaskRepository
{
    /** @var Task[] */
    private array $tasks = [];

    public function __construct(private readonly string $filePath)
    {
        $this->load();
    }

    private function load(): void
    {
        if (!file_exists($this->filePath)) {
            return;
        }

        $handle = fopen($this->filePath, 'r');
        if ($handle === false) {
            throw new \RuntimeException("Nie można otworzyć pliku do odczytu: {$this->filePath}");
        }

        while (($row = fgetcsv($handle)) !== false) {
            $this->tasks[] = Task::fromCsvRow($row);
        }

        fclose($handle);
    }

    public function save(): void
    {
        $dir = dirname($this->filePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $handle = fopen($this->filePath, 'w');
        if ($handle === false) {
            throw new \RuntimeException("Nie można zapisać pliku: {$this->filePath}");
        }

        foreach ($this->tasks as $task) {
            fputcsv($handle, $task->toCsvRow());
        }

        fclose($handle);
    }

    public function add(string $title, string $status = 'todo'): Task
    {
        $task = new Task($this->nextId(), $title, $status);
        $this->tasks[] = $task;

        return $task;
    }

    private function nextId(): int
    {
        if ($this->tasks === []) {
            return 1;
        }

        $ids = array_map(static fn (Task $t) => $t->id, $this->tasks);

        return max($ids) + 1;
    }

    /** @return Task[] */
    public function all(): array
    {
        return $this->tasks;
    }

    /** @return Task[] */
    public function filterByStatus(string $status): array
    {
        return array_values(array_filter(
            $this->tasks,
            static fn (Task $t) => $t->status === $status
        ));
    }
}