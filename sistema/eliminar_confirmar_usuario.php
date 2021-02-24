<?php 
session_start();
if($_SESSION['rol'] != 1)
{
  header('location: ../');
}
include "../conexion.php";

if(!empty($_POST)){

	if($_POST['idusuario'] == 1){
		header("location: lista_usuario.php");
		mysqli_close($conection);
		exit;
	}
$idusuario = $_POST['idusuario'];

//$query_delete = mysqli_query($conection,"DELETE FROM usuario WHERE idusuario = $idusuario");
$query_delete = mysqli_query($conection, "UPDATE usuario SET estatus = 0 WHERE idusuario = $idusuario");
mysqli_close($conection);
if($query_delete){
	header("location: lista_usuario.php");
}
else
{
	echo "Error al eliminar";
}
}// fin de if de post

if(empty($_REQUEST['id']) || ($_REQUEST['id'] == 1))
{
 header("location: lista_usuario.php");
 mysqli_close($conection);
}// fin de empty id
else{
	
	$idusuario = $_REQUEST['id'];
	$query = mysqli_query($conection,"SELECT u.nombre, u.usuario, r.rol
										FROM usuario u
										INNER JOIN
										rol r
										ON u.rol = r.idrol
										WHERE u.idusuario = $idusuario");
	$result = mysqli_num_rows($query);
	mysqli_close($conection);
	if($result > 0){ 
		while ($data = mysqli_fetch_array($query)) 
		{
			$nombre = $data['nombre'];
			$usuario = $data['usuario'];
			$rol = $data['rol'];
			# code...
		}// fin de while
		
	}// fin de if result
	else{
			 header("location: lista_usuario.php");
			}// fin de else de result
}// fin de else include

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php 
	include "includes/scripts.php";
	?>
		
	<title>HPA | Eliminar Usuario</title>
</head>
<body>
	<?php 
	include "includes/nav.php"
	?><br><br>
	<br>
	<br>
	<br>
	<section id="container">
		<div class="data_delete">
			<h2><i class="fas fa-trash fa-3x"></i> ¿Estas seguro de eliminar el siguiente registro?</h2>
			<p>Nombre: <span><?php echo $nombre;?></span></p>
			<p>Usuario: <span><?php echo $usuario;?></span></p>
			<p>Tipo usuario: <span><?php echo $rol;?></span></p>
			<form method="post" action="">
				<input type="hidden" name="idusuario" value="<?php echo $idusuario; ?>">
	
				<button type="submit" class="btn_ok"><i class="fas fa-trash "></i> Aceptar</button>
							<a href="lista_usuario.php" class="btn_ok">Cancelar</a>
			</form>
		</div>

	</section>

	<?php 
	include "includes/footer.php"
	?>
</body>
</html>