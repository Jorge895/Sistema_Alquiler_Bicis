<?php include("conexion.php"); ?>

<!DOCTYPE html>
<html>
<head><title>Revisar Base de Datos</title></head>
<body>

<h2>📊 Revisar Base de Datos</h2>

<h3>Filtrar Bicicletas</h3>
<form method="GET" action="">
    Estado: <select name="estado">
        <option value="">Todos</option>
        <option value="Disponible">Disponible</option>
        <option value="Alquilada">Alquilada</option>
    </select>
    <input type="submit" name="filtro_bici" value="Filtrar">
</form>

<h3>Bicicletas</h3>
<table border="1">
<?php
$estado = isset($_GET['estado']) ? $_GET['estado'] : '';
$q = "SELECT * FROM Bicicletas";
if($estado!="") $q.=" WHERE Estado='$estado'";
$res = $conn->query($q);
while($b=$res->fetch_assoc()){
    echo "<tr><td>".$b['ID_Bicicleta']."</td><td>".$b['Marca']."</td><td>".$b['Modelo']."</td><td>".$b['Tipo']."</td><td>".$b['Estado']."</td></tr>";
}
?>
</table>

<h3>Usuarios</h3>
<table border="1">
<?php
$res = $conn->query("SELECT * FROM Usuarios");
while($b=$res->fetch_assoc()){
    echo "<tr><td>".$b['ID_Usuario']."</td><td>".$b['Nombre']."</td><td>".$b['DNI']."</td></tr>";
}
?>
</table>

<h3>Alquileres</h3>
<table border="1">
<?php
$res = $conn->query("SELECT * FROM Alquileres");
while($b=$res->fetch_assoc()){
    echo "<tr><td>".$b['ID_Alquiler']."</td><td>".$b['ID_Bicicleta']."</td><td>".$b['ID_Usuario']."</td><td>".$b['Fecha_Alquiler']."</td></tr>";
}
?>
</table>

<h3>Pagos</h3>
<table border="1">
<?php
$res = $conn->query("SELECT * FROM Pagos");
while($b=$res->fetch_assoc()){
    echo "<tr><td>".$b['ID_Pago']."</td><td>".$b['ID_Alquiler']."</td><td>".$b['Monto']."</td><td>".$b['Metodo']."</td></tr>";
}
?>
</table>

</body>
</html>