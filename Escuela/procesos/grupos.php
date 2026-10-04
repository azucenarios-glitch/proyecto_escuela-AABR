<?php
$servidor = "localhost"; 
$usuario = "root"; 
$clave = ""; 
$base = "Escuela"; 

$conexion = mysqli_connect($servidor, $usuario,$clave);
if (!$conexion) {
    die("❌ Conexión fallida: " . mysqli_connect_error());
}

mysqli_query($conexion, "CREATE DATABASE IF NOT EXISTS `$base` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
mysqli_select_db($conexion,$base);

mysqli_query($conexion, "CREATE TABLE IF NOT EXISTS grupos (
    id_grupo INT AUTO_INCREMENT PRIMARY KEY,
    nombre_alumno VARCHAR(100) NOT NULL,
    grupo VARCHAR(50) NOT NULL,
    matricula VARCHAR(50) NOT NULL,
    carrera VARCHAR(100) NOT NULL,
    semestre VARCHAR(20) NOT NULL,
    estatus_grupo VARCHAR(20) NOT NULL
)");

mysqli_query($conexion, "CREATE TABLE IF NOT EXISTS ba_grupos (
    id_baja INT AUTO_INCREMENT PRIMARY KEY,
    id_grupo INT,
    nombre_alumno VARCHAR(100) NOT NULL,
    grupo VARCHAR(50) NOT NULL,
    matricula VARCHAR(50) NOT NULL,
    carrera VARCHAR(100) NOT NULL,
    semestre VARCHAR(20) NOT NULL,
    estatus_grupo VARCHAR(20) NOT NULL,
    fecha_baja TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Mover a ba_grupos de manera directa
if(isset($_GET['baja'])){ 
    $id_baja = (int)$_GET['baja'];
    
    $query_select = mysqli_query($conexion, "SELECT * FROM grupos WHERE id_grupo = $id_baja");
    if($query_select && mysqli_num_rows($query_select) > 0){$alumno = mysqli_fetch_assoc($query_select);$id_g = (int)$alumno['id_grupo'];$nombre = mysqli_real_escape_string($conexion,$alumno['nombre_alumno']);
        $grupo = mysqli_real_escape_string($conexion, $alumno['grupo']);$matricula = mysqli_real_escape_string($conexion,$alumno['matricula']);
        $carrera = mysqli_real_escape_string($conexion, $alumno['carrera']);$semestre = mysqli_real_escape_string($conexion,$alumno['semestre']);

        $sql_insert = "INSERT INTO ba_grupos (id_grupo, nombre_alumno, grupo, matricula, carrera, semestre, estatus_grupo) 
                       VALUES ($id_g, '$nombre', '$grupo', '$matricula', '$carrera', '$semestre', 'Inactivo')";
        
        if(mysqli_query($conexion,$sql_insert)){
            mysqli_query($conexion, "DELETE FROM grupos WHERE id_grupo = $id_baja");
        }
    }
    header("Location: pro_grupos.php"); 
    exit;
}

// Guardar edición
if($_POST && isset($_POST['editar'])){
    $id = (int)$_POST['id'];
    $nombre_alumno = mysqli_real_escape_string($conexion, $_POST['nombre_alumno']);$grupo = mysqli_real_escape_string($conexion,$_POST['grupo']);
    $matricula = mysqli_real_escape_string($conexion, $_POST['matricula']);$carrera = mysqli_real_escape_string($conexion,$_POST['carrera']);
    $semestre = mysqli_real_escape_string($conexion, $_POST['semestre']);$estatus_grupo = mysqli_real_escape_string($conexion,$_POST['estatus_grupo']);

    $sql = "UPDATE grupos SET nombre_alumno='$nombre_alumno', grupo='$grupo', matricula='$matricula', carrera='$carrera', semestre='$semestre', estatus_grupo='$estatus_grupo' WHERE id_grupo=$id";
    mysqli_query($conexion,$sql); 
    header("Location: pro_grupos.php"); 
    exit;
}

// Cargar datos para editar
$editar = null;
if(isset($_GET['edit'])){
    $res = mysqli_query($conexion, "SELECT * FROM grupos WHERE id_grupo=" . (int)$_GET['edit']);
    if($res){
        $editar = mysqli_fetch_assoc($res);
    }
}

$grupos = mysqli_query($conexion, "SELECT * FROM grupos ORDER BY grupo");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset='UTF-8'>
    <title>Procesos Grupos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #fff5f7; color: #333; }
        h2, h3 { color: #00FFFF; }
        table { border-collapse: collapse; width: 100%; background: #fff; margin: 15px 0; }
        th { background: #00FFFF; color: #fff; padding: 10px; text-align: left; }
        td { border: 1px solid #fce8e6; padding: 10px; vertical-align: middle; }
        tr:nth-child(even) { background: #fff0f3; }

        .btn-act {
            display: inline-flex; align-items: center; justify-content: center;
            width: 28px; height: 28px; color: #fff !important;
            border-radius: 4px; text-decoration: none !important; font-size: 13px; margin-right: 3px;
        }
        .btn-add  { background-color: #27ae60; }
        .btn-edit { background-color: #0000FF; }
        .btn-del  { background-color: #dc3545; }

        form { background: #fff; padding: 15px; border: 2px solid #FF69B4; max-width: 500px; margin-bottom: 20px; }
        input, select { width: 100%; padding: 8px; margin: 5px 0 10px; border: 1px solid #ccc; box-sizing: border-box; }
        button { background: #00FFFF; color: #fff; border: none; padding: 10px 15px; cursor: pointer; }
        a { text-decoration: none; color: #00FFFF; }
    </style>
</head>
<body>

<h2>📋 Procesos — Grupos</h2>

<?php if($editar): ?>
<h3>✏️ Editar Grupo</h3>
<form method='post'>
    <input type='hidden' name='editar' value='1'>
    <input type='hidden' name='id' value='<?=$editar['id_grupo'] ?? ''?>'>
    
    Nombre del Alumno: <input type='text' name='nombre_alumno' value='<?=$editar['nombre_alumno'] ?? ''?>' required>
    Grupo: <input type='text' name='grupo' value='<?=$editar['grupo'] ?? ''?>' required>
    Matrícula: <input type='text' name='matricula' value='<?=$editar['matricula'] ?? ''?>' required>
    Carrera: <input type='text' name='carrera' value='<?=$editar['carrera'] ?? ''?>' required>
    Semestre: <input type='text' name='semestre' value='<?=$editar['semestre'] ?? ''?>' required>
    
    Estatus: 
    <select name='estatus_grupo'>
        <option value='Activo' <?=isset($editar['estatus_grupo']) &&$editar['estatus_grupo']=='Activo'?'selected':''?>>Activo</option>
        <option value='Inactivo' <?=isset($editar['estatus_grupo']) &&$editar['estatus_grupo']=='Inactivo'?'selected':''?>>Inactivo</option>
    </select>
    
    <button type='submit'>💾 Guardar Cambios</button>
</form>
<?php endif; ?>

<table>
    <tr>
        <th>ID</th>
        <th>Alumno</th>
        <th>Grupo</th>
        <th>Matrícula</th>
        <th>Carrera</th>
        <th>Semestre</th>
        <th>Estatus</th>
        <th style="width: 120px; text-align: center;">Acciones</th>
    </tr>
    <?php if($grupos && mysqli_num_rows($grupos) > 0): ?>
        <?php while($g = mysqli_fetch_assoc($grupos)): ?>
        <tr>
            <td><?=$g['id_grupo'] ?? ''?></td>
            <td><?=$g['nombre_alumno'] ?? ''?></td>
            <td><?=$g['grupo'] ?? ''?></td>
            <td><?=$g['matricula'] ?? ''?></td>
            <td><?=$g['carrera'] ?? ''?></td>
            <td><?=$g['semestre'] ?? ''?></td>
            <td><?=$g['estatus_grupo'] ?? ''?></td>
            <td style="text-align: center; white-space: nowrap;">
                <a href='../registro/reg_grupos.php' class='btn-act btn-add' title='Nuevo'><i class='fa fa-plus'></i></a>
                <a href='?edit=<?=$g['id_grupo'] ?? 0?>' class='btn-act btn-edit' title='Editar'><i class='fa fa-pen'></i></a>
                <a href='?baja=<?=$g['id_grupo'] ?? 0?>' class='btn-act btn-del' title='Dar de Baja'><i class='fa fa-trash'></i></a>
            </td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan='8' style='text-align:center;'>No hay registros guardados.</td>
        </tr>
    <?php endif; ?>
</table>

<div>
    <a href='../registro/reg_grupos.php'>➕ Nuevo Grupo</a> | 
    <a href='../Baja/ba_grupos.php'>📁 Bajas</a> | 
    <a href='../reportes/rep_grupos.php'>📄 Reporte</a> | 
    <a href='../index.php'>← Inicio</a>
</div>

</body>
</html>
