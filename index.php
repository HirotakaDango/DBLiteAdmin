<?php
declare(strict_types=1);

define('APP_NAME', 'DBLiteAdmin');
define('APP_VERSION', '1.0.0');
define('AUTH_PASSWORD', '');

if (!extension_loaded('pdo_sqlite')) {
  $msg = "Error: 'pdo_sqlite' extension required.";
  php_sapi_name() === 'cli' ? fwrite(STDERR, $msg . PHP_EOL) : die($msg);
  exit(1);
}

function md_icon(string $name, int $size = 20, string $class = ''): string {
  static $icons = [
    'db' => 'M12 3C7.58 3 4 4.79 4 7v10c0 2.21 3.58 4 8 4s8-1.79 8-4V7c0-2.21-3.58-4-8-4zm0 2c3.87 0 6 1.5 6 2s-2.13 2-6 2-6-1.5-6-2 2.13-2 6-2zm6 12c0 .5-2.13 2-6 2s-6-1.5-6-2v-2.23c1.61.78 3.72 1.23 6 1.23s4.39-.45 6-1.23V17zm0-3.5c0 .5-2.13 2-6 2s-6-1.5-6-2v-2.23c1.61.78 3.72 1.23 6 1.23s4.39-.45 6-1.23V13.5z',
    'table' => 'M4 3h16c1.1 0 2 .9 2 2v14c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V5c0-1.1.9-2 2-2zm0 4h16V5H4v2zm0 4h7v-2H4v2zm9 0h7v-2h-7v2zm-9 4h7v-2H4v2zm9 0h7v-2h-7v2zm-9 4h16v-2H4v2z',
    'browse' => 'M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z',
    'relations' => 'M22 11V3h-7v3H9V3H2v8h7V8h2v10h4v3h7v-8h-7v3h-2V8h2v3h7zM4 5h3v4H4V5zm13 0h3v4h-3V5zm0 10h3v4h-3v-4z',
    'terminal' => 'M9.4 16.6L4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0l4.6-4.6-4.6-4.6L16 6l6 6-6 6-1.4-1.4z',
    'settings' => 'M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z',
    'add' => 'M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z',
    'delete' => 'M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-3.5l-1-1zM18 7H6v12c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7z',
    'play' => 'M8 5v14l11-7z',
    'chevron' => 'M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z',
    'close' => 'M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z',
    'download' => 'M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z',
    'upload' => 'M9 16h6v-6h4l-7-7-7 7h4zm-4 2h14v2H5z',
    'clean' => 'M16 11h-1V3c0-.55-.45-1-1-1h-4c-.55 0-1 .45-1 1v8H8c-2.76 0-5 2.24-5 5v5h18v-5c0-2.76-2.24-5-5-5zm-4-7h2v7h-2V4zm7 16H5v-3c0-1.65 1.35-3 3-3h8c1.65 0 3 1.35 3 3v3z',
    'bolt' => 'M11 21h-1l1-7H7.5c-.88 0-.33-.75-.31-.78C8.48 10.94 10.42 7.54 13.01 3h1l-1 7h3.51c.4 0 .62.19.4.66C12.97 17.55 11 21 11 21z',
    'back' => 'M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z',
    'folder' => 'M20 6h-8l-2-2H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 12H4V8h16v10z',
    'check' => 'M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z',
    'search' => 'M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z',
    'key' => 'M12.65 10C11.83 7.67 9.61 6 7 6c-3.31 0-6 2.69-6 6s2.69 6 6 6c2.61 0 4.83-1.67 5.65-4H17v4h4v-4h2v-4H12.65zM7 14c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z',
    'refresh' => 'M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z',
    'sun' => 'M12 7c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5zM2 13h2c.55 0 1-.45 1-1s-.45-1-1-1H2c-.55 0-1 .45-1 1s.45 1 1 1zm18 0h2c.55 0 1-.45 1-1s-.45-1-1-1h-2c-.55 0-1 .45-1 1s.45 1 1 1zM11 2v2c0 .55.45 1 1 1s1-.45 1-1V2c0-.55-.45-1-1-1s-1 .45-1 1zm0 18v2c0 .55.45 1 1 1s1-.45 1-1v-2c0-.55-.45-1-1-1s-1 .45-1 1zM5.99 4.58c-.39-.39-1.03-.39-1.41 0s-.39 1.03 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0s.39-1.03 0-1.41L5.99 4.58zm12.37 12.37c-.39-.39-1.03-.39-1.41 0s-.39 1.03 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0s.39-1.03 0-1.41l-1.06-1.06zm1.06-10.96c.39-.39.39-1.03 0-1.41s-1.03-.39-1.41 0l-1.06 1.06c-.39.39-.39 1.03 0 1.41s1.03.39 1.41 0l1.06-1.06zM7.05 18.36c.39-.39.39-1.03 0-1.41s-1.03-.39-1.41 0l-1.06 1.06c-.39.39-.39 1.03 0 1.41s1.03.39 1.41 0l1.06-1.06z',
    'moon' => 'M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9c3.12 0 5.86-1.59 7.47-4-.7.13-1.42.2-2.16.2-4.97 0-9-4.03-9-9 0-2.12.74-4.07 1.97-5.61C10.85 3.22 11.41 3 12 3z',
    'edit' => 'M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z',
    'copy' => 'M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z'
  ];
  $p = $icons[$name] ?? '';
  return "<svg class=\"i {$class}\" style=\"width:{$size}px;height:{$size}px\" viewBox=\"0 0 24 24\"><path d=\"{$p}\"/></svg>";
}

function cleanDbName(string $name): string {
  $b = basename(trim($name));
  $b = preg_replace('/[^a-zA-Z0-9_\.\-]/', '', $b);
  if ($b === '' || $b === '.' || $b === '..') return '';
  if (!preg_match('/\.(sqlite|sqlite3|db)$/i', $b)) {
    $b .= '.sqlite';
  }
  return $b;
}

function qi(string $ident): string {
  return '"' . str_replace('"', '""', $ident) . '"';
}

function getDbList(): array {
  $files = glob(__DIR__ . '/*.{db,sqlite,sqlite3}', GLOB_BRACE) ?: [];
  $list = array_values(array_unique(array_map('basename', $files)));
  sort($list, SORT_NATURAL | SORT_FLAG_CASE);
  return $list;
}

function getPdo(?string $db): ?PDO {
  if (!$db) return null;
  $c = cleanDbName($db);
  if (!$c && $db !== ':memory:') return null;
  $path = ($db === ':memory:') ? ':memory:' : __DIR__ . DIRECTORY_SEPARATOR . $c;
  try {
    $pdo = new PDO('sqlite:' . $path);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec("PRAGMA foreign_keys = ON;");
    return $pdo;
  } catch (Throwable $e) {
    return null;
  }
}

function runEmulatedQuery(PDO $pdo, string $sql, string &$currentDb): array {
  $t0 = microtime(true);
  $q = rtrim(trim($sql), ';');

  try {
    if (preg_match('/^USE\s+[`\'"]?([a-zA-Z0-9_\.\-]+)[`\'"]?$/i', $q, $m)) {
      $currentDb = cleanDbName($m[1]);
      return ['type' => 'info', 'message' => "Database changed to '{$currentDb}'", 'reconnect' => true, 'time' => microtime(true) - $t0];
    }
    if (preg_match('/^SHOW\s+DATABASES$/i', $q)) {
      $rows = array_map(fn($d) => ['Database' => $d], getDbList());
      return ['type' => 'select', 'rows' => $rows, 'count' => count($rows), 'time' => microtime(true) - $t0];
    }
    if (preg_match('/^SHOW\s+(?:FULL\s+)?TABLES$/i', $q)) {
      $rows = $pdo->query("SELECT name AS 'Tables' FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name")->fetchAll();
      return ['type' => 'select', 'rows' => $rows, 'count' => count($rows), 'time' => microtime(true) - $t0];
    }
    if (preg_match('/^(?:DESCRIBE|DESC|SHOW\s+COLUMNS\s+FROM)\s+[`\'"]?([a-zA-Z0-9_]+)[`\'"]?$/i', $q, $m)) {
      $cols = $pdo->query("PRAGMA table_info(" . qi($m[1]) . ")")->fetchAll();
      $rows = array_map(fn($c) => ['Field' => $c['name'], 'Type' => $c['type'] ?: 'TEXT', 'Null' => $c['notnull'] ? 'NO' : 'YES', 'Key' => $c['pk'] ? 'PRI' : '', 'Default' => $c['dflt_value'] ?? 'NULL'], $cols);
      return ['type' => 'select', 'rows' => $rows, 'count' => count($rows), 'time' => microtime(true) - $t0];
    }
    if (preg_match('/^SHOW\s+CREATE\s+TABLE\s+[`\'"]?([a-zA-Z0-9_]+)[`\'"]?$/i', $q, $m)) {
      $stmt = $pdo->prepare("SELECT name AS 'Table', sql AS 'Create Table' FROM sqlite_master WHERE type='table' AND name = ?");
      $stmt->execute([$m[1]]);
      return ['type' => 'select', 'rows' => $stmt->fetchAll(), 'count' => 1, 'time' => microtime(true) - $t0];
    }
    if (preg_match('/^SELECT\s+DATABASE\(\)/i', $q)) {
      return ['type' => 'select', 'rows' => [['DATABASE()' => $currentDb]], 'count' => 1, 'time' => microtime(true) - $t0];
    }
    if (preg_match('/^SELECT\s+VERSION\(\)/i', $q)) {
      return ['type' => 'select', 'rows' => [['VERSION()' => $pdo->query("SELECT sqlite_version()")->fetchColumn() . '-DBLiteAdmin']], 'count' => 1, 'time' => microtime(true) - $t0];
    }

    if (preg_match('/^\s*(SELECT|PRAGMA|EXPLAIN|WITH)\b/i', trim($sql))) {
      $rows = $pdo->query($sql)->fetchAll();
      return ['type' => 'select', 'rows' => $rows, 'count' => count($rows), 'time' => microtime(true) - $t0];
    }
    $affected = $pdo->exec($sql);
    return ['type' => 'dml', 'affected' => $affected !== false ? $affected : 0, 'time' => microtime(true) - $t0];
  } catch (Throwable $e) {
    return ['type' => 'error', 'error' => $e->getMessage(), 'time' => microtime(true) - $t0];
  }
}

