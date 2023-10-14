<?php
header('Content-Type: application/json');

// Función para limpiar y validar una cadena
function cleanAndValidate($value) {
    $value = trim($value);
    $value = filter_var($value, FILTER_SANITIZE_STRING);
    return $value;
}

// Conexión a la base de datos
$connect = new mysqli('localhost', 'usb', 'usb2022', 'formulatio');
if ($connect->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = cleanAndValidate($_POST['email']);
    $message = cleanAndValidate($_POST['message']);
    $cliente = cleanAndValidate($_POST['cliente']);
    $departamento = cleanAndValidate($_POST['department']);

    if (empty($email) || empty($message)) {
        $errors = [];
        if (empty($email)) {
            $errors['email'] = 'Por favor ingrese una dirección de correo válida.';
        }
        if (empty($message)) {
            $errors['message'] = 'Por favor ingrese un mensaje.';
        }
        echo json_encode(['success' => false, 'errors' => $errors]);
    } else {
        $atencionCliente = ['Empleado1', 'Empleado2', 'Empleado3', 'Empleado4'];
        $soporteTecnico = ['EmpleadoA', 'EmpleadoB', 'EmpleadoC', 'EmpleadoD'];
        $facturacion = ['EmpleadoX', 'EmpleadoY', 'EmpleadoZ', 'EmpleadoW'];

        $empleadoSeleccionado = '';
        switch ($departamento) {
            case 'atencion_cliente':
                $empleadoSeleccionado = $atencionCliente[array_rand($atencionCliente)];
                break;
            case 'soporte_tecnico':
                $empleadoSeleccionado = $soporteTecnico[array_rand($soporteTecnico)];
                break;
            case 'facturacion':
                $empleadoSeleccionado = $facturacion[array_rand($facturacion)];
                break;
        }

        $json_url = 'https://gitlab.com/usb-web-programming-autumn-2023/php/rest-read-json-v2';
        $json_data = file_get_contents($json_url);

        if ($json_data === false) {
            echo json_encode(['success' => false, 'message' => 'Error al obtener datos JSON del servicio web']);
            exit;
        }

        $json_data = json_decode($json_data, true);

        if ($json_data === null) {
            echo json_encode(['success' => false, 'message' => 'Error al decodificar el JSON']);
            exit;
        }

        // Aquí puedes acceder y procesar los datos JSON según tus necesidades
        // Por ejemplo, si el JSON contiene un campo "empleados", podrías seleccionar un empleado de allí

        $query = 'INSERT INTO contact (email, message, department, employee_name, cliente) VALUES (?, ?, ?, ?, ?)';
        $stmt = $connect->prepare($query);
        $stmt->bind_param("sssss", $email, $message, $departamento, $empleadoSeleccionado, $cliente);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Formulario enviado con éxito']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al enviar el formulario']);
        }

        $stmt->close();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no válido']);
}

$connect->close();
