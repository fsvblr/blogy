<?php

/**
 * Constant that is checked in included files to prevent direct access.
 */

\define('_PREVENT', 1);

\define('PATH_ROOT', dirname(__DIR__));

if (!file_exists(PATH_ROOT . '/app/configuration.php')) {
    echo 'No configuration file found. Exiting...';
    exit;
}
require_once PATH_ROOT . '/app/configuration.php';

if (!file_exists(PATH_ROOT . '/app/cache/autoload_psr4.php')) {
    echo 'Namespaces file not found. Exiting...';
    exit;
}
$map = require_once PATH_ROOT . '/app/cache/autoload_psr4.php';

if (!file_exists(PATH_ROOT . '/app/autoload.php')) {
    echo 'Autoload file not found. Exiting...';
    exit;
}
require_once PATH_ROOT . '/app/autoload.php';

// ------------------------------------------------------------------

$config = new \Config();

$password = isset($_GET['pass']) ? $_GET['pass'] : '';
$password_migration = !empty($config->password_migration) ? $config->password_migration : null;
if (empty($password_migration) || $password_migration !== $password) {
    exit('Invalid password.');
}

use Blogy\Helper\DatabaseHelper;

$sqlFiles = glob(__DIR__ . '/migrations/*.sql');

$pdo = DatabaseHelper::getDb();

$stmtAllTables = $pdo->query("SHOW TABLES");
$tables = $stmtAllTables->fetchAll(PDO::FETCH_COLUMN);
$tableExists = in_array('migrations', $tables);

do {
    $sqlFile = array_shift($sqlFiles);
    $file = basename($sqlFile);
    $needToRun = false;

    if ($tableExists) {
        try {
            $smpt = $pdo->prepare("SELECT `id` FROM `migrations` WHERE `file` = :file LIMIT 1");
            $smpt->execute(['file' => $file]);
            $migrationId = $smpt->fetchColumn();
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
        $needToRun = empty($migrationId);
    } else {
        $needToRun = true;
        $tableExists = true;
    }

    if($needToRun) {
        try {
            array_map(function ($query) use($pdo) {
                $smpt = $pdo->prepare($query);
                $smpt->execute();
            }, array_filter(DatabaseHelper::splitSql(file_get_contents($sqlFile)), function($query) {
                return !!trim($query);
            }));

            $sql = "INSERT INTO `migrations` (`file`, `datetime`) VALUES (:file, NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['file' => $file]);

        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
} while(count($sqlFiles));

exit('Done');
