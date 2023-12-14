<?php
// Suponiendo que ya tienes el valor de 'mes' de alguna manera (por ejemplo, desde una solicitud POST o GET)
$mes = $_GET['mes'] ?? 0; // O usa $_POST si es apropiado
$nombreMes = $meses[$mes]; // Asegúrate de tener un array $meses definido

$tablaMes = "<table border='1'>"
          . "<caption>Tabla del mes de " . htmlspecialchars($nombreMes) . "</caption>"
          . "<tr>"
          . "<th>Rango Inicial</th>"
          . "<th>Rango Final</th>"
          . "<th>Valor A</th>"
          . "<th>Valor B</th>"
          . "</tr>";

// Asegúrate de tener un array $sat definido
for ($i = 0; $i < count($sat); $i++) {
    $tablaMes .= "<tr>"
               . "<td>" . ($mes == 1 ? $sat[$i][0] : ($sat[$i][0] - 0.01) * $mes + 0.01) . "</td>"
               . "<td>" . $sat[$i][1] * $mes . "</td>"
               . "<td>" . $sat[$i][2] * $mes . "</td>"
               . "<td>" . $sat[$i][3] * $mes . "</td>"
               . "</tr>";
}

$tablaMes .= "</table>"
           . "<br>"
           . "<div>"
           . "<h4>Ingreso Bruto</h4>"
           . "<label>Ingresa el Ingreso</label>"
           . "<input id='ingreso' type='number'>"
           . "<input onclick='calcular()' type='button' value='Calcular'>"
           . "</div>";

echo $tablaMes;
?>
