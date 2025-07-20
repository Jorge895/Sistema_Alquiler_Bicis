<?php include("conexion.php"); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>📦 Registrar Entrega de Bicicleta</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; margin:0; padding:20px; }
        h1, h2 { text-align:center; color:#2c3e50; }
        form {
            width: 40%; margin: 20px auto; padding: 20px;
            background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        input[type="number"], input[type="submit"] {
            width: 100%; padding: 10px; margin: 10px 0;
            border: 1px solid #ccc; border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #27ae60; color: white;
            font-weight: bold; cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #219150;
        }
        a {
            text-decoration: none; background: #007BFF; color: white;
            padding: 10px 20px; border-radius: 5px;
            display: inline-block;
        }
        .mensaje {
            width: 60%; margin: 20px auto; padding: 15px;
            border-radius: 8px; font-weight: bold; text-align: center;
        }
        .exito { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
        .info  { background-color: #fff3cd; color: #856404; }
        .datos-alquiler {
            width: 60%; margin: 20px auto; padding: 15px;
            background: #ffffff; border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .datos-alquiler p {
            margin: 5px 0;
        }
    </style>
</head>
<body>

<a href="Principal.php">⬅️ Volver a la Página Principal</a>
<h2>📦 Registrar Entrega de Bicicleta</h2>

<form method="POST" action="">
    <label for="id_alquiler">ID del Alquiler:</label>
    <input type="number" name="id_alquiler" required>
    <input type="submit" name="registrar" value="Registrar Entrega">
</form>

<?php
if (isset($_POST['registrar'])) {
    date_default_timezone_set('America/Lima');
    $id_alquiler = $_POST['id_alquiler'];
    $fecha_actual = date("Y-m-d H:i:s");

    // 1. Obtener datos del alquiler si está activo
    $sql = "SELECT A.*, U.Nombre, U.Apellido, U.DNI 
            FROM Alquileres A
            JOIN Usuarios U ON A.ID_Usuario = U.ID_Usuario
            WHERE A.ID_Alquiler = $id_alquiler AND A.Estado = 'Activo'";
    $res = $conn->query($sql);

    if ($res->num_rows === 1) {
        $row = $res->fetch_assoc();
        $id_bici = $row['ID_Bicicleta'];
        $fecha_inicio = $row['Fecha_Alquiler'];
        $fecha_prevista = $row['Fecha_Devolucion'];
        $nombre = $row['Nombre'];
        $apellido = $row['Apellido'];
        $dni = $row['DNI'];

        echo "<div class='datos-alquiler'>";
        echo "<h3>📋 Datos del Alquiler</h3>";
        echo "<p><strong>Usuario:</strong> $nombre $apellido</p>";
        echo "<p><strong>DNI:</strong> $dni</p>";
        echo "<p><strong>ID Bicicleta:</strong> $id_bici</p>";
        echo "<p><strong>Fecha de Alquiler:</strong> $fecha_inicio</p>";
        echo "<p><strong>Fecha Prevista de Devolución:</strong> $fecha_prevista</p>";
        echo "<p><strong>Fecha Actual:</strong> $fecha_actual</p>";

        $segundos_diferencia = strtotime($fecha_actual) - strtotime($fecha_prevista);
        $minutos_diferencia = round(abs($segundos_diferencia) / 60);

        if ($segundos_diferencia <= 0) {
            echo "<p class='mensaje info'>🕒 Entregado <strong>$minutos_diferencia minutos antes</strong> de la hora prevista.</p>";
            $nuevo_estado = 'Finalizado';
        } else {
            echo "<p class='mensaje error'>⏰ Entregado <strong>$minutos_diferencia minutos tarde</strong>.</p>";
            $nuevo_estado = 'Retrasado';
        }
        echo "</div>";

        // 2. Actualizar alquiler
        $sql_update_alquiler = "UPDATE Alquileres 
                                SET Fecha_Devolucion = '$fecha_actual',
                                    Estado = '$nuevo_estado'
                                WHERE ID_Alquiler = $id_alquiler";

        // 3. Actualizar bicicleta
        $sql_update_bici = "UPDATE Bicicletas 
                            SET Estado = 'Disponible' 
                            WHERE ID_Bicicleta = $id_bici";

        if ($conn->query($sql_update_alquiler) && $conn->query($sql_update_bici)) {
            echo "<div class='mensaje exito'>✅ Entrega registrada correctamente. Estado actualizado a <strong>$nuevo_estado</strong>.</div>";
        } else {
            echo "<div class='mensaje error'>❌ Error al actualizar los datos.</div>";
        }

    } else {
        echo "<div class='mensaje error'>⚠️ No se encontró un alquiler activo con ese ID.</div>";
    }
}
?>

</body>
</html>
