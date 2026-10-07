<?php
$servidor = "localhost"; 
$usuario = "root"; 
$clave = ""; 
$base = "Escuela"; 

$conexion = mysqli_connect($servidor, $usuario, $clave, $base);
if (!$conexion) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Proceso para Reactivar / Dar de alta (cambia estatus a 1)
if (isset($_GET['alta'])) { 
    $id_alta = (int)$_GET['alta'];
    mysqli_query($conexion, "UPDATE alumnos SET estatus = 1 WHERE id = $id_alta"); 
    header("Location: ba_alumnos.php"); 
    exit; 
}

// Consulta SOLO los alumnos dados de BAJA (estatus = 0)
$alumnos_baja = mysqli_query($conexion, "SELECT * FROM alumnos WHERE estatus = 0 ORDER BY apaterno");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset='UTF-8'>
    <title>Módulo de Baja — Alumnos</title>
    <!-- Íconos de FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #fff5f7; color: #333; }
        h2 { color: #FF69B4; }
        table { border-collapse: collapse; width: 100%; background: #fff; margin: 15px 0; box-shadow: 0 0 10px #f8d7da; border-radius: 6px; overflow: hidden; }
        th { background: #FF69B4; color: #fff; padding: 12px; text-align: left; }
        td { border: 1px solid #fce8e6; padding: 10px; vertical-align: middle; }
        tr:nth-child(even) { background: #fff0f3; }
        tr.inactivo { background-color: #fbeed5 !important; color: #8a6d3b; }

        .btn-act {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            color: #fff !important;
            border-radius: 4px;
            text-decoration: none !important;
            font-size: 13px;
        }
        .btn-alta { background-color: #28a745; }
        .btn-alta:hover { background-color: #1e7e34; }

        a { text-decoration: none; color: #d63384; }
        a:hover { color: #a61e4d; text-decoration: underline; }
        .nav-links { margin-top: 15px; font-weight: bold; }
    </style>
</head>
<body>

<h2>📁 Módulo de Baja — Alumnos Inactivos</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Nombre Completo</th>
        <th>Correo</th>
        <th>Teléfono</th>
        <th>Grupo</th>
        <th>Estado</th>
        <th style="width: 100px; text-align: center;">Reactivar</th>
    </tr>
    <?php if(mysqli_num_rows($alumnos_baja) == 0): ?>
    <tr>
        <td colspan="7" style="text-align: center; color: #888;">No hay alumnos dados de baja en este momento.</td>
    </tr>
    <?php endif; ?>

    <?php while($a = mysqli_fetch_assoc($alumnos_baja)): ?>
    <tr class="inactivo">
        <td><?=$a['id']?></td>
        <td><?=$a['apaterno']?> <?=$a['amaterno']?>, <?=$a['nombre']?></td>
        <td><?=$a['mail']?></td>
        <td><?=$a['tel']?></td>
        <td><?=$a['id_grupo']?></td>
        <td><strong>Baja</strong></td>
        <td style="text-align: center;">
            <!-- Botón Dar de alta / Reactivar -->
            <a href='?alta=<?=$a['id']?>' class='btn-act btn-alta' title='Reactivar Alumno' onclick="return confirm('¿Deseas reactivar e ingresar nuevamente a este alumno?')">
                <i class='fa fa-check'></i>
            </a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<div class="nav-links">
    <a href='../procesos/pro_alumnos.php'>← Procesos</a> | 
    <a href='../reportes/rep_alumnos.php'>Reporte General</a>
</div>

</body>
</html>