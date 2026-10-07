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
    mysqli_query($conexion, "UPDATE materias SET estatus = 1 WHERE id_mat = $id_alta"); 
    header("Location: ba_materias.php"); 
    exit; 
}

// Consulta SOLO las materias dadas de BAJA (estatus = 0)
$materias_baja = mysqli_query($conexion, "SELECT m.*, CONCAT(p.apaterno_prof, ' ', p.amaterno_prof, ', ', p.nom_prof) AS profesor 
                                           FROM materias m 
                                           LEFT JOIN profesores p ON m.id_prof = p.id_prof 
                                           WHERE m.estatus = 0 
                                           ORDER BY m.descripcion");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset='UTF-8'>
    <title>Módulo de Baja — Materias</title>
    <!-- Íconos de FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #fff5f7; color: #333; }
        h2 { color: #00FF00; }
        table { border-collapse: collapse; width: 100%; background: #fff; margin: 15px 0; box-shadow: 0 0 10px #f8d7da; border-radius: 6px; overflow: hidden; }
        th { background: #00FF00; color: #fff; padding: 12px; text-align: left; }
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

        a { text-decoration: none; color: #00FF00; }
        a:hover { color: #00FF00; text-decoration: underline; }
        .nav-links { margin-top: 15px; font-weight: bold; }
    </style>
</head>
<body>

<h2>📁 Módulo de Baja — Materias Inactivas</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Nombre de la Materia</th>
        <th>Profesor Asignado</th>
        <th>Estado</th>
        <th style="width: 100px; text-align: center;">Reactivar</th>
    </tr>
    <?php if(mysqli_num_rows($materias_baja) == 0): ?>
    <tr>
        <td colspan="5" style="text-align: center; color: #888;">No hay materias dadas de baja en este momento.</td>
    </tr>
    <?php endif; ?>

    <?php while($m = mysqli_fetch_assoc($materias_baja)): ?>
    <tr class="inactivo">
        <td><?=$m['id_mat']?></td>
        <td><?=$m['descripcion']?></td>
        <td><?=$m['profesor'] ? $m['profesor'] : '<em>Sin asignar</em>'?></td>
        <td><strong>Baja</strong></td>
        <td style="text-align: center;">
            <!-- Botón Dar de alta / Reactivar -->
            <a href='?alta=<?=$m['id_mat']?>' class='btn-act btn-alta' title='Reactivar Materia' onclick="return confirm('¿Deseas reactivar esta materia?')">
                <i class='fa fa-check'></i>
            </a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<div class="nav-links">
    <a href='../procesos/pro_materias.php'>← Procesos</a> | 
    <a href='../reportes/rep_materias.php'>Reporte General</a>
</div>

</body>
</html>