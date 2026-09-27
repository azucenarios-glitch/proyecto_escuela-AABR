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
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Reporte de Grupos</title>
    <style>
        body{font-family:Arial;margin:30px;background:#f8f9fa}
        h2{color:#27ae60;text-align:center}
        table{border-collapse:collapse;width:100%;background:#fff;margin:20px 0;box-shadow:0 0 10px #ccc}
        th{background:#27ae60;color:#fff;padding:12px;text-align:left}
        td{border:1px solid #ddd;padding:10px}
        tr:nth-child(even){background:#f2f2f2}
        .btn-imprimir{display:block;width:150px;margin:20px auto;padding:10px;background:#2980b9;color:#fff;text-align:center;border-radius:5px;text-decoration:none;font-weight:bold}
        .navegacion{text-align:center;margin-top:20px}
        .navegacion a{color:#2980b9;text-decoration:none;font-weight:bold;margin:0 10px}
        @media print {
            .btn-imprimir, .navegacion { display: none; }
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
    <?php if($grupos && mysqli_num_rows($grupos) > 0): ?>
        <?php while($g = mysqli_fetch_assoc($grupos)): ?>
        <tr>
            <td><?=$g['id_grupo'] ?? ''?></td>
            <td><?=$g['nombre_alumno']?></td>
            <td><?=$g['grupo']?></td>
            <td><?=$g['matricula']?></td>
            <td><?=$g['carrera']?></td>
            <td><?=$g['semestre']?></td>
            <td><?=$g['estatus_grupo']?></td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan='7' style='text-align:center;'>No hay registros de grupos disponibles.</td>
        </tr>
    <?php endif; ?>
</table>

<!-- Botón para imprimir el reporte directamente -->
<a href='javascript:window.print();' class='btn-imprimir'>🖨️ Imprimir Reporte</a>

<div class='navegacion'>
    <a href='../procesos/grupos.php'>← Volver a Procesos</a> | 
    <a href='../index.php'>🏠 Inicio</a>
</div>

</body>
</html>