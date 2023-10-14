<?php
$connect = mysqli_connect('localhost', 'usb', 'usb2022', 'formulatio');

$email = isset($_POST['email']) ? $_POST['email'] : '';
$message = isset($_POST['message']) ? $_POST['message'] : '';
$cliente = isset($_POST['cliente']) ? $_POST['cliente'] : '';
$departamento = isset($_POST['department']) ? $_POST['department'] : '';

$email_error = '';
$message_error = '';

if (count($_POST)) {
    $errors = 0;

    if ($email == '') {
        $email_error = 'Please enter an email address';
        $errors++;
    }

    if ($message == '') {
        $message_error = 'Please enter a message';
        $errors++;
    }

    if ($errors == 0) {
        // Consumir el archivo JSON desde la URL
        $url = 'https://shorturl.at/mwyQV';
        $json_data = file_get_contents($url);

        if ($json_data === false) {
            die('Error al obtener datos JSON desde el servicio web.');
        }

        // Decodificar la respuesta JSON
        $data = json_decode($json_data, true);

        if ($data === null) {
            die('Error al decodificar el JSON.');
        }

        // Ahora, puedes acceder a los datos JSON y procesarlos según tus necesidades
        $empleadoSeleccionado = $data[$departamento][array_rand($data[$departamento])];

        // Resto del código
        $query = 'INSERT INTO contact (
            email,
            message,
            department,
            employee_name,
            cliente
        ) VALUES (
            "' . addslashes($email) . '",
            "' . addslashes($message) . '",
            "' . $departamento . '",
            "' . $empleadoSeleccionado . '",
            "' . $cliente . '"
        )';
        mysqli_query($connect, $query);

        $mensajeCorreo = 'You have received a contact form submission:

Email: ' . $email . '
Message: ' . $message . '
Department: ' . $departamento . '
Employee Name: ' . $empleadoSeleccionado . '
Cliente: ' . $cliente;

        mail('poveda.geovanny@hotmail.com', 'Contact Form Submission', $mensajeCorreo);

        header('Location: thankyou.html');
        die();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>PHP Contact Form</title>
</head>
<body>
    <!-- Resto del formulario -->
</body>
</html>

