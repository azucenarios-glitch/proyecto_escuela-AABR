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
    $d = mysqli_real_escape_string($conexion, $_POST['descripcion']); 
    $p = mysqli_real_escape_string($conexion, $_POST['id_prof']);
    
    // Inserción incluyendo columna estatus = 1 (Activo)
    $sql = "INSERT INTO materias (id_mat, descripcion, id_prof, estatus) 
            VALUES (NULL, '$d', '$p', 1)";

    $mensaje = mysqli_query($conexion, $sql) ? "✅ Materia registrada" : "❌ Error: " . mysqli_error($conexion);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset='UTF-8'>
    <title>Registro Materias</title>
    <style>
        body{font-family:Arial;margin:30px;background: #fff5f7;color:#333}
        form{max-width:500px;background:#fff;padding:25px;border-radius:8px;box-shadow:0 0 10px #f8d7da;border-top:4px solid #00FF00}
        input,select{width:100%;padding:8px;margin:5px 0 15px;box-sizing:border-box;border:1px solid #f1b0b7;border-radius:4px}
        button{padding:10px 20px;background: #00FF00;color:#fff;border:none;border-radius:5px;cursor:pointer;font-weight:bold}
        button:hover{background: #00FF00}
        a{color: #00FF00;text-decoration:none;display:inline-block;margin-top:10px;font-weight:bold}
        a:hover{text-decoration:underline;color: #00FF00}
        p{padding:10px;border-radius:4px;font-weight:bold}
        .ok{background: #d4edda;color: #155724}
        .err{background: #f8d7da;color: #00FF00}
    </style>
</head>
<body>

<h2>📖 Registro de Materias</h2>

<?php if($mensaje){?>
    <p class="<?=strpos($mensaje,'✅')===0?'ok':'err'?>"><?=$mensaje?></p>
<?php }?>

<form method='post'>
    Nombre de la Materia: <input type='text' name='descripcion' required>
    
    Profesor: 
    <select name='id_prof' required>
        <option value=''>-- Selecciona --</option>
        <?php
        $res = mysqli_query($conexion, "SELECT * FROM profesores ORDER BY apaterno_prof");
        if($res){
            while($p = mysqli_fetch_assoc($res)){
                echo "<option value='{$p['id_prof']}'>{$p['apaterno_prof']} {$p['amaterno_prof']}, {$p['nom_prof']}</option>";
            }
        }
        ?>
    </select>
    
    <button type='submit'>Guardar Materia</button>
</form>

<a href='../procesos/pro_materias.php'>📋 Lista</a><br>
<a href='../index.php'>← Inicio</a>

</body>
</html>