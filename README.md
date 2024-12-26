# “Time Tracker” Developer Exercise

## Description
This is a task management system that allows tracking time spent on tasks through sessions. The system is built with **Symfony** and follows software design principles such as **Hexagonal Architecture** and **SOLID**. 

## Requirements

- **PHP 8.0+**
- **Symfony 6+**
- **Composer**
- **MySQL** 

## Installation

Follow these steps to install and run the project locally:

### 1. Clone the repository:

```bash
git clone https://github.com/alorenteto8/degustabox.git
cd degustabox
```

### 2. Set up Docker environment (optional but recommended):

```bash
docker-compose up --build
```
This will build and run the necessary containers for the PHP application and MySQL database. Make sure your Docker daemon is running before executing this command.

### 3. Install dependencies with Composer:

```bash
composer install
```
This will install all the PHP dependencies listed in the composer.json file.

### 4. Install JavaScript dependencies with npm:

```bash
npm install
```
This will install all the JavaScript dependencies required for building the frontend.

### 5. Build frontend assets

```bash
npm run dev
```
This command will compile and bundle the frontend assets using Webpack.


## API Endpoints

### POST ```/api/task/start```

This endpoint starts a new session for a task.

- Request:
```bash
{ "taskName": "task name" }
```

- Response:
```bash
{
  "taskId": 1,
  "taskName": "task name",
  "startTime": "2023-01-01T10:00:00+00:00"
}

```

### POST ``/api/task/stop``

This endpoint ends a session for a task.

- Request:
```bash
{ "taskName": "task name" }
```
- Response:
```bash
{
  "taskId": 1,
  "taskName": "task name",
  "startTime": "2023-01-01T10:00:00+00:00",
  "endTime": "2023-01-01T10:00:10+00:00"
}
```

### GET ``/api/task/list``

- Response
```bash
[
    {
        "id": 1,
        "name": "Task 1",
        "totalTime": 120
    },
    {
        "id": 2,
        "name": "Task 2",
        "totalTime": 150
    },
    {
        "id": 3,
        "name": "Task 3",
        "totalTime": 90
    }
]
```

### GET ``/api/task/hours``

- Response
```bash
{"0h 13m 37s"}
```

## Testing
Instructions for running unit tests using PHPUnit

```bash
vendor/bin/phpunit tests
```

## Commands
These commands offer functionalities to manage tasks and their associated time tracking.

### List Tasks ``app:list-tasks``
This command displays a list of all tasks and total time.

```bash
php bin/console app:list-tasks
```

### Task Control ``app:task-control``
This command allows you to start or stop a task by providing its name.

```bash
php bin/console app:task-control [action] [taskName]

# Example: Start a task named "Task ABC"
php bin/console app:task-control start "Task ABC"

# Example: Stop a task named "Task ABC"
php bin/console app:task-control end "Task ABC"
```