function exportSqlDump(PDO $pdo): string {
  $out = "-- DBLiteAdmin SQL Dump\nPRAGMA foreign_keys=OFF;\nBEGIN TRANSACTION;\n";
  foreach ($pdo->query("SELECT name, sql FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'") as $tbl) {
    $out .= "DROP TABLE IF EXISTS " . qi($tbl['name']) . ";\n{$tbl['sql']};\n";
    foreach ($pdo->query("SELECT * FROM " . qi($tbl['name'])) as $r) {
      $k = array_map('qi', array_keys($r));
      $v = array_map(fn($x) => $x === null ? 'NULL' : $pdo->quote((string)$x), array_values($r));
      $out .= "INSERT INTO " . qi($tbl['name']) . " (" . implode(',', $k) . ") VALUES (" . implode(',', $v) . ");\n";
    }
  }
  return $out . "COMMIT;\nPRAGMA foreign_keys=ON;\n";
}

$dbs = getDbList();

if (php_sapi_name() === 'cli') {
  global $argv;
  $currentDb = $argv[1] ?? ($dbs[0] ?? '');
  if (!$currentDb) {
    echo "\n" . APP_NAME . " CLI " . APP_VERSION . "\n";
    echo "No SQLite database found in this folder.\n";
    echo "Enter database name to open/create (e.g., app.db): ";
    $input = trim((string)fgets(STDIN));
    if (!$input) exit("Error: Database not specified.\n");
    $currentDb = cleanDbName($input);
  }
  $pdo = getPdo($currentDb);
  if (!$pdo) exit("Error: Could not open database.\n");
  echo "\n" . APP_NAME . " Shell " . APP_VERSION . " [SQLite: " . $pdo->query("SELECT sqlite_version()")->fetchColumn() . " | DB: {$currentDb}]\n\n";
  $buf = '';
  while (true) {
    $line = readline($buf === '' ? "sql> " : "   -> ");
    if ($line === false) break;
    $t = trim((string)$line);
    if ($buf === '' && in_array(strtolower($t), ['exit', 'quit', '\q', 'exit;', 'quit;'], true)) break;
    if ($t === '\c') { $buf = ''; continue; }
    $buf .= ($buf === '' ? '' : ' ') . $t;
    if (str_ends_with($t, ';') || str_ends_with($t, '\g')) {
      $res = runEmulatedQuery($pdo, $buf, $currentDb);
      if (!empty($res['reconnect'])) $pdo = getPdo($currentDb);
      if ($res['type'] === 'select') {
        if (empty($res['rows'])) {
          echo "Empty set (" . sprintf('%.3f', $res['time']) . " sec)\n\n";
        } else {
          $keys = array_keys($res['rows'][0]);
          $w = array_combine($keys, array_map('strlen', $keys));
          foreach ($res['rows'] as $r) {
            foreach ($keys as $k) $w[$k] = max($w[$k], strlen((string)($r[$k] ?? 'NULL')));
          }
          $b = "+-" . implode("-+-", array_map(fn($k) => str_repeat("-", $w[$k]), $keys)) . "-+";
          echo "{$b}\n| " . implode(" | ", array_map(fn($k) => str_pad((string)$k, $w[$k]), $keys)) . " |\n{$b}\n";
          foreach ($res['rows'] as $r) {
            echo "| " . implode(" | ", array_map(fn($k) => str_pad((string)($r[$k] ?? 'NULL'), $w[$k]), $keys)) . " |\n";
          }
          echo "{$b}\n" . sprintf("%d row%s (%.3f sec)\n\n", $res['count'], $res['count'] === 1 ? '' : 's', $res['time']);
        }
      } elseif ($res['type'] === 'dml') {
        printf("Query OK, %d rows affected (%.3f sec)\n\n", $res['affected'], $res['time']);
      } elseif ($res['type'] === 'info') {
        printf("%s (%.3f sec)\n\n", $res['message'], $res['time']);
      } elseif ($res['type'] === 'error') {
        printf("ERROR: %s\n\n", $res['error']);
      }
      $buf = '';
    }
  }
  exit(0);
}

session_start();
if (empty($_SESSION['csrf'])) {
  $_SESSION['csrf'] = bin2hex(random_bytes(16));
}
$csrf = $_SESSION['csrf'];
$activePassword = AUTH_PASSWORD !== '' ? AUTH_PASSWORD : ($_SESSION['custom_password'] ?? '');

if ($activePassword !== '') {
  if (isset($_POST['p']) && hash_equals($activePassword, (string)$_POST['p'])) {
    $_SESSION['auth'] = true;
  }
  if (isset($_GET['logout'])) {
    unset($_SESSION['auth']);
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
  }
  if (empty($_SESSION['auth'])) {
    die('<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>' . APP_NAME . ' - Sign In</title><style>:root{--s:#111318;--o:#e2e2e9;--c:#1d2024;--p:#a8c7fa;--op:#04305f}body{background:var(--s);color:var(--o);font-family:system-ui,-apple-system,sans-serif;display:flex;align-items:center;justify-content:center;height:100dvh;margin:0}form{background:var(--c);padding:2.5rem;border-radius:28px;width:320px;border:1px solid rgba(255,255,255,.06);box-shadow:0 10px 30px rgba(0,0,0,.5)}input,button{width:100%;padding:.9rem;border-radius:99px;border:none;margin-top:.8rem;box-sizing:border-box;font-size:.9rem}input{background:#282a2f;color:#fff;border:1px solid #44474f}button{background:var(--p);color:var(--op);font-weight:700;cursor:pointer}</style></head><body><form method="post"><h2 style="margin:0 0 1rem;font-weight:600">' . APP_NAME . '</h2><input type="password" name="p" placeholder="Master Password" required autofocus><button>Sign In</button></form></body></html>');
  }
}

