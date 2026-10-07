<?php
$servidor = "localhost"; 
$usuario = "root"; 
$clave = ""; 
$base = "Escuela"; 

$conexion = mysqli_connect($servidor, $usuario, $clave, $base);
if (!$conexion) {
    die("❌ Conexión: " . mysqli_connect_error());
}

$mensaje = "";
if ($_POST) {
    // Sanitización básica de campos
    $n  = mysqli_real_escape_string($conexion, $_POST['nombre']); 
    $a  = mysqli_real_escape_string($conexion, $_POST['apaterno']); 
    $am = mysqli_real_escape_string($conexion, $_POST['amaterno']);
    $d  = mysqli_real_escape_string($conexion, $_POST['dom']); 
    $m  = mysqli_real_escape_string($conexion, $_POST['mail']); 
    $t  = mysqli_real_escape_string($conexion, $_POST['tel']); 
    $g  = mysqli_real_escape_string($conexion, $_POST['id_grupo']);
    
    // Se especifican las columnas exactas a insertar, agregando estatus = 1 (Activo)
    $sql = "INSERT INTO alumnos (id, nombre, apaterno, amaterno, dom, mail, tel, id_grupo, estatus) 
            VALUES (NULL, '$n', '$a', '$am', '$d', '$m', '$t', '$g', 1)";

    $mensaje = mysqli_query($conexion, $sql) ? "✅ Alumno registrado" : "❌ Error: " . mysqli_error($conexion);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset='UTF-8'>
    <title>Registro Alumnos</title>
    <style>
        body{font-family:Arial;margin:30px;background:#fff5f7;color:#333}
        form{max-width:500px;background:#fff;padding:25px;border-radius:8px;box-shadow:0 0 10px #f8d7da;border-top:4px solid #d63384}
        input,select{width:100%;padding:8px;margin:5px 0 15px;box-sizing:border-box;border:1px solid #f1b0b7;border-radius:4px}
        button{padding:10px 20px;background:#d63384;color:#fff;border:none;border-radius:5px;cursor:pointer;font-weight:bold}
        button:hover{background:#c2185b}
        a{color:#d63384;text-decoration:none;display:inline-block;margin-top:10px;font-weight:bold}
        a:hover{text-decoration:underline;color:#a61e4d}
        p{padding:10px;border-radius:4px;font-weight:bold}
        .ok{background:#d4edda;color:#155724}
        .err{background:#f8d7da;color:#721c24}
    </style>
</head>
<body>

<h2>🎓 Registro de Alumnos</h2>

<?php if($mensaje){?>
    <p class="<?=strpos($mensaje,'✅')===0?'ok':'err'?>"><?=$mensaje?></p>
<?php }?>

<form method='post'>
    Nombre: <input type='text' name='nombre' required>
    Apellido Paterno: <input type='text' name='apaterno' required>
    Apellido Materno: <input type='text' name='amaterno'>
    Domicilio: <input type='text' name='dom'>
    Correo: <input type='email' name='mail'>
    Teléfono: <input type='text' name='tel'>
    
    Grupo: 
    <select name='id_grupo' required>
        <option value=''>-- Selecciona --</option>
        <?php
        $res = mysqli_query($conexion, "SELECT DISTINCT grupo FROM grupos ORDER BY grupo");
        if($res){
            while($g = mysqli_fetch_assoc($res)){
                echo "<option value='{$g['grupo']}'>{$g['grupo']}</option>";
            }
        }
        ?>
    </select>
    
    <button type='submit'>Guardar Alumno</button>
</form>

<a href='../procesos/pro_alumnos.php'>📋 Lista</a><br>
<a href='../index.php'>← Inicio</a>

</body>
</html>