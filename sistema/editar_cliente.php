<?php
session_start();
//if($_SESSION['rol'] != 1)
//{
  //header('location: ../');
//}
  //lo comente por lo mismo de que editar clientes si lo puede hacer cualquier rol
include "../conexion.php";

if(!empty($_POST))
{
$alert='';
if(empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['direccion']))
 {
 $alert = '<p class="msg_error">Todos los campos son obligatorios.</p>';
 }// fin de if empty
 else
 {
   
   $idcliente = $_POST['id'];
   $nit = $_POST['nit'];
   $nombre = $_POST['nombre'];
   $telefono = $_POST['telefono']; 
   $direccion = ($_POST['direccion']);
 

    $result =0;

    if(is_numeric($nit) and $nit != 0 )
    {

   //echo "SELECT * FROM usuario WHERE usuario = '$user' OR correo = '$email' ";
   $query = mysqli_query($conection,"SELECT* FROM cliente
                              WHERE (nit = '$nit' and idcliente != '$idcliente')");

   $result = mysqli_fetch_array($query);
   $result = $result;
    }

   if($result > 0 ){
    $alert = '<p class="msg_error">El NIP ya existe, ingrese otro.</p>';
   }// fin de if result
   else{
    if($nit == '')
    {
      $nit =0;
    }
 
    
    $sql_update = mysqli_query($conection, "UPDATE cliente 
                                                SET nit = '$nit', nombre = '$nombre', telefono = '$telefono', direccion = '$direccion' WHERE  idcliente = $idcliente");
    
     if($sql_update){
// si es verdadero si se inserto o eso creo 0.o :(
    $alert = '<p class="msg_save">Cliente actualizado correctamente.</p>';
     }// fin de if query_insert
     else{
        $alert = '<p class="msg_error">Error al actualizar el cliente.</p>';
     }// fin de else de if query_insert

   }// fin de else if result

 }// fin de else de empty
   //mysqli_close($conection);
}//fin de !empty

//Mostrando datos
if(empty($_REQUEST['id']))// POR QUE TIENE LA CAPACIDA DE RECIBIR GET Y POST
{
 header('Location: lista_cliente.php');
 mysqli_close($conection);
}

$idcliente = $_REQUEST['id'];
$sql = mysqli_query($conection,"SELECT * FROM cliente u
WHERE idcliente = $idcliente and estatus = 1 ");
mysqli_close($conection);

$result_sql=mysqli_num_rows($sql);

if($result_sql == 0 )
{
 header('Location: lista_cliente.php');
}
else{
  //$option = '';
  while ($data = mysqli_fetch_array($sql)) 
  {

    # code...
  $idcliente = $data['idcliente'];
    $nit = $data['nit'];
      $nombre = $data['nombre'];
        $telefono = $data['telefono'];
          $direccion = $data['direccion'];
           
  }// fin del while
}// fin del esle


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php 
	include "includes/scripts.php";
	?>
		
	<title>HPA | Actualiza Cliente</title>
  
</head>
<body>
	<?php 
	include "includes/nav.php"
	?><br><br>
  <br>
  <br>
  <br>
	<section id="container">
		<div class="form_register">
        <h1>Actualización de cliente.</h1>
        <hr>
        <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
        <form action="" method="post">
          <input type="hidden" name="id" value="<?php echo $idcliente; ?>">
        <label for="nit">RFC/clave de cliente</label>
        <input type ="text" name="nit" id="nit" placeholder="Numero de Identidficación Personal" value="<?php echo $nit; ?>"></input>
        <label for="nombre">Nombre</label>
        <input type ="text" name="nombre" id="nombre" placeholder="Nombre Completo.(obligatorios)" value="<?php echo $nombre; ?>"></input>
        <label for="usuario">Telefono</label>
        <input type ="phone" name="telefono" id="telefono" placeholder="Telefono local.(obligatorios)" value="<?php echo $telefono; ?>"></input>
       <label for="clave">Dirección</label>
        <input type ="text" name="direccion" id="direccion" placeholder="Dirección completa.(obligatorios)" value="<?php echo $direccion; ?>"></input>
      <input type="submit" value="Actualizar cliente" class="btn_save">
     <center> <a type="submit"  class="btn_save" href="lista_cliente.php">Regresar a lista de clientes </a></center>
          
       </form>
        </div>
        </div>
	</section>

	<?php 
	include "includes/footer.php"
	?>
</body>
</html>