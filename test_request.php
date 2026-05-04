<?php
try {
    require 'vendor/autoload.php';
    
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = '/quizzes';
    $_SERVER['SERVER_NAME'] = 'localhost';
    $_SERVER['SERVER_PORT'] = '8004';
    $_SERVER['HTTP_HOST'] = 'localhost:8004';
    $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
    $_SERVER['SCRIPT_FILENAME'] = __DIR__.'/public/index.php';
    $_SERVER['SCRIPT_NAME'] = '/index.php';
    $_SERVER['PHP_SELF'] = '/index.php';
    
    $app = require 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    );
    
    echo "Response Status: " . $response->status() . "\n";
    echo "Response Headers: " . json_encode($response->headers->all(), JSON_PRETTY_PRINT) . "\n";
    echo "Response Content (first 500 chars): " . substr($response->getContent(), 0, 500) . "\n";
    
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
