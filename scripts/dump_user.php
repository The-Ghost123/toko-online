<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$status = $kernel->bootstrap();

use App\Models\User;
$user = User::where('email', 'pembeli1@gmail.com')->first();
if ($user) {
    echo "id: {$user->id}\n";
    echo "name: {$user->name}\n";
    echo "email: {$user->email}\n";
    echo "avatar: {$user->avatar}\n";
    echo "updated_at: {$user->updated_at}\n";
} else {
    echo "User not found\n";
}
