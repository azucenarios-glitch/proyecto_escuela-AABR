<?php
$servidor = "localhost"; 
$usuario = "root"; 
$clave = ""; 
$base = "Escuela"; 

// Se eliminó la variable del puerto 3307 para usar el puerto predeterminado de XAMPP (3306)
$conexion = mysqli_connect($servidor, $usuario, $clave, $base);
if (!$conexion) {
    die("❌ Conexión: " . mysqli_connect_error());
}

$mensaje = "";
if ($_POST) {
    $n = $_POST['nombre']; 
    $a = $_POST['apaterno']; 
    $am = $_POST['amaterno'];
    $d = $_POST['dom']; 
    $m = $_POST['mail']; 
    $t = $_POST['tel']; 
    $g = $_POST['id_grupo'];
    
    $sql = "INSERT INTO alumnos VALUES(NULL,'$n','$a','$am','$d','$m','$t','$g')";
    $mensaje = mysqli_query($conexion, $sql) ? "✅ Alumno registrado" : "❌ Error: " . mysqli_error($conexion);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Registro Alumnos</title>
    <style>
        body{font-family:Arial;margin:30px;background:#f8f9fa}
        form{max-width:500px;background:#fff;padding:25px;border-radius:8px;box-shadow:0 0 10px #ccc}
        input,select{width:100%;padding:8px;margin:5px 0 15px;box-sizing:border-box;border:1px solid #ddd;border-radius:4px}
        button{padding:10px 20px;background:#0864c0;color:#fff;border:none;border-radius:5px;cursor:pointer}
        a{color:#2980b9;text-decoration:none;display:inline-block;margin-top:15px}
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

<a href='../procesos/alumnos.php'>📋 Ver Lista</a><br>
<a href='../index.php'>← Volver al Inicio</a>

</body>
</html>