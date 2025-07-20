<?php include("conexion.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <title>💰 Ver Reportes de Ingresos</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; margin:0; padding:20px; }
        h1 { text-align:center; color:#2c3e50; }
        .dashboard { display:flex; justify-content:space-around; flex-wrap:wrap; margin:20px 0; }
        .card {
            background:#fff; padding:20px; width:28%; margin:10px;
            box-shadow:0 2px 5px rgba(0,0,0,0.1); border-radius:10px; text-align:center;
        }
        .card h2 { margin:0; color:#27ae60; font-size:2em; }
        .card p { margin-top:10px; font-weight:bold; color:#555; }
        table { width:90%; margin:20px auto; border-collapse:collapse; background:#fff; box-shadow:0 2px 5px rgba(0,0,0,0.1); }
        th, td { border:1px solid #ccc; padding:10px; text-align:center; }
        th { background:#27ae60; color:#fff; }
    </style>
</head>
<body>

<h1>💰 Dashboard de Ingresos</h1>

<?php
// Total de ingresos
$total = $conn->query("SELECT SUM(Monto) AS Total FROM Pagos")->fetch_assoc()['Total'];

// Total por método
$metodos = $conn->query("SELECT Metodo, SUM(Monto) AS Total FROM Pagos GROUP BY Metodo");

$efectivo = 0; $tarjeta = 0;
while($m = $metodos->fetch_assoc()){
    if($m['Metodo']=='Efectivo') $efectivo = $m['Total'];
    if($m['Metodo']=='Tarjeta') $tarjeta = $m['Total'];
}
?>

<div class="dashboard">
    <div class="card">
        <h2>$<?php echo number_format($total,2); ?></h2>
        <p>Ingreso Total</p>
    </div>
    <div class="card">
        <h2>$<?php echo number_format($efectivo,2); ?></h2>
        <p>Pagos en Efectivo</p>
    </div>
    <div class="card">
        <h2>$<?php echo number_format($tarjeta,2); ?></h2>
        <p>Pagos con Tarjeta</p>
    </div>
</div>

<!-- Ingresos por Fecha -->
<h2 style="text-align:center;">📅 Ingresos por Fecha</h2>
<table>
<tr><th>Fecha</th><th>Total</th></tr>
<?php
$res = $conn->query("SELECT DATE(Fecha_Pago) AS Fecha, SUM(Monto) AS Total FROM Pagos GROUP BY DATE(Fecha_Pago)");
while($row = $res->fetch_assoc()){
    echo "<tr><td>{$row['Fecha']}</td><td>$ ".number_format($row['Total'],2)."</td></tr>";
}
?>
</table>

<!-- Ingresos por Bicicleta -->
<h2 style="text-align:center;">🚲 Ingresos por Bicicleta</h2>
<table>
<tr><th>Marca</th><th>Modelo</th><th>Total</th></tr>
<?php
$res = $conn->query("
    SELECT B.Marca, B.Modelo, SUM(P.Monto) AS Total
    FROM Pagos P
    JOIN Alquileres A ON P.ID_Alquiler = A.ID_Alquiler
    JOIN Bicicletas B ON A.ID_Bicicleta = B.ID_Bicicleta
    GROUP BY B.ID_Bicicleta
");
while($row = $res->fetch_assoc()){
    echo "<tr><td>{$row['Marca']}</td><td>{$row['Modelo']}</td><td>$ ".number_format($row['Total'],2)."</td></tr>";
}
?>
</table>

<!-- Ingresos por Cliente -->
<h2 style="text-align:center;">👤 Ingresos por Cliente</h2>
<table>
<tr><th>Cliente</th><th>DNI</th><th>Total Pagado</th></tr>
<?php
$res = $conn->query("
    SELECT U.Nombre, U.Apellido, U.DNI, SUM(P.Monto) AS Total
    FROM Pagos P
    JOIN Alquileres A ON P.ID_Alquiler = A.ID_Alquiler
    JOIN Usuarios U ON A.ID_Usuario = U.ID_Usuario
    GROUP BY U.ID_Usuario
");
while($row = $res->fetch_assoc()){
    echo "<tr><td>{$row['Nombre']} {$row['Apellido']}</td><td>{$row['DNI']}</td><td>$ ".number_format($row['Total'],2)."</td></tr>";
}
?>
</table>

<?php $conn->close(); ?>

</body>
</html>
