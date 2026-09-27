<?php
$servidor = "localhost"; 
$usuario = "root"; 
$clave = ""; 
$base = "Escuela"; 

// Naikkaten ti puerto 3307 tapno agtrabaho iti default a puerto ti XAMPP (3306)
$conexion = mysqli_connect($servidor, $usuario, $clave, $base);
if (!$conexion) {
    die("❌ Conexión fallida: " . mysqli_connect_error());
}

$alumnos = mysqli_query($conexion, "SELECT * FROM alumnos ORDER BY apaterno, amaterno");
$total = mysqli_num_rows($alumnos);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Reporte Alumnos</title>
    <style>
        body{font-family:Arial;margin:30px;background:#fff}
        h1{text-align:center;color:#0864c0;border-bottom:2px solid #0864c0;padding-bottom:10px}
        table{border-collapse:collapse;width:100%;margin-top:20px}
        th{background:#0864c0;color:#fff;padding:12px;text-align:left;border:1px solid #ccc}
        td{border:1px solid #ccc;padding:10px}
        tr:nth-child(even){background:#f9f9f9}
        .total{margin-top:15px;font-weight:bold;font-size:16px}
        @media print{button,a{display:none}}
    </style>
</head>
<body>

<h1>📄 REPORTE DE ALUMNOS</h1>
<table>
<tr>
    <th>ID</th>
    <th>Nombre Completo</th>
    <th>Domicilio</th>
    <th>Correo</th>
    <th>Teléfono</th>
    <th>Grupo</th>
</tr>
<?php while($a = mysqli_fetch_assoc($alumnos)): ?>
<tr>
    <td><?=$a['id']?></td>
    <td><?=$a['apaterno']?> <?=$a['amaterno']?>, <?=$a['nombre']?></td>
    <td><?=$a['dom']?></td>
    <td><?=$a['mail']?></td>
    <td><?=$a['tel']?></td>
    <td><?=$a['id_grupo']?></td>
</tr>
<?php endwhile; ?>
</table>

<p class='total'>Total de alumnos registrados: <?=$total?></p>
<button onclick='window.print()'>🖨️ Imprimir</button>
<a href='../procesos/alumnos.php'>← Volver</a>

</body>
</html>