<html>
  <head>
    <meta charset="UTF-8">
    <title></title> 
    <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, maximum-scale=3.0, minimum-scale=1.0">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" >
    <link rel="stylesheet" href="css/segundo.css">
  </head>
  <body>
      <p>¡Has completado el cuestionario!</p>
      <a href="comparacion_def.php" class="link">Comprueba los resultados</a>
    <?php
      // Verificar si se recibieron las respuestas
      if (isset($_GET['responses'])) {
        session_start();
        // Obtener las respuestas desde el parámetro de consulta
        $encodedResponses = $_GET['responses'];
        // Decodificar las respuestas desde JSON a un array
        $responses = json_decode(urldecode($encodedResponses), true);

        $respuesta1=$responses[0];
        $respuesta2=$responses[1];
        $respuesta3=$responses[2];
        $respuesta4=$responses[3];
        $respuesta5=$responses[4];
        $respuesta6=$responses[5];
        $respuesta7=$responses[6];
        $respuesta8=$responses[7];
        $respuesta9=$responses[8];
        $respuesta10=$responses[9];
        $respuesta11=$responses[10];
        $respuesta12=$responses[11];
        

        #crea las variables que decidiran que tipo de tarifas nos mostrara la comparacion
        $fija=0;
        $franjas=0;
        $horas=0;
        #de aqui hasta el final comprueba cada respuesta para aumentar la variable correspondiente
        if ($respuesta1=="En la mañana"){
          $fija=$fija+1;
        } elseif ($respuesta1=="En la tarde"){
          $franjas=$franjas+1;
        } else{
          $horas=$horas+1;
        }

        if ($respuesta2=="Nunca"){
          $fija=$fija+1;
        } elseif ($respuesta2=="Siempre"){
          $franjas=$franjas+1;
        } else{
          $franjas=$franjas+0.5;
        }

        if ($respuesta3=="Si"){
          $fija=$fija+1;
        } elseif ($respuesta3=="Bastante"){
          $fija=$fija+0.5;
        } elseif ($respuesta3=="Poco"){
          $franjas=$franjas+1;
        } else{
          $franjas=$franjas+0.5;
        }

        if ($respuesta4=="Ninguno es de alta eficiencia energética"){
          $fija=$fija+1;
        } elseif ($respuesta4=="Todos son de alta eficiencia energética"){
          $franjas=$franjas+1;
        } else{
          $franjas=$franjas+0.5;
        }

        if ($respuesta5=="No"){
          $fija=$fija+1;
        } else{
          $franjas=$franjas+1;
          $horas=$horas+1;
        }

        if ($respuesta6=="No tengo una rutina establecida"){
          $fija=$fija+1;
        } elseif ($respuesta6=="Sí, siempre sigo una rutina"){
          $franjas=$franjas+1;
          $horas=$horas+1;
        } else{
          $horas=$horas+1;
        }

        if ($respuesta7=="Si"){
          $fija=$fija+1;
          $horas=$horas+1;
        } elseif ($respuesta7=="A veces"){
          $franjas=$franjas+1;
        } else{
          $franjas=$franjas+0.5;
        }
        
        if ($respuesta8=="Si"){
          $fija=$fija+1;
        } elseif ($respuesta8=="Bastante"){
          $franjas=$franjas+1;
        } else{
          $franjas=$franjas+0.5;
        }

        if ($respuesta9=="No"){
          $fija=$fija+1;
        } else{
          $horas=$horas+3;
        }

        if ($respuesta10=="Si"){
          $fija=$fija+1;
          $horas=$horas+1;
        } elseif ($respuesta10=="No"){
          $franjas=$franjas+0.5;
        } else{
          $horas=$horas+0.5;
        }

        if ($respuesta11=="No"){
          $fija=$fija+1;
        } else{
          $franjas=$franjas+1;
          $horas=$horas+1;
        }

        if ($respuesta12=="Si"){
          $franjas=$franjas+1;
          $horas=$horas+0.5;
        } else{
          $fija=$fija+1;
        }
        #comprueba que tipo de tarifa sera
        if ($fija >= $franjas && $fija >= $horas) {
          $prueba=0;
        } elseif ($franjas >= $fija && $franjas >= $horas) {
          $prueba=1;
        } else {
          $prueba=2;
        }
        #guarda el tipo de tarifa en una variable global y nos manda a la comparacion
        $_SESSION["prueba"]=$prueba;
      }
    ?>
  </body>
</html>