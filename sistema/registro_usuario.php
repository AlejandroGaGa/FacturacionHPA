<?php

session_start();

if($_SESSION['rol'] != 1)
{
  header('location: ../');
}
include "../conexion.php";
if(!empty($_POST))
{
$alert='';
if(empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario']) || empty(
    $_POST['clave']) || empty($_POST['rol']))
 {
 $alert = '<p class="msg_error">Todos los campos son obligatorios.</p>';
 }// fin de if empty
 else
 {
   

   $nombre = $_POST['nombre'];
   $email = $_POST['correo'];
   $user = $_POST['usuario']; 
   $clave = md5($_POST['clave']);// md5 para encriptar la contraseña y evitar hacking
   $rol = $_POST['rol'];
   // consulta para verificar si el correo ya existe o el usuario

   //echo "SELECT * FROM usuario WHERE usuario = '$user' OR correo = '$email' ";
   $query = mysqli_query($conection,"SELECT * FROM usuario WHERE usuario = '$user' OR correo = '$email'");
 //  mysqli_close($conection);
   $result = mysqli_fetch_array($query);

   if($result > 0 ){
    $alert = '<p class="msg_error">El correo o el usuario ya existe.</p>';
   }// fin de if result
   else{
    $query_insert = mysqli_query($conection,"INSERT INTO usuario(nombre,correo,usuario,clave,rol)
                                                         VALUES('$nombre', '$email', '$user', '$clave', '$rol')");
    //mysqli_close($conection);
     if($query_insert){
// si es verdadero si se inserto o eso creo 0.o :(
    $alert = '<p class="msg_save">Usuario creado correctamente.</p>';
     }// fin de if query_insert
     else{
        $alert = '<p class="msg_error">Error al crear el usuario.</p>';
     }// fin de else de if query_insert

   }// fin de else if result

 }// fin de else de empty

}//fin de !empty


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php 
	include "includes/scripts.php";
	?>
		
	<title>HPA | Registro usuario</title>
  
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
        <h1><i class="fas fa-user-ninja"></i> Registro de usuario.</h1>
        <hr>
        <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
        <form action="" method="post">
        <label for="nombre">Nombre</label>
        <input type ="text" name="nombre" id="nombre" placeholder="Nombre Completo."></input>
        
        <label for="correo">Correo electronico</label>
        <input type ="email" name="correo" id="correo" placeholder="Correo Electronico."></input>
       
        <label for="usuario">Usuario</label>
        <input type ="text" name="usuario" id="usuario" placeholder="Usuario."></input>
       
       <label for="clave">Contraseña</label>
        <input type ="password" name="clave" id="clave" placeholder="Clave de acceso."></input>
      
      <label for="rol">Tipo de usuario.</label>
      <?php 
      $query_rol = mysqli_query($conection, "SELECT * FROM rol");
      mysqli_close($conection);
      $result_rol = mysqli_num_rows($query_rol);
      ?>

      <select name="rol" id="rol">
          <?php 
          if($result_rol > 0 )
       {
            while($rol = mysqli_fetch_array($query_rol))
            {
      ?>
      <option value="<?php echo $rol["idrol"]; ?>"><?php echo $rol["rol"];?></option>
      <?php
            }
        }
          ?>
        </select>

     <center>  <button type="submit" class="btn_save"><i class="fas fa-user-ninja"></i> Crear Usuario</button> </center>
        <!--<center><a type="submit"  class="btn_save" href="lista_usuario.php">Regresar a lista de usuarios</a></center>-->
       </form>
        </div>
	</section>

	<?php 
	include "includes/footer.php"
	?>
</body>
</html>