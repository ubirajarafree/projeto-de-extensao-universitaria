<?php
// Autoloader para as Classes Backend
spl_autoload_register(function ($class) {

    $prefixes = [
        'Backend\\' => __DIR__ . '/../backend/',
    ];

    foreach ($prefixes as $prefix => $base_dir) {

        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            continue;
        }

        $relative_class = substr($class, $len);
        $relative_class = str_replace('\\', '/', $relative_class);

        $file = $base_dir . $relative_class . '.php';

        if (file_exists($file)) {
            require $file;
            return;
        } else {
            echo "Arquivo não encontrado: $file\n";
        }
    }

});
