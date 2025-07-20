<?php include("conexion.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <title>➕ Añadir Registro</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h2, h3 {
            text-align: center;
            color: #2c3e50;
        }
        p, form {
            max-width: 600px;
            margin: auto;
            font-size: 1em;
            color: #333;
        }
        input[type="text"], input[type="email"], input[type="datetime-local"], select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            background: #27ae60;
            color: white;
            border: none;
            padding: 10px 20px;
            margin-top: 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background: #219150;
        }
        select {
            background: #fff;
        }
        a {
            text-decoration: none;
            background: #007BFF;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 20px;
        }
        #imagen_yape {
            text-align: center;
            margin-top: 10px;
        }
        #imagen_yape img {
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .form-section {
            background: #fff;
            padding: 20px;
            margin: 20px auto;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            max-width: 650px;
        }
    </style>
</head>
<body>

<a href="Principal.php">⬅️ Volver a la Página Principal</a>

<h2>➕ Añadir Registro de Alquiler</h2>
<div class="form-section">
<form method="POST" action="">
    <label>Ingrese DNI:</label>
    <input type="text" name="dni" required>
    <input type="submit" name="verificar" value="Buscar Usuario">
</form>
</div>

<?php
$tarifa_hora = 5;

if(isset($_POST['verificar'])){
    $dni = $_POST['dni'];
    $busqueda = $conn->prepare("SELECT ID_Usuario, Nombre, Apellido, DNI, Tipo_Usuario FROM Usuarios WHERE DNI=?");
    $busqueda->bind_param("s", $dni);
    $busqueda->execute();
    $busqueda->store_result();

    if($busqueda->num_rows > 0){
        $busqueda->bind_result($id_usuario, $nombre, $apellido, $dni, $tipo_usuario);
        $busqueda->fetch();
        echo "<div class='form-section'><h3>👤 Usuario encontrado: $nombre $apellido</h3>";
        echo "<p><strong>Tipo de usuario:</strong> $tipo_usuario</p>";
    } else {
        echo "<div class='form-section'><h3>🆕 Usuario no registrado. Complete los datos:</h3>";
        echo "<form method='POST' action=''>
            <input type='hidden' name='dni' value='$dni'>
            <label>Nombre:</label>
            <input type='text' name='nombre' required>
            <label>Apellido:</label>
            <input type='text' name='apellido' required>
            <label>Correo:</label>
            <input type='email' name='correo' required>
            <label>Teléfono:</label>
            <input type='text' name='telefono' required>
            <label>Tipo de usuario:</label>
            <select name='tipo_usuario' required>
                <option value='Estudiante'>Estudiante</option>
                <option value='Docente'>Docente</option>
                <option value='Administrativo'>Administrativo</option>
            </select>
            <input type='submit' name='registrar_usuario' value='Registrar Usuario y Continuar'>
        </form></div>";
        exit();
    }

    echo "<h3>🚲 Datos del Alquiler:</h3><div class='form-section'>
    <form method='POST' action=''>
        <input type='hidden' name='id_usuario' value='$id_usuario'>
        <input type='hidden' name='nombre' value='$nombre'>
        <input type='hidden' name='apellido' value='$apellido'>
        <input type='hidden' name='dni' value='$dni'>
        <input type='hidden' name='tarifa_hora' value='$tarifa_hora'>

        <label>Modelo Bicicleta:</label>
        <select name='id_bicicleta'>";
        
    $bicis = $conn->query("SELECT * FROM Bicicletas WHERE Estado='Disponible'");
    while($bici = $bicis->fetch_assoc()){
        echo "<option value='".$bici['ID_Bicicleta']."|".$bici['Modelo']."'>".$bici['Marca']." - ".$bici['Modelo']."</option>";
    }

    echo "</select>
        <label>Fecha Pedido:</label>
        <input type='datetime-local' name='pedido' required>
        <label>Fecha Entrega:</label>
        <input type='datetime-local' name='entrega' required>
        <input type='submit' name='continuar' value='Continuar'>
    </form></div>";
    $busqueda->close();
}

if(isset($_POST['registrar_usuario'])){
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $tipo_usuario = $_POST['tipo_usuario'];

    $stmt = $conn->prepare("INSERT INTO Usuarios (Nombre, Apellido, DNI, Correo, Telefono, Tipo_Usuario) VALUES (?,?,?,?,?, ?)");
    $stmt->bind_param("ssssss", $nombre, $apellido, $dni, $correo, $telefono, $tipo_usuario);
    $stmt->execute();
    $id_usuario = $stmt->insert_id;
    $stmt->close();

    echo "<div class='form-section'><h3>👤 Usuario registrado: $nombre $apellido</h3>
    <p><strong>Tipo de usuario:</strong> $tipo_usuario</p>
    <form method='POST' action=''>
        <input type='hidden' name='id_usuario' value='$id_usuario'>
        <input type='hidden' name='nombre' value='$nombre'>
        <input type='hidden' name='apellido' value='$apellido'>
        <input type='hidden' name='dni' value='$dni'>
        <input type='hidden' name='tarifa_hora' value='$tarifa_hora'>

        <label>Modelo Bicicleta:</label>
        <select name='id_bicicleta'>";
        
    $bicis = $conn->query("SELECT * FROM Bicicletas WHERE Estado='Disponible'");
    while($bici = $bicis->fetch_assoc()){
        echo "<option value='".$bici['ID_Bicicleta']."|".$bici['Modelo']."'>".$bici['Marca']." - ".$bici['Modelo']."</option>";
    }

    echo "</select>
        <label>Fecha Pedido:</label>
        <input type='datetime-local' name='pedido' required>
        <label>Fecha Entrega:</label>
        <input type='datetime-local' name='entrega' required>
        <input type='submit' name='continuar' value='Continuar'>
    </form></div>";
    exit();
}

