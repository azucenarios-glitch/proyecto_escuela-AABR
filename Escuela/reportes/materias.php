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

// 2. Crear la base de datos si no existe
mysqli_query($conexion, "CREATE DATABASE IF NOT EXISTS `$base` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
mysqli_select_db($conexion,$base);

// 3. Crear las tablas necesarias automáticamente para evitar el error
mysqli_query($conexion, "CREATE TABLE IF NOT EXISTS profesores (
    id_prof INT AUTO_INCREMENT PRIMARY KEY,
    nom_prof VARCHAR(100) NOT NULL,
    apaterno_prof VARCHAR(100) NOT NULL,
    amaterno_prof VARCHAR(100) NOT NULL
)");

mysqli_query($conexion, "CREATE TABLE IF NOT EXISTS materias (
    id_mat INT AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(150) NOT NULL,
    id_prof INT NOT NULL,
    FOREIGN KEY (id_prof) REFERENCES profesores(id_prof)
)");

// 4. Consultar los datos de materias uniendo la tabla de profesores
$materias = mysqli_query($conexion, "SELECT m.*, CONCAT(p.apaterno_prof,' ',p.amaterno_prof,', ',p.nom_prof) AS profesor FROM materias m JOIN profesores p ON m.id_prof=p.id_prof ORDER BY descripcion");
$total = $materias ? mysqli_num_rows($materias) : 0;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Reporte Materias</title>
    <style>
        body{font-family:Arial;margin:30px;background:#fff}
        h1{text-align:center;color:#8e44ad;border-bottom:2px solid #8e44ad;padding-bottom:10px}
        table{border-collapse:collapse;width:100%;margin-top:20px}
        th{background:#8e44ad;color:#fff;padding:12px;text-align:left;border:1px solid #ccc}
        td{border:1px solid #ccc;padding:10px}
        tr:nth-child(even){background:#f9f9f9}
        .total{margin-top:15px;font-weight:bold}
        @media print{button,a{display:none}}
    </style>
</head>
<body>

<h1>📄 REPORTE DE MATERIAS</h1>
<table>
    <tr>
        <th>ID</th>
        <th>Materia</th>
        <th>Profesor Responsable</th>
    </tr>
    <?php if($materias && mysqli_num_rows($materias) > 0): ?>
        <?php while($m = mysqli_fetch_assoc($materias)): ?>
        <tr>
            <td><?=$m['id_mat']?></td>
            <td><?=$m['descripcion']?></td>
            <td><?=$m['profesor']?></td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan='3' style='text-align:center;'>No hay materias registradas todavía.</td>
        </tr>
    <?php endif; ?>
</table>

<p class='total'>Total de materias: <?=$total?></p>
<button onclick='window.print()'>🖨️ Imprimir</button>
<a href='../procesos/materias.php'>← Volver</a>

</body>
</html>