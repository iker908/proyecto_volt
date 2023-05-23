<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" type="text/css" href="css/perfil.css">
      <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
  </head>
  <body>
  <?php
        #empezamos la sesion y recogemos los datos de la base que estan asociados al dni del usuario
        session_start();
        $_SESSION['error2']="";
        $dni=$_SESSION['dni'];
        include "conexion.php";
        $conexion = conexion();
        $query="select * from registro_usuario where DNI = '".$dni."'";
        $consulta2=mysqli_query($conexion,$query);
        while ($valores = mysqli_fetch_array($consulta2)) {
            $nombre=$valores["nombre"];
            $correo=$valores["correo"];
            $telefono=$valores["telefono"];
            $ciudad=$valores["ciudad"];
        }
        
    ?>
    <section class="seccion-perfil-usuario">
      <div class="perfil-usuario-header">
          <div class="perfil-usuario-portada">
              <div class="perfil-usuario-avatar">
              <?php
                #aqui comprueba que haya una foto asignada al dni
                $result = $conexion->query("SELECT foto FROM fotos WHERE dni ='".$dni."'");
                $existe=mysqli_num_rows($result);
                if ($existe==0){
                  #si no la hay muestra una por defecto
                  echo "<img src='img/perfil_base.webp'>";
                } else {
                  #si la hay llama al archivo vista.php que recoge la imagen y la convierte en el formato correspondiente para mostrarla
                  echo "<img src='vista.php?id=4' alt='Img blob desde MySQL' />";
                }
              ?>
              </div>
          </div>
      </div>
      <div class="perfil-usuario-body">
          <div class="perfil-usuario-bio">
              <h3 class="titulo"><?php echo $nombre; ?></h3>
          </div>
          <div class="perfil-usuario-footer">
              <ul class="lista-datos">
                <!--mostramos los datos del usuario -->
                  <li> DNI: <?php echo $dni; ?></li>
                  <li> Telefono: <?php echo $telefono; ?></li>
                  <li> Correo: <?php echo $correo; ?></li>
                  <li> Ciudad: <?php echo $ciudad; ?></li>
                  <!--aqui podemos cambiar nuestra foto de perfil -->
                  <p>Cambiar foto de perfil</p>
                  <form action="perfil.php" method="post" enctype="multipart/form-data">
                    <input type="file" name="image">
                    <input type="submit" name="submit" value="Upload">
                  </form>
              </ul>
          </div>
      </div>
      <div>
        <p></br></p>
      </div>
      <!--este boton nos permite cambiar la contraseña, al clicarlo nos lleva al formulario para cambiarla -->
      <button onclick="cambiar()">Cambiar contraseña</button>
      <script type="text/javascript">
      function cambiar() {
        window.location.href = "cambiar_con.php";
      }
    </script>
      <div>
        <p></br></p>
      </div>
      <!--este boton nos permite cerrar la sesion, una vez cerrada nos manda al inicio-->
      <button  onclick="cerrar()">Cerrar sesion</button>
      <script type="text/javascript">
      function cerrar() {
        window.location.href = "index.php";
      }
    </script>
    <button  onclick="volver()">Volver</button>
      <script type="text/javascript">
      function volver() {
        window.location.href = "index_usuario.php";
      }
    </script>
  </section>
  <?php
    # Procesa la imagen si se envió un archivo
    if(isset($_POST["submit"])) {
      $image = $_FILES["image"]["tmp_name"];
      $imgContent = addslashes(file_get_contents($image));

      # Verifica que se haya seleccionado una imagen válida
      $check = getimagesize($image);
      if($check !== false) {

        #comprueba si ya hay una foto para decidir si actualizarla o insertarla
        $ssql = "select * from fotos where DNI='".$dni."'";
        $ronda=mysqli_query($conexion,$ssql);
        $existe=mysqli_num_rows($ronda);


        if ($existe==1){
          #si ya hay una foto la modifica y recarga el perfil
          $sql2 = "update fotos set foto='".$imgContent."' where DNI='".$dni."'";
          if ($conexion->query($sql2)) {
            echo "Imagen actualizada.";
            header("location: perfil.php");
          }
        } else{
          #si no hay foto guardada crea la ronda con la foto y el dni y recarga la pagina
          $sql = "insert into fotos (dni, foto) values ('$dni','$imgContent')";
          if ($conexion->query($sql)) {
            echo "Imagen subida.";
            header("location: perfil.php");
          }
        }
      } else {
        #en caso de que el formato no sea valido nos dara error
        echo "Selecciona un formato valido.";
      }
    }
  ?>
  </body>
</html>


