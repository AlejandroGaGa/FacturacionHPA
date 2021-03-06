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
		
	<title>HPA | Ventas</title>
</head>
<body>
	<?php 
	include "includes/nav.php"
	?><br><br>
	<br>
	<br>
	<br>
	<section id="container">
	<h1><i class="fas fa-scroll"></i> Lista | Ventas</h1>
	<br>
	<a href="nueva_venta.php" class="btn_new"><i class="fas fa-paperclip"></i> Crear una nueva venta</a>
	<br>
    <br>

<form action="buscar_venta.php" method="get" class="form_search">
	<input type="text" name="busqueda" id="busqueda" placeholder="No. Factura">
	<button type="submit" class="btn_search"><i class="fas fa-search"></i> Buscar</button>
</form>
     
     <div>
     	<h5>Buscar por Fecha</h5>
     	<form action="buscar_venta.php" method="get" class="form_search_date">
     		<label>De: </label>
     		<input type="date" name="fecha_de" id="fecha_de" required>
     		<label> A </label>
     		<input type="date" name="fecha_a" id="fecha_a" required>
     	    <button type="submit" class="btn_view"><i class="fas fa-search"></i></button>
     	</form>
     </div>

	<table>
		<tr>
			<th>No.</th>
			<th>Fecha|Hora</th>
			<th>Cliente</th>
			<th>Vendedor</th>
			<th>Estado</th>
			<th class="textright">Total factura</th>
			<th class="textright">Acciones</th>
		</tr>
		<?php 


		//paginador
		$sql_register = mysqli_query($conection, "SELECT COUNT(*) AS total_registro FROM factura Where estatus != 10");

		$result_register = mysqli_fetch_array($sql_register);
		$total_registro = $result_register['total_registro'];

		$por_pagina = 10;

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
		$query = mysqli_query($conection,"SELECT f.nofactura,f.fecha,f.totalfactura,f.codcliente,f.estatus,u.nombre as vendedor, cl.nombre as cliente FROM factura as f
			INNER JOIN usuario u
			ON f.usuario = u.idusuario
			INNER JOIN cliente as cl
			ON f.codcliente = cl.idcliente
			WHERE f.estatus != 10
			ORDER BY f.fecha DESC LIMIT $desde,$por_pagina"); 

		mysqli_close($conection);// cierro conexion por que ya ubtuve mis datos

		$result = mysqli_num_rows($query);

		if($result > 0)
		{
			while ($data= mysqli_fetch_array($query)) 
			{
				# code...
				if($data['estatus'] == 1)
				{
					$estatus = '<span class="pagada">PAGADA</span>' ;
				}
				else{
                    $estatus = '<span class="anulada">ANULADA</span>';
				}
			?>
		<tr id="row_<?php echo $data['nofactura']; ?>">
			<td><?php echo $data['nofactura']; ?></td>
			<td><?php echo $data['fecha'];?></td>
			<td><?php echo $data['cliente']; ?></td>
			<td><?php echo $data['vendedor']; ?></td>
			<td class="estado"><?php echo $estatus ?></td>
			<td class="textright"><span>$.</span><?php echo $data['totalfactura']; ?></td>
			<td>
			<div class="div_acciones">
				<div>
					<button class="btn_view view_factura" type="button" cl="<?php echo $data['codcliente']; ?>" f="<?php echo $data['nofactura'] ?>"> <i class="fas fa-eye"></i></button>
				</div>
				<?php if($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2){
					if($data['estatus'] ==1)
					{
                     ?>

					
				<div class="div_factura">
					<button class="btn_anular anular_factura" fac="<?php echo $data['nofactura']; ?>"><i class="fas fa-ban"></i></button>
				</div>
					<?php
				     	}else{ 
				 ?>
				 <div class="div_factura">
					<button type="button" class="btn_anular inactive"><i class="fas fa-ban"></i></button>
				</div>
			<?php } 
		        } ?>
			</div>
				
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