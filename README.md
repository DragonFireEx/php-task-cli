# php-task-cli

A simple PHP CLI for managing a task list, with CSV file storage. Built as a PHP learning project — covers OOP, file handling, CLI argument parsing, error handling, and containerization with Docker.

## Features

- Load task list from a CSV file
- Add a new task via CLI argument
- Filter tasks by status
- Save results back to the file
- Readable error messages for invalid arguments

## Project structure

```
php-task-cli/
├── composer.json
├── Dockerfile
├── docker-compose.yml
├── src/
│   ├── Task.php
│   └── TaskRepository.php
└── bin/
    └── console.php
```

## Requirements

- PHP >= 8.1
- [Composer](https://getcomposer.org/)

Alternatively: Docker + Docker Compose (see below).

## Installation

```bash
git clone https://github.com/your-username/php-task-cli.git
cd php-task-cli
composer install
```

## Usage

```bash
php bin/console.php list [--status=STATUS]
php bin/console.php add "Task title" [--status=STATUS]
php bin/console.php help
```

### Examples

Add a task:

```bash
php bin/console.php add "Learn PHP" --status=todo
```

```
Dodano zadanie [1] Learn PHP (todo)
```

List all tasks:

```bash
php bin/console.php list
```

List tasks with a specific status:

```bash
php bin/console.php list --status=todo
```

Data is stored in `data/tasks.csv`.

## Running with Docker

The project ships with a ready-to-use `Dockerfile` and `docker-compose.yml` — no local PHP or Composer installation required.

Build the image:

```bash
docker compose build
```

Run commands:

```bash
docker compose run --rm cli add "Task from Docker" --status=todo
docker compose run --rm cli list
```

The `data/` folder is mounted as a volume, so `tasks.csv` persists on your machine even though each container is disposable (`--rm`).

## Error handling

The program exits with code `0` on success and `1` on error (e.g. missing task title, unknown command), printing a readable message to `STDERR`.

## License

MIT