<?php
use Helpers\DotEnv;
use Server\Autoloader;
use App\Services\Importer\Importer;
require_once './server/Autoloader.php';
new Autoloader('System', '/../system/');
new Autoloader('App\\Services\\Importer', '/../app/Services/Importer');
new Autoloader('App\\Models', '/../app/Models');
new Autoloader('Helpers', '/../helpers');
new Autoloader('Migrations', '/../migrations');
(new DotEnv(__DIR__ . '/.env'))->load();

// migrations
(new \Migrations\CreateProductsTable())->run();
(new \Migrations\CreateSplitStringFunction())->run();
(new \Migrations\CreateCheckAndFillOldValueFunction())->run();

try {
    $start = microtime(true);
    (new Importer($_ENV['IMPORT_FILES_DIR']))->run();
    echo (microtime(true) - $start)." - seconds";
} catch (Exception $e) {
    throw new $e;
}