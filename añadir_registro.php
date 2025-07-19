<?php include("conexion.php"); ?>

<!DOCTYPE html>
<html>
<head><title>Añadir Registro</title></head>
<body>

<h2>➕ Añadir Registro de Alquiler</h2>

<form method="POST" action="">
    Ingrese DNI: <input type="text" name="dni" required>
    <input type="submit" name="verificar" value="Buscar Usuario">
</form>

<?php
$tarifa_hora = 5; // Tarifa por hora

if(isset($_POST['verificar'])){

    $dni = $_POST['dni'];
    $busqueda = $conn->prepare("SELECT ID_Usuario, Nombre, Apellido FROM Usuarios WHERE DNI=?");
    $busqueda->bind_param("s", $dni);
    $busqueda->execute();
    $busqueda->store_result();

    if($busqueda->num_rows > 0){
        // Usuario encontrado
        $busqueda->bind_result($id_usuario, $nombre, $apellido);
        $busqueda->fetch();

        echo "<h3>👤 Usuario encontrado: $nombre $apellido</h3>";

    } else {
        // Nuevo usuario
        echo "<h3>🆕 Usuario no registrado. Complete los datos:</h3>";
        echo "<form method='POST' action=''>
            <input type='hidden' name='dni' value='$dni'>
            Nombre: <input type='text' name='nombre' required><br>
            Apellido: <input type='text' name='apellido' required><br>
            Correo: <input type='email' name='correo' required><br>
            Teléfono: <input type='text' name='telefono' required><br>
            <input type='submit' name='registrar_usuario' value='Registrar Usuario y Continuar'>
        </form>";
        exit();
    }

    // Si el usuario existe, mostrar alquiler y pago (monto calculado):
    echo "<h3>🚲 Datos del Alquiler:</h3>
    <form method='POST' action=''>
        <input type='hidden' name='id_usuario' value='$id_usuario'>
        <input type='hidden' name='tarifa_hora' value='$tarifa_hora'>
        Modelo Bicicleta:
        <select name='id_bicicleta'>";
        
    $bicis = $conn->query("SELECT * FROM Bicicletas WHERE Estado='Disponible'");
    while($bici = $bicis->fetch_assoc()){
        echo "<option value='".$bici['ID_Bicicleta']."'>".$bici['Marca']." - ".$bici['Modelo']."</option>";
    }

    echo "</select><br>
        Fecha Pedido: <input type='datetime-local' name='pedido' required><br>
        Fecha Entrega: <input type='datetime-local' name='entrega' required><br>

        <h3>💰 Datos de Pago:</h3>
        (El monto se calculará automáticamente)<br><br>

        Método de Pago: 
        <select name='metodo'>
            <option value='Efectivo'>Efectivo</option>
            <option value='Tarjeta'>Tarjeta</option>
        </select><br><br>

        <input type='submit' name='calcular' value='Calcular Monto y Registrar'>
    </form>";
    $busqueda->close();
}

// Registrar nuevo usuario y continuar
if(isset($_POST['registrar_usuario'])){
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];

    $stmt = $conn->prepare("INSERT INTO Usuarios (Nombre, Apellido, DNI, Correo, Telefono, Tipo_Usuario) VALUES (?,?,?,?,?, 'Estudiante')");
    $stmt->bind_param("sssss", $nombre, $apellido, $dni, $correo, $telefono);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Usuario registrado. Vuelva a iniciar el alquiler.'); location.href='añadir_registro.php';</script>";
}

// Calcular monto y registrar alquiler y pago
if(isset($_POST['calcular'])){
    $id_usuario = $_POST['id_usuario'];
    $id_bicicleta = $_POST['id_bicicleta'];
    $pedido = $_POST['pedido'];
    $entrega = $_POST['entrega'];
    $metodo = $_POST['metodo'];
    $tarifa_hora = $_POST['tarifa_hora'];

    // Calcular horas
    $inicio = new DateTime($pedido);
    $fin = new DateTime($entrega);
    $intervalo = $inicio->diff($fin);
    $horas = $intervalo->days * 24 + $intervalo->h + ($intervalo->i > 0 ? 1 : 0);

    if($horas <= 0){
        echo "<script>alert('La fecha de entrega debe ser posterior a la de pedido.'); location.href='añadir_registro.php';</script>";
        exit();
    }

    $monto = $horas * $tarifa_hora;

    // Alquiler
    $alq = $conn->prepare("INSERT INTO Alquileres (ID_Bicicleta, ID_Usuario, Fecha_Alquiler, Fecha_Devolucion, Estado) VALUES (?,?,?,?, 'Activo')");
    $alq->bind_param("iiss", $id_bicicleta, $id_usuario, $pedido, $entrega);
    $alq->execute();
    $id_alquiler = $alq->insert_id;
    $alq->close();

    // Actualizar bicicleta
    $conn->query("UPDATE Bicicletas SET Estado='Alquilada' WHERE ID_Bicicleta=$id_bicicleta");

    // Pago
    $pago = $conn->prepare("INSERT INTO Pagos (ID_Alquiler, Monto, Fecha_Pago, Metodo) VALUES (?, ?, NOW(), ?)");
    $pago->bind_param("ids", $id_alquiler, $monto, $metodo);
    $pago->execute();
    $pago->close();

    echo "<h3>✅ Alquiler registrado.</h3>";
    echo "<p>Horas calculadas: $horas horas</p>";
    echo "<p>Monto total: S/ $monto</p>";
}
?>

</body>
</html>