<?php
#en este archivo hacemos la conexion con la base de datos
function conexion() {
  $mysqli_conexion = new mysqli("localhost", "root", "iker1234(908)", "aplicacion_consumo");
  if ($mysqli_conexion->connect_errno) {
    echo "Error de conexión con la base de datos: " . $mysqli_conexion->connect_errno;
    exit;
  }
  return $mysqli_conexion;
}
?>