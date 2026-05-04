<?php
try {
    require 'vendor/autoload.php';
    $app = require 'bootstrap/app.php';
    echo "Bootstrap OK\n";
    echo "App kernel: " . get_class($app->make('Illuminate\Contracts\Http\Kernel')) . "\n";
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
