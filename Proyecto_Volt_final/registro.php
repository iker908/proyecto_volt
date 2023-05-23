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
    #empezamos la sesion y cogemos la variable global error_registro, que nos muestra un error si hemos fallado al registrar, sino no muestra nada
    session_start();
    $error=$_SESSION['error_registro'];
    #este if nos sirve para que mientras no pulsemos el boton, que activaria el POST, nos mostrara el formulario de registro
    if (!$_POST) {
    ?>
 <form id="form" class="formulario" method="POST" action="registro.php">
    
    <h1>Registrate</h1>
     <div class="contenedor">
     <div class="input-contenedor">
         <input type="text" name="dni_registro" placeholder="DNI" required>

     </div>
     <div class="input-contenedor">
         <input type="text" name="nombre_registro" placeholder="Nombre Completo" required>
         
         </div>
         
         <div class="input-contenedor">
         <input type="text" name="correo_registro" placeholder="Correo Electronico" required>
         
         </div>

         <div class="input-contenedor">
         <input type="text" name="telefono_registro" placeholder="Introduzca el Telefono" required>
      
         </div>

         <div class="input-contenedor">
         <input type="password" name="contraseña_registro" placeholder="Contraseña" required>

         </div>

        <select style="padding-top: 8px;padding-bottom: 8px; padding-right: 273px; padding-left: 59px; font-size: 16pt" name="ciudad_registro">
        <?php
          #aqui cogemos los valores de las ciudades de la base de datos y los mostramos en un <select>
          include "conexion.php";
          $conexion = conexion();
          $query2="select Ciudad from localidad";
          $consulta=mysqli_query($conexion,$query2);
          while ($valores = mysqli_fetch_array($consulta)) {
            echo '<option value="'.$valores["Ciudad"].'">'.$valores["Ciudad"].'</option>';
          }
        ?>
        </select>
         <div>
          <p></p>
        </div>
        
         <input type="submit" value="Registrate" class="button">
         <!-- aqui hacemos que si hay algo en la variable de error se muestre-->
         <p><?php echo $error ?></p>
         <p>Al registrarte, aceptas nuestras Condiciones de uso y Política de privacidad.</p>
         <p>¿Ya tienes una cuenta?<a class="link" href="loginvista.php">Iniciar Sesion</a></p>
     </div>
    </form>
    <?php
    #continuando con el if de antes, en caso de que se pulse el boton ara lo siguiente
  } else {
    include "conexion.php";
    $conexion = conexion();
    #recoge los datos introducidos en el formulario
    $dni=$_POST["dni_registro"];
    $nombre = $_POST["nombre_registro"];
    $correo = $_POST["correo_registro"];
    #la contraseña se encripta
    $contraseña = sha1(md5($_POST["contraseña_registro"]));
    $telefono=$_POST["telefono_registro"];
    $ciudad=$_POST["ciudad_registro"];
    #creamos una sentencia SQL que compruebe si el usuario existe o no
    $sql = "select * from registro_usuario where DNI='".$dni."'";
    $ronda=mysqli_query($conexion,$sql);
    $existe=mysqli_num_rows($ronda);


    if ($existe==1){
      #la variable global se modifica si existe el usuario y recarga la pagina para mostrarlo
      $_SESSION['error_registro']="El dni ya existe";
      header("location: registro.php");
    }
    #hacemos la sentencia que guardara los datos en la base
    $ssql = "insert into registro_usuario (DNI, nombre, correo, telefono, ciudad, contraseña) values ('$dni','$nombre','$correo','$telefono','$ciudad','$contraseña')";

    #ejecutamos la sentencia y nos muestra si se ha ejecutado correctamente y nos da un boton para ir al inicio de sesion
    if($conexion->query($ssql)) {
      echo "<p>Registro exitoso</p>";
    } else {
      echo "<p>Hubo un error al ejecutar la sentencia de inserción: {$conexion->error}</p>";
    }
    $conexion->close();
  ?>
  <p><a href="loginvista.php">Ir a Iniciar Sesion</a></p>
  <?php
  }
  ?>
</body>
</html>