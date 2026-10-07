<?php
$servidor = "localhost"; 
$usuario = "root"; 
$clave = ""; 
$base = "Escuela"; 

// 1. Conexión inicial
$conexion = mysqli_connect($servidor, $usuario,$clave);
if (!$conexion) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// 2. Crear la base de datos automáticamente si no existe
$sql_db = "CREATE DATABASE IF NOT EXISTS `$base` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
mysqli_query($conexion,$sql_db);

// 3. Seleccionar la base de datos
mysqli_select_db($conexion,$base);

// Crear tabla si no existe
mysqli_query($conexion, "CREATE TABLE IF NOT EXISTS profesores (
    id_prof INT AUTO_INCREMENT PRIMARY KEY,
    nom_prof VARCHAR(50) NOT NULL,
    apaterno_prof VARCHAR(50) NOT NULL,
    amaterno_prof VARCHAR(50),
    dom_prof VARCHAR(100),
    mail_prof VARCHAR(100),
    tel_prof VARCHAR(20),
    estatus INT DEFAULT 1
)");

// Comprobación de seguridad para la columna 'estatus'
$check_column = mysqli_query($conexion, "SHOW COLUMNS FROM profesores LIKE 'estatus'");
if (mysqli_num_rows($check_column) == 0) {
    mysqli_query($conexion, "ALTER TABLE profesores ADD estatus INT DEFAULT 1");
}

// Dar de baja lógica (cambiar estatus a 0)
if (isset($_GET['del'])) { 
    $id_del = (int)$_GET['del'];
    mysqli_query($conexion, "UPDATE profesores SET estatus = 0 WHERE id_prof = $id_del"); 
    header("Location: pro_profesores.php"); 
    exit; 
}

// Guardar edición
if ($_POST && isset($_POST['editar'])) {
    $id = (int)$_POST['id'];
    $nombre = mysqli_real_escape_string($conexion, $_POST['nom_prof']);$apaterno = mysqli_real_escape_string($conexion,$_POST['apaterno_prof']);
    $amaterno = mysqli_real_escape_string($conexion, $_POST['amaterno_prof']);$dom = mysqli_real_escape_string($conexion,$_POST['dom_prof']);
    $mail = mysqli_real_escape_string($conexion, $_POST['mail_prof']);$tel = mysqli_real_escape_string($conexion,$_POST['tel_prof']);

    $sql = "UPDATE profesores SET 
                nom_prof='$nombre', 
                apaterno_prof='$apaterno', 
                amaterno_prof='$amaterno', 
                dom_prof='$dom', 
                mail_prof='$mail', 
                tel_prof='$tel' 
            WHERE id_prof=$id";
    mysqli_query($conexion,$sql); 
    header("Location: pro_profesores.php"); 
    exit;
}

// Cargar registro para editar
$editar = null;
if (isset($_GET['edit'])) { 
    $res = mysqli_query($conexion, "SELECT * FROM profesores WHERE id_prof=" . (int)$_GET['edit']); 
    $editar = mysqli_fetch_assoc($res); 
}

// Consulta de profesores activos
$profesores = mysqli_query($conexion, "SELECT * FROM profesores WHERE estatus = 1 ORDER BY apaterno_prof");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset='UTF-8'>
    <title>Procesos Profesores</title>
    <!-- Íconos para los botones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px; 
            background: #fff5f7; 
            color: #333; 
        }

        h2, h3 { 
            color: #8B4513; 
        }

        table { 
            border-collapse: collapse; 
            width: 100%; 
            background: #fff; 
            margin: 15px 0; 
        }

        th { 
            background: #8B4513; 
            color: #fff; 
            padding: 10px; 
            text-align: left; 
        }

        td { 
            border: 1px solid #fce8e6; 
            padding: 10px; 
            vertical-align: middle;
        }

        tr:nth-child(even) { 
            background: #fff0f3; 
        }

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
            margin-right: 3px;
        }

        .btn-add  { background-color: #27ae60; }
        .btn-edit { background-color: #0000FF; }
        .btn-del  { background-color: #dc3545; }

        form { 
            background: #fff; 
            padding: 15px; 
            border: 2px solid #8B4513; 
            max-width: 500px; 
            margin-bottom: 20px; 
        }

        input, select { 
            width: 100%; 
            padding: 8px; 
            margin: 5px 0 10px; 
            border: 1px solid #ccc; 
        }

        button { 
            background: #8B4513; 
            color: #fff; 
            border: none; 
            padding: 10px 15px; 
            cursor: pointer; 
        }

        a { 
            text-decoration: none; 
            color: #8B4513; 
        }
    </style>
</head>
<body>

<h2>Procesos — Profesores</h2>

<?php if($editar): ?>
<h3>Editar Profesor</h3>
<form method='post'>
    <input type='hidden' name='editar' value='1'>
    <input type='hidden' name='id' value='<?=$editar['id_prof']?>'>
    
    Nombre: <input type='text' name='nom_prof' value='<?=htmlspecialchars($editar['nom_prof'], ENT_QUOTES)?>' required>
    Apellido Paterno: <input type='text' name='apaterno_prof' value='<?=htmlspecialchars($editar['apaterno_prof'], ENT_QUOTES)?>' required>
    Apellido Materno: <input type='text' name='amaterno_prof' value='<?=htmlspecialchars($editar['amaterno_prof'], ENT_QUOTES)?>'>
    Domicilio: <input type='text' name='dom_prof' value='<?=htmlspecialchars($editar['dom_prof'], ENT_QUOTES)?>'>
    Correo: <input type='email' name='mail_prof' value='<?=htmlspecialchars($editar['mail_prof'], ENT_QUOTES)?>'>
    Teléfono: <input type='text' name='tel_prof' value='<?=htmlspecialchars($editar['tel_prof'], ENT_QUOTES)?>'>
    
    <button type='submit'>Guardar Cambios</button>
    <a href="pro_profesores.php" style="margin-left: 10px;">Cancelar</a>
</form>
<?php endif; ?>

<table>
    <tr>
        <th>ID</th>
        <th>Nombre Completo</th>
        <th>Correo</th>
        <th>Teléfono</th>
        <th>Estado</th>
        <th style="width: 120px; text-align: center;">Acciones</th>
    </tr>
    <?php while($p = mysqli_fetch_assoc($profesores)): ?>
    <tr>
        <td><?=$p['id_prof']?></td>
        <td><?=$p['apaterno_prof']?> <?=$p['amaterno_prof']?>, <?=$p['nom_prof']?></td>
        <td><?=$p['mail_prof']?></td>
        <td><?=$p['tel_prof']?></td>
        <td><strong>Activo</strong></td>
        <td style="text-align: center; white-space: nowrap;">
            <!-- Botón Añadir -->
            <a href='../registro/reg_profesores.php' class='btn-act btn-add' title='Añadir / Nuevo'><i class='fa fa-plus'></i></a>
            
            <!-- Botón Editar -->
            <a href='?edit=<?=$p['id_prof']?>' class='btn-act btn-edit' title='Editar'><i class='fa fa-pen'></i></a>
            
            <!-- Botón Dar de baja -->
            <a href='?del=<?=$p['id_prof']?>' class='btn-act btn-del' title='Dar de baja' onclick="return confirm('¿Dar de baja a este profesor?')"><i class='fa fa-trash'></i></a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<div>
    <a href='../registro/reg_profesores.php'>Nuevo Profesor</a> | 
    <a href='../Baja/ba_profesores.php'>Profesores en Baja</a> | 
    <a href='../reportes/rep_profesores.php'>Reporte</a> | 
    <a href='../index.php'>← Inicio</a>
</div>

</body>
</html>