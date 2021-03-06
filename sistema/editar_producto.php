<?php

session_start();

if($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2)
{
  header('location: ../');
}

include "../conexion.php";

if(!empty($_POST))
{
 
  $alert='';
if(empty($_POST['proveedor']) || empty($_POST['producto']) || empty($_POST['precio']) || empty($_POST['id'])|| empty($_POST['foto_actual']) || empty($_POST['foto_remove']))
 {
 $alert = '<p class="msg_error">Todos los campos son obligatorios.</p>';
 }// fin de if empty
 else
 {
   $codproducto = $_POST['id'];
   $proveedor  = $_POST['proveedor'];
   $producto   = $_POST['producto'];
   $precio   = $_POST['precio'];
   $imgProducto  = $_POST['foto_actual']; 
   $imgRemove = $_POST['foto_remove'];
// dejo la variable de id user para saber que usuario registra el producto
   $foto = $_FILES['foto'];
   $nombre_foto = $foto['name'];
   $type = $foto['type'];
   $url_temp = $foto['tmp_name'];

   $upd = '';

   if($nombre_foto != '')
   {
    $destino = 'img/inventario/';
    $img_nombre = 'img_'.md5(date('d-m-Y H:m:s'));
    $imgProducto= $img_nombre.'.jpg';
    $src = $destino.$imgProducto;
   }else
   {
    if($_POST['foto_actual'] != $_POST['foto_remove'])
    {
      $imgProducto = 'sinimagen.jpg';
    }// fin de if anidado
   }// fin de else de nombre foto

 $query_update = mysqli_query($conection,"UPDATE  producto
  SET descripcion = '$producto',
      proveedor = $proveedor,
      precio = $precio,
      foto = '$imgProducto'
      WHERE codproducto = $codproducto");

 if($query_update)
    {
// si es verdadero si se inserto o eso creo 0.o :(
      if(($nombre_foto != '' && $_POST['foto_actual'] != 'sinimagen.jpg') || ($_POST['foto_actual'] != $_POST['foto_remove'])) 
      {
      unlink ('img/inventario/'.$_POST['foto_actual']);
      }

      if($nombre_foto != ''){
        move_uploaded_file($url_temp,$src);
      }
    $alert = '<p class="msg_save">Producto actualizado correctamente.</p>';
     }// fin de if query_insert
     else
     {
        $alert = '<p class="msg_error">Error al actualizar el producto.</p>';

      }// fin de else  en donde si el NIP ya existe entonces si se insertara la informacion 
   }// fin de else de empty
  // mysqli_close($conection);
 }

//Validar producto
 if(empty($_REQUEST['id'])){
header("location: lista_producto.php");

 }// fin de if empty validación
 else{

  $id_producto = $_REQUEST['id'];
  // validación para saber si el valor que se manda es númerico
  if(!is_numeric($id_producto)){
   header("location: lista_producto.php");
  }// fin de validación para saber si es un numero
  $query_producto = mysqli_query($conection,"SELECT p.codproducto, p.descripcion,p.precio, p.foto, pr.codproveedor, pr.proveedor FROM producto as p 
    INNER JOIN proveedor as pr
    ON  p.proveedor = pr.codproveedor
    WHERE p.codproducto = $id_producto AND p.estatus = 1");

  $result_producto = mysqli_num_rows($query_producto);
// validacion de foto 
  $foto = '';
  $calssRemove = 'notBlock';

  if($result_producto >0){

    $data_producto = mysqli_fetch_assoc($query_producto);

    if($data_producto['foto'] != 'img_producto.png'){
      $calssRemove = '';
      $foto = '<img id="img" src="img/inventario/'.$data_producto['foto'].'" alt="">';
    }
    //print_r($data_producto);
  }else{
      header("location: lista_producto.php");
    }// fin de else validacion mayor a cero
 }// fin de else de  un valor existente 


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <?php 
  include "includes/scripts.php";
  ?>
    
  <title>HPA | Actualizar producto</title>
  
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
        <h1><i class="fab fa-product-hunt"></i> Actualización de producto</h1>
        <hr>
        <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
  

        <form action="" method="post" enctype="multipart/form-data">
          <input type="hidden" id= "id"  name="id" value="<?php echo $data_producto['codproducto']; ?>">
          <input type="hidden" id= "foto_actual" name="foto_actual" value="<?php echo $data_producto['foto']; ?>">
          <input type="hidden" id= "foto_remove" name="foto_remove" value="<?php echo $data_producto['foto']; ?>">
        <label for="proveedor">Proveedor</label>
        <?php 

          $query_proveedor = mysqli_query($conection, "SELECT codproveedor, proveedor FROM proveedor WHERE estatus = 1 ORDER BY proveedor ASC");

          $result_proveedor = mysqli_num_rows($query_proveedor);
          mysqli_close($conection);
         ?>
        <select name="proveedor" id="proveedor" class="notItemOne">
          <option value="<?php  echo $data_producto['codproveedor']; ?>" selected><?php  echo $data_producto['proveedor']; ?></option>
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
        <label for="producto">Producto</label>
        <input type ="text" name="producto" id="producto" placeholder="Nombre del producto" value="<?php echo $data_producto['descripcion']; ?>"></input>
        <label for="usuario">Pecio</label>
        <input type ="number" name="precio" id="precio" placeholder="Precio del producto" value="<?php echo $data_producto['precio']; ?>"></input>
       
        <div class="photo">
       <label for="foto">Foto</label>
        <div class="prevPhoto">
        <span class="delPhoto <?php echo$classRemove;?>">X</span>
        <label for="foto"></label>
        <?php
        echo $foto; ?>
        </div>
        <div class="upimg">
        <input type="file" name="foto" id="foto" accept="image/png, .jpeg, .jpg, image/gif" >
        </div>
        <div id="form_alert"></div>
        </div>

     <center> <button type="submit" class="btn_save"><i class="fas fa-user-plus"></i> Actualizar producto</button></center>
    <!-- <center> <a type="submit"  class="btn_save" href="lista_cliente.php">Regresar a lista de clientes </a></center>-->
       </form>
        </div>
      
      
       
        
  </section>

  <?php 
  include "includes/footer.php"
  ?>
</body>
</html>