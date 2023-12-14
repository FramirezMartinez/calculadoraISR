<?php

class ISR {
    private $isr;
    private $meses;
    private $tablaMes;
    private $mes;

    public function __construct() {
        $this->isr = [
            [0.01, 746.04, 0.00, 1.92],
            [746.05, 6332.05, 14.32, 6.40],
            [6332.06, 11128.01, 371.83, 10.88],
            [11128.02, 12935.82, 893.63, 16.00],
            [12935.83, 15487.71, 1182.88, 17.92],
            [15487.72, 31236.49, 1640.18, 21.36],
            [31236.50, 49233.00, 5004.12, 23.52],
            [49233.01, 93993.90, 9236.89, 30.00],
            [93993.91, 125325.20, 22665.17, 32.00],
            [125325.21, 375975.61, 32691.18, 34.00],
            [375975.62, -1, 117912.32, 35.00]
        ];

        $this->meses = [
            "Ningún mes",
            "Enero", "Febrero", "Marzo",
            "Abril", "Mayo", "Junio",
            "Julio", "Agosto", "Septiembre",
            "Octubre", "Noviembre", "Diciembre"
        ];

        $this->tablaMes = [];
    }

    public function generar($mes) {
        $this->mes = $mes;
        $r = "<div class=\"caja\">";
        $r .= "<div class=\"container\">";

        if ($mes == 0) {
            $r .= "<div class=\"alert alert-danger\">No seleccionó un mes</div>";
        } else {
            $r .= "<table class=\"table table-bordered\">";
            $r .= "<caption><b>ISR mensual mes de  " . htmlspecialchars($this->meses[$mes]) . " 2023</b></caption>";
            $r .= "<br>";

            $r .= "<tr class=\"table-primary\">";
            $r .= "<th>Límite inferior</th>";
            $r .= "<th>Límite superior</th>";
            $r .= "<th>Cuota fija</th>";
            $r .= "<th>% Excedente lím. inf</th>";
            $r .= "</tr>";

            for ($i = 0; $i < count($this->isr); $i++) {
                $this->tablaMes[$i][0] = ($this->isr[$i][0] - 0.01) * $mes + 0.01;
                $this->tablaMes[$i][1] = $this->isr[$i][1] * $mes;
                $this->tablaMes[$i][2] = $this->isr[$i][2] * $mes;
                $this->tablaMes[$i][3] = $this->isr[$i][3];

                $r .= "<tr>";
                $r .= "<td> $ " . number_format($this->tablaMes[$i][0], 2) . "</td>";
                $r .= "<td> $ ";
                if ($i == 10) {
                    $r .= "En adelante";
                } else {
                    $r .= number_format($this->tablaMes[$i][1], 2);
                }
                $r .= "</td>";
                $r .= "<td> $ " . number_format($this->tablaMes[$i][2], 2) . "</td>";
                $r .= "<td> " . number_format($this->tablaMes[$i][3], 2) . " % </td>";
                $r .= "</tr>";
            }
        }

        $r .= "</table>";
        $r .= "</div>";
        $r .= "</div>";

        $r .= "<div class=\"caja\">";
        $r .= "<div class=\"container\">";
        $r .= "<br>";
        $r .= "<div class=\"input-group\">";
        $r .= "<input type=\"text\" id=\"SaldoBruto\" class=\"form-control\" placeholder=\"Saldo Bruto\">";
        $r .= "<br>";

        $r .= "<button id=\"btnCalcular\" class=\"btn btn-primary\" onclick=\"calcular()\">Calcular</button>";
        $r .= "</div>";
        $r .= "<div id=\"resultadoCalculo\">";
        $r .= "En esta parte se muestran los resultados...";
        $r .= "</div>";
        $r .= "</div>";
        $r .= "</div>";
        return $r;
    }

