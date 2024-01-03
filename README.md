# Blog

## Requirements

- [Composer][1]
- PHP 8.2 or higher;
- PDO-SQLite PHP extension enabled;
- [MySQL][2] database server.

## Installation

### 1. Clone the project

```bash
$ git clone https://github.com/casrime/blog.git
```

### 2. Install the dependencies

```bash
$ composer install
```

### 3. Create the database, add tables and insert data

```bash
$ sh database.sh
```

## Usage

Launch the built-in web server and access the application in your browser at <http://localhost:8000>:

```bash
$ php -S localhost:8000 -t public/
```

## Misc

[Diagrams][3]

[1]: https://getcomposer.org/download/
[2]: https://www.mysql.com/downloads/
[3]: ./diagrams.md
