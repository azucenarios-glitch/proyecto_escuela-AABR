<?php
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirmar_borrado'])) {
    $id_empleado = intval($_POST['id_empleado']);
    $mensaje = "El registro del empleado con ID $id_empleado ha sido eliminado correctamente.";
}
?>
<html>
<head>
    <title>Eliminación de Registros de Empleados</title>
</head>
<body>
    <h2>Eliminación de Registros de Empleados</h2>

    <?php if ($mensaje): ?>
        <p style="color:green; font-weight:bold;"><?php echo $mensaje; ?></p>
    <?php endif; ?>

    <form method="post" onsubmit="return confirmarBorrado()">
        <label>ID del empleado a eliminar:</label><br>
        <input type="number" name="id_empleado" required min="1" placeholder="Escribe el ID"><br><br>
        <input type="hidden" name="confirmar_borrado" value="1">
        <button type="submit">Eliminar Registro</button>
    </form>

    <script>
        function confirmarBorrado() {
            var respuesta = confirm("¿Está seguro de eliminar este registro?");
            if (respuesta == true) {
                return true;
            } else {
                alert("Operación cancelada. No se eliminó ningún registro.");
                return false;
            }
        }
    </script>
</body>
</html>