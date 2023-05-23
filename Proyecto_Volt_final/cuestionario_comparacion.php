<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title></title> 
	<meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, maximum-scale=3.0, minimum-scale=1.0">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" >
	<link rel="stylesheet" href="css/primer_cuestionario.css">
  <script src=”https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js”></script>

</head>  
<body>
    <?php
    #empieza la sesion y hace lo mismo que el registro y login
    session_start();
    $_SESSION["lista"]=[];
    if (!$_POST) {
    ?>
 <form id="form" class="formulario" method="POST" action="cuestionario_comparacion.php">
    
    <h1>Questionario</h1>
      <div class="contenedor">
      <p style="font-size: 20px; text-align: left;">Introduzca los datos de su factura<p>
      <p style="font-size: 20px; text-align: left;">Cuando introduzca los datos use "." en vez de "," en los numeros, por ejemplo en vez de 10,10 ponga 10.10<p>
        <div>
          <p></br></p>
    </div>
      <p style="font-size: 20px; text-align: left;">1 Introduzca el consumo de kbh que ha tenido en esta factura</p>
         <div class="input-contenedor">
            <input type="text" name="consumo_prueba" placeholder="Consumo de kbh" required>
         </div>
         <p style="font-size: 20px; text-align: left;">2 Introduzca el consumo de kbh que ha tenido en esta factura en periodo punta</p>
         <div class="input-contenedor">
            <input type="text" name="consumo_punta" placeholder="Consumo punta" required>
         </div>
         <p style="font-size: 20px; text-align: left;">3 Introduzca el consumo de kbh que ha tenido en esta factura en periodo valle</p>
         <div class="input-contenedor">
            <input type="text" name="consumo_valle" placeholder="Consumo valle" required>
         </div>
         <p style="font-size: 20px; text-align: left;">4 Introduzca el consumo de kbh que ha tenido en esta factura en periodo llano</p>
         <div class="input-contenedor">
            <input type="text" name="consumo_llano" placeholder="Consumo llano" required>
         </div>
         <p style="font-size: 20px; text-align: left;">5 Introduzca el porcentage de descuento que tienes, si no tienes pon 0</p>
         <div class="input-contenedor">
         <input type="text" name="descuento_prueba" placeholder="Descuento" required>
         
         </div>
         <p style="font-size: 20px; text-align: left;">6 Introduzca la potencia punta que tiene contratada</p>
         <div class="input-contenedor">
         <input type="text" name="potencia_punta_prueba" placeholder="Potencia punta" required>
         
         </div>
         <p style="font-size: 20px; text-align: left;">7 Introduzca la potencia valle que tiene contratada</p>
         <div class="input-contenedor">
         <input type="text" name="potencia_valle_prueba" placeholder="Potencia valle" required>
         
         </div>
         
         <p style="font-size: 20px; text-align: left;">8 Introduzca la cantidad de dias que comprime esta factura</p>
         <div class="input-contenedor">
         <input type="text" name="dias_prueba" placeholder="Periodo" required>
         
         </div>
         <p style="font-size: 20px; text-align: left;">9 Introduzca el coste diario de su contador contratado, si no tiene contratado ponga 0</p>
         <div class="input-contenedor">
         <input type="text" name="contador_prueba" placeholder="Contador" required>
         
         </div>
         <p style="font-size: 20px; text-align: left;">10 Introduzca el coste de su factura</p>
         <div class="input-contenedor">
         <input type="text" name="coste_prueba" placeholder="Coste de la Factura" required>
         
         </div>
         <input type="submit" value="Siguiente" class="button">
     </div>
    </form>
    <?php
  } else {
    include "conexion.php";
    $conexion = conexion();
    #recoge los datos del formulario
    $dni=$_SESSION['dni'];
    $consumo = $_POST["consumo_prueba"];
    $consumo_p = $_POST["consumo_punta"];
    $consumo_v = $_POST["consumo_valle"];
    $consumo_l = $_POST["consumo_llano"];
    $potencia_p = $_POST["potencia_punta_prueba"];
    $potencia_v = $_POST["potencia_valle_prueba"];
    $coste=$_POST["coste_prueba"];
    $dias=$_POST["dias_prueba"];
    $contador=$_POST["contador_prueba"];
    $descuento=$_POST["descuento_prueba"];
    #inserta los datos en la tabla de facturas
    $ssql = "insert into factura (dni_usuario, consumo, consumo_punta, consumo_valle, consumo_llano, potencia_punta, potencia_valle, descuento, dias, contador, coste) values ('$dni','$consumo','$consumo_p','$consumo_v','$consumo_l','$potencia_p','$potencia_v','$descuento','$dias','$contador','$coste')";
    #si acierta convierte todas las variables en variables globales y pasa al siguiente cuestionario
    if($conexion->query($ssql)) {
      $_SESSION["consumo"]=$consumo;
      $_SESSION["consumo_p"]=$consumo_p;
      $_SESSION["consumo_v"]=$consumo_v;
      $_SESSION["consumo_l"]=$consumo_l;
      $_SESSION["potencia_p"]=$potencia_p;
      $_SESSION["potencia_v"]=$potencia_p;
      $_SESSION["dias"]=$dias;
      $_SESSION["contador"]=$contador;
      $_SESSION["descuento"]=$descuento;
      header("location: segundo_cuestionario.php");
    } else {
      echo "<p>Hubo un error al ejecutar la sentencia de inserción: {$conexion->error}</p>";
    }
    $conexion->close();
  }
  ?>
  
</body>
</html>