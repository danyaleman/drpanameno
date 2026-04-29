<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $inserted = DB::statement("
        INSERT INTO archivos (prescription_id, url_file, observaciones, created_at, updated_at)
        SELECT 
            id_consulta, 
            CONCAT('legacy_images/', foto_consulta), 
            observaciones, 
            NOW(), 
            NOW() 
        FROM imagen_consulta 
        WHERE id_consulta IN (SELECT id FROM prescriptions)
    ");
    
    echo "Migration completed successfully.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
