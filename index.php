<?php

$connect = mysqli_connect('localhost', 'usb', 'usb2022', 'formulatio');

$email = isset($_POST['email']) ? $_POST['email'] : '';
$message = isset($_POST['message']) ? $_POST['message'] : '';

$email_error = '';
$message_error = '';

// Crear tres arreglos con nombres de empleados para cada departamento
$atencionCliente = ['Empleado1', 'Empleado2', 'Empleado3', 'Empleado4'];
$soporteTecnico = ['EmpleadoA', 'EmpleadoB', 'EmpleadoC', 'EmpleadoD'];
$facturacion = ['EmpleadoX', 'EmpleadoY', 'EmpleadoZ', 'EmpleadoW'];

if (count($_POST)) {
    $errors = 0;

    if ($_POST['email'] == '') {
        $email_error = 'Please enter an email address';
        $errors++;
    }

    if ($_POST['message'] == '') {
        $message_error = 'Please enter a message';
        $errors++;
    }

    if ($errors == 0) {
        // Obtener un nombre aleatorio de acuerdo al departamento seleccionado
        $department = $_POST['department'];
        $randomName = '';

        switch ($department) {
            case 'atencion_cliente':
                $randomName = $atencionCliente[array_rand($atencionCliente)];
                break;
            case 'soporte_tecnico':
                $randomName = $soporteTecnico[array_rand($soporteTecnico)];
                break;
            case 'facturacion':
                $randomName = $facturacion[array_rand($facturacion)];
                break;
        }

        $query = 'INSERT INTO contact (
                email,
                message,
                department
            ) VALUES (
                "' . addslashes($_POST['email']) . '",
                "' . addslashes($_POST['message']) . '",
                "' . $randomName . '"
            )';
        mysqli_query($connect, $query);

        $message = 'You have received a contact form submission:
            
Email: ' . $_POST['email'] . '
Message: ' . $_POST['message'] . '
Department: ' . $randomName;

        mail('poveda.geovanny@hotmail.com',
            'Contact Form Submission',
            $message);

        header('Location: thankyou.html');
        die();
    }
}

?>
<!doctype html>
<html>

<head>
    <title>PHP Contact Form</title>
</head>

<body>

    <h1>PHP Contact Form</h1>

    <form method="post" action="">

        Email Address:
        <br>
        <input type="text" name="email" value="<?php echo $email; ?>">
        <?php echo $email_error; ?>

        <br><br>

        Message:
        <br>
        <textarea name="message"><?php echo $message; ?></textarea>
        <?php echo $message_error; ?>

        <br><br>

        <!-- Agregar un campo para seleccionar el departamento -->
        Department:
        <br>
        <select name="department">
            <option value="atencion_cliente">Atención al Cliente</option>
            <option value="soporte_tecnico">Soporte Técnico</option>
            <option value="facturacion">Facturación</option>
        </select>

        <br><br>

        <input type="submit" value="Submit">

    </form>

</body>

</html>
