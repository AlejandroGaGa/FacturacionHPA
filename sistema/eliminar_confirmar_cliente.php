<?php 
session_start();
if($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2)
{
  header('location: ../');
}
include "../conexion.php";

if(!empty($_POST)){

	if(empty($_POST['idcliente'])){
		header("location: lista_cliente.php");
		 mysqli_close($conection);
	}
$idcliente = $_POST['idcliente'];

//$query_delete = mysqli_query($conection,"DELETE FROM usuario WHERE idusuario = $idusuario");
$query_delete = mysqli_query($conection, "UPDATE cliente SET estatus = 0 WHERE idcliente = $idcliente");
mysqli_close($conection);
if($query_delete){
	header("location: lista_cliente.php");
}
else
{
	echo "Error al eliminar";
}
}// fin de if de post

if(empty($_REQUEST['id']))
{
 header("location: lista_cliente.php");
 mysqli_close($conection);
}// fin de empty id
else{
	
	$idcliente = $_REQUEST['id'];
	$query = mysqli_query($conection,"SELECT* FROM cliente WHERE idcliente = $idcliente");
	$result = mysqli_num_rows($query);
	mysqli_close($conection);
	if($result > 0){ 
		while ($data = mysqli_fetch_array($query)) 
		{
			$nit = $data['nit'];
			$nombre = $data['nombre'];
			//$rol = $data['rol'];
			# code...
		}// fin de while
		
	}// fin de if result
	else{
			 header("location: lista_cliente.php");
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
		
	<title>HPA | Eliminar Cliente</title>
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
			<p>Nombre del cliente: <span><?php echo $nombre;?></span></p>
			<p>NIP: <span><?php echo $nit;?></span></p>
		

			<form method="post" action="">
				<input type="hidden" name="idcliente" value="<?php echo $idcliente; ?>">
			
	    	<button type="submit" class="btn_ok"><i class="fas fa-trash "></i> Eliminar</button>
				<a href="lista_cliente.php" class="btn_ok">Cancelar</a>
			</form>

		</div>

	</section>

	<?php 
	include "includes/footer.php"
	?>
</body>
</html>