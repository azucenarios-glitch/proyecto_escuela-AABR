<?php
$libros = [
    "LIB001" => ["titulo" => "Programación en PHP y MySQL", "autor" => "Juan Pérez", "editorial" => "Alfaomega", "anio" => 2022],
    "LIB002" => ["titulo" => "Bases de Datos Relacionales", "autor" => "María López", "editorial" => "McGraw-Hill", "anio" => 2020],
    "LIB003" => ["titulo" => "Desarrollo Web HTML y CSS", "autor" => "Carlos Ruiz", "editorial" => "Pearson", "anio" => 2021],
    "LIB004" => ["titulo" => "Ingeniería de Software", "autor" => "Ana García", "editorial" => "Alfaomega", "anio" => 2019]
];

$resultado = [];
$busqueda = "";

if (isset($_GET['buscar']) && !empty(trim($_GET['buscar']))) {
    $busqueda = trim($_GET['buscar']);
    foreach ($libros as $codigo => $datos) {
        if (stripos($datos['titulo'], $busqueda) !== false || stripos($datos['autor'], $busqueda) !== false) {
            $resultado[$codigo] = $datos;
        }
    }
}
?>
<html>
<head>
    <title>Catálogo de Libros — Biblioteca TecNM</title>
</head>
<body>
    <h2>Catálogo de Libros — Biblioteca TecNM</h2>

    <form method="get">
        <label>Buscar por Título o Autor:</label><br>
        <input type="text" name="buscar" placeholder="Ejemplo: PHP, López..." value="<?php echo htmlspecialchars($busqueda); ?>">
        <button type="submit">Buscar</button>
    </form>

    <?php if (!empty($busqueda)): ?>
        <h3>Resultados para: "<?php echo htmlspecialchars($busqueda); ?>"</h3>

        <?php if (count($resultado) > 0): ?>
            <table border="1" cellpadding="8">
                <tr>
                    <th>Código</th>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Editorial</th>
                    <th>Año</th>
                </tr>
                <?php foreach ($resultado as $codigo => $libro): ?>
                <tr>
                    <td><?php echo $codigo; ?></td>
                    <td><?php echo $libro['titulo']; ?></td>
                    <td><?php echo $libro['autor']; ?></td>
                    <td><?php echo $libro['editorial']; ?></td>
                    <td><?php echo $libro['anio']; ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No se encontraron libros que coincidan con tu búsqueda.</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>