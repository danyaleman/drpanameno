<?php

/**
 * Script para migrar pacientes faltantes desde las tablas antiguas (persona, paciente)
 * a la nueva tabla (patients) de Doctorly.
 * 
 * Este script es seguro para ejecutarse en producción.
 * Evita colisiones de IDs y asegura que los historiales clínicos se mantengan vinculados.
 */

try {
    // Si estás usando Laravel, podríamos usar PDO directamente con las credenciales del .env
    $host = '127.0.0.1';
    $db   = 'doctorly_laravel'; // Asegúrate de que este nombre sea correcto en tu servidor de producción
    $user = 'root';
    $pass = ''; // Contraseña de tu servidor
    
    // Leer desde el .env si existe
    if (file_exists(__DIR__ . '/.env')) {
        $env = parse_ini_file(__DIR__ . '/.env');
        $host = $env['DB_HOST'] ?? $host;
        $db = $env['DB_DATABASE'] ?? $db;
        $user = $env['DB_USERNAME'] ?? $user;
        $pass = $env['DB_PASSWORD'] ?? $pass;
    }

    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Iniciando migración de pacientes faltantes...\n\n";

    // 1. Subir el AUTO_INCREMENT para evitar que nuevos pacientes pisen IDs antiguos durante el proceso
    $pdo->query("ALTER TABLE patients AUTO_INCREMENT = 15000");
    echo "[✓] AUTO_INCREMENT ajustado a 15000 para prevenir colisiones con nuevos registros.\n";

    // 2. Buscar pacientes que están en las tablas antiguas pero NO en la nueva
    $sql = "SELECT pa.*, pe.nombres, pe.apellidos, pe.direccion, pe.telefono, pe.telefono2, pe.email 
            FROM paciente pa
            JOIN persona pe ON pa.persona = pe.id
            LEFT JOIN patients pt ON pa.id = pt.id
            WHERE pt.id IS NULL";
            
    $stmt = $pdo->query($sql);
    $missing_patients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "[!] Se encontraron " . count($missing_patients) . " pacientes faltantes.\n\n";

    $count_inserted = 0;
    $count_collided = 0;

    $insert_stmt = $pdo->prepare("INSERT INTO patients (
        id, first_name, last_name, gender, address, is_deleted, created_at, updated_at, 
        phone_primary, phone_secondary, email, dui, marital_status, occupation, workplace, 
        birth_date, referred_by, emergency_contact_name, emergency_contact_phone, 
        pathological_history, non_pathological_history, medications_allergies
    ) VALUES (?, ?, ?, ?, ?, 0, NOW(), NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $insert_no_id_stmt = $pdo->prepare("INSERT INTO patients (
        first_name, last_name, gender, address, is_deleted, created_at, updated_at, 
        phone_primary, phone_secondary, email, dui, marital_status, occupation, workplace, 
        birth_date, referred_by, emergency_contact_name, emergency_contact_phone, 
        pathological_history, non_pathological_history, medications_allergies
    ) VALUES (?, ?, ?, ?, 0, NOW(), NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    foreach ($missing_patients as $p) {
        $old_id = $p['id'];
        
        // Limpiar nombre si es necesario
        $first_name = trim(preg_replace('/\s+/', ' ', (string) $p['nombres']));
        $last_name = trim(preg_replace('/\s+/', ' ', (string) $p['apellidos']));
        $gender = ($p['sexo'] == 'F') ? 'Female' : 'Male';
        $email = $p['email'] ? $p['email'] : 'paciente_' . $old_id . '_' . time() . '@example.com';
        
        // Revisar si el ID original ya está ocupado en la tabla patients (ej. un paciente nuevo se registró hoy y tomó ese ID)
        $check = $pdo->query("SELECT id FROM patients WHERE id = $old_id")->fetch();
        
        if ($check) {
            // COLISIÓN: El ID ya fue tomado por un paciente nuevo.
            // Insertamos al paciente antiguo con un ID nuevo (auto increment, que será 15000+)
            $insert_no_id_stmt->execute([
                $first_name, $last_name, $gender, $p['direccion'],
                $p['telefono'], $p['telefono2'], $email, $p['dui'],
                $p['estado_civil'], $p['ocupacion'], $p['lugar_trabajo'],
                $p['fecha_nacimiento'], $p['referido_por'], $p['persona_emergencia'],
                $p['telefono_emergencia'], $p['patologicos'], $p['no_patologicos'],
                $p['medicamentos_alergias']
            ]);
            
            $new_id = $pdo->lastInsertId();
            $count_collided++;
            
            // MUY IMPORTANTE: Actualizar las referencias antiguas al nuevo ID para no perder su historial!
            $pdo->query("UPDATE consulta SET paciente = $new_id WHERE paciente = $old_id");
            $pdo->query("UPDATE cita SET paciente = $new_id WHERE paciente = $old_id");
            // Puedes agregar más tablas aquí si el sistema antiguo usaba 'paciente' como llave foránea en otras tablas.
            
            echo "  -> Paciente migrado (Colisión de ID): $first_name $last_name (Viejo ID: $old_id -> Nuevo ID: $new_id)\n";
        } else {
            // SIN COLISIÓN: Insertamos con su ID original
            $insert_stmt->execute([
                $old_id,
                $first_name, $last_name, $gender, $p['direccion'],
                $p['telefono'], $p['telefono2'], $email, $p['dui'],
                $p['estado_civil'], $p['ocupacion'], $p['lugar_trabajo'],
                $p['fecha_nacimiento'], $p['referido_por'], $p['persona_emergencia'],
                $p['telefono_emergencia'], $p['patologicos'], $p['no_patologicos'],
                $p['medicamentos_alergias']
            ]);
            
            $count_inserted++;
            echo "  -> Paciente migrado (Mismo ID): $first_name $last_name (ID: $old_id)\n";
        }
    }

    echo "\n=========================================\n";
    echo "Resumen de Migración:\n";
    echo "Pacientes insertados con su ID original: $count_inserted\n";
    echo "Pacientes reasignados a un nuevo ID (por colisión): $count_collided\n";
    echo "=========================================\n";
    echo "¡Proceso finalizado exitosamente!\n";

} catch (Exception $e) {
    echo "\n[ERROR CRÍTICO] " . $e->getMessage() . "\n";
}
