<?php
include("conexion.php");

// Registrar nueva bicicleta
if (isset($_POST['registrar'])) {
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $tipo = $_POST['tipo'];
    $estado = $_POST['estado'];

    $sql = "INSERT INTO Bicicletas (Marca, Modelo, Tipo, Estado)
            VALUES ('$marca', '$modelo', '$tipo', '$estado')";

    if ($conn->query($sql) === TRUE) {
        $mensaje = "🚲 Bicicleta registrada exitosamente.";
    } else {
        $mensaje = "❌ Error: " . $conn->error;
    }
}

// Eliminar bicicleta
if (isset($_GET['eliminar'])) {
    $id_bici = $_GET['eliminar'];
    $conn->query("DELETE FROM Bicicletas WHERE ID_Bicicleta = $id_bici");
    header("Location: modificar_inventario.php");
    exit();
}

// Obtener bicicleta para edición
$editar_bici = null;
if (isset($_GET['editar'])) {
    $id_bici = $_GET['editar'];
    $res = $conn->query("SELECT * FROM Bicicletas WHERE ID_Bicicleta = $id_bici");
    $editar_bici = $res->fetch_assoc();
}

// Guardar cambios en bicicleta
if (isset($_POST['guardar_edicion'])) {
    $id_bici = $_POST['id_bici'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $tipo = $_POST['tipo'];
    $estado = $_POST['estado'];

    $sql = "UPDATE Bicicletas SET
            Marca = '$marca',
            Modelo = '$modelo',
            Tipo = '$tipo',
            Estado = '$estado'
            WHERE ID_Bicicleta = $id_bici";

    if ($conn->query($sql) === TRUE) {
        $mensaje = "✅ Bicicleta actualizada correctamente.";
        $editar_bici = null;
    } else {
        $mensaje = "❌ Error al actualizar: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modificar Inventario de Bicicletas</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        h1 { text-align: center; }
        table { border-collapse: collapse; width: 90%; margin: auto; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        form { width: 60%; margin: auto; background: #f9f9f9; padding: 20px; border-radius: 10px; }
        input, select { width: 100%; padding: 8px; margin: 8px 0; }
        .msg { text-align: center; font-weight: bold; margin: 10px 0; color: green; }
        .acciones a { margin: 0 5px; text-decoration: none; }
    </style>
</head>
<body>

<h1>🔧 Modificar Inventario de Bicicletas</h1>

<?php if (isset($mensaje)) echo "<p class='msg'>$mensaje</p>"; ?>

<form method="POST">
    <?php if ($editar_bici): ?>
        <input type="hidden" name="id_bici" value="<?= $editar_bici['ID_Bicicleta'] ?>">
    <?php endif; ?>

    <label>Marca:</label>
    <input type="text" name="marca" required value="<?= $editar_bici['Marca'] ?? '' ?>">

    <label>Modelo:</label>
    <input type="text" name="modelo" required value="<?= $editar_bici['Modelo'] ?? '' ?>">

    <label>Tipo:</label>
    <select name="tipo" required>
        <option value="Montaña" <?= (isset($editar_bici) && $editar_bici['Tipo'] == 'Montaña') ? 'selected' : '' ?>>Montaña</option>
        <option value="Ruta" <?= (isset($editar_bici) && $editar_bici['Tipo'] == 'Ruta') ? 'selected' : '' ?>>Ruta</option>
        <option value="Paseo" <?= (isset($editar_bici) && $editar_bici['Tipo'] == 'Paseo') ? 'selected' : '' ?>>Paseo</option>
    </select>

    <label>Estado:</label>
    <select name="estado" required>
        <option value="Disponible" <?= (isset($editar_bici) && $editar_bici['Estado'] == 'Disponible') ? 'selected' : '' ?>>Disponible</option>
        <option value="Alquilada" <?= (isset($editar_bici) && $editar_bici['Estado'] == 'Alquilada') ? 'selected' : '' ?>>Alquilada</option>
        <option value="Mantenimiento" <?= (isset($editar_bici) && $editar_bici['Estado'] == 'Mantenimiento') ? 'selected' : '' ?>>Mantenimiento</option>
    </select>

    <?php if ($editar_bici): ?>
        <button type="submit" name="guardar_edicion">💾 Guardar Cambios</button>
    <?php else: ?>
        <button type="submit" name="registrar">➕ Registrar Bicicleta</button>
    <?php endif; ?>
</form>

<h2 style="text-align:center; margin-top:40px;">📋 Bicicletas Registradas</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Marca</th>
        <th>Modelo</th>
        <th>Tipo</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>
    <?php
    $res = $conn->query("SELECT * FROM Bicicletas ORDER BY ID_Bicicleta ASC");
    while ($row = $res->fetch_assoc()):
    ?>
    <tr>
        <td><?= $row['ID_Bicicleta'] ?></td>
        <td><?= $row['Marca'] ?></td>
        <td><?= $row['Modelo'] ?></td>
        <td><?= $row['Tipo'] ?></td>
        <td><?= $row['Estado'] ?></td>
        <td class="acciones">
            <a href="modificar_inventario.php?editar=<?= $row['ID_Bicicleta'] ?>">✏️ Editar</a>
            <a href="modificar_inventario.php?eliminar=<?= $row['ID_Bicicleta'] ?>" onclick="return confirm('¿Seguro que deseas eliminar esta bicicleta?')">🗑️ Eliminar</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
