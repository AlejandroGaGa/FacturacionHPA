<?php 

session_start();


include "../conexion.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php 
	include "includes/scripts.php";
	?>
		
	<title>HPA | Clientes Encontrados</title>
</head>
<body>
	<?php 
	include "includes/nav.php"
	?>
	<br><br>
	<br>
	<br>
	<br>
	<section id="container">
		<?php 
           $busqueda = strtolower($_REQUEST['busqueda']);
           if(empty($busqueda)){
           	header("location: lista_cliente.php");

           	mysqli_close($conection);
           }

		?>
	<h1>Lista | Encontrados</h1>

	<a href="registro_cliente.php" class="btn_new">Crear un nuevo cliente.</a>
	<br>
    <br>
		<p>Si el (NIP) ES IGUAL A (0) SIGNIFICA QUE EL CLIENTE NO TIENE NÚMERO DE IDENTIFICACIÓN PERSONAL</p>

<form action="buscar_cliente.php" method="get" class="form_search">
	<input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda; ?>">
	<button type="submit" class="btn_search"><i class="fas fa-search"></i> Buscar</button>
</form>
	<table>
		<tr>
			<th>ID</th>
			<th>NIP</th>
			<th>Nombre</th>
			<th>Telefono</th>
			<th>Dirección</th>
			<th>Acciones</th>
		</tr>

		<?php 
		//paginador
		$sql_register = mysqli_query($conection, "SELECT COUNT(*) AS total_registro FROM cliente Where
		(
		    idcliente LIKE '%$busqueda%' OR 
		     nit LIKE '%$busqueda%' OR 
		     nombre LIKE '%$busqueda%' OR 
		     telefono LIKE '%$busqueda%' OR
		     direccion LIKE '%busqueda%'
		     
		)
		    AND  estatus = 1");

            
		$result_register = mysqli_fetch_array($sql_register);
		$total_registro = $result_register['total_registro'];

		$por_pagina = 5;

		if(empty($_GET['pagina']))
		{
			$pagina = 1;
		}
		else
		{
			$pagina = $_GET['pagina'];
		}


		$desde = ($pagina-1) * $por_pagina;
		 $total_paginas = ceil($total_registro / $por_pagina);


        // query para extraer todos los registros de mi base 
        //SELECT u.idusuario, u.nombre, u.correo, u.usuario, r.rol from usuario AS u INNER JOIN rol  as r ON u.rol = r.idrol
		
		// EN ESTE QUERY MODIFIQUE PARA PODER HAER UNA BUSQUEDA GENERAL Y AMPLIA PARA PODER BUSCAR HASTA POR UN NÚMERO DE COICIDENCIA
		$query = mysqli_query($conection, "SELECT* FROM 
			cliente WHERE 
		     (
		     idcliente LIKE '%$busqueda%' OR 
		     nit LIKE '$busqueda%'  OR 
		     nombre LIKE '%$busqueda%' OR 
		     telefono LIKE '%$busqueda%' OR 
		     direccion LIKE '%$busqueda%' 
		     ) 
		     AND 
		     estatus = 1 ORDER BY idcliente ASC LIMIT $desde,$por_pagina");

			mysqli_close($conection);
		
		$result = mysqli_num_rows($query);

		if($result > 0 )
		{
			while ($data= mysqli_fetch_array($query)) 
			{
				# code...

				if($data['nit'] = 0){
					$nit = "Sin nip";
				}
				else{
					$nit = $data['nit'];
				}
			?>
		<tr>
			<td><?php echo $data['idcliente'] ?></td>
			<td><?php echo $nit ?></td>
			<td><?php echo $data['nombre'] ?></td>
			<td><?php echo $data['telefono'] ?></td>
			<td><?php echo $data['direccion'] ?></td>
			<td>
				<a class="link_edit" href="editar_cliente.php?id=<?php echo $data['idcliente'] ?>"><i class="fas fa-edit"></i>

 Editar.</a><!--Paso el id por medio de php para poder indicar a que usuario actualizar-->

				<?php 
               if($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2){?>
				<a class="link_delete" href="eliminar_confirmar_cliente.php?id=<?php echo $data['idcliente'] ?>"><i class="fas fa-trash"></i>

 Eliminar.</a>
				<?php 
                  }
				?>
			</td>
		</tr>
	<?php
		}
	  }
	?>
	</table>

	<?php 
if($total_registro !=0 ){

	?>
	<div class ="paginador">
		<ul>

			<?php 
        if($pagina != 1){

       
			?>
			<li><a href="?pagina=<?php echo 1;?>&busqueda=<?php echo  $busqueda ?>">|<</a></li>
			<li><a href="?pagina=<?php echo $pagina-1;?>&busqueda=<?php echo  $busqueda ?>"><<</a></li>
			 
			<?php 
			 }
           for ($i=1; $i <= $total_paginas; $i++) { 
           	# code...
           	if($i == $pagina)
           	{
             echo '<li class="pageselected">'.$i.'</li>';
           	}else{
           	echo '<li><a href="?pagina='.$i.'$busqueda='.$busqueda.'>'.$i.'</a></li>';
           }
           }
           if($pagina != $total_paginas)
           {
			?>
			<li><a href="?pagina=<?php echo $pagina+1; ?>&busqueda=<?php echo  $busqueda ?>">>></a></li>
			<li><a href="?pagina=<?php echo $total_paginas; ?>&busqueda=<?php echo  $busqueda ?>">>>|</a></li>
			<?php 
             }
			?>
		</ul>
	</div>

	<?php 
          }
	  ?>
	</section>

	<?php 
	include "includes/footer.php"
	?>
</body>
</html>