<?php 
session_start();
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
<body  >
	<?php 
	include "includes/nav.php";
	include "../conexion.php";

	$query_dash = mysqli_query($conection,"CALL datadashboard();");
	$result_dash = mysqli_num_rows($query_dash);
	if($result_dash >0){
		$data_dash = mysqli_fetch_assoc($query_dash);
		mysqli_close($conection);
	}
	?>
	<br><br>
	<br>
	<br>
	<br>
<section id ="container">
	<div class="divContainer">
		<div>
			<h1 class="titlePanelControl">Panel de Control</h1>
		</div>
		<div class="dashboard">
			<?php if($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2){ ?>
			<a href="lista_usuario.php">
			<i class="fas fa-users"></i>
				<p>
					<strong>Usuarios</strong><span><?php  echo $data_dash['usuarios']; ?></span> 
				</p>
			</a>
		<?php }  ?>
			<a href="lista_cliente.php">
				<i class="fas fa-user"></i>
				<p>
					<strong>Clientes</strong><span><?php  echo $data_dash['clientes']; ?></span> 
				</p>
			</a>
<?php if($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2){ ?>
			<a href="lista_proveedor.php">
				<i class="fas fa-building"></i>
				<p>
					<strong>Proveedores</strong><span><?php  echo $data_dash['proveedores']; ?></span> 
				</p>
			</a>
<?php }  ?>
			<a href="lista_producto.php">
				<i class="fas fa-cubes"></i>
				<p>
					<strong>Productos</strong><span><?php  echo $data_dash['productos']; ?></span> 
				</p>
			</a>

			<a href="ventas.php">
				<i class="fas fa-file-alt"></i>
				<p>
					<strong>Ventas del dia</strong><span><?php  echo $data_dash['ventas']; ?></span> 
				</p>
			</a>
		</div>
	</div>

	<div class="divInfoSistema">
		<div>
			<h1 class="titlePanelControl">Configuracion</h1>
		</div>
		<div class="containerPerfil">
			<div class="containerDataUser">
			<div class="logoUser">
				<img src="img/logo.png" height="100" width="100">
			</div>
			<div class="divDataUser">
				<h4>Informacion Personal</h4>

				<?php 
				$rol = $_SESSION['rol'];

				if($rol == 1)
				{
                     $rol = 'Administrador';
				}else if($rol == 2)
				{
				  $rol = 'Supervisor';
				}else
				{
					$rol = 'Vendedor';
				}

				?>
				<div>
					<label>Nombre:</label> <span><?php echo $_SESSION['nombre'] ?></span>
				</div>
				<div>
					<label>Correo:</label> <span><?php echo $_SESSION['email'] ?></span>
				</div>
				<h4>Datos Usuario</h4>
				<div>
					<label>Rol:</label> <span><?php echo $rol;  ?></span>
				</div>
				<div>
					<label>Usuario:</label> <span><?php  echo $_SESSION['user'] ?></span>
				</div>
				<h4>Cambiar Contraseña</h4>
				<form action="" method="post" name="frmChangePass" id="frmChangePass">
					<div>
						<input type="password" name="txtPassUser" id="txtPassUser" placeholder="Contraseña actual" required>
					</div>
					<div>
						<input  class="newPass" type="password" name="txtNewPassUser" id="txtNewPassUser" placeholder="Nueva Contraseña" required>
					</div>
					<div>
						<input class="newPass" type="password" name="txtPassConfirm" id="txtPassConfirm" placeholder="Contraseña actual" required>
					</div>
					<div class="alertChangePass" style="display: none;">
					</div>
					<div>
						<button type="submit" class="btn_save btnChangePass"><i class="fas fa-key"></i> Cambiar Contraseña</button>
					</div>
				</form>
			  </div>
			</div>
				</form>

			</div>
		</div>
	</div>
</section>
    <?php 
	include "includes/footer.php"
	?>
	
	 <script src="js/loader.js"></script>  
 <div class="lds-hourglass loader" id="loader"></div>
</body>

</html>