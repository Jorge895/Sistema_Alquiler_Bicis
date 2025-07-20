<?php include("conexion.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Añadir Registro</title>
</head>
<body>

<br><br>
<a href="Principal.php" style="text-decoration:none; background:#007BFF; color:white; padding:10px 20px; border-radius:5px;">⬅️ Volver a la Página Principal</a>

<h2>➕ Añadir Registro de Alquiler</h2>

<form method="POST" action="">
    Ingrese DNI: <input type="text" name="dni" required>
    <input type="submit" name="verificar" value="Buscar Usuario">
</form>

<?php
$tarifa_hora = 5; // Tarifa por hora

if(isset($_POST['verificar'])){
    $dni = $_POST['dni'];
    $busqueda = $conn->prepare("SELECT ID_Usuario, Nombre, Apellido, DNI, Tipo_Usuario FROM Usuarios WHERE DNI=?");
    $busqueda->bind_param("s", $dni);
    $busqueda->execute();
    $busqueda->store_result();

    if($busqueda->num_rows > 0){
        $busqueda->bind_result($id_usuario, $nombre, $apellido, $dni, $tipo_usuario);
        $busqueda->fetch();
        echo "<h3>👤 Usuario encontrado: $nombre $apellido</h3>";
        echo "<p><strong>Tipo de usuario:</strong> $tipo_usuario</p>";
    } else {
        echo "<h3>🆕 Usuario no registrado. Complete los datos:</h3>";
        echo "<form method='POST' action=''>
            <input type='hidden' name='dni' value='$dni'>
            Nombre: <input type='text' name='nombre' required><br>
            Apellido: <input type='text' name='apellido' required><br>
            Correo: <input type='email' name='correo' required><br>
            Teléfono: <input type='text' name='telefono' required><br>
            Tipo de usuario:
            <select name='tipo_usuario' required>
                <option value='Estudiante'>Estudiante</option>
                <option value='Docente'>Docente</option>
                <option value='Administrativo'>Administrativo</option>
            </select><br><br>
            <input type='submit' name='registrar_usuario' value='Registrar Usuario y Continuar'>
        </form>";
        exit();
    }

    echo "<h3>🚲 Datos del Alquiler:</h3>
    <form method='POST' action=''>
        <input type='hidden' name='id_usuario' value='$id_usuario'>
        <input type='hidden' name='nombre' value='$nombre'>
        <input type='hidden' name='apellido' value='$apellido'>
        <input type='hidden' name='dni' value='$dni'>
        <input type='hidden' name='tarifa_hora' value='$tarifa_hora'>

        Modelo Bicicleta:
        <select name='id_bicicleta'>";
        
    $bicis = $conn->query("SELECT * FROM Bicicletas WHERE Estado='Disponible'");
    while($bici = $bicis->fetch_assoc()){
        echo "<option value='".$bici['ID_Bicicleta']."|".$bici['Modelo']."'>".$bici['Marca']." - ".$bici['Modelo']."</option>";
    }

    echo "</select><br>
        Fecha Pedido: <input type='datetime-local' name='pedido' required><br>
        Fecha Entrega: <input type='datetime-local' name='entrega' required><br><br>
        <input type='submit' name='continuar' value='Continuar'>
    </form>";
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

    // Mostrar directamente el formulario de alquiler
    echo "<h3>👤 Usuario registrado: $nombre $apellido</h3>
    <p><strong>Tipo de usuario:</strong> $tipo_usuario</p>
    <form method='POST' action=''>
        <input type='hidden' name='id_usuario' value='$id_usuario'>
        <input type='hidden' name='nombre' value='$nombre'>
        <input type='hidden' name='apellido' value='$apellido'>
        <input type='hidden' name='dni' value='$dni'>
        <input type='hidden' name='tarifa_hora' value='$tarifa_hora'>

        Modelo Bicicleta:
        <select name='id_bicicleta'>";
        
    $bicis = $conn->query("SELECT * FROM Bicicletas WHERE Estado='Disponible'");
    while($bici = $bicis->fetch_assoc()){
        echo "<option value='".$bici['ID_Bicicleta']."|".$bici['Modelo']."'>".$bici['Marca']." - ".$bici['Modelo']."</option>";
    }

    echo "</select><br>
        Fecha Pedido: <input type='datetime-local' name='pedido' required><br>
        Fecha Entrega: <input type='datetime-local' name='entrega' required><br><br>
        <input type='submit' name='continuar' value='Continuar'>
    </form>";
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

    echo "<h3>📝 Confirmación de Datos:</h3>";
    echo "<p><strong>DNI:</strong> $dni</p>";
    echo "<p><strong>Usuario:</strong> $nombre $apellido</p>";
    echo "<p><strong>ID Bicicleta:</strong> $id_bicicleta</p>";
    echo "<p><strong>Modelo:</strong> $modelo</p>";
    echo "<p><strong>Fecha Pedido:</strong> $pedido_formateado</p>";
    echo "<p><strong>Fecha Entrega:</strong> $entrega_formateada</p>";
    echo "<p><strong>Horas calculadas:</strong> $horas</p>";
    echo "<p><strong>Monto total:</strong> S/ $monto</p>";

    echo "<h3>💰 Datos de Pago:</h3>
    <form method='POST' action=''>
        <input type='hidden' name='id_usuario' value='$id_usuario'>
        <input type='hidden' name='id_bicicleta' value='$id_bicicleta'>
        <input type='hidden' name='pedido' value='$pedido'>
        <input type='hidden' name='entrega' value='$entrega'>
        <input type='hidden' name='tarifa_hora' value='$tarifa_hora'>

        Método de Pago: 
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
    </form>

    <script>
        function mostrarYape() {
            var metodo = document.getElementById('metodo_pago').value;
            var divYape = document.getElementById('imagen_yape');
            if(metodo === 'Yape') {
                divYape.style.display = 'block';
            } else {
                divYape.style.display = 'none';
            }
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

    echo "<h3>✅ Alquiler registrado.</h3>";
}
?>

</body>
</html>
