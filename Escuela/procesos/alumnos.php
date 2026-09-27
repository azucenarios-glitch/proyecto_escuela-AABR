<?php
$servidor = "localhost"; 
$usuario = "root"; 
$clave = ""; 
$base = "Escuela"; 

// 1. Conexión inicial sin seleccionar base de datos para verificar/crear el servidor
$conexion = mysqli_connect($servidor, $usuario,$clave);
if (!$conexion) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// 2. Crear la base de datos automáticamente si no existe para evitar errores
$sql_db = "CREATE DATABASE IF NOT EXISTS `$base` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
mysqli_query($conexion,$sql_db);

// 3. Seleccionar la base de datos
mysqli_select_db($conexion,$base);

// (Opcional) Crear tabla 'alumnos' y 'grupos' si no existen para que el sistema funcione de inmediato
mysqli_query($conexion, "CREATE TABLE IF NOT EXISTS grupos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    grupo VARCHAR(50) NOT NULL
)");

mysqli_query($conexion, "CREATE TABLE IF NOT EXISTS alumnos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apaterno VARCHAR(50) NOT NULL,
    amaterno VARCHAR(50),
    dom VARCHAR(100),
    mail VARCHAR(100),
    tel VARCHAR(20),
    id_grupo VARCHAR(50)
)");

// Eliminar
if (isset($_GET['del'])) { 
    mysqli_query($conexion, "DELETE FROM alumnos WHERE id=" . (int)$_GET['del']); 
    header("Location: alumnos.php"); 
    exit; 
}

// Guardar edición
if ($_POST && isset($_POST['editar'])) {$id = (int)$_POST['id'];$nombre = mysqli_real_escape_string($conexion,$_POST['nombre']);
    $apaterno = mysqli_real_escape_string($conexion, $_POST['apaterno']);$amaterno = mysqli_real_escape_string($conexion,$_POST['amaterno']);
    $dom = mysqli_real_escape_string($conexion, $_POST['dom']);$mail = mysqli_real_escape_string($conexion,$_POST['mail']);
    $tel = mysqli_real_escape_string($conexion, $_POST['tel']);$id_grupo = mysqli_real_escape_string($conexion,$_POST['id_grupo']);

    $sql = "UPDATE alumnos SET nombre='$nombre', apaterno='$apaterno', amaterno='$amaterno', dom='$dom', mail='$mail', tel='$tel', id_grupo='$id_grupo' WHERE id=$id";
    mysqli_query($conexion,$sql); 
    header("Location: alumnos.php"); 
    exit;
}

// Cargar para editar
$editar = null;
if (isset($_GET['edit'])) { 
    $res = mysqli_query($conexion, "SELECT * FROM alumnos WHERE id=" . (int)$_GET['edit']); 
    $editar = mysqli_fetch_assoc($res); 
}

$alumnos = mysqli_query($conexion, "SELECT * FROM alumnos ORDER BY apaterno");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Procesos Alumnos</title>
    <style>
        body{font-family:Arial;margin:20px;background:#f8f9fa}
        h2{color:#0864c0}
        table{border-collapse:collapse;width:100%;background:#fff;margin:15px 0;box-shadow:0 0 10px #ccc}
        th{background:#0864c0;color:#fff;padding:10px;text-align:left}
        td{border:1px solid #ddd;padding:10px}
        tr:nth-child(even){background:#f2f2f2}
        a{text-decoration:none}
        .edit{color:#e67e22;font-weight:bold}
        .del{color:#e74c3c;font-weight:bold}
        form{max-width:500px;background:#fff;padding:20px;border-radius:8px;box-shadow:0 0 10px #ccc;margin-bottom:20px}
        input,select{width:100%;padding:8px;margin:5px 0 12px;box-sizing:border-box;border:1px solid #ddd;border-radius:4px}
        button{padding:10px 20px;background:#0864c0;color:#fff;border:none;border-radius:5px;cursor:pointer}
    </style>
</head>
<body>

<h2>Procesos — Alumnos</h2>

<?php if($editar): ?>
<h3>Editar Alumno</h3>
<form method='post'>
    <input type='hidden' name='editar' value='1'>
    <input type='hidden' name='id' value='<?=$editar['id']?>'>
    
    Nombre: <input type='text' name='nombre' value='<?=$editar['nombre']?>' required>
    Apellido Paterno: <input type='text' name='apaterno' value='<?=$editar['apaterno']?>' required>
    Apellido Materno: <input type='text' name='amaterno' value='<?=$editar['amaterno']?>'>
    Domicilio: <input type='text' name='dom' value='<?=$editar['dom']?>'>
    Correo: <input type='email' name='mail' value='<?=$editar['mail']?>'>
    Teléfono: <input type='text' name='tel' value='<?=$editar['tel']?>'>
    
    Grupo: 
    <select name='id_grupo' required>
        <?php
        $g = mysqli_query($conexion, "SELECT DISTINCT grupo FROM grupos ORDER BY grupo");
        while($opt = mysqli_fetch_assoc($g)){$sel = ($opt['grupo'] ==$editar['id_grupo']) ? "selected" : ""; 
            echo "<option value='{$opt['grupo']}' $sel>{$opt['grupo']}</option>"; 
        }
        ?>
    </select>
    
    <button type='submit'>Guardar Cambios</button>
</form>
<?php endif; ?>

<table>
    <tr>
        <th>ID</th>
        <th>Nombre Completo</th>
        <th>Correo</th>
        <th>Teléfono</th>
        <th>Grupo</th>
        <th>Acciones</th>
    </tr>
    <?php while($a = mysqli_fetch_assoc($alumnos)): ?>
    <tr>
        <td><?=$a['id']?></td>
        <td><?=$a['apaterno']?> <?=$a['amaterno']?>, <?=$a['nombre']?></td>
        <td><?=$a['mail']?></td>
        <td><?=$a['tel']?></td>
        <td><?=$a['id_grupo']?></td>
        <td>
            <a href='?edit=<?=$a['id']?>' class='edit'>Editar</a> | 
            <a href='?del=<?=$a['id']?>' class='del' onclick="return confirm('¿Eliminar?')">Eliminar</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<a href='../registro/alumnos.php'>Nuevo Alumno</a> | 
<a href='../reportes/alumnos.php'>Reporte</a> | 
<a href='../index.php'>← Inicio</a>

</body>
</html>