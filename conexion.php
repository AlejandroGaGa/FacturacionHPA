
<?php
//INICIANDO CONEXIÓN PHP CON MYSQL 04 DE MAYO DEL 2020-->
$host = 'localhost';
$user = 'root';
$password ='';
$db ='dbherrajesparaaluminio';

$conection = @mysqli_connect($host,$user,$password,$db);

if(!$conection)
{
    echo "Error en la conexión.";
}
/*fin de conexión a base de datos en mysql*/
?>
