<?php
$peso = $altura = $imc = $diagnostico = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $peso = floatval($_POST['peso']);
    $altura = floatval($_POST['altura']);

    if ($peso > 0 && $altura > 0) {
        $imc = $peso / ($altura * $altura);

        if ($imc < 18.5) {
            $diagnostico = "Bajo peso";
        } elseif ($imc >= 18.5 && $imc <= 24.9) {
            $diagnostico = "Peso normal";
        } elseif ($imc >= 25 && $imc <= 29.9) {
            $diagnostico = "Sobrepeso";
        } else {
            $diagnostico = "Obesidad";
        }
    } else {
        $diagnostico = "Ingresa valores mayores a cero.";
    }
}
?>
<html>
<head>
    <title>Calculadora de IMC</title>
</head>
<body>
    <h2>Índice de Masa Corporal (IMC)</h2>

    <form method="post">
        <label>Peso (kilogramos):</label><br>
        <input type="number" step="0.1" name="peso" required><br><br>

        <label>Altura (metros):</label><br>
        <input type="number" step="0.01" name="altura" required><br><br>

        <button type="submit">Calcular IMC</button>
    </form>

    <?php if ($imc !== ""): ?>
        <h3>Resultado:</h3>
        <p>IMC: <?php echo number_format($imc, 2); ?></p>
        <p>Diagnóstico: <?php echo $diagnostico; ?></p>
    <?php endif; ?>
</body>
</html>