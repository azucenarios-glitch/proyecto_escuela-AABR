<?php
$servidor = "localhost"; 
$usuario = "root"; 
$clave = ""; 
$base = "Escuela"; 

$conexion = mysqli_connect($servidor, $usuario, $clave, $base);
if (!$conexion) {
    die("❌ Conexión fallida: " . mysqli_connect_error());
}

// Reactivar registro de manera directa
if(isset($_GET['reactivar'])){ 
    $id_reactivar = (int)$_GET['reactivar'];
    
    $res = mysqli_query($conexion, "SELECT * FROM ba_grupos WHERE id_baja = $id_reactivar");
    if($res && $alumno = mysqli_fetch_assoc($res)){
        $nombre = mysqli_real_escape_string($conexion, $alumno['nombre_alumno']);
        $grupo = mysqli_real_escape_string($conexion, $alumno['grupo']);
        $matricula = mysqli_real_escape_string($conexion, $alumno['matricula']);
        $carrera = mysqli_real_escape_string($conexion, $alumno['carrera']);
        $semestre = mysqli_real_escape_string($conexion, $alumno['semestre']);
        
        $sql_insert = "INSERT INTO grupos (nombre_alumno, grupo, matricula, carrera, semestre, estatus_grupo) 
                       VALUES ('$nombre', '$grupo', '$matricula', '$carrera', '$semestre', 'Activo')";
        
        if(mysqli_query($conexion, $sql_insert)){
            mysqli_query($conexion, "DELETE FROM ba_grupos WHERE id_baja = $id_reactivar");
            header("Location: pro_grupos.php"); 
            exit;
        }
    }
}

// Eliminar permanentemente
if(isset($_GET['del_perm'])){ 
    $id_del = (int)$_GET['del_perm'];
    mysqli_query($conexion, "DELETE FROM ba_grupos WHERE id_baja = $id_del"); 
    header("Location: ba_grupos.php"); 
    exit; 
}

$bajas = mysqli_query($conexion, "SELECT * FROM ba_grupos ORDER BY fecha_baja DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset='UTF-8'>
    <title>Bajas Grupos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #fff5f7; color: #333; }
        h2 { color: #00FFFF; }
        table { border-collapse: collapse; width: 100%; background: #fff; margin: 15px 0; box-shadow: 0 0 10px #f8d7da; border-radius: 6px; overflow: hidden; }
        th { background: #00FFFF; color: #fff; padding: 12px; text-align: left; }
        td { border: 1px solid #fce8e6; padding: 10px; vertical-align: middle; }
        tr.inactivo { background-color: #fbeed5 !important; color: #8a6d3b; }

        .btn-act {
            display: inline-flex; align-items: center; justify-content: center;
            width: 28px; height: 28px; color: #fff !important;
            border-radius: 4px; text-decoration: none !important; font-size: 13px; margin-right: 3px;
        }
        .btn-alta { background-color: #28a745; }
        .btn-del  { background-color: #dc3545; }
        a { text-decoration: none; color: #00FFFF; }
        .nav-links { margin-top: 15px; font-weight: bold; }
    </style>
</head>
<body>

<h2>📁 Registro de Grupos Dados de Baja</h2>

<table>
    <tr>
        <th>ID Baja</th>
        <th>Alumno</th>
        <th>Grupo</th>
        <th>Matrícula</th>
        <th>Carrera</th>
        <th>Semestre</th>
        <th>Estatus</th>
        <th>Fecha de Baja</th>
        <th style="width: 100px; text-align: center;">Acciones</th>
    </tr>
    <?php if($bajas && mysqli_num_rows($bajas) > 0): ?>
        <?php while($b = mysqli_fetch_assoc($bajas)): ?>
        <tr class="inactivo">
            <td><?=$b['id_baja'] ?? ''?></td>
            <td><?=$b['nombre_alumno'] ?? ''?></td>
            <td><?=$b['grupo'] ?? ''?></td>
            <td><?=$b['matricula'] ?? ''?></td>
            <td><?=$b['carrera'] ?? ''?></td>
            <td><?=$b['semestre'] ?? ''?></td>
            <td><strong><?=$b['estatus_grupo'] ?? 'Inactivo'?></strong></td>
            <td><?=$b['fecha_baja'] ?? ''?></td>
            <td style="text-align: center;">
                <a href='?reactivar=<?=$b['id_baja']?>' class='btn-act btn-alta' title='Reactivar'><i class='fa fa-check'></i></a>
                <a href='?del_perm=<?=$b['id_baja']?>' class='btn-act btn-del' title='Eliminar Definitivamente'><i class='fa fa-trash'></i></a>
            </td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan='9' style='text-align:center; color: #888;'>No hay grupos dados de baja.</td>
        </tr>
    <?php endif; ?>
</table>

<div class="nav-links">
    <a href='../procesos/pro_grupos.php'>← Procesos</a> | 
    <a href='../index.php'>🏠 Inicio</a>
</div>

</body>
</html>