if(isset($_POST['continuar'])){
    $id_usuario = $_POST['id_usuario'];
    list($id_bicicleta, $modelo) = explode("|", $_POST['id_bicicleta']);
    $pedido = $_POST['pedido'];
    $entrega = $_POST['entrega'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $tarifa_hora = $_POST['tarifa_hora'];

    $inicio = new DateTime($pedido);
    $fin = new DateTime($entrega);
    $intervalo = $inicio->diff($fin);
    $horas = $intervalo->days * 24 + $intervalo->h + ($intervalo->i > 0 ? 1 : 0);

    if($horas <= 0){
        echo "<script>alert('La fecha de entrega debe ser posterior a la de pedido.'); location.href='añadir_registro.php';</script>";
        exit();
    }

    $monto = $horas * $tarifa_hora;

    $pedido_formateado = $inicio->format('Y-m-d H:i');
    $entrega_formateada = $fin->format('Y-m-d H:i');

    echo "<div class='form-section'><h3>📝 Confirmación de Datos:</h3>
    <p><strong>DNI:</strong> $dni</p>
    <p><strong>Usuario:</strong> $nombre $apellido</p>
    <p><strong>ID Bicicleta:</strong> $id_bicicleta</p>
    <p><strong>Modelo:</strong> $modelo</p>
    <p><strong>Fecha Pedido:</strong> $pedido_formateado</p>
    <p><strong>Fecha Entrega:</strong> $entrega_formateada</p>
    <p><strong>Horas calculadas:</strong> $horas</p>
    <p><strong>Monto total:</strong> S/ $monto</p>

    <h3>💰 Datos de Pago:</h3>
    <form method='POST' action=''>
        <input type='hidden' name='id_usuario' value='$id_usuario'>
        <input type='hidden' name='id_bicicleta' value='$id_bicicleta'>
        <input type='hidden' name='pedido' value='$pedido'>
        <input type='hidden' name='entrega' value='$entrega'>
        <input type='hidden' name='tarifa_hora' value='$tarifa_hora'>

        <label>Método de Pago:</label>
        <select name='metodo' id='metodo_pago' onchange='mostrarYape()'>
            <option value='Efectivo'>Efectivo</option>
            <option value='Tarjeta'>Tarjeta</option>
            <option value='Yape'>Yape</option>
        </select><br><br>

        <div id='imagen_yape' style='display:none;'>
            <p><strong>Escanea este código QR de Yape:</strong></p>
            <img src='imagenes/yape_qr.jpg' alt='Código QR de Yape' width='200'>
        </div><br>

        <input type='submit' name='calcular' value='Calcular Monto y Registrar'>
    </form></div>

    <script>
        function mostrarYape() {
            var metodo = document.getElementById('metodo_pago').value;
            var divYape = document.getElementById('imagen_yape');
            divYape.style.display = (metodo === 'Yape') ? 'block' : 'none';
        }
    </script>";
}

if(isset($_POST['calcular'])){
    $id_usuario = $_POST['id_usuario'];
    $id_bicicleta = $_POST['id_bicicleta'];
    $pedido = $_POST['pedido'];
    $entrega = $_POST['entrega'];
    $metodo = $_POST['metodo'];
    $tarifa_hora = $_POST['tarifa_hora'];

    $inicio = new DateTime($pedido);
    $fin = new DateTime($entrega);
    $intervalo = $inicio->diff($fin);
    $horas = $intervalo->days * 24 + $intervalo->h + ($intervalo->i > 0 ? 1 : 0);

    if($horas <= 0){
        echo "<script>alert('La fecha de entrega debe ser posterior a la de pedido.'); location.href='añadir_registro.php';</script>";
        exit();
    }

    $monto = $horas * $tarifa_hora;

    $alq = $conn->prepare("INSERT INTO Alquileres (ID_Bicicleta, ID_Usuario, Fecha_Alquiler, Fecha_Devolucion, Estado) VALUES (?,?,?,?, 'Activo')");
    $alq->bind_param("iiss", $id_bicicleta, $id_usuario, $pedido, $entrega);
    $alq->execute();
    $id_alquiler = $alq->insert_id;
    $alq->close();

    $conn->query("UPDATE Bicicletas SET Estado='Alquilada' WHERE ID_Bicicleta=$id_bicicleta");

    $pago = $conn->prepare("INSERT INTO Pagos (ID_Alquiler, Monto, Fecha_Pago, Metodo) VALUES (?, ?, NOW(), ?)");
    $pago->bind_param("ids", $id_alquiler, $monto, $metodo);
    $pago->execute();
    $pago->close();

    echo "<div class='form-section'><h3>✅ Alquiler registrado.</h3></div>";
}
?>

</body>
</html>

