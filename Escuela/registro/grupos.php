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

// 2. Crear la base de datos automáticamente si no existe
mysqli_query($conexion, "CREATE DATABASE IF NOT EXISTS `$base` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");

// 3. Seleccionar la base de datos
mysqli_select_db($conexion,$base);

// 4. Crear la tabla 'grupos' si no existe
mysqli_query($conexion, "CREATE TABLE IF NOT EXISTS grupos (
    id_grupo INT AUTO_INCREMENT PRIMARY KEY
)");

// Función segura para agregar columnas solo si no existen (evita el error de columna duplicada)
function agregarColumnaSiNoExiste($conexion,$tabla, $columna,$tipo) {
    $verificar = mysqli_query($conexion, "SHOW COLUMNS FROM `$tabla` LIKE '$columna'");
    if (mysqli_num_rows($verificar) == 0) {
        mysqli_query($conexion, "ALTER TABLE `$tabla` ADD COLUMN `$columna` $tipo");
    }
}

// Verificar y añadir columnas necesarias de forma segura
agregarColumnaSiNoExiste($conexion, 'grupos', 'nombre_alumno', 'VARCHAR(100) NOT NULL');
agregarColumnaSiNoExiste($conexion, 'grupos', 'grupo', 'VARCHAR(50) NOT NULL');
agregarColumnaSiNoExiste($conexion, 'grupos', 'matricula', 'VARCHAR(50) NOT NULL');
agregarColumnaSiNoExiste($conexion, 'grupos', 'carrera', 'VARCHAR(100) NOT NULL');
agregarColumnaSiNoExiste($conexion, 'grupos', 'semestre', 'VARCHAR(20) NOT NULL');
agregarColumnaSiNoExiste($conexion, 'grupos', 'estatus_grupo', 'VARCHAR(20) NOT NULL');

$mensaje = "";
if ($_POST) {$nom = mysqli_real_escape_string($conexion,$_POST['nombre_alumno']); 
    $grp = mysqli_real_escape_string($conexion, $_POST['grupo']);$mat = mysqli_real_escape_string($conexion,$_POST['matricula']);
    $car = mysqli_real_escape_string($conexion, $_POST['carrera']);$sem = mysqli_real_escape_string($conexion,$_POST['semestre']); 
    $est = mysqli_real_escape_string($conexion, $_POST['estatus_grupo']);$sql = "INSERT INTO grupos (nombre_alumno, grupo, matricula, carrera, semestre, estatus_grupo) 
            VALUES ('$nom', '$grp', '$mat', '$car', '$sem', '$est')";
            
    $mensaje = mysqli_query($conexion,$sql) ? "✅ Grupo registrado con éxito" : "❌ Error: " . mysqli_error($conexion);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Registro Grupos</title>
    <style>
        body{font-family:Arial;margin:30px;background:#f8f9fa}
        form{max-width:500px;background:#fff;padding:25px;border-radius:8px;box-shadow:0 0 10px #ccc}
        input,select{width:100%;padding:8px;margin:5px 0 15px;box-sizing:border-box;border:1px solid #ddd;border-radius:4px}
        button{padding:10px 20px;background:#27ae60;color:#fff;border:none;border-radius:5px;cursor:pointer}
        a{color:#2980b9;text-decoration:none;display:inline-block;margin-top:15px}
        p{padding:10px;border-radius:4px;font-weight:bold}
        .ok{background:#d4edda;color:#155724}
        .err{background:#f8d7da;color:#721c24}
    </style>
</head>
<body>

<h2>📚 Registro de Grupos</h2>

<?php if($mensaje): ?>
    <p class="<?=strpos($mensaje,'✅')===0?'ok':'err'?>"><?=$mensaje?></p>
<?php endif; ?>

<form method='post'>
    Nombre del Alumno: <input type='text' name='nombre_alumno' required>
    Grupo: <input type='text' name='grupo' placeholder='Ej: A1' required>
    Matrícula: <input type='text' name='matricula' required>
    Carrera: <input type='text' name='carrera' required>
    Semestre: <input type='text' name='semestre' required>
    
    Estatus: 
    <select name='estatus_grupo'>
        <option value='Activo'>Activo</option>
        <option value='Inactivo'>Inactivo</option>
    </select>
    
    <button type='submit'>Guardar Grupo</button>
</form>

<a href='../procesos/grupos.php'>📋 Ver Lista</a><br>
<a href='../index.php'>← Volver al Inicio</a>

</body>
</html>