function checkCsrf(): void {
  $token = $_POST['csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
  if (!hash_equals($_SESSION['csrf'] ?? '', (string)$token)) {
    http_response_code(403);
    die('Forbidden: Invalid CSRF Token');
  }
}

$currentDb = isset($_GET['db']) ? cleanDbName($_GET['db']) : ($dbs[0] ?? '');
$pdo = getPdo($currentDb);
$tab = $_GET['tab'] ?? ($currentDb ? 'tables' : 'settings');
$table = isset($_GET['table']) ? trim($_GET['table']) : '';
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  checkCsrf();
  $act = $_POST['action'] ?? '';

  if ($act === 'sql') {
    header('Content-Type: application/json; charset=utf-8');
    if (!$pdo) {
      echo json_encode(['type' => 'error', 'error' => 'No active database connection.'], JSON_INVALID_UTF8_SUBSTITUTE);
      exit;
    }
    echo json_encode(runEmulatedQuery($pdo, $_POST['sql'] ?? '', $currentDb), JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
  }
  if ($act === 'create_table' && $pdo && !empty($_POST['name'])) {
    $tName = trim($_POST['name']);
    $cols = trim($_POST['cols'] ?? '') ?: 'id INTEGER PRIMARY KEY AUTOINCREMENT';
    $pdo->exec("CREATE TABLE " . qi($tName) . " ({$cols});");
    header("Location: ?db=" . urlencode($currentDb) . "&tab=browse&table=" . urlencode($tName));
    exit;
  }
  if ($act === 'create_db' && !empty($_POST['name'])) {
    $n = cleanDbName($_POST['name']);
    if ($n) {
      getPdo($n);
      header("Location: ?db=" . urlencode($n) . "&tab=tables");
      exit;
    }
  }
  if ($act === 'rename_db' && $currentDb && !empty($_POST['new_name'])) {
    $new = cleanDbName($_POST['new_name']);
    $oldPath = __DIR__ . DIRECTORY_SEPARATOR . $currentDb;
    $newPath = __DIR__ . DIRECTORY_SEPARATOR . $new;
    if ($new && file_exists($oldPath) && !file_exists($newPath)) {
      rename($oldPath, $newPath);
      header("Location: ?db=" . urlencode($new) . "&tab=settings&msg=Database+renamed");
      exit;
    }
  }
  if ($act === 'clone_db' && $currentDb) {
    $ext = pathinfo($currentDb, PATHINFO_EXTENSION);
    $base = pathinfo($currentDb, PATHINFO_FILENAME);
    $new = cleanDbName($base . '_clone_' . date('Ymd_His') . '.' . $ext);
    $oldPath = __DIR__ . DIRECTORY_SEPARATOR . $currentDb;
    $newPath = __DIR__ . DIRECTORY_SEPARATOR . $new;
    if ($new && file_exists($oldPath)) {
      copy($oldPath, $newPath);
      header("Location: ?db=" . urlencode($new) . "&tab=tables&msg=Database+cloned");
      exit;
    }
  }
  if ($act === 'backup_db' && $currentDb) {
    $dest = __DIR__ . DIRECTORY_SEPARATOR . $currentDb . '.' . date('Ymd_His') . '.bak.sqlite';
    $src = __DIR__ . DIRECTORY_SEPARATOR . $currentDb;
    if (file_exists($src)) {
      copy($src, $dest);
      header("Location: ?db=" . urlencode($currentDb) . "&tab=settings&msg=Backup+created");
      exit;
    }
  }
  if ($act === 'delete_db' && $currentDb) {
    $src = __DIR__ . DIRECTORY_SEPARATOR . $currentDb;
    if (file_exists($src)) {
      unlink($src);
      $remain = getDbList();
      $nxt = $remain[0] ?? '';
      header("Location: " . ($nxt ? "?db=" . urlencode($nxt) . "&tab=tables" : "?tab=settings"));
      exit;
    }
  }
  if ($act === 'upload_db' && !empty($_FILES['db_file']['tmp_name'])) {
    $fname = cleanDbName($_FILES['db_file']['name']);
    if ($fname) {
      $dest = __DIR__ . DIRECTORY_SEPARATOR . $fname;
      move_uploaded_file($_FILES['db_file']['tmp_name'], $dest);
      header("Location: ?db=" . urlencode($fname) . "&tab=tables");
      exit;
    }
  }
  if ($act === 'insert_row' && $table && $pdo) {
    $f = []; $v = []; $p = [];
    foreach ($_POST['col'] ?? [] as $k => $val) {
      $f[] = qi($k);
      if ($val === '') { $v[] = 'NULL'; } else { $v[] = '?'; $p[] = $val; }
    }
    if ($f) $pdo->prepare("INSERT INTO " . qi($table) . " (" . implode(',', $f) . ") VALUES (" . implode(',', $v) . ")")->execute($p);
    header("Location: ?db=" . urlencode($currentDb) . "&tab=browse&table=" . urlencode($table));
    exit;
  }
  if ($act === 'update_row' && $table && $pdo) {
    $rowid = $_POST['__rowid__'] ?? null;
    $sets = []; $p = [];
    foreach ($_POST['col'] ?? [] as $k => $val) {
      $sets[] = qi($k) . " = " . ($val === '' ? "NULL" : "?");
      if ($val !== '') $p[] = $val;
    }
    if ($rowid !== null && $sets) {
      $p[] = $rowid;
      $pdo->prepare("UPDATE " . qi($table) . " SET " . implode(', ', $sets) . " WHERE rowid = ?")->execute($p);
    }
    header("Location: ?db=" . urlencode($currentDb) . "&tab=browse&table=" . urlencode($table));
    exit;
  }
  if ($act === 'delete_row' && $table && $pdo && isset($_POST['rowid'])) {
    $pdo->prepare("DELETE FROM " . qi($table) . " WHERE rowid = ?")->execute([$_POST['rowid']]);
    header("Location: ?db=" . urlencode($currentDb) . "&tab=browse&table=" . urlencode($table));
    exit;
  }
  if ($act === 'bulk_delete' && $table && $pdo && !empty($_POST['selected_rowids'])) {
    $ids = explode(',', $_POST['selected_rowids']);
    $cleanIds = array_filter($ids, fn($x) => is_numeric($x));
    if ($cleanIds) {
      $placeholders = implode(',', array_fill(0, count($cleanIds), '?'));
      $pdo->prepare("DELETE FROM " . qi($table) . " WHERE rowid IN ({$placeholders})")->execute(array_values($cleanIds));
    }
    header("Location: ?db=" . urlencode($currentDb) . "&tab=browse&table=" . urlencode($table));
    exit;
  }
  if ($act === 'drop_table' && $table && $pdo) {
    $pdo->exec("DROP TABLE IF EXISTS " . qi($table));
    header("Location: ?db=" . urlencode($currentDb) . "&tab=tables");
    exit;
  }
  if ($act === 'truncate_table' && $table && $pdo) {
    $pdo->exec("DELETE FROM " . qi($table));
    $pdo->exec("VACUUM");
    header("Location: ?db=" . urlencode($currentDb) . "&tab=browse&table=" . urlencode($table));
    exit;
  }
  if ($act === 'add_relation' && $pdo && !empty($_POST['from_table']) && !empty($_POST['from_col']) && !empty($_POST['to_table']) && !empty($_POST['to_col'])) {
    $ft = $_POST['from_table']; $fc = $_POST['from_col']; $tt = $_POST['to_table']; $tc = $_POST['to_col'];
    $onDel = in_array($_POST['on_delete'] ?? '', ['CASCADE', 'SET NULL', 'RESTRICT', 'NO ACTION']) ? $_POST['on_delete'] : 'NO ACTION';
    try {
      $schemaSql = $pdo->query("SELECT sql FROM sqlite_master WHERE type='table' AND name = " . $pdo->quote($ft))->fetchColumn();
      if ($schemaSql) {
        $tempTable = $ft . '_alter_' . time();
        $fkClause = ", FOREIGN KEY (" . qi($fc) . ") REFERENCES " . qi($tt) . " (" . qi($tc) . ") ON DELETE {$onDel}";
        $newSchema = preg_replace('/\)\s*$/', "{$fkClause})", trim((string)$schemaSql), 1);
        $tempCreate = preg_replace('/^CREATE\s+TABLE\s+([^\s\(]+)/i', "CREATE TABLE " . qi($tempTable), $newSchema, 1);

        $pdo->beginTransaction();
        $pdo->exec("PRAGMA foreign_keys = OFF;");
        $pdo->exec($tempCreate);
        $pdo->exec("INSERT INTO " . qi($tempTable) . " SELECT * FROM " . qi($ft) . ";");
        $pdo->exec("DROP TABLE " . qi($ft) . ";");
        $pdo->exec("ALTER TABLE " . qi($tempTable) . " RENAME TO " . qi($ft) . ";");
        $pdo->commit();
        $pdo->exec("PRAGMA foreign_keys = ON;");
        $msg = "Relation+created";
      }
    } catch (Throwable $e) {
      if ($pdo->inTransaction()) $pdo->rollBack();
      $msg = urlencode($e->getMessage());
    }
    header("Location: ?db=" . urlencode($currentDb) . "&tab=relations&msg=" . ($msg ?? 'Done'));
    exit;
  }
  if ($act === 'vacuum' && $pdo) {
    $pdo->exec("VACUUM;");
    header("Location: ?db=" . urlencode($currentDb) . "&tab=settings&msg=Database+optimized");
    exit;
  }
  if ($act === 'toggle_wal' && $pdo) {
    $m = strtoupper((string)$pdo->query("PRAGMA journal_mode")->fetchColumn()) === 'WAL' ? 'DELETE' : 'WAL';
    $pdo->exec("PRAGMA journal_mode = {$m};");
    header("Location: ?db=" . urlencode($currentDb) . "&tab=settings&msg=WAL+" . $m);
    exit;
  }
  if ($act === 'set_auth' && AUTH_PASSWORD === '') {
    $p = trim((string)$_POST['auth_pass']);
    $_SESSION['custom_password'] = $p;
    if ($p !== '') $_SESSION['auth'] = true;
    header("Location: ?db=" . urlencode($currentDb) . "&tab=settings&msg=Security+updated");
    exit;
  }
  if ($act === 'import_sql' && $pdo) {
    $s = !empty($_FILES['file']['tmp_name']) ? file_get_contents($_FILES['file']['tmp_name']) : ($_POST['sql'] ?? '');
    if ($s) {
      $pdo->beginTransaction();
      try {
        $pdo->exec($s);
        $pdo->commit();
        $m = "SQL+Imported";
      } catch (Throwable $e) {
        $pdo->rollBack();
        $m = $e->getMessage();
      }
      header("Location: ?db=" . urlencode($currentDb) . "&tab=settings&msg=" . urlencode($m));
      exit;
    }
  }
}

if ($tab === 'dump' && $pdo) {
  header('Content-Type: application/sql');
  header('Content-Disposition: attachment; filename="' . pathinfo($currentDb, PATHINFO_FILENAME) . '.sql"');
  exit(exportSqlDump($pdo));
}
if ($tab === 'download' && $currentDb) {
  $f = __DIR__ . DIRECTORY_SEPARATOR . $currentDb;
  if (file_exists($f)) {
    header('Content-Type: application/x-sqlite3');
    header('Content-Disposition: attachment; filename="' . $currentDb . '"');
    header('Content-Length: ' . filesize($f));
    readfile($f);
    exit;
  }
}

$tables = $pdo ? $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name")->fetchAll(PDO::FETCH_COLUMN) : [];
if (!$table && $tables) $table = $tables[0];
$dbSize = ($currentDb && file_exists(__DIR__ . '/' . $currentDb)) ? round(filesize(__DIR__ . '/' . $currentDb) / 1024, 1) . ' KB' : '0 KB';
$sqliteVer = $pdo ? $pdo->query("SELECT sqlite_version()")->fetchColumn() : 'N/A';

$pageTitle = APP_NAME;
if ($table && $tab === 'browse') {
  $pageTitle = "{$table} - {$currentDb} - " . APP_NAME;
} elseif ($currentDb) {
  $pageTitle = ucfirst($tab) . " - {$currentDb} - " . APP_NAME;
} else {
  $pageTitle = APP_NAME . " - SQLite Admin";
}

if ($isAjax && $_SERVER['REQUEST_METHOD'] === 'GET') {
  ob_start();
  renderView($tab, $table, $pdo, $currentDb, $tables, (string)$sqliteVer, $dbSize, $dbs);
  echo json_encode(['title' => $pageTitle, 'html' => ob_get_clean()]);
  exit;
}

function renderView($tab, $table, $pdo, $currentDb, $tables, string $sqliteVer, string $dbSize, array $dbs) {
  $csrf = $_SESSION['csrf'] ?? '';
  ?>
  <div class="view-shell">
    <?php if (!$currentDb): ?>
      <div class="headline">
        <h1 class="title">Welcome to <?= APP_NAME ?></h1>
        <p class="subtitle">No SQLite database selected or found in this directory.</p>
      </div>
      <div class="grid">
        <div class="card">
          <h3 style="margin:0 0 .5rem">Create Database</h3>
          <p class="muted" style="margin-bottom:1rem">Initialize a fresh SQLite database file instantly.</p>
          <form method="post" class="row gap-sm">
            <input type="hidden" name="csrf" value="<?= $csrf ?>">
            <input type="hidden" name="action" value="create_db">
            <input type="text" name="name" placeholder="production.sqlite" required class="input">
            <button class="btn btn-prim" style="white-space:nowrap">Create</button>
          </form>
        </div>
        <div class="card">
          <h3 style="margin:0 0 .5rem">Upload Database</h3>
          <p class="muted" style="margin-bottom:1rem">Open an existing .db, .sqlite or .sqlite3 file.</p>
          <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf" value="<?= $csrf ?>">
            <input type="hidden" name="action" value="upload_db">
            <label class="file-box" style="margin-bottom:.8rem">
              <input type="file" name="db_file" accept=".db,.sqlite,.sqlite3" required onchange="updateFileName(this, 'db-file-name')">
              <span class="icon-wrap" style="margin:0 auto .5rem"><?= md_icon('upload', 24) ?></span>
              <div class="file-label-title" id="db-file-name">Tap to select database file</div>
              <div class="muted">Supports SQLite 3 formats</div>
            </label>
            <button class="btn btn-prim" style="width:100%"><?= md_icon('upload', 18) ?> Upload & Open</button>
          </form>
        </div>
      </div>

    <?php elseif ($tab === 'tables'): ?>
      <div class="headline">
        <h1 class="title">Tables</h1>
        <p class="subtitle"><?= count($tables) ?> tables found in <?= htmlspecialchars($currentDb) ?></p>
      </div>
      <div class="grid">
        <?php foreach ($tables as $t): ?>
          <a href="?db=<?= urlencode($currentDb) ?>&tab=browse&table=<?= urlencode($t) ?>" class="card action-card" data-nav>
            <span class="icon-wrap"><?= md_icon('table', 22) ?></span>
            <div style="flex:1">
              <span class="card-title"><?= htmlspecialchars($t) ?></span>
              <span class="muted"><?= $pdo->query("SELECT COUNT(*) FROM " . qi($t))->fetchColumn() ?> rows</span>
            </div>
            <?= md_icon('chevron', 20, 'muted-icon') ?>
          </a>
        <?php endforeach; ?>
        <?php if (!$tables): ?>
          <div class="empty-box"><?= md_icon('folder', 48, 'muted-icon') ?><p>No tables present yet. Tap the + button to create one.</p></div>
        <?php endif; ?>
      </div>

    <?php elseif ($tab === 'browse' && $table): ?>
      <?php
      $page = max(1, (int)($_GET['p'] ?? 1));
      $q = trim($_GET['q'] ?? '');
      $sort = trim($_GET['sort'] ?? '');
      $dir = strtoupper($_GET['dir'] ?? 'ASC') === 'DESC' ? 'DESC' : 'ASC';
      $limit = 25;
      $offset = ($page - 1) * $limit;
      $total = (int)$pdo->query("SELECT COUNT(*) FROM " . qi($table))->fetchColumn();
      $cols = array_column($pdo->query("PRAGMA table_info(" . qi($table) . ")")->fetchAll(), 'name');

      $w = ""; $p = [];
      if ($q !== '' && $cols) {
        $likes = array_map(fn($c) => qi($c) . " LIKE ?", $cols);
        $w = " WHERE " . implode(' OR ', $likes);
        $p = array_fill(0, count($cols), "%{$q}%");
        $stmtC = $pdo->prepare("SELECT COUNT(*) FROM " . qi($table) . " {$w}");
        $stmtC->execute($p);
        $total = (int)$stmtC->fetchColumn();
      }

      $orderClause = "";
      if ($sort && in_array($sort, $cols, true)) {
        $orderClause = " ORDER BY " . qi($sort) . " {$dir} ";
      }

      $hasRowId = true;
      try {
        $pdo->query("SELECT rowid FROM " . qi($table) . " LIMIT 0");
        $st = $pdo->prepare("SELECT rowid AS __rowid__, * FROM " . qi($table) . " {$w} {$orderClause} LIMIT {$limit} OFFSET {$offset}");
      } catch (Throwable $e) {
        $hasRowId = false;
        $st = $pdo->prepare("SELECT * FROM " . qi($table) . " {$w} {$orderClause} LIMIT {$limit} OFFSET {$offset}");
      }
      $st->execute($p);
      $rows = $st->fetchAll();
      ?>
      <div class="headline">
        <h1 class="title"><?= htmlspecialchars($table) ?></h1>
        <p class="subtitle"><?= $total ?> total records</p>
      </div>

      <div class="scroll-chips" style="margin-bottom:1rem">
        <a href="?db=<?= urlencode($currentDb) ?>&tab=structure&table=<?= urlencode($table) ?>" class="chip" data-nav><?= md_icon('relations', 16) ?> Schema</a>
        <button type="button" class="chip" onclick="openSheet()"><?= md_icon('add', 16) ?> Add Row</button>
        <form method="post" action="?db=<?= urlencode($currentDb) ?>&table=<?= urlencode($table) ?>" onsubmit="return confirm('Empty all records from this table?');" style="display:inline-flex">
          <input type="hidden" name="csrf" value="<?= $csrf ?>">
          <input type="hidden" name="action" value="truncate_table">
          <button type="submit" class="chip chip-err"><?= md_icon('clean', 16) ?> Truncate</button>
        </form>
        <form method="post" action="?db=<?= urlencode($currentDb) ?>&table=<?= urlencode($table) ?>" onsubmit="return confirm('Drop entire table?');" style="display:inline-flex">
          <input type="hidden" name="csrf" value="<?= $csrf ?>">
          <input type="hidden" name="action" value="drop_table">
          <button type="submit" class="chip chip-err"><?= md_icon('delete', 16) ?> Drop</button>
        </form>
      </div>

      <div id="bulk-bar" class="bulk-bar" style="display:none">
        <span id="bulk-count" class="bulk-count">0 items selected</span>
        <form method="post" action="?db=<?= urlencode($currentDb) ?>&tab=browse&table=<?= urlencode($table) ?>" onsubmit="return confirm('Delete all selected records?');">
          <input type="hidden" name="csrf" value="<?= $csrf ?>">
          <input type="hidden" name="action" value="bulk_delete">
          <input type="hidden" name="selected_rowids" id="bulk-ids" value="">
          <button type="submit" class="btn btn-err" style="min-height:32px;padding:0 12px"><?= md_icon('delete', 16) ?> Delete Selected</button>
        </form>
      </div>

      <form method="get" class="search-form" onsubmit="event.preventDefault(); nav('?db=<?= urlencode($currentDb) ?>&tab=browse&table=<?= urlencode($table) ?>&q=' + encodeURIComponent(this.q.value));">
        <div class="search-input-wrap">
          <span class="search-icon"><?= md_icon('search', 18) ?></span>
          <input type="search" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Search columns in <?= htmlspecialchars($table) ?>..." class="input search-input">
        </div>
      </form>

      <div class="tbl-box">
        <table class="tbl">
          <thead>
            <tr>
              <?php if ($hasRowId): ?>
                <th style="width:36px;text-align:center"><input type="checkbox" id="chk-all" onchange="toggleSelectAll(this)"></th>
                <th style="width:70px">Actions</th>
              <?php endif; ?>
              <?php foreach ($cols as $c): ?>
                <th>
                  <a href="?db=<?= urlencode($currentDb) ?>&tab=browse&table=<?= urlencode($table) ?>&q=<?= urlencode($q) ?>&sort=<?= urlencode($c) ?>&dir=<?= ($sort === $c && $dir === 'ASC') ? 'DESC' : 'ASC' ?>" class="tbl-sort" data-nav>
                    <?= htmlspecialchars($c) ?>
                    <?php if ($sort === $c): ?><span class="sort-arr"><?= $dir === 'ASC' ? '▲' : '▼' ?></span><?php endif; ?>
                  </a>
                </th>
              <?php endforeach; ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $r): ?>
              <tr>
                <?php if ($hasRowId): ?>
                  <td style="text-align:center">
                    <input type="checkbox" class="row-chk" value="<?= $r['__rowid__'] ?>" onchange="updateBulkBar()">
                  </td>
                  <td class="row gap-sm" style="padding:.5rem .75rem">
                    <button type="button" class="icon-btn" onclick='openEditRow(<?= json_encode($r) ?>)' title="Edit Record"><?= md_icon('edit', 16) ?></button>
                    <form method="post" action="?db=<?= urlencode($currentDb) ?>&table=<?= urlencode($table) ?>" onsubmit="return confirm('Delete this record?');">
                      <input type="hidden" name="csrf" value="<?= $csrf ?>">
                      <input type="hidden" name="action" value="delete_row">
                      <input type="hidden" name="rowid" value="<?= $r['__rowid__'] ?>">
                      <button type="submit" class="icon-btn-del" title="Delete"><?= md_icon('close', 16) ?></button>
                    </form>
                  </td>
                <?php endif; ?>
                <?php foreach ($cols as $k): ?>
                  <td><?= $r[$k] === null ? '<span class="pill-null">NULL</span>' : htmlspecialchars((string)$r[$k]) ?></td>
                <?php endforeach; ?>
              </tr>
            <?php endforeach; ?>
            <?php if (!$rows): ?><tr><td colspan="100%" class="center muted" style="padding:2.5rem 1rem">No records found</td></tr><?php endif; ?>
          </tbody>
        </table>
      </div>

      <?php if ($total > $limit): ?>
        <div class="between pagination">
          <span class="subtitle">Page <?= $page ?> of <?= (int)ceil($total / $limit) ?></span>
          <div class="row gap-sm">
            <?php if ($page > 1): ?><a href="?db=<?= urlencode($currentDb) ?>&tab=browse&table=<?= urlencode($table) ?>&q=<?= urlencode($q) ?>&sort=<?= urlencode($sort) ?>&dir=<?= $dir ?>&p=<?= $page - 1 ?>" class="btn btn-tonal" data-nav>« Prev</a><?php endif; ?>
            <?php if ($offset + $limit < $total): ?><a href="?db=<?= urlencode($currentDb) ?>&tab=browse&table=<?= urlencode($table) ?>&q=<?= urlencode($q) ?>&sort=<?= urlencode($sort) ?>&dir=<?= $dir ?>&p=<?= $page + 1 ?>" class="btn btn-tonal" data-nav>Next »</a><?php endif; ?>
          </div>
        </div>
      <?php endif; ?>

    <?php elseif ($tab === 'relations'): ?>
      <?php
      $schemaGraph = [];
      $allFks = [];
      foreach ($tables as $t) {
        $cList = $pdo->query("PRAGMA table_info(" . qi($t) . ")")->fetchAll();
        $fkList = $pdo->query("PRAGMA foreign_key_list(" . qi($t) . ")")->fetchAll();
        $schemaGraph[$t] = [
          'columns' => array_map(fn($c) => ['name' => $c['name'], 'type' => $c['type'] ?: 'TEXT', 'pk' => (bool)$c['pk']], $cList),
          'fks' => array_map(function($f) use ($t, &$allFks) {
            $item = ['fromTable' => $t, 'fromCol' => $f['from'], 'toTable' => $f['table'], 'toCol' => $f['to']];
            $allFks[] = $item;
            return $item;
          }, $fkList)
        ];
      }
      ?>
      <div class="headline between">
        <div>
          <h1 class="title">Visual Table Relations</h1>
          <p class="subtitle"><?= count($allFks) ?> relationships across <?= count($tables) ?> tables</p>
        </div>
        <div class="row gap-sm">
          <button type="button" class="chip" onclick="openAddRelationSheet()"><?= md_icon('add', 16) ?> Add Relation</button>
          <button type="button" class="chip" onclick="autoLayoutDiagram()"><?= md_icon('refresh', 16) ?> Auto Layout</button>
        </div>
      </div>

      <div class="erd-viewport" id="erd-viewport">
        <div class="erd-controls">
          <button type="button" class="erd-ctrl-btn" onclick="panErd(0, 80)" title="Pan Up">▲</button>
          <div class="erd-controls-row">
            <button type="button" class="erd-ctrl-btn" onclick="panErd(80, 0)" title="Pan Left">◀</button>
            <button type="button" class="erd-ctrl-btn" onclick="resetErdPan()" title="Reset Pan">●</button>
            <button type="button" class="erd-ctrl-btn" onclick="panErd(-80, 0)" title="Pan Right">▶</button>
          </div>
          <button type="button" class="erd-ctrl-btn" onclick="panErd(0, -80)" title="Pan Down">▼</button>
        </div>
        <svg id="erd-svg"></svg>
        <div id="erd-canvas">
          <?php foreach ($schemaGraph as $tName => $tData): ?>
            <div class="erd-card" data-table="<?= htmlspecialchars($tName) ?>" id="node-<?= htmlspecialchars($tName) ?>">
              <div class="erd-header">
                <span class="erd-header-icon"><?= md_icon('table', 18) ?></span>
                <span class="erd-header-title"><?= htmlspecialchars($tName) ?></span>
                <a href="?db=<?= urlencode($currentDb) ?>&tab=browse&table=<?= urlencode($tName) ?>" class="erd-link" data-nav title="Browse"><?= md_icon('browse', 14) ?></a>
              </div>
              <div class="erd-body">
                <?php foreach ($tData['columns'] as $c): ?>
                  <?php
                  $isFk = false;
                  foreach ($tData['fks'] as $fk) { if ($fk['fromCol'] === $c['name']) { $isFk = true; break; } }
                  ?>
                  <div class="erd-row" data-col="<?= htmlspecialchars($c['name']) ?>">
                    <span class="erd-col-name">
                      <?php if ($c['pk']): ?><span class="badge-pk"><?= md_icon('key', 12) ?></span><?php endif; ?>
                      <?php if ($isFk): ?><span class="badge-fk">FK</span><?php endif; ?>
                      <?= htmlspecialchars($c['name']) ?>
                    </span>
                    <span class="erd-col-type"><?= htmlspecialchars($c['type']) ?></span>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <script>
        window.erdFks = <?= json_encode($allFks) ?>;
        setTimeout(() => { if (typeof initErd === 'function') initErd(); }, 60);
      </script>

    <?php elseif ($tab === 'structure' && $table): ?>
      <div class="headline">
        <a href="?db=<?= urlencode($currentDb) ?>&tab=browse&table=<?= urlencode($table) ?>" class="back-link" data-nav><?= md_icon('back', 18) ?> Back to records</a>
        <h1 class="title"><?= htmlspecialchars($table) ?> Schema</h1>
      </div>
      <div class="tbl-box" style="margin-bottom:1.2rem">
        <table class="tbl">
          <thead><tr><th>#</th><th>Field</th><th>Type</th><th>Not Null</th><th>Default</th><th>PK</th></tr></thead>
          <tbody>
            <?php foreach ($pdo->query("PRAGMA table_info(" . qi($table) . ")") as $c): ?>
              <tr>
                <td><?= $c['cid'] ?></td>
                <td><strong><?= htmlspecialchars($c['name']) ?></strong></td>
                <td><span class="chip" style="padding:2px 8px"><?= htmlspecialchars($c['type'] ?: 'ANY') ?></span></td>
                <td><?= $c['notnull'] ? 'YES' : 'NO' ?></td>
                <td><?= $c['dflt_value'] === null ? '<span class="pill-null">NULL</span>' : htmlspecialchars((string)$c['dflt_value']) ?></td>
                <td><?= $c['pk'] ? 'PRI' : '' ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="card">
        <h3 style="margin:0 0 .5rem">DDL Statement</h3>
        <pre class="code"><?= htmlspecialchars((string)$pdo->query("SELECT sql FROM sqlite_master WHERE type='table' AND name=" . $pdo->quote($table))->fetchColumn()) ?></pre>
      </div>

    <?php elseif ($tab === 'sql'): ?>
      <div class="headline">
        <h1 class="title">SQL Studio</h1>
        <p class="subtitle">Interactive query terminal with extended SQL syntax emulation</p>
      </div>
      <div class="card">
        <div class="scroll-chips">
          <button type="button" class="chip" onclick="putSql('SHOW TABLES;')">SHOW TABLES</button>
          <button type="button" class="chip" onclick="putSql('SHOW DATABASES;')">SHOW DATABASES</button>
          <?php if ($table): ?>
            <button type="button" class="chip" onclick="putSql('DESCRIBE <?= htmlspecialchars($table) ?>;')">DESCRIBE</button>
            <button type="button" class="chip" onclick="putSql('SELECT * FROM <?= htmlspecialchars($table) ?> LIMIT 25;')">SELECT 25</button>
          <?php endif; ?>
        </div>
        <textarea id="sql-in" class="textarea" rows="4" placeholder="SELECT * FROM table;"></textarea>
        <div class="row" style="justify-content:flex-end;margin-top:.8rem">
          <button type="button" class="btn btn-prim" onclick="runSql()"><?= md_icon('play', 18) ?> Execute</button>
        </div>
      </div>
      <div id="sql-out" style="margin-top:1rem"></div>

    <?php elseif ($tab === 'settings'): ?>
      <div class="headline">
        <h1 class="title">Settings & Storage</h1>
        <p class="subtitle">SQLite database engine controls & security</p>
      </div>
      <?php if (!empty($_GET['msg'])): ?><div class="alert"><?= md_icon('check', 16) ?> <?= htmlspecialchars($_GET['msg']) ?></div><?php endif; ?>
      <?php if ($pdo): ?>
        <div class="card">
          <h3 style="margin:0 0 .9rem">Engine Specifications</h3>
          <div class="line"><span>Selected Database</span><strong><?= htmlspecialchars($currentDb) ?></strong></div>
          <div class="line"><span>Disk Space</span><strong><?= $dbSize ?></strong></div>
          <div class="line"><span>SQLite Version</span><strong>v<?= htmlspecialchars($sqliteVer) ?></strong></div>
          <div class="line"><span>Journal Mode</span><strong><?= strtoupper((string)$pdo->query("PRAGMA journal_mode")->fetchColumn()) ?></strong></div>
        </div>

        <div class="card">
          <h3 style="margin:0 0 .9rem">Database Editor</h3>
          <div class="db-editor-scroll">
            <form method="post" class="row gap-sm" style="flex-shrink:0">
              <input type="hidden" name="csrf" value="<?= $csrf ?>">
              <input type="hidden" name="action" value="rename_db">
              <input type="text" name="new_name" placeholder="Rename database..." required class="input" style="width:200px">
              <button class="btn btn-tonal" style="white-space:nowrap"><?= md_icon('edit', 16) ?> Rename</button>
            </form>
            <form method="post" style="flex-shrink:0">
              <input type="hidden" name="csrf" value="<?= $csrf ?>">
              <input type="hidden" name="action" value="clone_db">
              <button class="btn btn-tonal" style="white-space:nowrap"><?= md_icon('copy', 18) ?> Clone DB</button>
            </form>
            <form method="post" onsubmit="return confirm('Delete this database file permanently?');" style="flex-shrink:0">
              <input type="hidden" name="csrf" value="<?= $csrf ?>">
              <input type="hidden" name="action" value="delete_db">
              <button class="btn btn-err" style="white-space:nowrap"><?= md_icon('delete', 18) ?> Delete DB</button>
            </form>
          </div>
        </div>

        <div class="card">
          <h3 style="margin:0 0 .9rem">Maintenance & Backup</h3>
          <div class="row gap-sm" style="flex-wrap:wrap">
            <form method="post"><input type="hidden" name="csrf" value="<?= $csrf ?>"><input type="hidden" name="action" value="backup_db"><button class="btn btn-prim"><?= md_icon('download', 18) ?> Snapshot Backup</button></form>
            <form method="post"><input type="hidden" name="csrf" value="<?= $csrf ?>"><input type="hidden" name="action" value="vacuum"><button class="btn btn-tonal"><?= md_icon('clean', 18) ?> VACUUM</button></form>
            <form method="post"><input type="hidden" name="csrf" value="<?= $csrf ?>"><input type="hidden" name="action" value="toggle_wal"><button class="btn btn-tonal"><?= md_icon('bolt', 18) ?> Toggle WAL</button></form>
            <a href="?db=<?= urlencode($currentDb) ?>&tab=dump" class="btn btn-tonal"><?= md_icon('download', 18) ?> Dump .SQL</a>
            <a href="?db=<?= urlencode($currentDb) ?>&tab=download" class="btn btn-tonal"><?= md_icon('download', 18) ?> Raw .DB</a>
          </div>
        </div>
      <?php endif; ?>

      <div class="card">
        <h3 style="margin:0 0 .8rem">Switch Database</h3>
        <div class="scroll-chips" style="margin-bottom:1rem">
          <?php foreach ($dbs as $d): ?>
            <a href="?db=<?= urlencode($d) ?>&tab=tables" class="chip <?= $d === $currentDb ? 'chip-active' : '' ?>" data-nav><?= md_icon('db', 14) ?> <?= htmlspecialchars($d) ?></a>
          <?php endforeach; ?>
          <?php if (!$dbs): ?><span class="muted">No databases found on server</span><?php endif; ?>
        </div>
        <form method="post" class="row gap-sm" style="max-width:400px">
          <input type="hidden" name="csrf" value="<?= $csrf ?>">
          <input type="hidden" name="action" value="create_db">
          <input type="text" name="name" placeholder="new_database.sqlite" required class="input">
          <button class="btn btn-prim" style="white-space:nowrap">Create</button>
        </form>
      </div>

      <div class="card">
        <h3 style="margin:0 0 .8rem">Security & Authentication</h3>
        <?php if (AUTH_PASSWORD !== ''): ?>
          <p class="muted">Hardcoded AUTH_PASSWORD constant is currently active.</p>
          <a href="?logout=1" class="btn btn-tonal">Sign Out</a>
        <?php else: ?>
          <p class="muted" style="margin-bottom:.8rem">Set or clear session password. Leave empty for unlocked mode.</p>
          <form method="post" class="row gap-sm" style="max-width:400px">
            <input type="hidden" name="csrf" value="<?= $csrf ?>">
            <input type="hidden" name="action" value="set_auth">
            <input type="password" name="auth_pass" placeholder="New Master Password" value="<?= htmlspecialchars($_SESSION['custom_password'] ?? '') ?>" class="input">
            <button class="btn btn-prim">Save</button>
          </form>
        <?php endif; ?>
      </div>

      <?php if ($pdo): ?>
        <div class="card">
          <h3 style="margin:0 0 .8rem">Import SQL Script</h3>
          <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf" value="<?= $csrf ?>">
            <input type="hidden" name="action" value="import_sql">
            <label class="file-box" style="margin-bottom:1rem">
              <input type="file" name="file" accept=".sql" onchange="updateFileName(this, 'sql-file-name')">
              <span class="icon-wrap" style="margin:0 auto .5rem"><?= md_icon('upload', 24) ?></span>
              <div class="file-label-title" id="sql-file-name">Tap to select .sql file or drag & drop</div>
              <div class="muted">Supports standard schema and data dumps</div>
            </label>
            <textarea name="sql" class="textarea" rows="3" placeholder="Or paste raw SQL queries here..."></textarea>
            <button class="btn btn-prim" style="margin-top:.8rem"><?= md_icon('play', 18) ?> Execute Import</button>
          </form>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
  <?php
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?= htmlspecialchars($_SESSION['csrf']) ?>">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23a8c7fa' d='M12 3C7.58 3 4 4.79 4 7v10c0 2.21 3.58 4 8 4s8-1.79 8-4V7c0-2.21-3.58-4-8-4zm0 2c3.87 0 6 1.5 6 2s-2.13 2-6 2-6-1.5-6-2 2.13-2 6-2zm6 12c0 .5-2.13 2-6 2s-6-1.5-6-2v-2.23c1.61.78 3.72 1.23 6 1.23s4.39-.45 6-1.23V17zm0-3.5c0 .5-2.13 2-6 2s-6-1.5-6-2v-2.23c1.61.78 3.72 1.23 6 1.23s4.39-.45 6-1.23V13.5z'/%3E%3C/svg%3E">
    <style>
      :root, [data-theme="dark"] {
        --s: #111318; --os: #e2e2e9; --c: #1d2024; --ch: #282a2f; --p: #a8c7fa;
        --op: #04305f; --pc: #1b4777; --opc: #d3e4ff; --out: #8e9099; --out-var: #44474f; --err: #ffb4ab;
        --border: rgba(255,255,255,.06); --erd-bg: #0c0e12;
      }
      [data-theme="light"] {
        --s: #f8f9ff; --os: #191c20; --c: #ffffff; --ch: #eef1f6; --p: #0b57d0;
        --op: #ffffff; --pc: #d3e4ff; --opc: #04305f; --out: #74777f; --out-var: #c4c7cf; --err: #ba1a1a;
        --border: rgba(0,0,0,.08); --erd-bg: #f3f5f9;
      }
      * { box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
      html, body { margin: 0; padding: 0; width: 100vw; height: 100dvh; background: var(--s); color: var(--os); font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; overflow: hidden; }
      .i { fill: currentColor; display: inline-block; vertical-align: middle; }
      .app { display: flex; flex-direction: column; width: 100%; height: 100dvh; }
      .topbar { height: 60px; display: flex; align-items: center; justify-content: space-between; padding: 0 1rem; border-bottom: 1px solid var(--border); flex-shrink: 0; }
      .main { flex: 1; overflow-y: auto; overflow-x: hidden; overscroll-behavior-y: contain; padding: 1.25rem 1rem 6rem; }
      .bnav { position: fixed; bottom: 0; left: 0; width: 100vw; height: 72px; background: var(--c); display: flex; align-items: center; justify-content: space-around; padding-bottom: env(safe-area-inset-bottom); border-top: 1px solid var(--ch); z-index: 20; }
      .tab { display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--out); text-decoration: none; width: 64px; gap: 3px; font-size: .7rem; }
      .pill { width: 50px; height: 30px; border-radius: 99px; display: flex; align-items: center; justify-content: center; transition: background .15s; }
      .tab.active { color: var(--os); font-weight: 700; }
      .tab.active .pill { background: var(--pc); color: var(--opc); }
      .fab { position: fixed; right: 1.25rem; bottom: calc(84px + env(safe-area-inset-bottom)); width: 56px; height: 56px; border-radius: 16px; background: var(--pc); color: var(--opc); border: none; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 16px rgba(0,0,0,.3); cursor: pointer; z-index: 25; }
      .headline { margin-bottom: 1.25rem; }
      .title { font-size: 1.45rem; margin: 0; font-weight: 600; letter-spacing: -0.02em; }
      .subtitle { font-size: .82rem; color: var(--out); margin: .25rem 0 0; }
      .card { background: var(--c); border-radius: 20px; padding: 1.4rem; margin-bottom: 1rem; border: 1px solid var(--border); box-shadow: 0 2px 8px rgba(0,0,0,.08); }
      .action-card { display: flex; align-items: center; gap: 1rem; text-decoration: none; color: inherit; min-height: 64px; }
      .action-card:active { background: var(--ch); }
      .action-card .muted-icon { margin-right: .75rem; flex-shrink: 0; }
      select.input {
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%238e9099'%3E%3Cpath d='M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1.25rem center;
        background-size: 18px;
        padding-right: 2.75rem;
      }
      .icon-wrap { width: 44px; height: 44px; border-radius: 12px; background: var(--ch); display: flex; align-items: center; justify-content: center; color: var(--p); flex-shrink: 0; }
      .card-title { font-weight: 600; font-size: .95rem; display: block; }
      .tbl-box { width: 100%; overflow-x: auto; border-radius: 16px; border: 1px solid var(--ch); -webkit-overflow-scrolling: touch; }
      .tbl { width: 100%; border-collapse: collapse; font-size: .83rem; white-space: nowrap; }
      .tbl th, .tbl td { padding: .85rem 1rem; text-align: left; }
      .tbl th { background: var(--ch); color: var(--out); font-weight: 600; }
      .tbl tr:not(:last-child) td { border-bottom: 1px solid var(--ch); }
      .tbl tr:hover td { background: rgba(125,125,125,.04); }
      .tbl-sort { color: inherit; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; }
      .sort-arr { font-size: .65rem; color: var(--p); }
      .btn { display: inline-flex; align-items: center; justify-content: center; gap: .4rem; min-height: 42px; padding: 0 1.3rem; border-radius: 99px; font-size: .84rem; font-weight: 600; border: none; cursor: pointer; text-decoration: none; transition: transform .1s, opacity .15s; }
      .btn:active { transform: scale(0.98); }
      .btn-prim { background: var(--p); color: var(--op); }
      .btn-tonal { background: var(--ch); color: var(--os); }
      .btn-err { background: rgba(255,180,171,.15); color: var(--err); border: 1px solid var(--err); }
      .chip { display: inline-flex; align-items: center; gap: .35rem; background: var(--ch); color: var(--os); padding: 7px 14px; border-radius: 10px; font-size: .78rem; font-weight: 500; border: none; text-decoration: none; cursor: pointer; }
      .chip-active { background: var(--pc); color: var(--opc); }
      .chip-err { color: var(--err); }
      .input, .textarea { width: 100%; background: var(--ch); border: 1px solid var(--out-var); color: inherit; border-radius: 10px; padding: .75rem .9rem; font-family: inherit; font-size: .88rem; transition: border-color .15s, box-shadow .15s; }
      .input:focus, .textarea:focus { outline: none; border-color: var(--p); box-shadow: 0 0 0 1px var(--p); }
      .textarea { font-family: monospace; }
      .search-input-wrap { position: relative; display: flex; align-items: center; }
      .search-icon { position: absolute; left: 1rem; color: var(--out); pointer-events: none; }
      .search-input { padding-left: 2.75rem; border-radius: 99px; }
      .code { background: #090b0e; padding: 1rem; border-radius: 12px; font-size: .8rem; overflow-x: auto; color: #7dd3fc; margin: 0; border: 1px solid var(--border); }
      .pill-null { background: var(--ch); color: var(--out); padding: 2px 6px; border-radius: 4px; font-size: .7rem; }
      .icon-btn, .icon-btn-del { background: transparent; border: none; cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center; }
      .icon-btn { color: var(--out); }
      .icon-btn:hover { color: var(--p); }
      .icon-btn-del { color: var(--err); }
      .row { display: flex; align-items: center; }
      .between { display: flex; justify-content: space-between; align-items: center; }
      .gap-sm { gap: .5rem; }
      .scroll-chips { display: flex; gap: .5rem; overflow-x: auto; -webkit-overflow-scrolling: touch; }
      .alert { background: rgba(168,199,250,.1); border: 1px solid var(--p); color: var(--p); padding: .8rem 1.1rem; border-radius: 12px; margin-bottom: 1.2rem; font-size: .84rem; display: flex; align-items: center; gap: .5rem; }
      .line { display: flex; justify-content: space-between; padding: .55rem 0; border-bottom: 1px solid var(--border); font-size: .84rem; }
      .file-box { position: relative; border: 2px dashed var(--out-var); border-radius: 16px; padding: 1.75rem 1rem; text-align: center; cursor: pointer; display: block; background: rgba(125,125,125,.02); transition: all .2s; }
      .file-box:hover { border-color: var(--p); background: rgba(168,199,250,.04); }
      .file-box input[type="file"] { position: absolute; inset: 0; opacity: 0; width: 100%; height: 100%; cursor: pointer; }
      .file-label-title { font-weight: 600; font-size: .9rem; margin-bottom: .25rem; color: var(--os); }
      .sheet-bg { position: fixed; inset: 0; background: rgba(0,0,0,.65); backdrop-filter: blur(4px); z-index: 50; display: none; align-items: flex-end; }
      .sheet-bg.active { display: flex; }
      .sheet { width: 100%; max-height: 85dvh; background: var(--c); border-radius: 28px 28px 0 0; padding: 1.5rem; overflow-y: auto; overscroll-behavior: contain; box-shadow: 0 -4px 20px rgba(0,0,0,.4); }
      .drag-bar { width: 36px; height: 4px; background: var(--out); border-radius: 2px; margin: 0 auto 1.25rem; }
      .muted { color: var(--out); font-size: .8rem; }
      .muted-icon { fill: var(--out); }
      .empty-box { text-align: center; padding: 3.5rem 1rem; color: var(--out); }
      .search-form { margin-bottom: 1rem; }
      .back-link { display: inline-flex; align-items: center; gap: .4rem; color: var(--p); text-decoration: none; font-size: .82rem; font-weight: 500; margin-bottom: .4rem; }
      .bulk-bar { display: flex; align-items: center; justify-content: space-between; background: var(--ch); border: 1px solid var(--out-var); border-radius: 12px; padding: .6rem 1rem; margin-bottom: 1rem; }
      .bulk-count { font-size: .84rem; font-weight: 600; }
      .db-editor-scroll { display: flex; align-items: center; gap: .75rem; overflow-x: auto; -webkit-overflow-scrolling: touch; padding-bottom: 6px; scrollbar-width: thin; }
      .db-editor-scroll > * { flex-shrink: 0; }
      .erd-viewport { position: relative; width: 100%; height: calc(100dvh - 220px); min-height: 480px; background: var(--erd-bg); border-radius: 24px; border: 1px solid var(--ch); overflow: hidden; touch-action: none; cursor: default; }
      #erd-svg { position: absolute; inset: 0; width: 100%; height: 100%; pointer-events: none; z-index: 2; }
      #erd-canvas { position: absolute; width: 3200px; height: 3200px; top: 0; left: 0; z-index: 3; }
      .erd-controls { position: absolute; right: 1rem; top: 1rem; z-index: 10; display: flex; flex-direction: column; align-items: center; gap: 4px; background: var(--c); padding: 6px; border-radius: 14px; border: 1px solid var(--border); box-shadow: 0 4px 16px rgba(0,0,0,.25); backdrop-filter: blur(8px); }
      .erd-controls-row { display: flex; align-items: center; gap: 4px; }
      .erd-ctrl-btn { background: var(--ch); color: var(--os); border: 1px solid var(--border); width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: .75rem; font-weight: 700; transition: background .15s, border-color .15s; }
      .erd-ctrl-btn:hover { background: var(--pc); color: var(--opc); border-color: var(--p); }
      .erd-ctrl-btn:active { transform: scale(0.94); }
      .erd-card { position: absolute; width: 230px; background: var(--c); border: 1px solid var(--border); border-radius: 16px; box-shadow: 0 4px 18px rgba(0,0,0,.2); cursor: grab; user-select: none; z-index: 4; }
      .erd-card:active { cursor: grabbing; border-color: var(--p); }
      .erd-header { padding: .75rem 1rem; background: var(--ch); border-radius: 16px 16px 0 0; display: flex; align-items: center; gap: .5rem; border-bottom: 1px solid var(--border); }
      .erd-header-icon { color: var(--p); display: flex; }
      .erd-header-title { font-weight: 600; font-size: .88rem; flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
      .erd-link { color: var(--out); display: flex; align-items: center; text-decoration: none; }
      .erd-link:hover { color: var(--p); }
      .erd-body { padding: .5rem 0; font-size: .78rem; max-height: 220px; overflow-y: auto; }
      .erd-row { display: flex; justify-content: space-between; align-items: center; padding: .35rem 1rem; }
      .erd-row:hover { background: rgba(125,125,125,.04); }
      .erd-col-name { display: flex; align-items: center; gap: .3rem; font-weight: 500; }
      .erd-col-type { color: var(--out); font-size: .72rem; font-family: monospace; }
      .badge-pk { color: #facc15; display: inline-flex; align-items: center; }
      .badge-fk { background: var(--pc); color: var(--opc); font-size: .65rem; padding: 1px 4px; border-radius: 4px; font-weight: 700; }
      @media(min-width:768px) {
        .app { flex-direction: row; }
        .topbar { display: none; }
        .bnav { order: -1; position: static; width: 84px; height: 100dvh; flex-direction: column; justify-content: flex-start; align-items: center; padding-top: 2rem; gap: 1.25rem; border-top: none; border-right: 1px solid var(--ch); flex-shrink: 0; }
        .main { padding: 2.5rem; }
        .fab { position: static; width: 48px; height: 48px; border-radius: 16px; margin-bottom: .5rem; box-shadow: 0 2px 8px rgba(0,0,0,.2); flex-shrink: 0; }
        .sheet-bg { align-items: center; justify-content: center; }
        .sheet { width: 520px; border-radius: 28px; }
        .erd-viewport { height: calc(100dvh - 180px); }
      }
    </style>
  </head>
  <body>
    <div class="app">
      <header class="topbar">
        <div class="row gap-sm" style="font-weight:700">
          <span class="icon-wrap" style="width:34px;height:34px;border-radius:8px"><?= md_icon('db', 18) ?></span>
          <span><?= APP_NAME ?></span>
        </div>
        <div class="row gap-sm">
          <button type="button" class="chip" onclick="toggleTheme()" aria-label="Toggle Theme"><?= md_icon('sun', 16, 'theme-icon-light') ?><?= md_icon('moon', 16, 'theme-icon-dark') ?></button>
          <?php if ($currentDb): ?>
            <a href="?db=<?= urlencode($currentDb) ?>&tab=settings" class="chip" data-nav><?= htmlspecialchars($currentDb) ?></a>
          <?php endif; ?>
        </div>
      </header>

      <main class="main" id="app">
        <?php renderView($tab, $table, $pdo, $currentDb, $tables, (string)$sqliteVer, $dbSize, $dbs); ?>
      </main>

      <nav class="bnav">
        <?php if ($pdo): ?>
          <button class="fab" onclick="openSheet()" aria-label="Add"><?= md_icon('add', 24) ?></button>
        <?php endif; ?>
        <a href="?db=<?= urlencode($currentDb) ?>&tab=tables" class="tab <?= $tab === 'tables' ? 'active' : '' ?>" data-nav>
          <div class="pill"><?= md_icon('table', 20) ?></div><span>Tables</span>
        </a>
        <a href="?db=<?= urlencode($currentDb) ?>&tab=browse&table=<?= urlencode($table) ?>" class="tab <?= $tab === 'browse' ? 'active' : '' ?>" data-nav>
          <div class="pill"><?= md_icon('browse', 20) ?></div><span>Browse</span>
        </a>
        <a href="?db=<?= urlencode($currentDb) ?>&tab=relations" class="tab <?= $tab === 'relations' ? 'active' : '' ?>" data-nav>
          <div class="pill"><?= md_icon('relations', 20) ?></div><span>Relations</span>
        </a>
        <a href="?db=<?= urlencode($currentDb) ?>&tab=sql" class="tab <?= $tab === 'sql' ? 'active' : '' ?>" data-nav>
          <div class="pill"><?= md_icon('terminal', 20) ?></div><span>SQL</span>
        </a>
        <a href="?db=<?= urlencode($currentDb) ?>&tab=settings" class="tab <?= $tab === 'settings' ? 'active' : '' ?>" data-nav>
          <div class="pill"><?= md_icon('settings', 20) ?></div><span>Settings</span>
        </a>
      </nav>
    </div>

    <?php if ($pdo): ?>
      <div class="sheet-bg" id="sheet" onclick="closeSheet(event)">
        <div class="sheet" onclick="event.stopPropagation()">
          <div class="drag-bar"></div>
          <?php if ($tab === 'browse' && $table): ?>
            <h2 style="margin:0 0 1rem;font-size:1.25rem">Add Row to <?= htmlspecialchars($table) ?></h2>
            <form method="post" action="?db=<?= urlencode($currentDb) ?>&table=<?= urlencode($table) ?>">
              <input type="hidden" name="csrf" value="<?= $csrf ?>">
              <input type="hidden" name="action" value="insert_row">
              <?php foreach ($pdo->query("PRAGMA table_info(" . qi($table) . ")") as $c): if ($c['pk']) continue; ?>
                <div style="margin-bottom:.85rem">
                  <label class="muted" style="display:block;margin-bottom:4px"><?= htmlspecialchars($c['name']) ?> (<?= htmlspecialchars($c['type'] ?: 'TEXT') ?>)</label>
                  <input type="text" name="col[<?= htmlspecialchars($c['name']) ?>]" class="input">
                </div>
              <?php endforeach; ?>
              <button class="btn btn-prim" style="width:100%;margin-top:.4rem">Save Record</button>
            </form>
          <?php else: ?>
            <h2 style="margin:0 0 1rem;font-size:1.25rem">Create New Table</h2>
            <form method="post" action="?db=<?= urlencode($currentDb) ?>">
              <input type="hidden" name="csrf" value="<?= $csrf ?>">
              <input type="hidden" name="action" value="create_table">
              <div style="margin-bottom:.85rem">
                <label class="muted" style="display:block;margin-bottom:4px">Table Name</label>
                <input type="text" name="name" placeholder="customers" required class="input">
              </div>
              <div style="margin-bottom:.85rem">
                <label class="muted" style="display:block;margin-bottom:4px">Fields Definition</label>
                <textarea name="cols" class="textarea" rows="3">id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP</textarea>
              </div>
              <button class="btn btn-prim" style="width:100%">Create Table</button>
            </form>
          <?php endif; ?>
        </div>
      </div>

      <div class="sheet-bg" id="edit-sheet" onclick="closeEditSheet(event)">
        <div class="sheet" onclick="event.stopPropagation()">
          <div class="drag-bar"></div>
          <h2 style="margin:0 0 1rem;font-size:1.25rem">Edit Record</h2>
          <form method="post" id="edit-record-form" action="?db=<?= urlencode($currentDb) ?>&table=<?= urlencode($table) ?>">
            <input type="hidden" name="csrf" value="<?= $csrf ?>">
            <input type="hidden" name="action" value="update_row">
            <input type="hidden" name="__rowid__" id="edit-rowid" value="">
            <div id="edit-fields-container"></div>
            <button class="btn btn-prim" style="width:100%;margin-top:.4rem">Update Record</button>
          </form>
        </div>
      </div>

      <div class="sheet-bg" id="relation-sheet" onclick="closeRelationSheet(event)">
        <div class="sheet" onclick="event.stopPropagation()">
          <div class="drag-bar"></div>
          <h2 style="margin:0 0 1rem;font-size:1.25rem">Create Foreign Key Relation</h2>
          <form method="post" action="?db=<?= urlencode($currentDb) ?>&tab=relations">
            <input type="hidden" name="csrf" value="<?= $csrf ?>">
            <input type="hidden" name="action" value="add_relation">
            <div style="margin-bottom:.85rem">
              <label class="muted" style="display:block;margin-bottom:4px">Source Table (Child)</label>
              <select name="from_table" id="rel-from-table" class="input" onchange="loadRelColumns('from', this.value)" required>
                <option value="">Select Table...</option>
                <?php foreach ($tables as $t): ?><option value="<?= htmlspecialchars($t) ?>"><?= htmlspecialchars($t) ?></option><?php endforeach; ?>
              </select>
            </div>
            <div style="margin-bottom:.85rem">
              <label class="muted" style="display:block;margin-bottom:4px">Source Column (Foreign Key)</label>
              <select name="from_col" id="rel-from-col" class="input" required></select>
            </div>
            <div style="margin-bottom:.85rem">
              <label class="muted" style="display:block;margin-bottom:4px">Target Table (Parent)</label>
              <select name="to_table" id="rel-to-table" class="input" onchange="loadRelColumns('to', this.value)" required>
                <option value="">Select Table...</option>
                <?php foreach ($tables as $t): ?><option value="<?= htmlspecialchars($t) ?>"><?= htmlspecialchars($t) ?></option><?php endforeach; ?>
              </select>
            </div>
            <div style="margin-bottom:.85rem">
              <label class="muted" style="display:block;margin-bottom:4px">Target Column (Primary/Unique Key)</label>
              <select name="to_col" id="rel-to-col" class="input" required></select>
            </div>
            <div style="margin-bottom:.85rem">
              <label class="muted" style="display:block;margin-bottom:4px">On Delete</label>
              <select name="on_delete" class="input">
                <option value="NO ACTION">NO ACTION</option>
                <option value="CASCADE">CASCADE</option>
                <option value="SET NULL">SET NULL</option>
                <option value="RESTRICT">RESTRICT</option>
              </select>
            </div>
            <button class="btn btn-prim" style="width:100%;margin-top:.4rem">Apply Schema Relation</button>
          </form>
        </div>
      </div>
    <?php endif; ?>

    <script>
      let erdPanX = 0, erdPanY = 0;

      function applyTheme(th) {
        document.documentElement.setAttribute('data-theme', th);
        localStorage.setItem('dblite_theme', th);
        document.querySelectorAll('.theme-icon-light').forEach(i => i.style.display = th === 'light' ? 'none' : 'inline-block');
        document.querySelectorAll('.theme-icon-dark').forEach(i => i.style.display = th === 'light' ? 'inline-block' : 'none');
      }

      function toggleTheme() {
        const cur = document.documentElement.getAttribute('data-theme') || 'dark';
        applyTheme(cur === 'dark' ? 'light' : 'dark');
      }

      applyTheme(localStorage.getItem('dblite_theme') || (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark'));

      async function nav(url, push = true) {
        const apply = async () => {
          const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
          const json = await res.json();
          document.getElementById('app').innerHTML = json.html;
          document.title = json.title;
          const tab = new URL(url, location.origin).searchParams.get('tab') || 'tables';
          document.querySelectorAll('.tab').forEach(e => e.classList.toggle('active', e.href.includes('tab=' + tab)));
          if (push) history.pushState({ url }, '', url);
          if (tab === 'relations') initErd();
        };
        document.startViewTransition ? document.startViewTransition(apply) : await apply();
      }

      document.addEventListener('click', e => {
        const a = e.target.closest('a[data-nav]');
        if (a) { e.preventDefault(); nav(a.href); }
      });

      window.addEventListener('popstate', e => { if (e.state?.url) nav(e.state.url, false); });
      function openSheet() { document.getElementById('sheet')?.classList.add('active'); }
      function closeSheet(e) { if (!e || e.target.id === 'sheet') document.getElementById('sheet')?.classList.remove('active'); }
      function openAddRelationSheet() { document.getElementById('relation-sheet')?.classList.add('active'); }
      function closeRelationSheet(e) { if (!e || e.target.id === 'relation-sheet') document.getElementById('relation-sheet')?.classList.remove('active'); }
      function openEditSheet() { document.getElementById('edit-sheet')?.classList.add('active'); }
      function closeEditSheet(e) { if (!e || e.target.id === 'edit-sheet') document.getElementById('edit-sheet')?.classList.remove('active'); }

      function openEditRow(data) {
        const container = document.getElementById('edit-fields-container');
        const rowidInput = document.getElementById('edit-rowid');
        if (!container || !rowidInput) return;
        rowidInput.value = data.__rowid__ ?? '';
        let h = '';
        Object.keys(data).forEach(k => {
          if (k === '__rowid__') return;
          const val = data[k] === null ? '' : data[k];
          h += `
            <div style="margin-bottom:.85rem">
              <label class="muted" style="display:block;margin-bottom:4px">${k}</label>
              <input type="text" name="col[${k}]" value="${String(val).replace(/"/g, '&quot;')}" class="input">
            </div>
          `;
        });
        container.innerHTML = h;
        openEditSheet();
      }

      function toggleSelectAll(master) {
        document.querySelectorAll('.row-chk').forEach(c => c.checked = master.checked);
        updateBulkBar();
      }

      function updateBulkBar() {
        const checked = Array.from(document.querySelectorAll('.row-chk:checked')).map(c => c.value);
        const bar = document.getElementById('bulk-bar');
        const count = document.getElementById('bulk-count');
        const ids = document.getElementById('bulk-ids');
        if (!bar || !count || !ids) return;
        if (checked.length > 0) {
          bar.style.display = 'flex';
          count.textContent = `${checked.length} record(s) selected`;
          ids.value = checked.join(',');
        } else {
          bar.style.display = 'none';
          ids.value = '';
          const master = document.getElementById('chk-all');
          if (master) master.checked = false;
        }
      }

      function putSql(q) { const el = document.getElementById('sql-in'); if (el) el.value = q; }
      function updateFileName(input, targetId) {
        const label = document.getElementById(targetId);
        if (label && input.files && input.files[0]) label.textContent = input.files[0].name;
      }

      window.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?= $csrf ?>';

      async function runSql() {
        const sqlInput = document.getElementById('sql-in');
        const out = document.getElementById('sql-out');
        if (!sqlInput || !out) return;
        const sql = sqlInput.value.trim();
        if (!sql) return;

        out.innerHTML = '<div class="muted">Executing query...</div>';

        try {
          const activeDb = new URLSearchParams(window.location.search).get('db') || '<?= urlencode($currentDb) ?>';
          const token = window.csrfToken || document.querySelector('input[name="csrf"]')?.value || '';

          const res = await fetch(`?db=${encodeURIComponent(activeDb)}`, {
            method: 'POST',
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'X-CSRF-Token': token,
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({ action: 'sql', sql, csrf: token })
          });

          if (!res.ok) {
            const raw = await res.text();
            out.innerHTML = `<div class="alert" style="border-color:var(--err);color:var(--err)">HTTP Error ${res.status}: ${raw || res.statusText}</div>`;
            return;
          }

          const d = await res.json();

          if (d.type === 'error') {
            out.innerHTML = `<div class="alert" style="border-color:var(--err);color:var(--err)">Error: ${d.error}</div>`;
          } else if (d.type === 'dml' || d.type === 'info') {
            out.innerHTML = `<div class="alert">${d.message || `Query OK, ${d.affected} row(s) affected`} (${d.time.toFixed(3)}s)</div>`;
          } else if (d.type === 'select') {
            if (!d.rows || d.rows.length === 0) {
              out.innerHTML = `<div class="alert">Empty set (${d.time.toFixed(3)}s)</div>`;
              return;
            }
            const cols = Object.keys(d.rows[0]);
            let h = `<div class="muted" style="margin-bottom:.5rem">${d.count} rows (${d.time.toFixed(3)}s)</div><div class="tbl-box"><table class="tbl"><thead><tr>`;
            cols.forEach(c => h += `<th>${c}</th>`);
            h += `</tr></thead><tbody>`;
            d.rows.forEach(r => {
              h += `<tr>`;
              cols.forEach(c => h += `<td>${r[c] === null ? '<span class="pill-null">NULL</span>' : r[c]}</td>`);
              h += `</tr>`;
            });
            out.innerHTML = h + `</tbody></table></div>`;
          }
        } catch (err) {
          out.innerHTML = `<div class="alert" style="border-color:var(--err);color:var(--err)">Execution Error: ${err.message}</div>`;
        }
      }

      function loadRelColumns(type, tableName) {
        const target = document.getElementById(`rel-${type}-col`);
        if (!target || !tableName) return;
        const card = document.querySelector(`.erd-card[data-table="${tableName}"]`);
        if (!card) return;
        const cols = Array.from(card.querySelectorAll('.erd-row')).map(r => r.getAttribute('data-col'));
        target.innerHTML = cols.map(c => `<option value="${c}">${c}</option>`).join('');
      }

      function panErd(dx, dy) {
        erdPanX += dx;
        erdPanY += dy;
        applyErdTransform();
      }

      function resetErdPan() {
        erdPanX = 0;
        erdPanY = 0;
        applyErdTransform();
      }

      function applyErdTransform() {
        const canvas = document.getElementById('erd-canvas');
        if (canvas) canvas.style.transform = `translate(${erdPanX}px, ${erdPanY}px)`;
        drawRelations();
      }

      function initErd() {
        const viewport = document.getElementById('erd-viewport');
        const canvas = document.getElementById('erd-canvas');
        if (!viewport || !canvas) return;

        autoLayoutDiagram();

        let activeNode = null;
        let isPanning = false;
        let startX, startY, initialLeft, initialTop;

        viewport.onmousedown = (e) => {
          const card = e.target.closest('.erd-card');
          if (card) {
            activeNode = card;
            startX = e.clientX;
            startY = e.clientY;
            initialLeft = parseInt(activeNode.style.left, 10) || 0;
            initialTop = parseInt(activeNode.style.top, 10) || 0;
          } else if (!e.target.closest('.erd-controls')) {
            isPanning = true;
            startX = e.clientX - erdPanX;
            startY = e.clientY - erdPanY;
            viewport.style.cursor = 'grabbing';
          }
        };

        window.onmousemove = (e) => {
          if (activeNode) {
            const dx = e.clientX - startX;
            const dy = e.clientY - startY;
            activeNode.style.left = `${Math.max(10, initialLeft + dx)}px`;
            activeNode.style.top = `${Math.max(10, initialTop + dy)}px`;
            drawRelations();
          } else if (isPanning) {
            erdPanX = e.clientX - startX;
            erdPanY = e.clientY - startY;
            applyErdTransform();
          }
        };

        window.onmouseup = () => {
          activeNode = null;
          if (isPanning) {
            isPanning = false;
            if (viewport) viewport.style.cursor = 'default';
          }
        };
      }

      function autoLayoutDiagram() {
        const nodes = Array.from(document.querySelectorAll('.erd-card'));
        if (!nodes.length) return;
        const cols = Math.max(1, Math.min(4, Math.ceil(Math.sqrt(nodes.length))));
        nodes.forEach((node, i) => {
          const col = i % cols;
          const row = Math.floor(i / cols);
          node.style.left = `${40 + col * 280}px`;
          node.style.top = `${40 + row * 270}px`;
        });
        resetErdPan();
      }

      function drawRelations() {
        const svg = document.getElementById('erd-svg');
        const canvas = document.getElementById('erd-canvas');
        if (!svg || !canvas || !window.erdFks) return;

        const vRect = svg.getBoundingClientRect();
        let lines = `
          <defs>
            <marker id="erd-arrow" viewBox="0 0 10 10" refX="7" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse">
              <path d="M 0 1 L 10 5 L 0 9 z" fill="var(--p)"/>
            </marker>
          </defs>
        `;

        window.erdFks.forEach(fk => {
          const fromRow = document.querySelector(`.erd-card[data-table="${fk.fromTable}"] .erd-row[data-col="${fk.fromCol}"]`);
          const toCard = document.querySelector(`.erd-card[data-table="${fk.toTable}"]`);
          const toRow = toCard?.querySelector(`.erd-row[data-col="${fk.toCol}"]`) || toCard;

          if (!fromRow || !toRow) return;

          const r1 = fromRow.getBoundingClientRect();
          const r2 = toRow.getBoundingClientRect();

          const x1 = r1.right - vRect.left;
          const y1 = r1.top + r1.height / 2 - vRect.top;
          const x2 = r2.left - vRect.left;
          const y2 = r2.top + r2.height / 2 - vRect.top;

          const dx = Math.max(30, Math.abs(x2 - x1) * 0.45);
          lines += `<path d="M ${x1} ${y1} C ${x1 + dx} ${y1}, ${x2 - dx} ${y2}, ${x2} ${y2}" stroke="var(--p)" stroke-width="2" fill="none" marker-end="url(#erd-arrow)" opacity="0.85"/>`;
        });

        svg.innerHTML = lines;
      }

      document.addEventListener('DOMContentLoaded', () => {
        const tab = new URL(location.href).searchParams.get('tab') || 'tables';
        if (tab === 'relations') initErd();
      });
    </script>
  </body>
</html>
