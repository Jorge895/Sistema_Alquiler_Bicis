<?php include("conexion.php"); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Alquiler de Bicicletas - Página Principal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f4f8;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #007BFF;
            color: white;
            padding: 20px;
            text-align: center;
        }
        h1 {
            margin: 0;
        }
        main {
            padding: 30px;
        }
        h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th {
            background-color: #007BFF;
            color: white;
            padding: 10px;
        }
        td {
            text-align: center;
            padding: 8px;
        }
        tr:nth-child(even){
            background-color: #f9f9f9;
        }
        .opciones {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .opciones a {
            display: inline-block;
            margin: 10px 15px 10px 0;
            text-decoration: none;
            color: white;
            background-color: #28a745;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .opciones a:hover {
            background-color: #218838;
        }
        footer {
            text-align: center;
            padding: 15px;
            background-color: #007BFF;
            color: white;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>

<header>
    <h1>🚲 Sistema de Alquiler de Bicicletas</h1>
</header>

<main>
    <h2>Bicicletas Disponibles</h2>

    <table>
        <tr><th>ID</th><th>Marca</th><th>Modelo</th><th>Tipo</th><th>Estado</th></tr>
        <?php
        $bicis = $conn->query("SELECT * FROM Bicicletas WHERE Estado='Disponible'");
        while($row = $bicis->fetch_assoc()){
            echo "<tr>
                    <td>".$row['ID_Bicicleta']."</td>
                    <td>".$row['Marca']."</td>
                    <td>".$row['Modelo']."</td>
                    <td>".$row['Tipo']."</td>
                    <td>".$row['Estado']."</td>
                  </tr>";
        }
        ?>
    </table>

    <div class="opciones">
        <h2>📋 Opciones:</h2>
        <a href="añadir_registro.php">➕ Añadir Registro</a>
        <a href="borrar_registro.php">❌ Borrar Registro</a>
        <a href="revisar_bd.php">📊 Revisar Base de Datos</a>
        <a href="modificar_inventario.php">🛠️ Modificar Inventario</a>
        <a href="reportes_de_ingresos.php">📈 Ver Reportes de Ingresos</a>
        <a href="tendencias_uso.php">📈 Ver Tendencias de Uso</a>
    </div>
</main>

<footer>
    &copy; <?php echo date("Y"); ?> Sistema de Alquiler de Bicicletas
</footer>

</body>
</html>
