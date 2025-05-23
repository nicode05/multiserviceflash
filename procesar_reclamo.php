<?php
// ====================================================================================================
// ARCHIVO: procesar_reclamo.php
// DESCRIPCIÓN: Script PHP para procesar el formulario de libro de reclamaciones,
//              conectarse a la base de datos MySQL y guardar los datos.
// ====================================================================================================

// --- CONFIGURACIÓN DE ERRORES PARA DEPURACIÓN ---
error_reporting(E_ALL); // Muestra todos los errores de PHP
ini_set('display_errors', '1'); // Asegura que los errores se muestren en el navegador

// --- CONFIGURACIÓN DE LA BASE DE DATOS ---
$servername = "localhost"; // La dirección del servidor de la base de datos. En XAMPP, suele ser 'localhost'.
$username = "root";        // El usuario por defecto de MySQL en XAMPP.
$password = "";            // La contraseña por defecto de MySQL en XAMPP (suele estar vacía).
$dbname = "libro_reclamaciones_db"; // El nombre de la base de datos que creaste con el script SQL.

// --- CONEXIÓN A LA BASE DE DATOS ---
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// --- PROCESAMIENTO DE DATOS DEL FORMULARIO (solo si se envió por POST) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Sanitizar y validar los datos del formulario
    // Ahora 'isset' verificará si el campo fue enviado.
    // 'htmlspecialchars' previene XSS. 'real_escape_string' previene SQL Injection.
    $nombres = isset($_POST['nombres']) ? $conn->real_escape_string(htmlspecialchars($_POST['nombres'])) : '';
    $apellido_paterno = isset($_POST['apellidoPaterno']) ? $conn->real_escape_string(htmlspecialchars($_POST['apellidoPaterno'])) : '';
    $apellido_materno = isset($_POST['apellidoMaterno']) ? $conn->real_escape_string(htmlspecialchars($_POST['apellidoMaterno'])) : '';
    $docIdentidad_str = isset($_POST['docIdentidad']) ? $conn->real_escape_string(htmlspecialchars($_POST['docIdentidad'])) : '';
    $numDocumento = isset($_POST['numDocumento']) ? $conn->real_escape_string(htmlspecialchars($_POST['numDocumento'])) : '';
    $telefono = isset($_POST['telefono']) ? $conn->real_escape_string(htmlspecialchars($_POST['telefono'])) : '';
    $email = ''; // Mantengo esto vacío si no tienes el campo 'email' en HTML. Si lo añades, descomenta la línea de abajo
    // $email = isset($_POST['email']) ? $conn->real_escape_string(htmlspecialchars($_POST['email'])) : '';

    $tipoReclamoQueja_str = isset($_POST['tipoReclamoQueja']) ? $conn->real_escape_string(htmlspecialchars($_POST['tipoReclamoQueja'])) : '';
    $numPedido = isset($_POST['numPedido']) && $_POST['numPedido'] != '' ? $conn->real_escape_string(htmlspecialchars($_POST['numPedido'])) : NULL;
    $tipoComprobante_str = isset($_POST['tipoComprobante']) ? $conn->real_escape_string(htmlspecialchars($_POST['tipoComprobante'])) : NULL;
    $fechaCompra = isset($_POST['fechaCompra']) && $_POST['fechaCompra'] != '' ? $conn->real_escape_string($_POST['fechaCompra']) : NULL;
    $montoReclamado = isset($_POST['montoReclamado']) && $_POST['montoReclamado'] != '' ? $conn->real_escape_string($_POST['montoReclamado']) : NULL;
    $bienContratado_str = isset($_POST['bienContratado']) ? $conn->real_escape_string(htmlspecialchars($_POST['bienContratado'])) : '';
    $detalleReclamo = isset($_POST['detalleReclamo']) ? $conn->real_escape_string(htmlspecialchars($_POST['detalleReclamo'])) : '';
    $solicitud = isset($_POST['solicitud']) ? $conn->real_escape_string(htmlspecialchars($_POST['solicitud'])) : '';
    $acepta_terminos = isset($_POST['aceptaTerminos']) ? 1 : 0; // Checkbox, si está marcado, su valor está en $_POST

    // --- BLOQUE DE DEPURACIÓN DE VALORES RECIBIDOS ---
    // Puedes descomentar estas líneas temporalmente para ver qué valores está recibiendo el PHP
    /*
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    echo "Nombres: " . $nombres . "<br>";
    echo "Doc Identidad String: " . $docIdentidad_str . "<br>";
    echo "Tipo Reclamo/Queja String: " . $tipoReclamoQueja_str . "<br>";
    echo "Bien Contratado String: " . $bienContratado_str . "<br>";
    // exit(); // Descomenta esta línea para detener el script aquí y solo ver los valores
    */
    // --------------------------------------------------

    // 2. Obtener IDs de las tablas de catálogo
    // Para 'id_tipo_documento'
    $id_tipo_documento = 0; // Inicializar a 0 por si no se encuentra
    if ($docIdentidad_str) {
        $stmt = $conn->prepare("SELECT id_tipo_documento FROM tipos_documento WHERE nombre_tipo = ?");
        // DEPURACIÓN: Verifica si la preparación de la consulta falla
        if (false === $stmt) {
            die("Error en la preparación de la consulta de tipos_documento: " . $conn->error);
        }
        $stmt->bind_param("s", $docIdentidad_str);
        $stmt->execute();
        $stmt->bind_result($id_tipo_documento_res);
        $stmt->fetch();
        $stmt->close();
        if ($id_tipo_documento_res) {
            $id_tipo_documento = $id_tipo_documento_res;
        }
    }

    // Para 'id_tipo_reclamacion'
    $id_tipo_reclamacion = 0; // Inicializar a 0 por si no se encuentra
    if ($tipoReclamoQueja_str) {
        $stmt = $conn->prepare("SELECT id_tipo_reclamacion FROM tipos_reclamacion WHERE nombre_tipo = ?");
        if (false === $stmt) { die("Error en la preparación de la consulta de tipos_reclamacion: " . $conn->error); }
        $stmt->bind_param("s", $tipoReclamoQueja_str);
        $stmt->execute();
        $stmt->bind_result($id_tipo_reclamacion_res);
        $stmt->fetch();
        $stmt->close();
        if ($id_tipo_reclamacion_res) {
            $id_tipo_reclamacion = $id_tipo_reclamacion_res;
        }
    }

    // Para 'id_tipo_comprobante'
    $id_tipo_comprobante = NULL; // Inicializar a NULL si no se selecciona o no se encuentra
    if ($tipoComprobante_str) {
        $stmt = $conn->prepare("SELECT id_tipo_comprobante FROM tipos_comprobante WHERE nombre_tipo = ?");
        if (false === $stmt) { die("Error en la preparación de la consulta de tipos_comprobante: " . $conn->error); }
        $stmt->bind_param("s", $tipoComprobante_str);
        $stmt->execute();
        $stmt->bind_result($id_tipo_comprobante_res);
        $stmt->fetch();
        $stmt->close();
        if ($id_tipo_comprobante_res) {
            $id_tipo_comprobante = $id_tipo_comprobante_res;
        }
    }

    // Para 'id_tipo_bien_contratado'
    $id_tipo_bien_contratado = 0; // Inicializar a 0 por si no se encuentra
    if ($bienContratado_str) {
        $stmt = $conn->prepare("SELECT id_tipo_bien_contratado FROM tipos_bien_contratado WHERE nombre_tipo = ?");
        if (false === $stmt) { die("Error en la preparación de la consulta de tipos_bien_contratado: " . $conn->error); }
        $stmt->bind_param("s", $bienContratado_str);
        $stmt->execute();
        $stmt->bind_result($id_tipo_bien_contratado_res);
        $stmt->fetch();
        $stmt->close();
        if ($id_tipo_bien_contratado_res) {
            $id_tipo_bien_contratado = $id_tipo_bien_contratado_res;
        }
    }


    // --- DEPURACIÓN DE IDs ENCONTRADOS ---
    /*
    echo "ID Doc: " . $id_tipo_documento . "<br>";
    echo "ID Reclamacion: " . $id_tipo_reclamacion . "<br>";
    echo "ID Comprobante: " . ($id_tipo_comprobante ?? 'NULL') . "<br>";
    echo "ID Bien Contratado: " . $id_tipo_bien_contratado . "<br>";
    // exit(); // Descomenta esta línea para detener el script aquí y solo ver los IDs
    */
    // ------------------------------------

    // 3. Insertar o encontrar el cliente
    $id_cliente = 0;
    // Búsqueda de cliente existente por tipo y número de documento
    $stmt = $conn->prepare("SELECT id_cliente FROM clientes WHERE id_tipo_documento = ? AND numero_documento = ?");
    if (false === $stmt) { die("Error en la preparación de la consulta de cliente: " . $conn->error); }
    $stmt->bind_param("is", $id_tipo_documento, $numDocumento);
    $stmt->execute();
    $stmt->bind_result($id_cliente_res);
    $stmt->fetch();
    $stmt->close();

    if ($id_cliente_res) {
        $id_cliente = $id_cliente_res; // Cliente existente
    } else {
        // Cliente no encontrado, insertar nuevo
        $stmt = $conn->prepare("INSERT INTO clientes (nombres, apellido_paterno, apellido_materno, id_tipo_documento, numero_documento, telefono, email) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if (false === $stmt) { die("Error en la preparación de la inserción de cliente: " . $conn->error); }
        $stmt->bind_param("sssisis", $nombres, $apellido_paterno, $apellido_materno, $id_tipo_documento, $numDocumento, $telefono, $email);

        if ($stmt->execute()) {
            $id_cliente = $conn->insert_id; // ID del nuevo cliente
        } else {
            // Este es el 'die' que se activa si la inserción de cliente falla
            die("Error al insertar cliente: " . $stmt->error);
        }
        $stmt->close();
    }

    // 4. Insertar el reclamo/queja
    $stmt = $conn->prepare("INSERT INTO reclamos_quejas (id_cliente, id_tipo_reclamacion, numero_pedido, id_tipo_comprobante, fecha_compra, monto_reclamado, id_tipo_bien_contratado, detalle_reclamo, solicitud, acepta_terminos) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (false === $stmt) { die("Error en la preparación de la inserción de reclamo/queja: " . $conn->error); }

    // El 'd' en "iissssdss" es para double (monto_reclamado)
    $stmt->bind_param("iissssdssi",
        $id_cliente,
        $id_tipo_reclamacion,
        $numPedido,
        $id_tipo_comprobante,
        $fechaCompra,
        $montoReclamado,
        $id_tipo_bien_contratado,
        $detalleReclamo,
        $solicitud,
        $acepta_terminos
    );

    if ($stmt->execute()) {
        // Redirección o mensaje de éxito
        echo "<!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Reclamo/Queja Enviado</title>
            <style>
                body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
                .success-message { color: green; font-size: 1.2em; }
                .button-container { margin-top: 20px; }
                .button-container a {
                    display: inline-block;
                    padding: 10px 20px;
                    background-color: #007bff;
                    color: white;
                    text-decoration: none;
                    border-radius: 5px;
                }
            </style>
        </head>
        <body>
            <div class='success-message'>
                <h2>¡Reclamo/Queja enviado con éxito!</h2>
                <p>Su información ha sido recibida y será procesada.</p>
                <p>ID de Reclamo/Queja: " . $conn->insert_id . "</p>
            </div>
            <div class='button-container'>
                <a href='libro-reclamaciones.html'>Volver al formulario</a>
            </div>
        </body>
        </html>";
    } else {
        // Mensaje de error
        echo "<!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Error al Enviar</title>
            <style>
                body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
                .error-message { color: red; font-size: 1.2em; }
                .button-container { margin-top: 20px; }
                .button-container a {
                    display: inline-block;
                    padding: 10px 20px;
                    background-color: #dc3545;
                    color: white;
                    text-decoration: none;
                    border-radius: 5px;
                }
            </style>
        </head>
        <body>
            <div class='error-message'>
                <h2>Error al enviar el Reclamo/Queja.</h2>
                <p>Por favor, intente nuevamente.</p>
                <p>Detalle del error: " . $stmt->error . "</p>
            </div>
            <div class='button-container'>
                <a href='libro-reclamaciones.html'>Volver al formulario</a>
            </div>
        </body>
        </html>";
    }

    $stmt->close();
} else {
    // Acceso directo al script sin POST
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Acceso no permitido</title>
        <style>
            body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
            .info-message { color: blue; font-size: 1.2em; }
            .button-container { margin-top: 20px; }
            .button-container a {
                display: inline-block;
                padding: 10px 20px;
                background-color: #6c757d;
                color: white;
                text-decoration: none;
                border-radius: 5px;
            }
        </style>
    </head>
    <body>
        <div class='info-message'>
            <h2>Acceso no permitido.</h2>
            <p>Este archivo solo procesa datos enviados desde el formulario.</p>
        </div>
        <div class='button-container'>
            <a href='libro-reclamaciones.html'>Ir al formulario</a>
        </div>
    </body>
    </html>";
}

$conn->close();
?>