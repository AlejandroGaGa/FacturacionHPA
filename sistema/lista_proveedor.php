<?php 
session_start();
if($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2)
{
  header('location: ../');
}

include "../conexion.php";
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php 
	include "includes/scripts.php";
	?>
		
	<title>HPA | Proveedores Registrados</title>
</head>
<body>
	<?php 
	include "includes/nav.php"
	?><br><br>
	<br>
	<br>
	<br>
	<section id="container">
	<h1><i class="far fa-address-card"></i> Lista | Proveedores</h1>
	<a href="registro_proveedor.php" class="btn_new"><i class="fas fa-ad"></i> Crear un nuevo proveedor</a>
	<br>
    <br>

<form action="buscar_proveedor.php" method="get" class="form_search">
	<input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="">
	<button type="submit" class="btn_search"><i class="fas fa-search"></i> Buscar</button>
	
</form>

	<table>
		<tr>
			<th>ID</th>
			<th>Proveedor</th>
			<th>Contacto</th>
			<th>Telefono</th>
			<th>Dirección</th>
			<th>Fecha de creación</th>
			<th>Acciones</th>
		</tr>
		<?php 


		//paginador
		$sql_register = mysqli_query($conection, "SELECT COUNT(*) AS total_registro FROM proveedor Where estatus = 1");

		$result_register = mysqli_fetch_array($sql_register);
		$total_registro = $result_register['total_registro'];

		$por_pagina = 5;

		if(empty($_GET['pagina'])){
			$pagina = 1;
		}
		else{
			$pagina = $_GET['pagina'];
		}


		$desde = ($pagina-1) * $por_pagina;
		 $total_paginas = ceil($total_registro / $por_pagina);


        // query para extraer todos los registros de mi base 
        //SELECT u.idusuario, u.nombre, u.correo, u.usuario, r.rol from usuario AS u INNER JOIN rol  as r ON u.rol = r.idrol
		$query = mysqli_query($conection, "SELECT* FROM proveedor 
			                               WHERE estatus = 1 ORDER BY codproveedor ASC LIMIT $desde,$por_pagina");
		mysqli_close($conection);// cierro conexion por que ya ubtuve mis datos

		$result = mysqli_num_rows($query);

		if($result > 0)
		{
			while ($data= mysqli_fetch_array($query)) 
			{
				# code...
				$formato = 'Y-m-d H:i:s';// para modificar fecha
				$fecha = DateTime::createFromFormat($formato,$data["date_add"]);		
			?>
		<tr>
			<td><?php echo $data['codproveedor']; ?></td>
			<td><?php echo $data['proveedor']; ?></td>
			<td><?php echo $data['contacto']; ?></td>
			<td><?php echo $data['telefono']; ?></td>
			<td><?php echo $data['direccion']; ?></td>
			<td><?php echo $fecha->format('d-m-Y'); ?></td>
			<td>
				<a class="link_edit" href="editar_proveedor.php?id=<?php echo $data["codproveedor"]; ?>"><i class="fas fa-edit"></i>

 Editar.</a>
 |
				<!--Paso el id por medio de php para poder indicar a que cliente actualizar-->
             
				<a class="link_delete" href="eliminar_confirmar_proveedor.php?id=<?php echo $data["codproveedor"]; ?>"><i class="fas fa-trash"></i>

 Eliminar.</a>
			
				
			</td>
		</tr>
	<?php
		}
	  }
	?>
	</table>
	<div class ="paginador">
		<ul>

			<?php 
        if($pagina != 1){

       
			?>
			<li><a href="?pagina=<?php echo 1;?>">|<</a></li>
			<li><a href="?pagina=<?php echo $pagina-1;?>"><<</a></li>
			 
			<?php 
			 }
           for ($i=1; $i <= $total_paginas; $i++) { 
           	# code...
           	if($i == $pagina)
           	{
             echo '<li class="pageselected">'.$i.'</li>';
           	}else{
           	echo '<li><a href="?pagina='.$i.'">'.$i.'</a></li>';
           }
           }
           if($pagina != $total_paginas)
           {
			?>
			<li><a href="?pagina=<?php echo $pagina+1; ?>">>></a></li>
			<li><a href="?pagina=<?php echo $total_paginas; ?>">>>|</a></li>
			<?php 
             }
			?>
		</ul>
	</div>
	</section>

	<?php 
	include "includes/footer.php"
	?>
</body>
</html>