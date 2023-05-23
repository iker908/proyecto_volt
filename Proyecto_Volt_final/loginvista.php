<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title></title> 
	<meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, maximum-scale=3.0, minimum-scale=1.0">
 <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" >
	<link rel="stylesheet" href="css/inicio_sesion.css">
	

</head>  
<body>
    <form class="formulario" method="POST" action="loginvista.php">
    <?php
    #empezamos la sesion, recogemos el error(si hay) y empezamos el mismo if que hicimos en el registro
    session_start();
    $error=$_SESSION['error_login'];
    if (!$_POST) {
    ?>
    <h1>Login</h1>
     <div class="contenedor">
         <div class="input-contenedor">
         <i class="fas fa-user icon"></i>
         <input type="text" name="dni_inicio" placeholder="DNI" required>
         
         </div>
         
         <div class="input-contenedor">
        <i class="fas fa-key icon"></i>
         <input type="password" name="contraseña_inicio" placeholder="Contraseña" required>
         
         </div>
         <input type="submit" value="Login" class="button">
         <!-- aqui hacemos que si hay algo en la variable de error se muestre-->
         <p><?php echo $error ?></p>
         <p>¿No tienes una cuenta? <a class="link" href="registro.php">Registrate </a></p>
     </div>
    </form>
    <?php
  } else {
    include "conexion.php";
    $conexion = conexion();
    #recogemos los datos del formulario
    $dni = $_POST["dni_inicio"];
    $contraseña = sha1(md5($_POST["contraseña_inicio"]));
    #hacemos una sentencia que compruebe si es correcto el login
    $sql = "select * from registro_usuario where DNI='".$dni."' and contraseña='".$contraseña."'";

    $query=mysqli_query($conexion,$sql);
    $counter=mysqli_num_rows($query);
    #si nos devuelve una ronda significa que es correcto
    if ($counter==1){
    #en caso de ser correcto guarda el dni como una variable global que demuestra que la sesion esta iniciada y nos lleva a la pagina
    #principal con la sesion iniciada
    $_SESSION['dni'] = $dni;
		header("location: index_usuario.php");
	
    } else {
      #en caso de fallar el usuario o la contraseña modificara la variable de error y recargara la pagina
        $_SESSION['error_login']="El usuario o la contraseña son incorrectos";
        header("location: loginvista.php");
    }
    $conexion->close();
  ?>
  <?php
  }
  ?>
</body>
</html>