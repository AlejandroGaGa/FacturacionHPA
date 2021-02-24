<?php 
session_start();
if($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2)
{
  header('location: ../');
}
include "../conexion.php";

if(!empty($_POST))
{

	if(empty($_POST['idproveedor']))
	{
		header("location: lista_proveedor.php");
		 mysqli_close($conection);
	}

$idproveedor = $_POST['idproveedor'];

//$query_delete = mysqli_query($conection,"DELETE FROM usuario WHERE idusuario = $idusuario");
$query_delete = mysqli_query($conection, "UPDATE proveedor SET estatus = 0 WHERE codproveedor = $idproveedor");
mysqli_close($conection);

if($query_delete){
	header("location: lista_proveedor.php");
}
else
{
	echo "Error al eliminar";
}
}// fin de if de post



if(empty($_REQUEST['id']))
{
 header("location: lista_proveedor.php");
 mysqli_close($conection);
}// fin de empty id
else{
	
	$idproveedor = $_REQUEST['id'];
	$query = mysqli_query($conection,"SELECT* FROM proveedor WHERE codproveedor = $idproveedor");

	mysqli_close($conection);
	$result = mysqli_num_rows($query);

	if($result > 0){ 
		while ($data = mysqli_fetch_array($query)) 
		{
		
			$proveedor = $data['proveedor'];
			//$rol = $data['rol'];
			# code...
		}// fin de while
		
	}// fin de if result
	else{
			 header("location: lista_proveedor.php");
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
		
	<title>HPA | Eliminar Proveedor</title>
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
			<i class="far fa-building fa-7x" style="color: #e66262"></i> 
			<h2>¿Estas seguro de eliminar el siguiente proveedor?</h2>
			<p>Nombre del proveedor: <span><?php echo $proveedor;?></span></p>
			<!--<p>NIP: <span><?php echo $nit;?></span></p>-->
		

			<form method="post" action="">
				<input type="hidden" name="idproveedor" value="<?php echo $idproveedor; ?>">
			
	    	<button type="submit" class="btn_ok"><i class="fas fa-trash "></i> Eliminar</button>
				<a href="lista_proveedor.php" class="btn_ok">Cancelar</a>
			</form>

		</div>

	</section>

	<?php 
	include "includes/footer.php"
	?>
</body>
</html>