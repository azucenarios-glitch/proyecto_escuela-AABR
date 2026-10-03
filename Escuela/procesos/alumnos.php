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

// Crear tablas si no existen
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

// Comprobación de seguridad para la columna 'estatus'
$check_column = mysqli_query($conexion, "SHOW COLUMNS FROM alumnos LIKE 'estatus'");
if (mysqli_num_rows($check_column) == 0) {
    mysqli_query($conexion, "ALTER TABLE alumnos ADD estatus INT DEFAULT 1");
}

// Dar de baja lógica (cambiar estatus a 0)
if (isset($_GET['del'])) { 
    $id_del = (int)$_GET['del'];
    mysqli_query($conexion, "UPDATE alumnos SET estatus = 0 WHERE id = $id_del"); 
    header("Location: pro_alumnos.php"); 
    exit; 
}

// Guardar edición
if ($_POST && isset($_POST['editar'])) {$id = (int)$_POST['id'];$nombre = mysqli_real_escape_string($conexion,$_POST['nombre']);
    $apaterno = mysqli_real_escape_string($conexion, $_POST['apaterno']);$amaterno = mysqli_real_escape_string($conexion,$_POST['amaterno']);
    $dom = mysqli_real_escape_string($conexion, $_POST['dom']);$mail = mysqli_real_escape_string($conexion,$_POST['mail']);
    $tel = mysqli_real_escape_string($conexion, $_POST['tel']);$id_grupo = mysqli_real_escape_string($conexion,$_POST['id_grupo']);

    $sql = "UPDATE alumnos SET nombre='$nombre', apaterno='$apaterno', amaterno='$amaterno', dom='$dom', mail='$mail', tel='$tel', id_grupo='$id_grupo' WHERE id=$id";
    mysqli_query($conexion,$sql); 
    header("Location: pro_alumnos.php"); 
    exit;
}

// Cargar registro para editar
$editar = null;
if (isset($_GET['edit'])) { 
    $res = mysqli_query($conexion, "SELECT * FROM alumnos WHERE id=" . (int)$_GET['edit']); 
    $editar = mysqli_fetch_assoc($res); 
}

// Consulta de alumnos activos
$alumnos = mysqli_query($conexion, "SELECT * FROM alumnos WHERE estatus = 1 ORDER BY apaterno");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset='UTF-8'>
    <title>Procesos Alumnos</title>
    <!-- Íconos para los botones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px; 
            background: #fff5f7; /* Fondo general rosa claro */
            color: #333; 
        }

        h2, h3 { 
            color: #FF69B4; /* Color de títulos principales (Rosa magenta) */
        }

        /* === 2. TABLA === */
        table { 
            border-collapse: collapse; 
            width: 100%; 
            background: #fff; 
            margin: 15px 0; 
        }

        th { 
            background: #FF69B4; /* Encabezado de la tabla (Rosa magenta) */
            color: #fff;         /* Texto blanco */
            padding: 10px; 
            text-align: left; 
        }

        td { 
            border: 1px solid #fce8e6; 
            padding: 10px; 
            vertical-align: middle;
        }

        tr:nth-child(even) { 
            background: #fff0f3; /* Filas pares (Rosa muy suave) */
        }

        /* === 3. COLORES DE LOS BOTONES === */
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

        .btn-add  { background-color: #27ae60; } /* Botón Añadir (Verde) */
        .btn-edit { background-color: #0000FF; } /* Botón Editar (Rosa magenta) */
        .btn-del  { background-color: #dc3545; } /* Botón Dar de Baja (Rojo) */

        /* === 4. FORMULARIO Y NAVEGACIÓN === */
        form { 
            background: #fff; 
            padding: 15px; 
            border: 2px solid #FF69B4; 
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
            background: #FF69B4; 
            color: #fff; 
            border: none; 
            padding: 10px 15px; 
            cursor: pointer; 
        }

        a { 
            text-decoration: none; 
            color: #FF69B4; 
        }
    </style>
</head>
<body>

<h2>Procesos — Alumnos</h2>

<?php if($editar): ?>
<h3>Editar Alumno</h3>
<form method='post'>
    <input type='hidden' name='editar' value='1'>
    <input type='hidden' name='id' value='<?=$editar['id']?>'>
    
    Nombre: <input type='text' name='nombre' value='<?=htmlspecialchars($editar['nombre'], ENT_QUOTES)?>' required>
    Apellido Paterno: <input type='text' name='apaterno' value='<?=htmlspecialchars($editar['apaterno'], ENT_QUOTES)?>' required>
    Apellido Materno: <input type='text' name='amaterno' value='<?=htmlspecialchars($editar['amaterno'], ENT_QUOTES)?>'>
    Domicilio: <input type='text' name='dom' value='<?=htmlspecialchars($editar['dom'], ENT_QUOTES)?>'>
    Correo: <input type='email' name='mail' value='<?=htmlspecialchars($editar['mail'], ENT_QUOTES)?>'>
    Teléfono: <input type='text' name='tel' value='<?=htmlspecialchars($editar['tel'], ENT_QUOTES)?>'>
    
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
    <a href="pro_alumnos.php" style="margin-left: 10px;">Cancelar</a>
</form>
<?php endif; ?>

<table>
    <tr>
        <th>ID</th>
        <th>Nombre Completo</th>
        <th>Correo</th>
        <th>Teléfono</th>
        <th>Grupo</th>
        <th>Estado</th>
        <th style="width: 120px; text-align: center;">Acciones</th>
    </tr>
    <?php while($a = mysqli_fetch_assoc($alumnos)): ?>
    <tr>
        <td><?=$a['id']?></td>
        <td><?=$a['apaterno']?> <?=$a['amaterno']?>, <?=$a['nombre']?></td>
        <td><?=$a['mail']?></td>
        <td><?=$a['tel']?></td>
        <td><?=$a['id_grupo']?></td>
        <td><strong>Activo</strong></td>
        <td style="text-align: center; white-space: nowrap;">
            <!-- Botón Añadir -->
            <a href='../registro/reg_alumno.php' class='btn-act btn-add' title='Añadir / Nuevo'><i class='fa fa-plus'></i></a>
            
            <!-- Botón Editar -->
            <a href='?edit=<?=$a['id']?>' class='btn-act btn-edit' title='Editar'><i class='fa fa-pen'></i></a>
            
            <!-- Botón Dar de baja -->
            <a href='?del=<?=$a['id']?>' class='btn-act btn-del' title='Dar de baja' onclick="return confirm('¿Dar de baja a este alumno?')"><i class='fa fa-trash'></i></a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<div>
    <a href='../registro/reg_alumno.php'>Nuevo Alumno</a> | 
    <a href='../Baja/ba_alumnos.php'>Alumnos en Baja</a> | 
    <a href='../reportes/rep_alumnos.php'>Reporte</a> | 
    <a href='../index.php'>← Inicio</a>
</div>

</body>
</html>
