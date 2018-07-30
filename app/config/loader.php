<?php

/**
 * Registering an autoloader
 */
$loader = new \Phalcon\Loader();

$loader->registerDirs(
    [
        $config->application->modelsDir,
        $config->application->controllersDir,
        $config->application->libraryDir,
        $config->application->corePluginDir,
        $config->application->vendorDir,
    ]
);

$loader->registerFiles([
    $config->application->vendorDir . 'autoload.php'
]);

$loader->register();
