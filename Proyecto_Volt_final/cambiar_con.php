<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title></title> 
	<meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, maximum-scale=3.0, minimum-scale=1.0">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" >
	<link rel="stylesheet" href="css/registro.css">
  <script src=”https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js”></script>

</head>  
<body>
    <?php
    #lo mismo que el login y registro, la variable error y el if del cuestionario
    session_start();
    $error2=$_SESSION['error2'];
    if (!$_POST) {
    ?>
 <form id="form" class="formulario" method="POST" action="cambiar_con.php">
     <div class="contenedor">
     <div class="input-contenedor">
         <input type="password" name="contraseña1" placeholder="Introduzca su contraseña">

     </div>
     <div class="input-contenedor">
         <input type="password" name="contraseña2" placeholder="Introduzca su contraseña otra vez">

     </div>
         
     <div class="input-contenedor">
         <input type="password" name="contraseña_n" placeholder="Introduzca su nueva contraseña">

     </div>
        
         <input type="submit" value="Cambiar" class="button">
         <!-- aqui hacemos que si hay algo en la variable de error se muestre-->
         <p><?php echo $error2 ?></p>
     </div>
    </form>
    <?php
  } else {
    include "conexion.php";
    $conexion = conexion();
    #recoge los datos de el formulario
    $dni=$_SESSION['dni'];
    $contraseña1 = sha1(md5($_POST["contraseña1"]));
    $contraseña2 = sha1(md5($_POST["contraseña2"]));
    $contraseña_n = sha1(md5($_POST["contraseña_n"]));
    #comprueba si has introducido la contraseña correcta las dos veces
    if ($contraseña1==$contraseña2){
        #ahora recoge la contraseña guardada en la base y comprueba si es correcta
        $ssql = "select contraseña from registro_usuario where DNI='".$dni."'";
        $ronda2=mysqli_query($conexion,$ssql);
        $resultado=mysqli_fetch_array($ronda2);
        foreach ($resultado as &$valor) {
            $total=$valor;
        }
        if ($total==$contraseña1){
            #al ser correcta modifica la que esta guardada con la nueva que has introducido
            $sql = "update registro_usuario set contraseña='".$contraseña_n."' where DNI='".$dni."' and contraseña='".$contraseña1."'";
            if($conexion->query($sql)) {
                echo "<p>Cambio exitoso</p>";
              } else {
                echo "error";
              }
              $conexion->close();
            } else {
              #si falla cambia la variable de error y recarga la pagina
              $_SESSION["error2"]="Contraseña incorrecta";
              header("location: cambiar_con.php");
            }
    } else{
        #si falla cambia la variable de error y recarga la pagina
        $_SESSION["error2"]="Las contraseñas no son iguales";
        header("location: cambiar_con.php");
    }
  ?>
  <p><a href="perfil.php">Volver al Perfil</a></p>
  <?php
  }
  ?>
</body>
</html>