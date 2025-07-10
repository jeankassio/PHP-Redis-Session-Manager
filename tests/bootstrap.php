<?php
// Tenta carregar o autoload do projeto principal
$paths = [
    __DIR__ . '/../vendor/autoload.php',                     // desenvolvimento fora do vendor
    __DIR__ . '/../../../autoload.php',                     // quando instalado via composer
];

foreach ($paths as $path) {
    if (file_exists($path)) {
        require_once $path;
        return;
    }
}

fwrite(STDERR, "Could not find Composer autoload.\n");
exit(1);
