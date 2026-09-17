DBLiteAdmin

<img width="1280" height="720" alt="17896425978136953830483168558461" src="https://github.com/user-attachments/assets/a96f117a-f334-4eb7-babc-3f1cdcc6130c" />

A lightweight, single-file SQLite database manager written in PHP. It provides both a mobile-responsive modern web UI and an interactive command-line terminal shell with extended SQL syntax emulation.


FEATURES

- Single-File Deployment: No external dependencies, Composer packages, or asset build steps required.
- Modern Web UI:
  - Browse, paginate, full-text search, and sort table records.
  - Insert, edit, delete, and bulk-delete rows using bottom-sheet modals.
  - Create tables with custom schema definitions, truncate data, or drop tables.
  - Visual ERD / Table Relations editor: draggable table nodes, dynamic relation lines, auto-layout, and a foreign key creator.
  - SQL Studio: interactive query runner with quick shortcuts and extended SQL syntax emulation (such as USE, SHOW DATABASES, SHOW TABLES, DESCRIBE, and SHOW CREATE TABLE).
  - Database controls: create, upload, clone, rename, snapshot backup, optimize (VACUUM), and toggle WAL mode.
  - Data transfer: import SQL scripts and export as raw SQLite files or complete SQL transaction dumps.
  - Light and Dark theme toggle with local storage persistence.
- Interactive CLI Shell:
  - Run directly from terminal with auto-detected databases or interactive prompts.
  - Formatted ASCII tables for query result inspection.
- Security:
  - Built-in CSRF token protection for all state-changing actions.
  - Optional hardcoded master password or session-based security password.


REQUIREMENTS

- PHP 8.0 or newer
- PHP PDO SQLite extension (pdo_sqlite)


GETTING STARTED

1. Running in a Web Browser

Place the PHP script into your project or web server directory. You can also start the built-in PHP development server right away:

```
php -S localhost:8000 index.php
```

Then visit http://localhost:8000 in your browser. Any SQLite database files (.db, .sqlite, .sqlite3) located in the same directory will appear automatically.

2. Running in Terminal (CLI Mode)

Execute the script directly from the terminal, optionally passing the database name as the first argument:

```
php index.php production.db
```

If no database name is provided, you will be prompted to pick or create one. Inside the shell, you can type queries ending with a semicolon:

```
sql> SHOW TABLES;
sql> DESCRIBE users;
sql> SELECT * FROM users LIMIT 10;
sql> exit
```


CONFIGURATION

Open the script in a text editor to modify configuration constants at the top:

```
define('AUTH_PASSWORD', 'your_password_here');
```

Set a fixed string to require a login screen. Leave empty to allow open access or enable manual password protection through the Settings tab.


LICENSE

Free and open-source for personal and commercial use.
