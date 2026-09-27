<?php
$servidor = "localhost"; 
$usuario = "root"; 
$clave = ""; 
$base = "Escuela"; 

// Se eliminó el puerto 3307 para conectar al puerto predeterminado de XAMPP (3306)
$conexion = mysqli_connect($servidor, $usuario, $clave, $base);
if (!$conexion) die("❌ Conexión: ".mysqli_connect_error());

if(isset($_GET['del'])){ 
    mysqli_query($conexion,"DELETE FROM materias WHERE id_mat=".(int)$_GET['del']); 
    header("Location: materias.php"); 
    exit; 
}

if($_POST && isset($_POST['editar'])){
    $id = (int)$_POST['id'];
    $sql = "UPDATE materias SET descripcion='{$_POST['descripcion']}', id_prof='{$_POST['id_prof']}' WHERE id_mat=$id";
    mysqli_query($conexion, $sql); 
    header("Location: materias.php"); 
    exit;
}

$editar = isset($_GET['edit']) ? mysqli_fetch_assoc(mysqli_query($conexion,"SELECT m.*, p.nom_prof, p.apaterno_prof FROM materias m JOIN profesores p ON m.id_prof=p.id_prof WHERE id_mat=".(int)$_GET['edit'])) : null;
$materias = mysqli_query($conexion,"SELECT m.*, CONCAT(p.apaterno_prof,' ',p.amaterno_prof,', ',p.nom_prof) AS profesor FROM materias m JOIN profesores p ON m.id_prof=p.id_prof ORDER BY descripcion");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Procesos Materias</title>
    <style>
        body{font-family:Arial;margin:20px;background:#f8f9fa}
        h2{color:#8e44ad}
        table{border-collapse:collapse;width:100%;background:#fff;margin:15px 0;box-shadow:0 0 10px #ccc}
        th{background:#8e44ad;color:#fff;padding:10px;text-align:left}
        td{border:1px solid #ddd;padding:10px}
        tr:nth-child(even){background:#f2f2f2}
        a{text-decoration:none}
        .edit{color:#e67e22;font-weight:bold}
        .del{color:#e74c3c;font-weight:bold}
        form{max-width:500px;background:#fff;padding:20px;border-radius:8px;box-shadow:0 0 10px #ccc;margin-bottom:20px}
        input,select{width:100%;padding:8px;margin:5px 0 12px;box-sizing:border-box;border:1px solid #ddd;border-radius:4px}
        button{padding:10px 20px;background:#8e44ad;color:#fff;border:none;border-radius:5px;cursor:pointer}
    </style>
</head>
<body>

<h2>📋 Procesos — Materias</h2>

<?php if($editar): ?>
<h3>✏️ Editar Materia</h3>
<form method='post'>
    <input type='hidden' name='editar'>
    <input type='hidden' name='id' value='<?=$editar['id_mat']?>'>
    
    Nombre de la Materia: <input type='text' name='descripcion' value='<?=$editar['descripcion']?>' required>
    
    Profesor: 
    <select name='id_prof' required>
        <?php
        $p = mysqli_query($conexion,"SELECT * FROM profesores ORDER BY apaterno_prof");
        while($opt = mysqli_fetch_assoc($p)){ 
            $sel = $opt['id_prof'] == $editar['id_prof'] ? "selected" : ""; 
            echo "<option value='{$opt['id_prof']}' $sel>{$opt['apaterno_prof']} {$opt['amaterno_prof']}, {$opt['nom_prof']}</option>"; 
        }
        ?>
    </select>
    
    <button type='submit'>💾 Guardar</button>
</form>
<?php endif; ?>

<table>
    <tr>
        <th>ID</th>
        <th>Materia</th>
        <th>Profesor</th>
        <th>Acciones</th>
    </tr>
    <?php while($m = mysqli_fetch_assoc($materias)): ?>
    <tr>
        <td><?=$m['id_mat']?></td>
        <td><?=$m['descripcion']?></td>
        <td><?=$m['profesor']?></td>
        <td>
            <a href='?edit=<?=$m['id_mat']?>' class='edit'>Editar</a> | 
            <a href='?del=<?=$m['id_mat']?>' class='del' onclick="return confirm('¿Eliminar?')">Eliminar</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<a href='../registro/materias.php'>➕ Nueva Materia</a> | 
<a href='../reportes/materias.php'>📄 Reporte</a> | 
<a href='../index.php'>← Inicio</a>

</body>
</html>