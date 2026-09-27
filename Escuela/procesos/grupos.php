<?php
$servidor = "localhost"; 
$usuario = "root"; 
$clave = ""; 
$base = "Escuela"; 

// 1. Conexión inicial al servidor
$conexion = mysqli_connect($servidor, $usuario,$clave);
if (!$conexion) {
    die("❌ Conexión fallida: " . mysqli_connect_error());
}

// 2. Crear base de datos y seleccionar
mysqli_query($conexion, "CREATE DATABASE IF NOT EXISTS `$base` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
mysqli_select_db($conexion,$base);

// 3. Pannakaaramid ti tabla nga addaan iti umno a primary key
mysqli_query($conexion, "CREATE TABLE IF NOT EXISTS grupos (
    id_grupo INT AUTO_INCREMENT PRIMARY KEY,
    nombre_alumno VARCHAR(100) NOT NULL,
    grupo VARCHAR(50) NOT NULL,
    matricula VARCHAR(50) NOT NULL,
    carrera VARCHAR(100) NOT NULL,
    semestre VARCHAR(20) NOT NULL,
    estatus_grupo VARCHAR(20) NOT NULL
)");

// Eliminar registro
if(isset($_GET['del'])){ 
    $id_del = (int)$_GET['del'];
    mysqli_query($conexion, "DELETE FROM grupos WHERE id_grupo = $id_del"); 
    header("Location: grupos.php"); 
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
    header("Location: grupos.php"); 
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
<html>
<head>
    <meta charset='UTF-8'>
    <title>Procesos Grupos</title>
    <style>
        body{font-family:Arial;margin:20px;background:#f8f9fa}
        h2{color:#27ae60}
        table{border-collapse:collapse;width:100%;background:#fff;margin:15px 0;box-shadow:0 0 10px #ccc}
        th{background:#27ae60;color:#fff;padding:10px;text-align:left}
        td{border:1px solid #ddd;padding:10px}
        tr:nth-child(even){background:#f2f2f2}
        a{text-decoration:none}
        .edit{color:#e67e22;font-weight:bold}
        .del{color:#e74c3c;font-weight:bold}
        form{max-width:500px;background:#fff;padding:20px;border-radius:8px;box-shadow:0 0 10px #ccc;margin-bottom:20px}
        input,select{width:100%;padding:8px;margin:5px 0 12px;box-sizing:border-box;border:1px solid #ddd;border-radius:4px}
        button{padding:10px 20px;background:#27ae60;color:#fff;border:none;border-radius:5px;cursor:pointer}
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
    
    <button type='submit'>💾 Guardar</button>
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
        <th>Acciones</th>
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
            <td>
                <a href='?edit=<?=$g['id_grupo'] ?? 0?>' class='edit'>Editar</a> | 
                <a href='?del=<?=$g['id_grupo'] ?? 0?>' class='del' onclick="return confirm('¿Eliminar?')">Eliminar</a>
            </td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan='8' style='text-align:center;'>No hay registros guardados.</td>
        </tr>
    <?php endif; ?>
</table>

<a href='../registro/grupos.php'>➕ Nuevo Grupo</a> | 
<a href='../reportes/grupos.php'>📄 Reporte</a> | 
<a href='../index.php'>← Inicio</a>

</body>
</html>