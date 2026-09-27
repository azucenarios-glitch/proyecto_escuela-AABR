<?php
$servidor = "localhost"; 
$usuario = "root"; 
$clave = ""; 
$base = "Escuela"; 

// Se eliminó la variable del puerto 3307 para conectar correctamente al puerto predeterminado de XAMPP (3306)
$conexion = mysqli_connect($servidor, $usuario, $clave, $base);
if (!$conexion) die("❌ Conexión: ".mysqli_connect_error());

$mensaje = "";
if ($_POST) {
    $d = $_POST['descripcion']; 
    $p = $_POST['id_prof'];
    $sql = "INSERT INTO materias VALUES(NULL,'$d','$p')";
    $mensaje = mysqli_query($conexion,$sql) ? "✅ Materia registrada" : "❌ Error: ".mysqli_error($conexion);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Registro Materias</title>
    <style>
        body{font-family:Arial;margin:30px;background:#f8f9fa}
        form{max-width:500px;background:#fff;padding:25px;border-radius:8px;box-shadow:0 0 10px #ccc}
        input,select{width:100%;padding:8px;margin:5px 0 15px;box-sizing:border-box;border:1px solid #ddd;border-radius:4px}
        button{padding:10px 20px;background:#8e44ad;color:#fff;border:none;border-radius:5px;cursor:pointer}
        a{color:#2980b9;text-decoration:none;display:inline-block;margin-top:15px}
        p{padding:10px;border-radius:4px;font-weight:bold}
        .ok{background:#d4edda;color:#155724}
        .err{background:#f8d7da;color:#721c24}
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
        $res = mysqli_query($conexion,"SELECT * FROM profesores ORDER BY apaterno_prof");
        if($res){
            while($p = mysqli_fetch_assoc($res)){
                echo "<option value='{$p['id_prof']}'>{$p['apaterno_prof']} {$p['amaterno_prof']}, {$p['nom_prof']}</option>";
            }
        }
        ?>
    </select>
    
    <button type='submit'>Guardar Materia</button>
</form>

<a href='../procesos/materias.php'>📋 Ver Lista</a><br>
<a href='../index.php'>← Volver al Inicio</a>

</body>
</html>