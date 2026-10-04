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

// 2. Crear base de datos si no existe
$sql_db = "CREATE DATABASE IF NOT EXISTS `$base` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
mysqli_query($conexion,$sql_db);

// 3. Seleccionar la base de datos
mysqli_select_db($conexion,$base);

// Crear tablas si no existen
mysqli_query($conexion, "CREATE TABLE IF NOT EXISTS profesores (
    id_prof INT AUTO_INCREMENT PRIMARY KEY,
    nom_prof VARCHAR(50) NOT NULL,
    apaterno_prof VARCHAR(50) NOT NULL,
    amaterno_prof VARCHAR(50)
)");

mysqli_query($conexion, "CREATE TABLE IF NOT EXISTS materias (
    id_mat INT AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(100) NOT NULL,
    id_prof INT NOT NULL
)");

// Comprobación de seguridad para la columna 'estatus'
$check_column = mysqli_query($conexion, "SHOW COLUMNS FROM materias LIKE 'estatus'");
if (mysqli_num_rows($check_column) == 0) {
    mysqli_query($conexion, "ALTER TABLE materias ADD estatus INT DEFAULT 1");
}

// Dar de baja lógica (cambiar estatus a 0)
if (isset($_GET['del'])) { 
    $id_del = (int)$_GET['del'];
    mysqli_query($conexion, "UPDATE materias SET estatus = 0 WHERE id_mat = $id_del"); 
    header("Location: pro_materias.php"); 
    exit; 
}

// Guardar edición
if ($_POST && isset($_POST['editar'])) {
    $id = (int)$_POST['id'];
    $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);$id_prof = mysqli_real_escape_string($conexion,$_POST['id_prof']);

    $sql = "UPDATE materias SET descripcion='$descripcion', id_prof='$id_prof' WHERE id_mat=$id";
    mysqli_query($conexion,$sql); 
    header("Location: pro_materias.php"); 
    exit;
}

// Cargar registro para editar
$editar = null;
if (isset($_GET['edit'])) { 
    $res = mysqli_query($conexion, "SELECT m.*, p.nom_prof, p.apaterno_prof FROM materias m LEFT JOIN profesores p ON m.id_prof=p.id_prof WHERE m.id_mat=" . (int)$_GET['edit']); 
    $editar = mysqli_fetch_assoc($res); 
}

// Consulta de materias activas
$materias = mysqli_query($conexion, "SELECT m.*, CONCAT(p.apaterno_prof, ' ', p.amaterno_prof, ', ', p.nom_prof) AS profesor 
                                     FROM materias m 
                                     LEFT JOIN profesores p ON m.id_prof = p.id_prof 
                                     WHERE m.estatus = 1 
                                     ORDER BY m.descripcion");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset='UTF-8'>
    <title>Procesos Materias</title>
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
            color: #00FF00; 
        }

        /* === TABLA === */
        table { 
            border-collapse: collapse; 
            width: 100%; 
            background: #fff; 
            margin: 15px 0; 
        }

        th { 
            background: #00FF00; 
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

        /* === COLORES DE LOS BOTONES === */
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

        /* === FORMULARIO Y NAVEGACIÓN === */
        form { 
            background: #fff; 
            padding: 15px; 
            border: 2px solid #00FF00; 
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
            background: #00FF00; 
            color: #fff; 
            border: none; 
            padding: 10px 15px; 
            cursor: pointer; 
        }

        a { 
            text-decoration: none; 
            color: #00FF00; 
        }
    </style>
</head>
<body>

<h2>Procesos — Materias</h2>

<?php if($editar): ?>
<h3>Editar Materia</h3>
<form method='post'>
    <input type='hidden' name='editar' value='1'>
    <input type='hidden' name='id' value='<?=$editar['id_mat']?>'>
    
    Nombre de la Materia: <input type='text' name='descripcion' value='<?=htmlspecialchars($editar['descripcion'], ENT_QUOTES)?>' required>
    
    Profesor: 
    <select name='id_prof' required>
        <?php
        $p = mysqli_query($conexion, "SELECT * FROM profesores ORDER BY apaterno_prof");
        while($opt = mysqli_fetch_assoc($p)){$sel = ($opt['id_prof'] ==$editar['id_prof']) ? "selected" : ""; 
            echo "<option value='{$opt['id_prof']}' $sel>{$opt['apaterno_prof']} {$opt['amaterno_prof']}, {$opt['nom_prof']}</option>"; 
        }
        ?>
    </select>
    
    <button type='submit'>Guardar Cambios</button>
    <a href="pro_materias.php" style="margin-left: 10px;">Cancelar</a>
</form>
<?php endif; ?>

<table>
    <tr>
        <th>ID</th>
        <th>Nombre de la Materia</th>
        <th>Profesor Asignado</th>
        <th>Estado</th>
        <th style="width: 120px; text-align: center;">Acciones</th>
    </tr>
    <?php while($m = mysqli_fetch_assoc($materias)): ?>
    <tr>
        <td><?=$m['id_mat']?></td>
        <td><?=$m['descripcion']?></td>
        <td><?=$m['profesor'] ?$m['profesor'] : '<em>Sin asignar</em>'?></td>
        <td><strong>Activo</strong></td>
        <td style="text-align: center; white-space: nowrap;">
            <!-- Botón Añadir -->
            <a href='../registro/reg_materias.php' class='btn-act btn-add' title='Añadir / Nueva'><i class='fa fa-plus'></i></a>
            
            <!-- Botón Editar -->
            <a href='?edit=<?=$m['id_mat']?>' class='btn-act btn-edit' title='Editar'><i class='fa fa-pen'></i></a>
            
            <!-- Botón Dar de baja -->
            <a href='?del=<?=$m['id_mat']?>' class='btn-act btn-del' title='Dar de baja' onclick="return confirm('¿Dar de baja esta materia?')"><i class='fa fa-trash'></i></a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<div>
    <a href='../registro/reg_materias.php'>Nueva Materia</a> | 
    <a href='../Baja/ba_materias.php'>Materias en Baja</a> | 
    <a href='../reportes/rep_materias.php'>Reporte</a> | 
    <a href='../index.php'>← Inicio</a>
</div>

</body>
</html>
