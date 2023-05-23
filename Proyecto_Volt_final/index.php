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
    # Destruimos cualquier sesion que este iniciada y luego configuramos las variables de los errores que puedan dar otras paginas
    #la sesion sirve para guardar variables que podremos usar en todos los documentos que empiezen una sesion, al destruirla se borran las variables
    session_start();
    session_destroy();
    session_start();
    $_SESSION['error_login']="";
    $_SESSION['error_registro']="";
    $_SESSION['error2']="";
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
                    <a href="registro.php" class="link">Registro</a>
                    <a href="loginvista.php" class="link">Iniciar Sesion</a>
                </nav>
            </header>

            <div class="banner">
                <div class="banner_textos">
                    <h1>Obtenga la mejor comparación</h1>
                    <p>Somos la empresa puntera que te compara tus precios
                        entre compañias electricas. Elijenos !!</p>
                    <a href="loginvista.php">CONSULTA AQUI!</a>
                </div>
            </div>
        </section>
</body>
</html>