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
    $n  = mysqli_real_escape_string($conexion, $_POST['nom_prof']); 
    $a  = mysqli_real_escape_string($conexion, $_POST['apaterno_prof']); 
    $am = mysqli_real_escape_string($conexion, $_POST['amaterno_prof']);
    $d  = mysqli_real_escape_string($conexion, $_POST['dom_prof']); 
    $m  = mysqli_real_escape_string($conexion, $_POST['mail_prof']); 
    $t  = mysqli_real_escape_string($conexion, $_POST['tel_prof']); 
    
    // Inserción asignando estatus = 1 (Activo)
    $sql = "INSERT INTO profesores (id_prof, nom_prof, apaterno_prof, amaterno_prof, dom_prof, mail_prof, tel_prof, estatus) 
            VALUES (NULL, '$n', '$a', '$am', '$d', '$m', '$t', 1)";

    $mensaje = mysqli_query($conexion, $sql) ? "✅ Profesor registrado" : "❌ Error: " . mysqli_error($conexion);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset='UTF-8'>
    <title>Registro Profesores</title>
    <style>
        body{font-family:Arial;margin:30px;background: #fff5f7;color: #333}
        form{max-width:500px;background:#fff;padding:25px;border-radius:8px;box-shadow:0 0 10px #f8d7da;border-top:4px solid #8B4513}
        input,select{width:100%;padding:8px;margin:5px 0 15px;box-sizing:border-box;border:1px solid #f1b0b7;border-radius:4px}
        button{padding:10px 20px;background: #8B4513;color:#fff;border:none;border-radius:5px;cursor:pointer;font-weight:bold}
        button:hover{background: #8B4513}
        a{color: #8B4513;text-decoration:none;display:inline-block;margin-top:10px;font-weight:bold}
        a:hover{text-decoration:underline;color: #8B4513}
        p{padding:10px;border-radius:4px;font-weight:bold}
        .ok{background: #d4edda;color: #155724}
        .err{background: #f8d7da;color: #721c24}
    </style>
</head>
<body>

<h2> Registro de Profesores</h2>

<?php if($mensaje){?>
    <p class="<?=strpos($mensaje,'✅')===0?'ok':'err'?>"><?=$mensaje?></p>
<?php }?>

<form method='post'>
    Nombre: <input type='text' name='nom_prof' required>
    Apellido Paterno: <input type='text' name='apaterno_prof' required>
    Apellido Materno: <input type='text' name='amaterno_prof'>
    Domicilio: <input type='text' name='dom_prof'>
    Correo: <input type='email' name='mail_prof'>
    Teléfono: <input type='text' name='tel_prof'>
    
    <button type='submit'>Guardar Profesor</button>
</form>

<a href='../procesos/pro_profesores.php'>📋 Lista</a><br>
<a href='../index.php'>← Inicio</a>

</body>
</html>