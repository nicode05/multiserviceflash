<?php
// Mostrar errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Conexión
$conexion = new mysqli("localhost", "root", "", "registro_datos");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Verificar que el método sea POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'] ?? '';
    $placa = $_POST['placa'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $email = $_POST['email'] ?? '';
    $mensaje = $_POST['mensaje'] ?? '';

    $fecha = date("Y-m-d");
    $hora = date("H:i:s");

    // Insertar en cliente
    $stmt_cliente = $conexion->prepare("INSERT INTO cliente (nombre, placa, telefono, email, fecha_envio, hora_envio) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt_cliente) {
        die("Error al preparar stmt_cliente: " . $conexion->error);
    }

    $stmt_cliente->bind_param("ssssss", $nombre, $placa, $telefono, $email, $fecha, $hora);
    if (!$stmt_cliente->execute()) {
        die("Error al insertar cliente: " . $stmt_cliente->error);
    }

    $id_cliente = $conexion->insert_id;

    // Insertar mensaje
    $stmt_mensaje = $conexion->prepare("INSERT INTO mensaje (id_cliente, mensaje, fecha_envio, hora_envio) VALUES (?, ?, ?, ?)");
    if (!$stmt_mensaje) {
        die("Error al preparar stmt_mensaje: " . $conexion->error);
    }

    $stmt_mensaje->bind_param("isss", $id_cliente, $mensaje, $fecha, $hora);
    if (!$stmt_mensaje->execute()) {
        die("Error al insertar mensaje: " . $stmt_mensaje->error);
    }

    echo "✅ Datos guardados correctamente.";
} else {
    echo "❌ Método no permitido.";
}
?>
