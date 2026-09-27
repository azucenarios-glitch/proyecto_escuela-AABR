<?php
$servidor = "localhost"; 
$usuario = "root"; 
$clave = ""; 
$base = "Escuela"; 

// Se eliminó el puerto 3307 para conectar al puerto predeterminado de XAMPP (3306)
$conexion = mysqli_connect($servidor, $usuario, $clave, $base);
if (!$conexion) die("❌ Conexión: ".mysqli_connect_error());

if(isset($_GET['del'])){ 
    mysqli_query($conexion,"DELETE FROM profesores WHERE id_prof=".(int)$_GET['del']); 
    header("Location: profesores.php"); 
    exit; 
}

if($_POST && isset($_POST['editar'])){
    $id = (int)$_POST['id'];
    $sql = "UPDATE profesores SET nom_prof='{$_POST['nom_prof']}', apaterno_prof='{$_POST['apaterno_prof']}', amaterno_prof='{$_POST['amaterno_prof']}', dom_prof='{$_POST['dom_prof']}', mail_prof='{$_POST['mail_prof']}', tel_prof='{$_POST['tel_prof']}', estatus_prof='{$_POST['estatus_prof']}' WHERE id_prof=$id";
    mysqli_query($conexion, $sql); 
    header("Location: profesores.php"); 
    exit;
}

$editar = isset($_GET['edit']) ? mysqli_fetch_assoc(mysqli_query($conexion,"SELECT * FROM profesores WHERE id_prof=".(int)$_GET['edit'])) : null;
$profes = mysqli_query($conexion,"SELECT * FROM profesores ORDER BY apaterno_prof");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Procesos Profesores</title>
    <style>
        body{font-family:Arial;margin:20px;background:#f8f9fa}
        h2{color:#e67e22}
        table{border-collapse:collapse;width:100%;background:#fff;margin:15px 0;box-shadow:0 0 10px #ccc}
        th{background:#e67e22;color:#fff;padding:10px;text-align:left}
        td{border:1px solid #ddd;padding:10px}
        tr:nth-child(even){background:#f2f2f2}
        a{text-decoration:none}
        .edit{color:#e67e22;font-weight:bold}
        .del{color:#e74c3c;font-weight:bold}
        form{max-width:500px;background:#fff;padding:20px;border-radius:8px;box-shadow:0 0 10px #ccc;margin-bottom:20px}
        input,select{width:100%;padding:8px;margin:5px 0 12px;box-sizing:border-box;border:1px solid #ddd;border-radius:4px}
        button{padding:10px 20px;background:#e67e22;color:#fff;border:none;border-radius:5px;cursor:pointer}
    </style>
</head>
<body>

<h2>📋 Procesos — Profesores</h2>

<?php if($editar): ?>
<h3>✏️ Editar Profesor</h3>
<form method='post'>
    <input type='hidden' name='editar'>
    <input type='hidden' name='id' value='<?=$editar['id_prof']?>'>
    
    Nombre: <input type='text' name='nom_prof' value='<?=$editar['nom_prof']?>' required>
    Apellido Paterno: <input type='text' name='apaterno_prof' value='<?=$editar['apaterno_prof']?>' required>
    Apellido Materno: <input type='text' name='amaterno_prof' value='<?=$editar['amaterno_prof']?>'>
    Domicilio: <input type='text' name='dom_prof' value='<?=$editar['dom_prof']?>'>
    Correo: <input type='email' name='mail_prof' value='<?=$editar['mail_prof']?>'>
    Teléfono: <input type='text' name='tel_prof' value='<?=$editar['tel_prof']?>'>
    
    Estatus: 
    <select name='estatus_prof'>
        <option <?=$editar['estatus_prof']=='Activo'?'selected':''?>>Activo</option>
        <option <?=$editar['estatus_prof']=='Inactivo'?'selected':''?>>Inactivo</option>
    </select>
    
    <button type='submit'>💾 Guardar</button>
</form>
<?php endif; ?>

<table>
    <tr>
        <th>ID</th>
        <th>Nombre Completo</th>
        <th>Correo</th>
        <th>Teléfono</th>
        <th>Estatus</th>
        <th>Acciones</th>
    </tr>
    <?php while($p = mysqli_fetch_assoc($profes)): ?>
    <tr>
        <td><?=$p['id_prof']?></td>
        <td><?=$p['apaterno_prof']?> <?=$p['amaterno_prof']?>, <?=$p['nom_prof']?></td>
        <td><?=$p['mail_prof']?></td>
        <td><?=$p['tel_prof']?></td>
        <td><?=$p['estatus_prof']?></td>
        <td>
            <a href='?edit=<?=$p['id_prof']?>' class='edit'>Editar</a> | 
            <a href='?del=<?=$p['id_prof']?>' class='del' onclick="return confirm('¿Eliminar?')">Eliminar</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<a href='../registro/profesores.php'>➕ Nuevo Profesor</a> | 
<a href='../reportes/profesor.php'>📄 Reporte</a> | 
<a href='../index.php'>← Inicio</a>

</body>
</html>