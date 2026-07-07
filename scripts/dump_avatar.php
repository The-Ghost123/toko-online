<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$status = $kernel->bootstrap();

use App\Models\User;
$user = User::where('email', 'admin@fashionstore.com')->first();
if ($user) {
    echo $user->avatar . PHP_EOL;
} else {
    echo "User not found\n";
}
