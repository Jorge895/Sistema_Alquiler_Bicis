<?php include("conexion.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <title>📊 Tendencias de Uso</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial; background:#f4f4f4; padding:20px; }
        h1, h2 { text-align:center; color:#2c3e50; }
        .grafico { background:#fff; padding:20px; margin:20px auto; width:80%; box-shadow:0 2px 5px rgba(0,0,0,0.1); border-radius:10px; }
        canvas { margin:20px auto; display:block; }
    </style>
</head>
<body>

<h1>📊 Tendencias de Uso de Bicicletas</h1>

<?php
// 1️⃣ Días con más alquileres
$dias = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
$diasContador = array_fill(0,7,0);

$resDias = $conn->query("SELECT WEEKDAY(Fecha_Alquiler) AS Dia, COUNT(*) AS Cantidad FROM Alquileres GROUP BY Dia");
while($row = $resDias->fetch_assoc()){
    $indice = ($row['Dia']+1)%7; 
    $diasContador[$indice] = $row['Cantidad'];
}

// 2️⃣ Horas pico
$horas = range(0,23);
$horasContador = array_fill(0,24,0);

$resHoras = $conn->query("SELECT HOUR(Fecha_Alquiler) AS Hora, COUNT(*) AS Cantidad FROM Alquileres GROUP BY Hora");
while($row = $resHoras->fetch_assoc()){
    $horasContador[$row['Hora']] = $row['Cantidad'];
}

// 3️⃣ Bicicletas más usadas (todas, en barras horizontales)
$biciRes = $conn->query("
    SELECT CONCAT(B.Marca,' ',B.Modelo) AS Bicicleta, COUNT(*) AS Cantidad
    FROM Alquileres A
    JOIN Bicicletas B ON A.ID_Bicicleta = B.ID_Bicicleta
    GROUP BY B.ID_Bicicleta
    ORDER BY Cantidad DESC
");

$bicis = []; $cantidadesBici = [];
while($row = $biciRes->fetch_assoc()){
    $bicis[] = $row['Bicicleta'];
    $cantidadesBici[] = $row['Cantidad'];
}
?>

<!-- Gráfico: Días con más alquileres -->
<div class="grafico">
    <h2>📅 Días con Más Alquileres</h2>
    <canvas id="graficoDias" width="600" height="300"></canvas>
</div>

<!-- Gráfico: Horas pico -->
<div class="grafico">
    <h2>⏰ Horas Pico de Alquiler</h2>
    <canvas id="graficoHoras" width="600" height="300"></canvas>
</div>

<!-- Gráfico: Bicicletas más usadas -->
<div class="grafico">
    <h2>🚴‍♂️ Bicicletas Más Usadas</h2>
    <canvas id="graficoBicis" width="600" height="300"></canvas>
</div>

<script>
// 📅 Gráfico Días con más alquileres
new Chart(document.getElementById('graficoDias'), {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($dias); ?>,
        datasets: [{
            label: 'Cantidad de Alquileres',
            data: <?php echo json_encode($diasContador); ?>,
            backgroundColor: '#3498db'
        }]
    }
});

// ⏰ Gráfico Horas Pico
new Chart(document.getElementById('graficoHoras'), {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($horas); ?>,
        datasets: [{
            label: 'Cantidad de Alquileres por Hora',
            data: <?php echo json_encode($horasContador); ?>,
            backgroundColor: '#2ecc71'
        }]
    },
    options: {
        indexAxis: 'y'
    }
});

// 🚴‍♂️ Gráfico Bicicletas Más Usadas (barras horizontales)
new Chart(document.getElementById('graficoBicis'), {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($bicis); ?>,
        datasets: [{
            label: 'Veces Alquilada',
            data: <?php echo json_encode($cantidadesBici); ?>,
            backgroundColor: '#9b59b6'
        }]
    },
    options: {
        indexAxis: 'y'
    }
});
</script>

<?php $conn->close(); ?>

</body>
</html>


