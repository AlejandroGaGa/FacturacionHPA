<?php

if (empty($_SESSION['active'])) {
  header('location: ../');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<nav>
  <div class="logo">
    <a href="index.php"> <img src="img/LogoHPA.jpg" alt="Logo Image"></a>
  </div>
  <div class="hamburger">
    <div class="line1"></div>
    <div class="line2"></div>
    <div class="line3"></div>
  </div>
  <ul class="nav-links">
    <?php
    if ($_SESSION['rol'] == 1) {
      ?>
      <li><a href="#">Usuarios</a>
        <ul>
          <li><a href="registro_usuario.php"><i class="fas fa-user-ninja"></i> Nuevo Usuario</a></li>
          <li> <a href="lista_usuario.php"><i class="fas fa-users"></i> Lista de Usuarios</a></li>
        </ul>
      </li>
      <?php
    } ?>
    <li><a href="#">Clientes</a>
      <ul>
        <li><a href="registro_cliente.php"><i class="fas fa-user-plus"></i> Nuevo Cliente</a></li>
        <li> <a href="lista_cliente.php"><i class="fas fa-user-check"></i> Lista de Clientes</a></li>
      </ul>
    </li>
    <li><a href="#">Proveedores</a>
      <ul class="nav-dropdown">
        <li>
          <a href="registro_proveedor.php"><i class="fas fa-ad"></i> Nuevo Proveedor</a>
        </li>
        <li>
          <a href="lista_proveedor.php"><i class="far fa-address-card"></i> Lista de Proveedores</a>
        </li>
      </ul>
    </li>
    <li><a href="#">Productos</a>
      <ul class="nav-dropdown">
        <?php if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) { ?>
          <li>
            <a href="registro_producto.php"><i class="fas fa-ellipsis-h"></i> Nuevo Producto</a>
          </li>
        <?php } ?>
        <li>
          <a href="lista_producto.php"><i class="fas fa-list-ol"></i> Lista de Productos</a>
        </li>
      </ul>
    </li>
    <li>
      <a href="#!">Ventas</a>
      <ul class="nav-dropdown">
        <li>
          <a href="nueva_venta.php"><i class="fas fa-paperclip"></i> Nueva Venta</a>
        </li>
        <li>
          <a href="ventas.php"><i class="fas fa-scroll"></i> Facturas</a>
        </li>
      </ul>
    </li>
    <li> <a href="salir.php"><img class="close" src="img/salir.png" alt="Salir del sistema" title="Salir"></a></li>
    <li><a class="join-button" href="registro_usuario.php">+ Usuario</a></li>
  </ul>
</nav>
<script src="js/navbar.js"></script>
<div class="modal">
  <div class="bodyModal">

  </div>
</div>

</html>