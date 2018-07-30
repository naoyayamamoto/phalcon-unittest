<?php

use Phalcon\Loader;
use Phalcon\Di;
use Phalcon\Mvc\Model\Migration;

ini_set("display_errors", 1);
error_reporting(E_ALL);

ini_set('memory_limit', '1024M');

define("ROOT_PATH", __DIR__);

set_include_path(
    ROOT_PATH . PATH_SEPARATOR . get_include_path()
);

// require default application config
$config = include __DIR__ . "/config.php";

// Required for phalcon/incubator
include __DIR__ . "/../app/vendor/autoload.php";

// Use the application autoloader to autoload the classes
// Autoload the dependencies found in composer
$loader = new Loader();

$loader->registerDirs(array_values(
    // exclued files
    array_filter((array)$config->application, function($key) {
        return $key !== 'files' && $key !== 'namespaces';
    }, ARRAY_FILTER_USE_KEY)
));

$loader->register();

// データの投入用のDB準備
$di = new Di();
// サービスの用意
include __DIR__ . "/TestServices.php";

// マイグレーションクラスを準備
$migration = new Migration();
Migration::setup($config->database);
Migration::setMigrationPath($config->application->fixturesDir);

// fixtureディレクトリ内のファイル検索
if (is_dir($config->application->fixturesDir) && $handle = opendir($config->application->fixturesDir)) {
    while (($file = readdir($handle)) !== false) {
        $info = new SplFileInfo($config->application->fixturesDir . '/' . $file);
        // データファイルであれば投入していく
        if ($info->isFile() && $info->getExtension() === 'dat') {
            $migration->batchInsert($info->getBasename('.dat'), null);
        }
    }
}
// 用済み
$di = null;
