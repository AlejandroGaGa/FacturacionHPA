<?php

session_start();


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
   
   $nit        = $_POST['nit'];
   $nombre     = $_POST['nombre'];
   $telefono   = $_POST['telefono'];
   $direccion  = $_POST['direccion']; 
   $usuario_id = $_SESSION['idUser'];

   $result = 0;

   if(is_numeric($nit) and $nit !=0)
   {

     $query = mysqli_query($conection,"SELECT * FROM cliente WHERE nit = '$nit'"); 
    $result = mysqli_fetch_array($query);
   } 

  if($result >0)
      { 

      $alert = '<p class="msg_error">El número de NIP ya existe.</p>';
      }
      else
      {
      $query_insert = mysqli_query($conection,"INSERT INTO cliente(nit,nombre,telefono,direccion,usuario_id)
                                             VALUES('$nit','$nombre','$telefono','$direccion','$usuario_id')");
    

 if($query_insert)
    {
// si es verdadero si se inserto o eso creo 0.o :(
    $alert = '<p class="msg_save">Cliente creado correctamente.</p>';
     }// fin de if query_insert
     else
     {
        $alert = '<p class="msg_error">Error al crear el cliente.</p>';
     }// fin de else de if query_insert

      }// fin de else  en donde si el NIP ya existe entonces si se insertara la informacion 
     mysqli_close($conection);

   }// fin de else de empty

 }




?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <?php 
  include "includes/scripts.php";
  ?>
    
  <title>HPA | Registro cliente</title>
  
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
        <h1><i class="fas fa-user-plus"></i> Registro de cliente.</h1>
        <hr>
        <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
  

        <form action="" method="post">
        <label for="nit">RFC / clave de cliente</label>
        <input type ="text" name="nit" id="nit" placeholder="Numero de Identidficación Personal"></input>
        <label for="nombre">Nombre</label>
        <input type ="text" name="nombre" id="nombre" placeholder="Nombre Completo.(obligatorios)"></input>
        <label for="usuario">Telefono</label>
        <input type ="number" name="telefono" id="telefono" placeholder="Telefono local.(obligatorios)"></input>
       <label for="clave">Dirección</label>
        <input type ="text" name="direccion" id="direccion" placeholder="Dirección completa.(obligatorios)"></input>
     <center> <button type="submit" class="btn_save"><i class="fas fa-user-plus"></i> Crear cliente</button></center>
    <!-- <center> <a type="submit"  class="btn_save" href="lista_cliente.php">Regresar a lista de clientes </a></center>-->
       </form>
        </div>
      
      
       
        
  </section>

  <?php 
  include "includes/footer.php"
  ?>
</body>
</html>