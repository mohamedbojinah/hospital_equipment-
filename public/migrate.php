<?php

use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

try {
    $kernel->call('migrate:fresh', [
        '--seed' => true,
        '--force' => true
    ]);
    echo "<h1>Database migration and seeding completed successfully!</h1>";
} catch (\Exception $e) {
    echo "<h1>Error:</h1>";
    echo "<pre>" . $e->getMessage() . "</pre>";
}