    public function calcular($saldoBruto) {
        $limInf = 0.0;
        $difer = 0.0;
        $tasa = 0.0;
        $impMarginal = 0.0;
        $cuotaFija = 0.0;
        $impRetener = 0.0;
        $percepcion = 0.0;

        // Buscar el rango correspondiente en la tabla
        for ($i = 0; $i < count($this->tablaMes); $i++) {
            if ($saldoBruto >= $this->tablaMes[$i][0] && ($saldoBruto <= $this->tablaMes[$i][1] || $this->tablaMes[$i][1] == -1)) {
                $limInf = $this->tablaMes[$i][0];
                $difer = $saldoBruto - $limInf;
                $tasa = $this->tablaMes[$i][3];
                $impMarginal = ($difer * ($tasa / 100));
                $cuotaFija = $this->tablaMes[$i][2];
                $impRetener = ($impMarginal + $cuotaFija);

                $percepcion = ($saldoBruto - $impRetener);
                break;
            }
        }

        $r = "<div class=\"cont-tabla-result\">";
        $r .= "  <table class=\"tabla-result\" border=\"1\">";
        $r .= "      <tr>";
        $r .= "          <td colspan=\"3\"><h4 style=\"text-align: center;\"> RESULTADO </h4></td>";
        $r .= "      </tr>";
        $r .= "      <tr>";
        $r .= "          <td>    </td>";
        $r .= "          <td align=\"right\">Impuesto gravable</td>";
        $r .= "          <td align=\"right\"> " . number_format($saldoBruto, 2) . " </td>";
        $r .= "      </tr>";
        $r .= "      <tr>";
        $r .= "          <td> - </td>";
        $r .= "          <td align=\"right\">Diferencia</td>";
        $r .= "          <td align=\"right\"> " . number_format($limInf, 2) . " </td>";
        $r .= "      </tr>";
        $r .= "      <tr>";
        $r .= "          <td> = </td>";
        $r .= "          <td align=\"right\">Diferencia</td>";
        $r .= "          <td align=\"right\"> " . number_format($difer, 2) . " </td>";
        $r .= "      </tr>";
        $r .= "      <tr>";
        $r .= "          <td> X </td>";
        $r .= "          <td align=\"right\">% del impuesto</td>";
        $r .= "          <td align=\"right\"> " . number_format($tasa, 2) . "% </td>";
        $r .= "      </tr>";
        $r .= "      <tr>";
        $r .= "          <td> = </td>";
        $r .= "          <td align=\"right\">Impuesto marginal</td>";
        $r .= "          <td align=\"right\"> " . number_format($impMarginal, 2) . " </td>";
        $r .= "      </tr>";
        $r .= "      <tr>";
        $r .= "          <td> = </td>";
        $r .= "          <td align=\"right\">Cuota fija</td>";
        $r .= "          <td align=\"right\"> " . number_format($cuotaFija, 2) . " </td>";
        $r .= "      </tr>";
        $r .= "      <tr>";
        $r .= "          <td> = </td>";
        $r .= "          <td align=\"right\">Limite a retener</td>";
        $r .= "          <td align=\"right\"> " . number_format($impRetener, 2) . " </td>";
        $r .= "      </tr>";
        $r .= "      <tr id =\"text-conclusion\">";
        $r .= "          <td colspan=\"3\"><h4 style=\"text-align: center;\"> CONCLUSIÓN </h4></td>";
        $r .= "      </tr>";
        $r .= "      <tr>";
        $r .= "          <td> </td>";
        $r .= "          <td align=\"right\">Ingreso gravable</td>";
        $r .= "          <td align=\"right\"> " . number_format($saldoBruto, 2) . " </td>";
        $r .= "      </tr>";
        $r .= "      <tr>";
        $r .= "          <td> + </td>";
        $r .= "          <td align=\"right\">Impuesto a retener</td>";
        $r .= "          <td align=\"right\"> " . number_format($impRetener, 2) . " </td>";
        $r .= "      </tr>";
        $r .= "      <tr>";
        $r .= "          <td> = </td>";
        $r .= "          <td align=\"right\">Percepción Efectiva</td>";
        $r .= "          <td align=\"right\"> " . number_format($percepcion, 2) . " </td>";
        $r .= "      </tr>";
        $r .= "  </table>";
        $r .= "</div>";
        return  $r;        
    }
}

?>
