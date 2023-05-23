<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/planes.css">
        <title>Estimación de Consumo</title>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    </head>
    <body>
        <div class="center">
            <h1 class="item">Estimación de Consumo</h1>
        </div>
        <?php
        #como todas las paginas de planes son similares ves a la pagina de "3 periodos.php" para ver un poco mas detallado
        #y no tener que explicarme varias veces
        session_start();
        include "../conexion.php";
        $conexion = conexion();
        $potencia_p=$_SESSION["potencia_p"];
        $potencia_v=$_SESSION["potencia_v"];
        $consumo=$_SESSION["consumo"];
        $consumo_p=$_SESSION["consumo_p"];
        $consumo_v=$_SESSION["consumo_v"];
        $consumo_l=$_SESSION["consumo_l"];
        $contador=$_SESSION["contador"];
        $descuento=$_SESSION["descuento"];
        $dias=$_SESSION["dias"];
        $prueba=$_SESSION["prueba"];

        $comparar="select * from planes";


    if ($contador!=0){
      $coste_contador=$contador*$dias;
    }else{
      $coste_contador=0.0;
    }

    if ($descuento==0){
      $q=0;
    }
    else{
      $q=1;
    }

    $consulta2=mysqli_query($conexion,$comparar);
          while ($valores = mysqli_fetch_array($consulta2)) {
              if ($valores["nombre"]=="Tempo Happy 1 dia"){
                $horas_mod=$valores["horas_modificadas"];
                $horas_normal=24-$horas_mod;
                $consumo_n=($consumo/24)*$horas_normal;
                $consumo_m=($consumo/24)*$horas_mod;
                $suma_c=($consumo_n/100)*15;
                $consumo_m=($consumo_n/100)*85;
                $consumo_n=$consumo_n+$suma_c;
                $consumo_calculo=$consumo_m*$valores["precio_hora"]+$consumo_n*$valores["precio_hora_modificado"];
              $precio_consumo=$consumo_calculo;
              if ($q==1){
                $consumo_calculo=$consumo_calculo-($consumo_calculo/100)*$descuento;
              }
              $punta=$valores["potencia_punta_al_mes"];
              $punta=$punta/365;
              $punta=$punta*$dias;
              $valle=$valores["potencia_valle_al_mes"];
              $valle=$valle/365;
              $valle=$valle*$dias;
              $potencia_punta_calculo=$potencia_p*$punta;
              $potencia_valle_calculo=$potencia_v*$valle;
              $suma1=$consumo_calculo+$potencia_punta_calculo+$potencia_valle_calculo;
              $impuesto=$suma1*0.05;
              $suma1=$suma1+$coste_contador;
              if (($potencia_p+$potencia_v)/2<10){
                $iva=$suma1*0.05;
              }
              else {
                $iva=$suma1*0.21;
              }
              $suma_total=$suma1+$impuesto+$iva;
              
              $potencia_total=$potencia_punta_calculo+$potencia_valle_calculo;
              $datos1 = [
                ['Tipo', 'Precio'],
                ['Punta', $potencia_punta_calculo],
                ['Valle', $potencia_valle_calculo]
            ];
            $datos2 = [
                ['Parte', 'Precio'],
                ['Consumo', $consumo_calculo],
                ['Potencia', $potencia_total],
                ['Contador', $coste_contador],
                ['Impuesto', $impuesto],
                ['IVA', $iva]
            ];
            }
        }
        ?>
        <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart1);
      google.charts.setOnLoadCallback(drawChart2);

      function drawChart1() {
        var data = google.visualization.arrayToDataTable(<?php echo json_encode($datos1); ?>);


        var options = {
          title: 'Diferencia de precio de potencias',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('chart1'));
        chart.draw(data, options);
      }

      function drawChart2() {
        var data = google.visualization.arrayToDataTable(<?php echo json_encode($datos2); ?>);


        var options = {
          title: 'Division del precio final',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('chart2'));
        chart.draw(data, options);
      }
    </script>
        <div class="container">

            <div class="left">
                <div class="item-1">
                    <p class="item-3">El importe de tu consumo en este plan seria:</p>
                    <p><?php echo $precio_consumo ?> €</p>
                </div>

                <div class="item-1">
                    <p class="item-3">El importe del contador a sido:</p>
                    <p><?php echo $coste_contador ?> €</p>
                </div>

                <div class="item-1">
                     <p class="item-3">El importe del impuesto de la Luz, que es un 5%, a sido:</p>
                     <p><?php echo $impuesto ?> €</p>
                </div>
                <div class="item-1">
                    <p class="item-3">El importe del IVA, que es un 5% en caso de potencias menores a 10KW y 21% en caso de mayor, a sido:</p>
                    <p><?php echo $iva ?> €</p>
                </div>

                
            </div>

            <div class="right">
                <div class="item-1">
                    <p class="item-3">El importe del consumo tras aplicar el descuento es:</p>
                    <p><?php echo $consumo_calculo ?> €</p>
                </div>
                <div class="item-1">
                    <p class="item-3">El importe de la potencia a sido:</p>
                    <p><?php echo $potencia_punta_calculo+$potencia_valle_calculo ?> €</p>
                </div>
                <div class="paquete_grafica" id="chart1">
                
                </div>

            </div>
        </div>

        <div class="total">
            <div class="item">La estimación total a sido:</div>
            <p><?php echo $suma_total ?> €</p>  
            <div class="paquete_grafica_grande" id="chart2" style="width: 500px; height: 300px;">
                
                </div>
            <div>
          <p></br></p>
    </div>
    <div class="item"><button onclick="irAPagina()">Volver a los planes</button></div>
    <script type="text/javascript">
      function irAPagina() {
        window.location.href = "../comparacion_def.php";
      }
    </script>
            <div class="item"><button onclick="irAPagina2()">Volver al inicio</button></div>
            <script type="text/javascript">
      function irAPagina2() {
        window.location.href = "../index_usuario.php";
      }
    </script>
        </div>
    </body>
</html>