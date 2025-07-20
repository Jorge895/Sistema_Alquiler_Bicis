<?php include("conexion.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Revisar Base de Datos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        h2 {
            color: #007BFF;
            margin-top: 20px;
        }
        h3 {
            color: #495057;
            margin-top: 30px;
        }
        a {
            display: inline-block;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            margin-bottom: 40px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        table th, table td {
            padding: 10px 15px;
            border: 1px solid #dee2e6;
            text-align: center;
        }
        table th {
            background-color: #007BFF;
            color: white;
        }
        input[type="text"], select {
            padding: 8px;
            margin: 10px 0;
            width: 250px;
            border: 1px solid #ced4da;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #007BFF;
            border: none;
            padding: 8px 15px;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        form {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<a href="Principal.php" style="text-decoration:none; background:#007BFF; color:white; padding:10px 20px; border-radius:5px;">⬅️ Volver a la Página Principal</a>

<h2>📊 Revisar Base de Datos</h2>

<!-- Formulario de búsqueda y filtro para Bicicletas -->
<h3>🚲 Filtrar Bicicletas</h3>
<form method="GET" action="">
    Estado: 
    <select name="estado">
        <option value="">Todos</option>
        <option value="Disponible">Disponible</option>
        <option value="Alquilada">Alquilada</option>
    </select>
    <input type="submit" name="filtro_bici" value="Filtrar">
</form>

<form method="GET" action="">
    <input type="text" name="buscar_bici" placeholder="Buscar por Marca, Modelo o Tipo">
    <input type="submit" name="buscar_bici_submit" value="Buscar">
</form>

<h3>Bicicletas</h3>
<table>
    <tr>
        <th>ID Bicicleta</th>
        <th>Marca</th>
        <th>Modelo</th>
        <th>Tipo</th>
        <th>Estado</th>
    </tr>
<?php
$estado = isset($_GET['estado']) ? $_GET['estado'] : '';
$buscar_bici = isset($_GET['buscar_bici']) ? $_GET['buscar_bici'] : '';

$q = "SELECT * FROM Bicicletas";
if($estado != "") $q .= " WHERE Estado='$estado'";
if ($buscar_bici != "") {
    $q .= ($estado != "" ? " AND" : " WHERE") . " (Marca LIKE '%$buscar_bici%' OR Modelo LIKE '%$buscar_bici%' OR Tipo LIKE '%$buscar_bici%')";
}
$res = $conn->query($q);
while($b = $res->fetch_assoc()) {
    echo "<tr><td>".$b['ID_Bicicleta']."</td><td>".$b['Marca']."</td><td>".$b['Modelo']."</td><td>".$b['Tipo']."</td><td>".$b['Estado']."</td></tr>";
}
?>
</table>

<!-- Formulario de búsqueda para Usuarios -->
<h3>🧑 Buscar Usuarios</h3>
<form method="GET" action="">
    <input type="text" name="buscar_usuario" placeholder="Buscar por Nombre o DNI">
    <input type="submit" name="buscar_usuario_submit" value="Buscar">
</form>

<h3>Usuarios</h3>
<table>
    <tr>
        <th>ID Usuario</th>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>DNI</th>
        <th>Correo</th>
        <th>Teléfono</th>
        <th>Tipo Usuario</th>
    </tr>
<?php
$buscar_usuario = isset($_GET['buscar_usuario']) ? $_GET['buscar_usuario'] : '';
$q_usuarios = "SELECT * FROM Usuarios";
if ($buscar_usuario != "") {
    $q_usuarios .= " WHERE Nombre LIKE '%$buscar_usuario%' OR DNI LIKE '%$buscar_usuario%'";
}
$res_usuarios = $conn->query($q_usuarios);
while($b = $res_usuarios->fetch_assoc()) {
    echo "<tr><td>".$b['ID_Usuario']."</td><td>".$b['Nombre']."</td><td>".$b['Apellido']."</td><td>".$b['DNI']."</td><td>".$b['Correo']."</td><td>".$b['Telefono']."</td><td>".$b['Tipo_Usuario']."</td></tr>";
}
?>
</table>

<!-- Formulario de búsqueda para Alquileres -->
<h3>📅 Buscar Alquileres</h3>
<form method="GET" action="">
    <input type="text" name="buscar_alquiler" placeholder="Buscar por ID_Bicicleta o ID_Usuario">
    <input type="submit" name="buscar_alquiler_submit" value="Buscar">
</form>

<h3>Alquileres</h3>
<table>
    <tr>
        <th>ID Alquiler</th>
        <th>ID Bicicleta</th>
        <th>ID Usuario</th>
        <th>Fecha Alquiler</th>
        <th>Fecha Devolución</th>
        <th>Estado</th>
    </tr>
<?php
$buscar_alquiler = isset($_GET['buscar_alquiler']) ? $_GET['buscar_alquiler'] : '';
$q_alquileres = "SELECT * FROM Alquileres";
if ($buscar_alquiler != "") {
    $q_alquileres .= " WHERE ID_Bicicleta LIKE '%$buscar_alquiler%' OR ID_Usuario LIKE '%$buscar_alquiler%'";
}
$res_alquileres = $conn->query($q_alquileres);
while($b = $res_alquileres->fetch_assoc()) {
    echo "<tr><td>".$b['ID_Alquiler']."</td><td>".$b['ID_Bicicleta']."</td><td>".$b['ID_Usuario']."</td><td>".$b['Fecha_Alquiler']."</td><td>".$b['Fecha_Devolucion']."</td><td>".$b['Estado']."</td></tr>";
}
?>
</table>

<!-- Formulario de búsqueda para Pagos -->
<h3>💰 Buscar Pagos</h3>
<form method="GET" action="">
    <input type="text" name="buscar_pago" placeholder="Buscar por ID_Alquiler o Monto">
    <input type="submit" name="buscar_pago_submit" value="Buscar">
</form>

<h3>Pagos</h3>
<table>
    <tr>
        <th>ID Pago</th>
        <th>ID Alquiler</th>
        <th>Monto</th>
        <th>Fecha Pago</th>
        <th>Metodo</th>
    </tr>
<?php
$buscar_pago = isset($_GET['buscar_pago']) ? $_GET['buscar_pago'] : '';
$q_pagos = "SELECT * FROM Pagos";
if ($buscar_pago != "") {
    $q_pagos .= " WHERE ID_Alquiler LIKE '%$buscar_pago%' OR Monto LIKE '%$buscar_pago%'";
}
$res_pagos = $conn->query($q_pagos);
while($b = $res_pagos->fetch_assoc()) {
    echo "<tr><td>".$b['ID_Pago']."</td><td>".$b['ID_Alquiler']."</td><td>".$b['Monto']."</td><td>".$b['Fecha_Pago']."</td><td>".$b['Metodo']."</td></tr>";
}
?>
</table>

</body>
</html>

