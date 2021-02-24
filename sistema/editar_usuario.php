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
if(empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario']) || empty($_POST['rol']))
 {
 $alert = '<p class="msg_error">Todos los campos son obligatorios.</p>';
 }// fin de if empty
 else
 {
   
   $idUsuario = $_POST['id'];
   $nombre = $_POST['nombre'];
   $email = $_POST['correo'];
   $user = $_POST['usuario']; 
   $clave = md5($_POST['clave']);// md5 para encriptar la contraseña y evitar hacking
   $rol = $_POST['rol'];
   // consulta para verificar si el correo ya existe o el usuario

   //echo "SELECT * FROM usuario WHERE usuario = '$user' OR correo = '$email' ";
   $query = mysqli_query($conection,"SELECT * FROM usuario
                              WHERE (usuario = '$user' AND idusuario != $idUsuario)
                              OR (correo  = '$email' AND idusuario != $idUsuario and estatus = 1) ");

   $result = mysqli_fetch_array($query);
   $result = $result;

   if($result > 0 ){
    $alert = '<p class="msg_error">El correo o el usuario ya existe.</p>';
   }// fin de if result
   else{
    if(empty($_POST['clave'])){
    
    $sql_update = mysqli_query($conection, "UPDATE usuario 
                                                SET nombre = '$nombre', correo = '$email', usuario = '$user', rol = '$rol' WHERE  idusuario = $idUsuario");
    }
    else{

    $sql_update = mysqli_query($conection, "UPDATE usuario 
                                                SET nombre = '$nombre', correo = '$email', usuario = '$user',clave = '$clave', rol = '$rol' WHERE  idusuario = $idUsuario");
    }
     if($sql_update){
// si es verdadero si se inserto o eso creo 0.o :(
    $alert = '<p class="msg_save">Usuario actualizado correctamente.</p>';
     }// fin de if query_insert
     else{
        $alert = '<p class="msg_error">Error al actualizar el usuario.</p>';
     }// fin de else de if query_insert

   }// fin de else if result

 }// fin de else de empty
   //mysqli_close($conection);
}//fin de !empty

//Mostrando datos
if(empty($_REQUEST['id']))// POR QUE TIENE LA CAPACIDA DE RECIBIR GET Y POST
{
 header('Location: lista_usuario.php');
 mysqli_close($conection);
}

$iduser = $_REQUEST['id'];
$sql = mysqli_query($conection,"SELECT u.idusuario, u.nombre, u.correo, u.usuario, (u.rol) as idrol, (r.rol) as rol 
FROM usuario u
INNER JOIN rol r
ON u.rol = r.idrol
WHERE idusuario= $iduser ");
mysqli_close($conection);

$result_sql=mysqli_num_rows($sql);

if($result_sql == 0 )
{
 header('Location: lista_usuario.php');
}
else{
  $option = '';
  while ($data = mysqli_fetch_array($sql)) {

    # code...
  $iduser = $data['idusuario'];
    $nombre = $data['nombre'];
      $correo = $data['correo'];
        $usuario = $data['usuario'];
          $idrol = $data['idrol'];
            $rol = $data['rol'];
            if($idrol == 1){
              $option = '<option value="'.$idrol.'" select>'.$rol.'</option>';
            }
            else if($idrol == 2){
               $option = '<option value="'.$idrol.'"select>'.$rol.'</option>';
            }
            else if($idrol == 3){
               $option = '<option value="'.$idrol.'"select>'.$rol.'</option>';
            }
  }
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php 
	include "includes/scripts.php";
	?>
		
	<title>HPA | Actualiza Usuario</title>
  
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
        <h1>Actualización de usuario.</h1>
        <hr>
        <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
        <form action="" method="post">
          <input type="hidden" name="id" value="<?php echo $iduser; ?>">
        <label for="nombre">Nombre</label>
        <input type ="text" name="nombre" id="nombre" placeholder="Nombre Completo." value="<?php echo $nombre ?>"></input>
        
        <label for="correo">Correo electronico</label>
        <input type ="email" name="correo" id="correo" placeholder="Correo Electronico." value="<?php echo  $correo;?>"</input>
       
        <label for="usuario">Usuario</label>
        <input type ="text" name="usuario" id="usuario" placeholder="Usuario." value="<?php echo  $usuario;?>"</input>
       
       <label for="clave">Contraseña</label>
        <input type ="password" name="clave" id="clave" placeholder="Clave de acceso."></input>
      
      <label for="rol">Tipo de usuario.</label>
      <?php 
      include "../conexion.php";
      $query_rol = mysqli_query($conection, "SELECT * FROM rol");
      mysqli_close($conection);
      $result_rol = mysqli_num_rows($query_rol);
      ?>

      <select name="rol" id="rol" class="notItemOne">
          <?php 
          echo $option;
          if($result_rol > 0 )
       {
            while($rol = mysqli_fetch_array($query_rol))
            {
      ?>
      <option value="<?php echo $rol["idrol"]; ?>"><?php echo $rol["rol"]; ?></option>
      <?php
            }
        }
          ?>
      <input type="submit" value="Guardar actualización" class="btn_save">
 <center> <a type="submit"  class="btn_save" href="lista_usuario.php">Regresar a lista de usuarios</a></center>
       </form>
        </div>
	</section>

	<?php 
	include "includes/footer.php"
	?>
</body>
</html>