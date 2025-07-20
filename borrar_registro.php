<?php include("conexion.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <title>🚲 Panel de Control - Bicicletas</title>
    <style>
        body {
            font-family: Arial, sans-serif; background: #f4f4f4; color: #333;
            margin: 0; padding: 20px;
        }
        h1, h2 {
            text-align: center; color: #2c3e50;
        }
        table {
            width: 90%; margin: auto; border-collapse: collapse; background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #ddd; padding: 10px; text-align: center;
        }
        th {
            background: #27ae60; color: #fff;
        }
        tr:nth-child(even) { background: #f9f9f9; }
        .mensaje {
            text-align: center; margin-top: 15px; font-size: 1.1em; color: #c0392b;
        }
        .success {
            color: #27ae60;
        }
        input[type="submit"] {
            padding: 5px 10px; background: #c0392b; color: #fff; border: none; border-radius: 4px; cursor: pointer;
        }
        input[type="submit"]:hover {
            background: #e74c3c;
        }
        .form-section {
            background: #fff; padding: 15px; margin: 20px auto; width: 50%; box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }
        input[type="text"], input[type="number"] {
            padding: 8px; width: 60%; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px;
        }
    </style>
</head>
<body>

<h1>🚲 Sistema de Alquiler de Bicicletas</h1>

<?php
// Borrar alquiler por ID o por botón
if(isset($_POST['borrar_alquiler_id']) || isset($_POST['borrar_alquiler'])){
    $id = isset($_POST['borrar_alquiler_id']) ? $_POST['alquiler_id'] : $_POST['id_alquiler'];

    $verificar = $conn->prepare("SELECT ID_Bicicleta FROM Alquileres WHERE ID_Alquiler=?");
    $verificar->bind_param("i", $id);
    $verificar->execute();
    $verificar->store_result();

    if($verificar->num_rows > 0){
        $verificar->bind_result($id_bicicleta);
        $verificar->fetch();

        $update = $conn->prepare("UPDATE Bicicletas SET Estado='Disponible' WHERE ID_Bicicleta=?");
        $update->bind_param("i", $id_bicicleta);
        $update->execute();
        $update->close();

        $conn->query("DELETE FROM Pagos WHERE ID_Alquiler=$id");
        $conn->query("DELETE FROM Alquileres WHERE ID_Alquiler=$id");

        echo "<div class='mensaje success'>✅ Alquiler eliminado correctamente.</div>";
    } else {
        echo "<div class='mensaje'>⚠️ El ID de alquiler no existe.</div>";
    }

    $verificar->close();
}

// Borrar usuario por DNI o por botón
if(isset($_POST['borrar_usuario_dni']) || isset($_POST['borrar_usuario'])){
    if(isset($_POST['borrar_usuario_dni'])){
        $dni = $_POST['usuario_dni'];

        $verif_usuario = $conn->prepare("SELECT ID_Usuario FROM Usuarios WHERE DNI=?");
        $verif_usuario->bind_param("s", $dni);
        $verif_usuario->execute();
        $verif_usuario->store_result();

        if($verif_usuario->num_rows > 0){
            $verif_usuario->bind_result($id_usuario);
            $verif_usuario->fetch();
        } else {
            echo "<div class='mensaje'>⚠️ No existe un usuario con ese DNI.</div>";
            $verif_usuario->close();
            $id_usuario = null;
        }
        $verif_usuario->close();
    } else {
        $id_usuario = $_POST['id_usuario'];
    }

    if(isset($id_usuario) && $id_usuario != null){
        $verif_alquiler = $conn->prepare("SELECT ID_Alquiler FROM Alquileres WHERE ID_Usuario=?");
        $verif_alquiler->bind_param("i", $id_usuario);
        $verif_alquiler->execute();
        $verif_alquiler->store_result();

        if($verif_alquiler->num_rows > 0){
            echo "<div class='mensaje'>⚠️ No se puede borrar el usuario porque tiene alquileres activos.</div>";
        } else {
            $del_usuario = $conn->prepare("DELETE FROM Usuarios WHERE ID_Usuario=?");
            $del_usuario->bind_param("i", $id_usuario);
            $del_usuario->execute();
            $del_usuario->close();

            echo "<div class='mensaje success'>✅ Usuario eliminado correctamente.</div>";
        }

        $verif_alquiler->close();
    }
}
?>

<!-- 1️⃣ Cuadro para borrar alquiler por ID -->
<div class="form-section">
    <h3>❌ Borrar Alquiler por ID</h3>
    <form method="POST" action="">
        ID de Alquiler: <br>
        <input type="number" name="alquiler_id" required><br>
        <input type="submit" name="borrar_alquiler_id" value="Borrar Alquiler por ID">
    </form>
</div>

<!-- 2️⃣ Tabla de alquileres con botón por fila -->
<h3>📋 Alquileres Actuales</h3>

<table>
<tr>
    <th>ID Alquiler</th><th>Usuario</th><th>Bicicleta</th><th>Fecha Pedido</th><th>Fecha Entrega</th><th>Estado</th><th>Acción</th>
</tr>

<?php
$alquileres = $conn->query("
    SELECT A.ID_Alquiler, U.Nombre, U.Apellido, B.Marca, B.Modelo, A.Fecha_Alquiler, A.Fecha_Devolucion, A.Estado
    FROM Alquileres A
    JOIN Usuarios U ON A.ID_Usuario = U.ID_Usuario
    JOIN Bicicletas B ON A.ID_Bicicleta = B.ID_Bicicleta
");

if($alquileres->num_rows == 0){
    echo "<tr><td colspan='7'>🚫 No hay alquileres registrados.</td></tr>";
} else {
    while($row = $alquileres->fetch_assoc()){
        echo "<tr>
                <td>{$row['ID_Alquiler']}</td>
                <td>{$row['Nombre']} {$row['Apellido']}</td>
                <td>{$row['Marca']} {$row['Modelo']}</td>
                <td>{$row['Fecha_Alquiler']}</td>
                <td>{$row['Fecha_Devolucion']}</td>
                <td>{$row['Estado']}</td>
                <td>
                    <form method='POST' action=''>
                        <input type='hidden' name='id_alquiler' value='{$row['ID_Alquiler']}'>
                        <input type='submit' name='borrar_alquiler' value='Borrar'>
                    </form>
                </td>
              </tr>";
    }
}
?>
</table>

<!-- 3️⃣ Cuadro para borrar usuario por DNI -->
<div class="form-section">
    <h3>❌ Borrar Usuario por DNI</h3>
    <form method="POST" action="">
        DNI del Usuario: <br>
        <input type="text" name="usuario_dni" required><br>
        <input type="submit" name="borrar_usuario_dni" value="Borrar Usuario por DNI">
    </form>
</div>

<!-- 4️⃣ Tabla de usuarios con botón por fila -->
<h3>👥 Usuarios Registrados</h3>

<table>
<tr>
    <th>ID Usuario</th><th>Nombre</th><th>Apellido</th><th>DNI</th><th>Correo</th><th>Teléfono</th><th>Acción</th>
</tr>

<?php
$usuarios = $conn->query("SELECT * FROM Usuarios");

if($usuarios->num_rows == 0){
    echo "<tr><td colspan='7'>🚫 No hay usuarios registrados.</td></tr>";
} else {
    while($row = $usuarios->fetch_assoc()){
        echo "<tr>
                <td>{$row['ID_Usuario']}</td>
                <td>{$row['Nombre']}</td>
                <td>{$row['Apellido']}</td>
                <td>{$row['DNI']}</td>
                <td>{$row['Correo']}</td>
                <td>{$row['Telefono']}</td>
                <td>
                    <form method='POST' action=''>
                        <input type='hidden' name='id_usuario' value='{$row['ID_Usuario']}'>
                        <input type='submit' name='borrar_usuario' value='Borrar'>
                    </form>
                </td>
              </tr>";
    }
}
?>
</table>

</body>
</html>
