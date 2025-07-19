<?php include("conexion.php"); ?>

<!DOCTYPE html>
<html>
<head><title>Borrar Registro</title></head>
<body>

<h2>❌ Borrar Alquiler</h2>

<form method="POST" action="">
    Código de Alquiler: <input type="number" name="id_alquiler" required>
    <input type="submit" name="borrar" value="Borrar">
</form>

<?php
if(isset($_POST['borrar'])){
    $id = $_POST['id_alquiler'];

    // Recuperar bicicleta y poner disponible
    $bicicleta = $conn->query("SELECT ID_Bicicleta FROM Alquileres WHERE ID_Alquiler=$id")->fetch_assoc();
    $conn->query("UPDATE Bicicletas SET Estado='Disponible' WHERE ID_Bicicleta=".$bicicleta['ID_Bicicleta']);

    // Borrar alquiler y pago
    $conn->query("DELETE FROM Pagos WHERE ID_Alquiler=$id");
    $conn->query("DELETE FROM Alquileres WHERE ID_Alquiler=$id");

    echo "<h3>✅ Alquiler eliminado.</h3>";
}
?>
</body>
</html>