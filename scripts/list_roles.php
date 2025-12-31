<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
foreach (App\Models\Rol::all() as $r) {
    echo $r->id_rol . ' | ' . $r->nombre . ' => ' . json_encode($r->menu_permissions) . PHP_EOL;
}
