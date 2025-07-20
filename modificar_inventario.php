<?php
include("conexion.php");

// Buscar bicicleta por ID
$busqueda_resultado = null;
if (isset($_POST['buscar_bici'])) {
    $id_buscado = $_POST['id_bici_buscar'];
    $busqueda = $conn->query("SELECT * FROM Bicicletas WHERE ID_Bicicleta = $id_buscado");
    if ($busqueda && $busqueda->num_rows > 0) {
        $busqueda_resultado = $busqueda->fetch_assoc();
    } else {
        $mensaje = "❌ No se encontró una bicicleta con ID $id_buscado.";
    }
}

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

<br><br>
<a href="Principal.php" style="text-decoration:none; background:#007BFF; color:white; padding:10px 20px; border-radius:5px;">⬅️ Volver a la Página Principal</a>

<h1>🔧 Modificar Inventario de Bicicletas</h1>

<h2 style="text-align:center;">🔍 Buscar y eliminar bicicleta por ID</h2>

<form method="POST" style="text-align:center; margin-bottom: 30px;">
    <input type="number" name="id_bici_buscar" placeholder="ID de bicicleta" required>
    <button type="submit" name="buscar_bici">Buscar</button>
</form>

<?php if ($busqueda_resultado): ?>
    <div style="width: 60%; margin: auto; background-color: #fff4e6; border: 1px solid #ccc; padding: 15px; border-radius: 10px;">
        <p><strong>🚲 Bicicleta encontrada:</strong></p>
        <ul>
            <li><strong>ID:</strong> <?= $busqueda_resultado['ID_Bicicleta'] ?></li>
            <li><strong>Marca:</strong> <?= $busqueda_resultado['Marca'] ?></li>
            <li><strong>Modelo:</strong> <?= $busqueda_resultado['Modelo'] ?></li>
            <li><strong>Tipo:</strong> <?= $busqueda_resultado['Tipo'] ?></li>
            <li><strong>Estado:</strong> <?= $busqueda_resultado['Estado'] ?></li>
        </ul>
        <a href="modificar_inventario.php?eliminar=<?= $busqueda_resultado['ID_Bicicleta'] ?>" 
           onclick="return confirm('¿Estás seguro de eliminar esta bicicleta?')"
           style="color: red; text-decoration: none;">🗑️ Eliminar esta bicicleta</a>
    </div>
<?php endif; ?>


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
