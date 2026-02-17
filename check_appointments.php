<?php

$con = new mysqli('localhost', 'root', '', 'doctorly_laravel');

// Contar citas con y sin patient_id
$result = $con->query('SELECT COUNT(*) as con_pid FROM appointments WHERE patient_id IS NOT NULL');
$row = $result->fetch_assoc();
echo "Citas con patient_id: " . $row['con_pid'] . PHP_EOL;

$result2 = $con->query('SELECT COUNT(*) as sin_pid FROM appointments WHERE patient_id IS NULL');
$row2 = $result2->fetch_assoc();
echo "Citas sin patient_id: " . $row2['sin_pid'] . PHP_EOL;

// Mostrar citas para paciente 8271
echo "\n=== Buscando citas para patient_id O appointment_for = 8271 ===" . PHP_EOL;
$result3 = $con->query('SELECT id, appointment_for, patient_id, appointment_date FROM appointments WHERE patient_id = 8271 OR appointment_for = 8271 LIMIT 5');
if ($result3->num_rows > 0) {
    while($r = $result3->fetch_assoc()) {
        echo "ID: " . $r['id'] . ", appointment_for: " . $r['appointment_for'] . ", patient_id: " . $r['patient_id'] . ", date: " . $r['appointment_date'] . PHP_EOL;
    }
} else {
    echo "No se encontraron citas para patient_id = 8271 O appointment_for = 8271" . PHP_EOL;
}

// Buscar usuarios con ID 8271
echo "\n=== Datos de usuario 8271 ===" . PHP_EOL;
$result4 = $con->query('SELECT id, email, first_name, last_name FROM users WHERE id = 8271 LIMIT 1');
if ($result4->num_rows > 0) {
    $u = $result4->fetch_assoc();
    echo "User ID: " . $u['id'] . ", Email: " . $u['email'] . ", Name: " . $u['first_name'] . " " . $u['last_name'] . PHP_EOL;
    
    // Buscar patient con ese email
    echo "\n=== Paciente con ese email ===" . PHP_EOL;
    $result5 = $con->query("SELECT id, email, first_name, last_name FROM patients WHERE email = '" . $u['email'] . "' LIMIT 1");
    if ($result5->num_rows > 0) {
        $p = $result5->fetch_assoc();
        echo "Patient ID: " . $p['id'] . ", Email: " . $p['email'] . ", Name: " . $p['first_name'] . " " . $p['last_name'] . PHP_EOL;
    } else {
        echo "No hay paciente con ese email en la tabla patients" . PHP_EOL;
    }
} else {
    echo "No existe usuario con ID 8271" . PHP_EOL;
}

// Mostrar muestra de citas sin migrar
echo "\n=== Muestra de citas SIN patient_id (primeras 3) ===" . PHP_EOL;
$result6 = $con->query('SELECT a.id, a.appointment_for, a.patient_id, a.appointment_date, u.email FROM appointments a LEFT JOIN users u ON a.appointment_for = u.id WHERE a.patient_id IS NULL LIMIT 3');
if ($result6->num_rows > 0) {
    while($r = $result6->fetch_assoc()) {
        echo "Appointment ID: " . $r['id'] . ", appointment_for (user_id): " . $r['appointment_for'] . ", user_email: " . $r['email'] . ", date: " . $r['appointment_date'] . PHP_EOL;
    }
}

$con->close();

