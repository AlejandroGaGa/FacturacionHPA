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
		
	<title>HPA | Productos Registrados</title>
</head>
<body>
	<?php 
	include "includes/nav.php"
	?><br><br>
	<br>
	<br>
	<br>
	<section id="container">
	<h1><i class="fas fa-list-ol"></i>  Lista | Productos</h1>
	<a href="registro_producto.php" class="btn_new"><i class="fas fa-ellipsis-h"></i> Crear un nuevo Producto</a>
	<br>
    <br>

<form action="buscar_producto.php" method="get" class="form_search">
	<input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="">
	<button type="submit" class="btn_search"><i class="fas fa-search"></i> Buscar</button>
	
</form>

	<table>
		<tr>
			<th>Código</th>
			<th>Descripción</th>
			<th>Precio</th>
			<th>Existencia</th>
			<th>
				 <?php 

          $query_proveedor = mysqli_query($conection, "SELECT codproveedor, proveedor FROM proveedor WHERE estatus = 1 ORDER BY proveedor ASC");

          $result_proveedor = mysqli_num_rows($query_proveedor);
          //mysqli_close($conection);
         ?>
        <select name="proveedor" id="search_proveedor">
        	<option value= "" selected>PROVEEDOR</option>
        <?php
        if($result_proveedor > 0)
        {      
         while ($proveedor = mysqli_fetch_array($query_proveedor)) 
         {
           # code...
         ?>
        <option value="<?php echo $proveedor['codproveedor']; ?>"><?php echo $proveedor['proveedor']; ?></option>
         <?php
         }// fin de while
        }// fin de if 
          ?>
          </select>
			</th>
			<th>Fotos</th>
			<th>Acciones</th>
		</tr>
	
		<?php 


		//paginador
		$sql_register = mysqli_query($conection, "SELECT COUNT(*) AS total_registro FROM producto Where estatus = 1");

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
		$query = mysqli_query($conection, "SELECT p.codproducto, p.descripcion, p.precio, p.existencia, pr.proveedor, p.foto 
			FROM producto as p 
		    INNER JOIN  proveedor as pr
		    ON p.proveedor = pr.codproveedor
			 WHERE p.estatus = 1 ORDER BY p.codproducto DESC LIMIT $desde,$por_pagina");
		mysqli_close($conection);// cierro conexion por que ya ubtuve mis datos

		$result = mysqli_num_rows($query);

		if($result > 0)
		{
			while ($data= mysqli_fetch_array($query)) 
			{
				# code...
				if($data['foto'] != 'sinimagen.jpg'){
					$foto = 'img/inventario/'.$data['foto'];
				}else{
					$foto = 'img/'.$data['foto'];
				}
			 
			?>
		<tr class="row<?php echo $data['codproducto'] ?>">
			<td><?php echo $data['codproducto'] ?></td>
			<td><?php echo $data['descripcion'] ?></td>
			<td class="celPrecio"><?php echo $data['precio'] ?></td>
			<td class="celExistencia"><?php echo $data['existencia'] ?></td>
			<td><?php echo $data['proveedor'] ?></td>
		    <td ><img src="<?php echo $foto; ?>" alt="<?php echo $data['descripcion'] ?>" class="img_producto"></td>
                   <?php  if($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2){?>
			<td>

				<a class="link_add add_product" product = "<?php echo $data["codproducto"]; ?>" href="#"><i class="fas fa-plus"></i> Agregar.</a> |
				<a class="link_edit" href="editar_producto.php?id=<?php echo $data["codproducto"]; ?>"><i class="fas fa-edit"></i> Editar.</a>
				<!--Paso el id por medio de php para poder indicar a que cliente actualizar-->
                |    
				<a class="link_delete del_product" href="#" product = "<?php echo $data["codproducto"]; ?>"><i class="fas fa-trash"></i> Eliminar.</a>
				
			</td>
				<?php  }?>
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

	
</body>

<?php 
	include "includes/footer.php"
	?>
	
</html>