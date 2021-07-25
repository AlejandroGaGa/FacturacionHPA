<?php
session_start();
//if($_SESSION['rol'] != 1)
//{
  //header('location: ../');
//}
  //lo comente por lo mismo de que editar clientes si lo puede hacer cualquier rol
if($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2)
{
  header('location: ../');
}
include "../conexion.php";

if(!empty($_POST))
{
$alert='';
if(empty($_POST['proveedor']) || empty($_POST['contacto']) || empty($_POST['telefono']) || empty($_POST['direccion']))
 {
 $alert = '<p class="msg_error">Todos los campos son obligatorios.</p>';
 }// fin de if empty
 else
 {
   
   $idproveedor = $_POST['id'];
   $proveedor = $_POST['proveedor'];
   $contacto = $_POST['contacto'];
   $telefono = $_POST['telefono']; 
   $direccion = $_POST['direccion'];
 
 
    
    $sql_update = mysqli_query($conection, "UPDATE proveedor 
                                           SET proveedor = '$proveedor', contacto = '$contacto', telefono = '$telefono', direccion = '$direccion'  
                                           WHERE  codproveedor = $idproveedor");
    
     if($sql_update){
// si es verdadero si se inserto o eso creo 0.o :(
    $alert = '<p class="msg_save">Proveedor actualizado correctamente.</p>';
     }// fin de if query_insert
     else{
        $alert = '<p class="msg_error">Error al actualizar el Proveedor.</p>';
   }// fin de else if result

 }// fin de else de empty
   //mysqli_close($conection);
}//fin de !empty



//Mostrando datos
if(empty($_REQUEST['id']))// POR QUE TIENE LA CAPACIDA DE RECIBIR GET Y POST
{
 header('Location: lista_proveedor.php');
 mysqli_close($conection);
}

$idproveedor = $_REQUEST['id'];
$sql = mysqli_query($conection,"SELECT * FROM proveedor
WHERE codproveedor = $idproveedor and estatus = 1 ");
mysqli_close($conection);

$result_sql=mysqli_num_rows($sql);

if($result_sql == 0 )
{
 header('Location: lista_proveedor.php');
}
else{
  //$option = '';
  while ($data = mysqli_fetch_array($sql)) 
  {

    # code...
  $idproveedor = $data['codproveedor'];
    $proveedor = $data['proveedor'];
      $contacto = $data['contacto'];
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
		
	<title>HPA | Actualiza proveedor</title>
  
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
        <h1><i class="far fa-address-card"></i> Actualización de proveedor.</h1>
        <hr>
        <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
    <form action="" method="post">
      <input type="hidden" name="id" value="<?php echo $idproveedor; ?>">
        <label for="proveedor">Proveedor</label>
        <input type ="text" name="proveedor" id="proveedor" placeholder="Nombre del Proveedor.(obligatorios)" value="<?php echo $proveedor; ?>"></input>
        <label for="contacto">Contacto</label>
        <input type ="text" name="contacto" id="contacto" placeholder="Nombre completo contacto.(obligatorios)" value="<?php echo $contacto; ?>"></input>
        <label for="usuario">Telefono</label>
        <input type ="number" name="telefono" id="telefono" placeholder="Telefono local.(obligatorios)" value="<?php echo $telefono; ?>"></input>
       <label for="clave">Dirección</label>
        <input type ="text" name="direccion" id="direccion" placeholder="Dirección completa.(obligatorios)" value="<?php echo $direccion; ?>"></input>
     <center> <button type="submit" class="btn_save"><i class="fas fa-user-plus"></i> Actualizar proveedor</button></center>
    <!-- <center> <a type="submit"  class="btn_save" href="lista_cliente.php">Regresar a lista de clientes </a></center>-->
       </form>
        </div>
        </div>
	</section>

	<?php 
	include "includes/footer.php"
	?>
</body>
</html>