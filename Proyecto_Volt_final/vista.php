<?php
#este archivo hace que si hay una foto asignada al usuario la convierte al formato correspondiente y espera a ser llamado para mostrarla
if(!empty($_GET['id'])){
    session_start();
    $dni=$_SESSION['dni'];
    include "conexion.php";
    $conexion = conexion();
    $result = $conexion->query("SELECT foto FROM fotos WHERE dni ='".$dni."'");
    
    if($result->num_rows > 0){
        $imgDatos = $result->fetch_assoc();
        
        //Mostrar Imagen
        header("Content-type: image/jpg"); 
        echo $imgDatos['foto']; 
    }
}
?>