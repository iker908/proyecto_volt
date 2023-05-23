<?php
    #empezamos la sesion y comprobamos que se haya iniciado sesion correctamente con la variable del dni, si falla nos lleva a la pagina de inicio
    session_start();
    if (!isset($_SESSION['dni'])){
        header("location: index.php");
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/inicio.css">
    <title>Proyecto_Volt</title>
</head>
<body>
    <?php
        #si la sesion es correcta usa el dni en una sentencia SQL para recibir el nombre asignado y mostrarlo en el apartado de perfil
        $dni=$_SESSION['dni'];
        #las dos lineas de abajo sirven para convertir la conexion con la base en una variable
        include "conexion.php";
        $conexion = conexion();
        $query="select nombre from registro_usuario where DNI = '".$dni."'";
        $consulta=mysqli_query($conexion,$query);
        $resultado=mysqli_fetch_array($consulta);
        foreach ($resultado as &$valor) {
            $total=$valor;
        }
    ?>
    <main class="main">
        <section class="contenedor-1">
            <header class="cabecera">
                <div class="logo">
                </div>
                <nav class="navegacion">
                    <a href="index.php" class="link">Inicio</a>
                    <a href="nosotros.html" class="link">Nosotros</a>
                    <a href="inicio_consultas.php" class="link">Contacto</a>
                    <!-- la variable $total lleva el nombre, al hacer un echo en <a> nos mostrara el boton con el nombre-->
                    <a href="perfil.php" class="link"><?php echo $total ?></a>
                </nav>
            </header>

            <div class="banner">
                <div class="banner_textos">
                    <h1>Obtenga la mejor comparación</h1>
                    <p>Somos la empresa puntera que te compara tus precios
                        entre compañias electricas. Elijenos !!</p>
                    <a href="cuestionario_comparacion.php">CONSULTA AQUI!</a>
                </div>
            </div>
        </section>
</body>
</html>