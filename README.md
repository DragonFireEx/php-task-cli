# php-task-cli

Prosty CLI w PHP do zarządzania listą zadań, z zapisem do pliku CSV. Projekt stworzony w ramach nauki PHP — obejmuje OOP, obsługę plików, parsowanie argumentów CLI, obsługę błędów i konteneryzację przez Dockera.

## Funkcjonalności

- Wczytywanie listy zadań z pliku CSV
- Dodawanie nowego zadania z argumentu CLI
- Filtrowanie zadań po statusie
- Zapis wyniku z powrotem do pliku
- Czytelne komunikaty przy błędnych argumentach

## Struktura projektu

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

## Wymagania

- PHP >= 8.1
- [Composer](https://getcomposer.org/)

Alternatywnie: Docker + Docker Compose (patrz sekcja niżej).

## Instalacja

```bash
git clone https://github.com/twoj-nick/php-task-cli.git
cd php-task-cli
composer install
```

## Użycie

```bash
php bin/console.php list [--status=STATUS]
php bin/console.php add "Tytuł zadania" [--status=STATUS]
php bin/console.php help
```

### Przykłady

Dodanie zadania:

```bash
php bin/console.php add "Nauczyć się PHP" --status=todo
```

```
Dodano zadanie [1] Nauczyć się PHP (todo)
```

Wyświetlenie wszystkich zadań:

```bash
php bin/console.php list
```

Wyświetlenie zadań z konkretnym statusem:

```bash
php bin/console.php list --status=todo
```

Dane są zapisywane w `data/tasks.csv`.

## Uruchamianie przez Docker

Projekt zawiera gotowy `Dockerfile` i `docker-compose.yml` — nie musisz mieć zainstalowanego PHP ani Composera lokalnie.

Budowanie obrazu:

```bash
docker compose build
```

Uruchamianie komend:

```bash
docker compose run --rm cli add "Zadanie z Dockera" --status=todo
docker compose run --rm cli list
```

Folder `data/` jest zamontowany jako wolumen, więc `tasks.csv` zapisuje się na Twoim dysku i przetrwa mimo że każdy kontener jest jednorazowy (`--rm`).

## Obsługa błędów

Program zwraca kod wyjścia `0` przy sukcesie i `1` przy błędzie (np. brak tytułu zadania, nieznana komenda), z czytelnym komunikatem wypisanym na `STDERR`.

## Licencja

MIT