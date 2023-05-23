<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/comparaciones.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
</head>
<body>
    <?php
    #empezamos la sesion y recogemos los datos de los formularios anteriores
        session_start();
        include "conexion.php";
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
        #esta variable es la que decide el tipo de plan
        $prueba=$_SESSION["prueba"];

    #recoge todos los datos de los planes
    $comparar="select * from planes";
    #creamos los arrays donde se guardaran los datos, esta comparacion es simple y solo muestra pocos datos, luego ya podremos ver los datos mas detallados
    $array_plan=[];
    $array_empresa=[];
    $array_total=[];
    $array_potencia=[];
    $array_consumo=[];

    #comprueba si hay contador y decide el precio segun los dias
    if ($contador!=0){
      $coste_contador=$contador*$dias;
    }else{
      $coste_contador=0;
    }
    #comprueba si hay descuento
    if ($descuento==0){
      $q=0;
    }
    else{
      $q=1;
    }

    $consulta2=mysqli_query($conexion,$comparar);
          #hacemos un while con cada plan
          while ($valores = mysqli_fetch_array($consulta2)) {
            #si el tipo de tarifa recomendada es 0(fija), hace lo siguiente
              if ($prueba==0){
              #comprueba si la tarifa en el que estamos es de tipo fijo
              if ($valores["plan_3_franjas"]==0){
              #aqui comienca a hacer los calculos
              #primero hace el calculo del precio del consumo
              $consumo_calculo=$consumo*$valores["precio_hora"];
              #si hay descuento lo aplica al consumo
              if ($q==1){
                $consumo_calculo=$consumo_calculo-($consumo_calculo/100)*$descuento;
              }
              #calcula los valores de los dos tipos de potencia
              $punta=$valores["potencia_punta_al_mes"];
              $punta=$punta/365;
              $punta=$punta*$dias;
              $valle=$valores["potencia_valle_al_mes"];
              $valle=$valle/365;
              $valle=$valle*$dias;
              $potencia_punta_calculo=$potencia_p*$punta;
              $potencia_valle_calculo=$potencia_v*$valle;
              #hacemos la suma de los componentes que llevamos
              $suma1=$consumo_calculo+$potencia_punta_calculo+$potencia_valle_calculo;
              #calculamos el impuesto de la luz usando la suma anterior
              $impuesto=$suma1*0.05;
              #a la suma le sumamos el contador
              $suma1=$suma1+$coste_contador;
              #hacemos la media de la potencia contratada para calcular la cantidad de IVA 
              if (($potencia_p+$potencia_v)/2<10){
                $iva=$suma1*0.05;
              }
              else {
                $iva=$suma1*0.21;
              }
              #sumamos la suma con el impuesto y el iva
              $suma_total=$suma1+$impuesto+$iva;
              #guardamos los datos mas relevantes en los arrays para mostrarlos en esta pagina
              $array_plan[]=$valores["nombre"];
              $array_empresa[]=$valores["empresa"];
              $array_total[]=$suma_total;
              $array_potencia[]=$potencia_punta_calculo+$potencia_valle_calculo;
              $array_consumo[]=$consumo_calculo;
            }
            }
            #en cambio, si el tipo de tarifa recomendada es 1(franjas), hace lo siguiente
            elseif ($prueba==1){
            #comprueba si la tarifa en el que estamos es de tipo franja 
            if ($valores["plan_3_franjas"]==1){
              #hacemos los calculos de cada tipo de consumo y lo sumamos al final
              $consumo_pu=$consumo_p*$valores["precio_punta"];
              $consumo_va=$consumo_v*$valores["precio_valle"];
              $consumo_ll=$consumo_l*$valores["precio_llano"];
              $consumo_calculo=$consumo_ll+$consumo_pu+$consumo_va;
              #aplicamos el descuento
              if ($q==1){
                $consumo_calculo=$consumo_calculo-($consumo_calculo/100)*$descuento;
              }
              #de aqui hasta el final hace lo mismo que el codigo anterior
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
              $array_plan[]=$valores["nombre"];
              $array_empresa[]=$valores["empresa"];
              $array_total[]=$suma_total;
              $array_potencia[]=$potencia_punta_calculo+$potencia_valle_calculo;
              $array_consumo[]=$consumo_calculo;
            }
            }
            #por ultimo si el tipo de tarifa es 2(discriminacion horaria), hace lo siguiente
            else{
                if ($valores["plan_3_franjas"]==2){
                    #recogemos la cantidad diaria de horas que tienen un precio diferente en este plan
                    $horas_mod=$valores["horas_modificadas"];
                    #lo restamos a las horas del dia para tener dos variables, las horas normales y las modificadas
                    $horas_normal=24-$horas_mod;
                    #hacemos una division equitativa del consumo segun el numero de horas
                    $consumo_n=($consumo/24)*$horas_normal;
                    $consumo_m=($consumo/24)*$horas_mod;
                    #como las horas modificadas son aquellas en las que consumiras mas, he hecho una aproximacion comparando con diferentes
                    #facturas y he decidido restartle un 15% al consumo en horas normales para darselo al consumo modificado
                    $suma_c=($consumo_n/100)*15;
                    $consumo_m=($consumo_n/100)*85;
                    $consumo_n=$consumo_n+$suma_c;
                    #por ultimo calculamos el precio total con ambos consumos
                    $consumo_calculo=$consumo_m*$valores["precio_hora"]+$consumo_n*$valores["precio_hora_modificado"];
                    #aplicamos descuento si hay
                    if ($q==1){
                      $consumo_calculo=$consumo_calculo-($consumo_calculo/100)*$descuento;
                    }
                    #a partir de aqui lo mismo que antes
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
                    $array_plan[]=$valores["nombre"];
                    $array_empresa[]=$valores["empresa"];
                    $array_total[]=$suma_total;
                    $array_potencia[]=$potencia_punta_calculo+$potencia_valle_calculo;
                    $array_consumo[]=$consumo_calculo;
                  }
            }
          }
    #contamos la cantidad de tarifas que mostraremos
    $x=count($array_plan);

        #dado que nustra pagina muestra las tarifas de dos en dos, tenemos que calcular si la cantidad de planes es impar
        $impar=$x%2;
        $solo1=0;
        #si no es impar dividimos la cantidad de planes para saber cuantas rondas necesitamos
        if ($impar==0){
            $numero=$x/2;
        }
        #si es impar resta uno a la cantidad y la divide entre dos para lo mismo de antes, pero la variable
        #$solo1 se modifica para ser usada
        else {
            $x=$x-1;
            $numero=$x/2;
            $solo1=1;
        }
        $endesa="img/Endesa.svg.png";
        $iberdrola="img/Iberdrola-logo-11.png";
        $factor="img/factor-energia_principal.svg";

        #la variable y servira para contar cuantas rondas llevamos
        $y=0;
        #la variable z sera unicamente para la primera ronda que contiene el titulo
        $z=0;
        #dado que vamos de dos en dos, la variable $a es la que nos permitira extraer los datos para la primera posicion
        #la $b es para la segunda posicion, esta un numero adelantado a $a, ya que al usar numeros podemos sacar los datos
        #de los arrays en la posicion que estan
        $a=0;
        $b=1;
        #si es la primera ronda $z sera sero por lo tanto imprimira esta primera ronda con dos planes
        if ($z==0){
            if ($array_empresa[$a]=="Endesa"){
              $img1=$endesa;
            }
            elseif($array_empresa[$a]=="Iberdrola"){
              $img1=$iberdrola;
            } else{
              $img1=$factor;
            }
            if ($array_empresa[$b]=="Endesa"){
              $img2=$endesa;
            }
            elseif($array_empresa[$b]=="Iberdrola"){
              $img2=$iberdrola;
            } else{
              $img2=$factor;
            }
            echo " <section class='contenedor-6'>
           <h2 class='contenedor-6_titulo'>COMPARACIÓN</h2>
           <div class='contenedor-6_paquete'>
               <div class='paquete'>
                  <img src=$img1 width='200'>
                   <h3>$array_plan[$a]</h3>
                   <p>Potencia total: $array_potencia[$a]</p>
                   <p>Consumo total con descuento: $array_consumo[$a]</p>
                   <p>Estimación total: $array_total[$a]</p>
                   <a href='planes/$array_plan[$a].php'>Información mas detallada</a>    
               </div>
               <div class='paquete'>
               <img src=$img2 width='200'>
               <h3>$array_plan[$b]</h3>
                   <p>Potencia total: $array_potencia[$b]</p>
                   <p>Consumo total con descuento: $array_consumo[$b]</p>
                   <p>Estimación total: $array_total[$b]</p>
                   <a href='planes/$array_plan[$b].php'>Información mas detallada</a    
               </div>
           </div>
       </section>";
       #modificamos los datos de las variables para poder comenzar con el while
       $y=$y+1;
       $z=1;
       $a=$a+2;
       $b=$b+2;
        }
        #empezamos el while que imprimira el resto de rondas, cuando $y llege a ser igual que el numero de planes parara
        while ($y!==$numero){
          if ($array_empresa[$a]=="Endesa"){
            $img1=$endesa;
          }
          elseif($array_empresa[$a]=="Iberdrola"){
            $img1=$iberdrola;
          } else{
            $img1=$factor;
          }
          if ($array_empresa[$b]=="Endesa"){
            $img2=$endesa;
          }
          elseif($array_empresa[$b]=="Iberdrola"){
            $img2=$iberdrola;
          } else{
            $img2=$factor;
          }
           echo " <section class='contenedor-6'>
           <div class='contenedor-6_paquete'>
               <div class='paquete'>
               <img src=$img1 width='200'>
               <h3>$array_plan[$a]</h3>
                   <p>Potencia total: $array_potencia[$a]</p>
                   <p>Consumo total con descuento: $array_consumo[$a]</p>
                   <p>Estimación total: $array_total[$a]</p>
                   <a href='planes/$array_plan[$a].php'>Información mas detallada</a    
               </div>
               <div class='paquete'>
               <img src=$img1 width='200'>
               <h3>$array_plan[$b]</h3>
                   <p>Potencia total: $array_potencia[$b]</p>
                   <p>Consumo total con descuento: $array_consumo[$b]</p>
                   <p>Estimación total: $array_total[$b]</p>
                   <a href='planes/$array_plan[$b].php'>Información mas detallada</a
               </div>
           </div>
       </section>";
       #igual que antes modificamos las variables para la siguiente ronda
       $y=$y+1;
       $a=$a+2;
       $b=$b+2;
          }
        #una vez acabado el while, si la cantidad de tarifas eran impares imprimira una ronda con una unica
        #tarifa centrada al centro
        if ($solo1==1){
          if ($array_empresa[$x]=="Endesa"){
            $img1=$endesa;
          }
          elseif($array_empresa[$x]=="Iberdrola"){
            $img1=$iberdrola;
          } else{
            $img1=$factor;
          }
            echo " <section class='contenedor-6'>
           <div class='contenedor-6_paquete'>
               <div class='paquete'>
               <img src=$img1 width='200'>
               <h3>$array_plan[$x]</h3>
                   <p>Potencia total: $array_potencia[$x]</p>
                   <p>Consumo total con descuento: $array_consumo[$x]</p>
                   <p>Estimación total: $array_total[$x]</p>
                   <a href='planes/$array_plan[$x].php'>Información mas detallada</a   
               </div>
           </div>
       </section>";
        }
        
    ?>
    <!-- Aqui imprimimos las graficas que crearemos mas abajo -->
    <section class='contenedor-6'>
      <div class='contenedor-6_paquete'>
      <div class="paquete_grafica">
      <canvas id="grafica_consumo"></canvas>
      </div>
      <div class="paquete_grafica">
      <canvas id="grafica_potencia"></canvas>
      </div>
      </div>
    </div>  
    <section class='contenedor-6'>
      <div class='contenedor-6_paquete'>
    <div class="paquete_grafica">
      <canvas id="grafica_total"></canvas>
      </div>
      </div>
    </div> 
    <a href="index_usuario.php" class="link">Volver al inicio</a>
    <?php
     # Creamos una instancia de Chart.js para la primera gráfica, que contendra una comparativa
     # de los precios de el consumo total de todos los planes
     echo "<script>
     var ctx1 = document.getElementById('grafica_consumo').getContext('2d');
     var grafica1 = new Chart(ctx1, {
         type: 'bar',
         data: {
             labels: " . json_encode($array_plan) . ",
             datasets: [{
                 label: 'Consumo en €',
                 data: " . json_encode($array_consumo) . ",
                 backgroundColor: 'blue'
             }]
         },
         options: {
          scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }],
        },
         }
     });
 </script>";

     # Creamos una instancia de Chart.js para la segunda gráfica, que contendra una comparativa
     # de los precios de la potencia total de todos los planes
 echo "<script>
     var ctx2 = document.getElementById('grafica_potencia').getContext('2d');
     var grafica2 = new Chart(ctx2, {
         type: 'bar',
         data: {
             labels: " . json_encode($array_plan) . ",
             datasets: [{
                 label: 'Potencia en €',
                 data: " . json_encode($array_potencia) . ",
                 backgroundColor: 'blue'
             }]
         },
         options: {
          scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }],
        },
         }
     });
 </script>";
     # Creamos una instancia de Chart.js para la ultima gráfica, que contendra una comparativa
     # de los precios totales de todos los planes
 echo "<script>
     var ctx3 = document.getElementById('grafica_total').getContext('2d');
     var grafica3 = new Chart(ctx3, {
         type: 'bar',
         data: {
             labels: " . json_encode($array_plan) . ",
             datasets: [{
                 label: 'Precio total en €',
                 data: " . json_encode($array_total) . ",
                 backgroundColor: 'blue'
             }]
         },
         options: {
          scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }],
        },
         }
     });
 </script>";
    ?>
    
</body>
</html>