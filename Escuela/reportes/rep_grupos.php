<?php
$servidor = "localhost"; 
$usuario = "root"; 
$clave = ""; 
$base = "Escuela"; 

// 1. Conexión inicial al servidor
$conexion = mysqli_connect($servidor, $usuario, $clave);
if (!$conexion) {
    die("❌ Conexión fallida: " . mysqli_connect_error());
}

// 2. Seleccionar la base de datos
mysqli_select_db($conexion, $base);

// 3. Obtener todos los registros de la tabla grupos
$grupos = mysqli_query($conexion, "SELECT * FROM grupos ORDER BY grupo");

// 4. Definir la variable total contando el número de filas obtenidas
$total = $grupos ? mysqli_num_rows($grupos) : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset='UTF-8'>
    <title>Reporte de Grupos</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background: #f8f9fa; }
        h2 { color: #00FFFF; text-align: center; }
        table { border-collapse: collapse; width: 100%; background: #fff; margin: 20px 0; box-shadow: 0 0 10px #ccc; }
        th { background: #00FFFF; color: #fff; padding: 12px; text-align: left; }
        td { border: 1px solid #ddd; padding: 10px; }
        tr:nth-child(even) { background: #f2f2f2; }
        .total { font-weight: bold; font-size: 16px; margin-top: 10px; }
        button, .btn-volver { 
            padding: 8px 15px; 
            background: #2980b9; 
            color: #fff; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            margin-right: 10px;
        }
        button:hover, .btn-volver:hover { background: #1f6391; }
        @media print {
            button, .btn-volver { display: none; }
            body { background: #fff; margin: 0; }
            table { box-shadow: none; }
        }
    </style>
</head>
<body>

<h2>📄 Reporte General de Grupos</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Alumno</th>
        <th>Grupo</th>
        <th>Matrícula</th>
        <th>Carrera</th>
        <th>Semestre</th>
        <th>Estatus</th>
    </tr>
    <?php if ($total > 0): ?>
        <?php while ($g = mysqli_fetch_assoc($grupos)): ?>
        <tr>
            <td><?=$g['id_grupo'] ?? ''?></td>
            <td><?=$g['nombre_alumno'] ?? ''?></td>
            <td><?=$g['grupo'] ?? ''?></td>
            <td><?=$g['matricula'] ?? ''?></td>
            <td><?=$g['carrera'] ?? ''?></td>
            <td><?=$g['semestre'] ?? ''?></td>
            <td><?=$g['estatus_grupo'] ?? ''?></td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan='7' style='text-align:center;'>No hay registros de grupos disponibles.</td>
        </tr>
    <?php endif; ?>
</table>

<p class='total'>Total de grupos registrados: <?=$total?></p>

<button onclick='window.print()'>🖨️ Imprimir</button>
<a href='../procesos/pro_grupos.php' class='btn-volver'>← Volver</a>

</body>
</html>