<?php
$connect = mysqli_connect('localhost', 'usb', 'usb2022', 'formulatio');

$email = isset($_POST['email']) ? $_POST['email'] : '';
$message = isset($_POST['message']) ? $_POST['message'] : '';
$cliente = isset($_POST['cliente']) ? $_POST['cliente'] : '';
$departamento = isset($_POST['department']) ? $_POST['department'] : '';

$email_error = '';
$message_error = '';

$atencionCliente = ['Empleado1', 'Empleado2', 'Empleado3', 'Empleado4'];
$soporteTecnico = ['EmpleadoA', 'EmpleadoB', 'EmpleadoC', 'EmpleadoD'];
$facturacion = ['EmpleadoX', 'EmpleadoY', 'EmpleadoZ', 'EmpleadoW'];

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
    <h1>PHP Contact Form</h1>
    <form method="post" action="">
        Email Address:<br>
        <input type="text" name="email" value="<?php echo $email; ?>">
        <?php echo $email_error; ?><br><br>
        Message:<br>
        <textarea name="message"><?php echo $message; ?></textarea>
        <?php echo $message_error; ?><br><br>
        Name of Client:<br>
        <input type="text" name="cliente" value="<?php echo $cliente; ?>"><br><br>
        Department:<br>
        <select name="department">
            <option value="atencion_cliente">Atención al Cliente</option>
            <option value="soporte_tecnico">Soporte Técnico</option>
            <option value="facturacion">Facturación</option>
        </select><br><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>
