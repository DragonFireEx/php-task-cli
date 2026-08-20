#!/usr/bin/env php
<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\TaskRepository;

const CSV_FILE = __DIR__ . '/../data/tasks.csv';

function printUsage(): void
{
    echo <<<TXT
    Użycie:
      php bin/console.php list [--status=STATUS]
      php bin/console.php add "Tytuł zadania" [--status=STATUS]
      php bin/console.php help

    Przykłady:
      php bin/console.php add "Nauczyć się PHP" --status=todo
      php bin/console.php list --status=todo

    TXT;
}

function getFlag(array $args, string $name): ?string
{
    foreach ($args as $arg) {
        if (str_starts_with($arg, "--{$name}=")) {
            return substr($arg, strlen("--{$name}="));
        }
    }

    return null;
}

$args = array_slice($argv, 1);
$command = $args[0] ?? null;

if ($command === null || $command === 'help') {
    printUsage();
    exit(0);
}

try {
    $repo = new TaskRepository(CSV_FILE);

    match ($command) {
        'list' => runList($repo, $args),
        'add' => runAdd($repo, $args),
        default => throw new InvalidArgumentException("Nieznana komenda: \"{$command}\". Wpisz \"php bin/console.php help\", aby zobaczyć dostępne komendy."),
    };
} catch (InvalidArgumentException $e) {
    fwrite(STDERR, "Błąd argumentów: {$e->getMessage()}\n");
    exit(1);
} catch (\Throwable $e) {
    fwrite(STDERR, "Nieoczekiwany błąd: {$e->getMessage()}\n");
    exit(1);
}

function runList(TaskRepository $repo, array $args): void
{
    $status = getFlag($args, 'status');
    $tasks = $status !== null ? $repo->filterByStatus($status) : $repo->all();

    if ($tasks === []) {
        echo "Brak zadań do wyświetlenia.\n";
        return;
    }

    foreach ($tasks as $task) {
        printf("[%d] %s (%s)\n", $task->id, $task->title, $task->status);
    }
}

function runAdd(TaskRepository $repo, array $args): void
{
    $title = null;
    foreach (array_slice($args, 1) as $arg) {
        if (!str_starts_with($arg, '--')) {
            $title = $arg;
            break;
        }
    }

    if ($title === null || trim($title) === '') {
        throw new InvalidArgumentException('Musisz podać tytuł zadania, np.: add "Kupić mleko"');
    }

    $status = getFlag($args, 'status') ?? 'todo';

    $task = $repo->add($title, $status);
    $repo->save();

    printf("Dodano zadanie [%d] %s (%s)\n", $task->id, $task->title, $task->status);
}
