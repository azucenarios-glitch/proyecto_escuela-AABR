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

$mensaje = "";
if ($_POST) {
    $nom = mysqli_real_escape_string($conexion, $_POST['nombre_alumno']);$grp = mysqli_real_escape_string($conexion,$_POST['grupo']);
    $mat = mysqli_real_escape_string($conexion, $_POST['matricula']);$car = mysqli_real_escape_string($conexion,$_POST['carrera']);
    $sem = mysqli_real_escape_string($conexion, $_POST['semestre']);$est = mysqli_real_escape_string($conexion,$_POST['estatus_grupo']);

    $sql = "INSERT INTO grupos (nombre_alumno, grupo, matricula, carrera, semestre, estatus_grupo) 
            VALUES ('$nom', '$grp', '$mat', '$car', '$sem', '$est')";
            
    $mensaje = mysqli_query($conexion,$sql) ? "✅ Grupo registrado con éxito" : "❌ Error: " . mysqli_error($conexion);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset='UTF-8'>
    <title>Registro Grupos</title>
    <style>
        body{font-family:Arial;margin:30px;background:#fff5f7;color:#333}
        form{max-width:500px;background:#fff;padding:25px;border-radius:8px;box-shadow:0 0 10px #f8d7da;border-top:4px solid #00FFFF}
        input,select{width:100%;padding:8px;margin:5px 0 15px;box-sizing:border-box;border:1px solid #f1b0b7;border-radius:4px}
        button{padding:10px 20px;background: #00FFFF;color:#fff;border:none;border-radius:5px;cursor:pointer;font-weight:bold}
        button:hover{background: #00FFFF}
        a{color: #00FFFF;text-decoration:none;display:inline-block;margin-top:10px;font-weight:bold}
        a:hover{text-decoration:underline;color: #a61e4d}
        p{padding:10px;border-radius:4px;font-weight:bold}
        .ok{background: #d4edda;color: #155724}
        .err{background: #f8d7da;color: #721c24}
    </style>
</head>
<body>

<h2>👥 Registro de Grupos</h2>

<?php if($mensaje): ?>
    <p class="<?=strpos($mensaje,'✅')===0?'ok':'err'?>"><?=$mensaje?></p>
<?php endif; ?>

<form method='post'>
    Alumno: <input type='text' name='nombre_alumno' required>
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

<a href='../procesos/pro_grupos.php'>📋 Ver Lista</a><br>
<a href='../index.php'>← Volver al Inicio</a>

</body>
</html>