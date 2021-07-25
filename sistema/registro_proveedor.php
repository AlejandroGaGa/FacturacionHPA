<?php

session_start();

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
   
   $proveedor        = $_POST['proveedor'];
   $contacto     = $_POST['contacto'];
   $telefono   = $_POST['telefono'];
   $direccion  = $_POST['direccion']; 
   $usuario_id = $_SESSION['idUser'];

   
 $query_insert = mysqli_query($conection,"INSERT INTO proveedor(proveedor,contacto,telefono,direccion,usuario_id)
                                             VALUES('$proveedor','$contacto','$telefono','$direccion','$usuario_id')");

 if($query_insert)
    {
// si es verdadero si se inserto o eso creo 0.o :(
    $alert = '<p class="msg_save">Proveedor creado correctamente.</p>';
     }// fin de if query_insert
     else
     {
        $alert = '<p class="msg_error">Error al crear el proveedor.</p>';

      }// fin de else  en donde si el NIP ya existe entonces si se insertara la informacion 
   }// fin de else de empty
   mysqli_close($conection);
 }




?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <?php 
  include "includes/scripts.php";
  ?>
    
  <title>HPA | Registro proveedor</title>
  
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
        <h1><i class="far fa-bookmark"></i> Registro de proveedor</h1>
        <hr>
        <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
  

        <form action="" method="post">
        <label for="proveedor">Proveedor</label>
        <input type ="text" name="proveedor" id="proveedor" placeholder="Nombre del Proveedor"></input>
        <label for="contacto">Contacto</label>
        <input type ="text" name="contacto" id="contacto" placeholder="Nombre completo contacto.(obligatorios)"></input>
        <label for="usuario">Telefono</label>
        <input type ="number" name="telefono" id="telefono" placeholder="Telefono local.(obligatorios)"></input>
       <label for="clave">Dirección</label>
        <input type ="text" name="direccion" id="direccion" placeholder="Dirección completa.(obligatorios)"></input>
     <center> <button type="submit" class="btn_save"><i class="fas fa-user-plus"></i> Crear proveedor</button></center>
    <!-- <center> <a type="submit"  class="btn_save" href="lista_cliente.php">Regresar a lista de clientes </a></center>-->
       </form>
        </div>
      
      
       
        
  </section>

  <?php 
  include "includes/footer.php"
  ?>
</body>
</html>