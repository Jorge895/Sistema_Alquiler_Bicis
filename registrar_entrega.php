<?php include("conexion.php"); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Entrega de Bicicleta</title>
</head>
<body>

<h2>📦 Registrar Entrega de Bicicleta</h2>

<form method="POST" action="">
    ID del Alquiler: <input type="number" name="id_alquiler" required><br><br>
    <input type="submit" name="registrar" value="Registrar Entrega">
</form>

<?php
if (isset($_POST['registrar'])) {
    date_default_timezone_set('America/Lima');
    $id_alquiler = $_POST['id_alquiler'];
    $fecha_actual = date("Y-m-d H:i:s");

    // 1. Obtener datos del alquiler si está activo
    $sql = "SELECT * FROM Alquileres WHERE ID_Alquiler = $id_alquiler AND Estado = 'Activo'";
    $res = $conn->query($sql);

    if ($res->num_rows === 1) {
        $row = $res->fetch_assoc();
        $id_bici = $row['ID_Bicicleta'];
        $fecha_prevista = $row['Fecha_Devolucion'];

        // 2. Comparar fechas para determinar si es retrasado
        $nuevo_estado = (strtotime($fecha_actual) <= strtotime($fecha_prevista)) ? 
                        'Finalizado' : 'Retrasado';

        // 3. Actualizar alquiler
        $sql_update_alquiler = "UPDATE Alquileres 
                                SET Fecha_Devolucion = '$fecha_actual',
                                    Estado = '$nuevo_estado'
                                WHERE ID_Alquiler = $id_alquiler";

        // 4. Actualizar bicicleta
        $sql_update_bici = "UPDATE Bicicletas 
                            SET Estado = 'Disponible' 
                            WHERE ID_Bicicleta = $id_bici";

        if ($conn->query($sql_update_alquiler) && $conn->query($sql_update_bici)) {
            echo "<p style='color:green;'>✅ Entrega registrada correctamente. Estado actualizado a <strong>$nuevo_estado</strong>.</p>";
        } else {
            echo "<p style='color:red;'>❌ Error al actualizar los datos.</p>";
        }

    } else {
        echo "<p style='color:red;'>⚠️ No se encontró un alquiler activo con ese ID.</p>";
    }
}
?>

</body>
</html>
