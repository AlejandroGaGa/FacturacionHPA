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
if(empty($_POST['proveedor']) || empty($_POST['producto']) || empty($_POST['precio']) || empty($_POST['cantidad']))
 {
 $alert = '<p class="msg_error">Todos los campos son obligatorios.</p>';
 }// fin de if empty
 else
 {
   
   $proveedor  = $_POST['proveedor'];
   $producto   = $_POST['producto'];
   $precio   = $_POST['precio'];
   $cantidad  = $_POST['cantidad']; 
   $usuario_id = $_SESSION['idUser'];
// dejo la variable de id user para saber que usuario registra el producto
   $foto = $_FILES['foto'];
   $nombre_foto = $foto['name'];
   $type = $foto['type'];
   $url_temp = $foto['tmp_name'];

   $imgProducto = 'sinimagen.jpg';

   if($nombre_foto != '')
   {
    $destino = 'img/inventario/';
    $img_nombre = 'img_'.md5(date('d-m-Y H:m:s'));
    $imgProducto= $img_nombre.'.jpg';
    $src = $destino.$imgProducto;
   }

 $query_insert = mysqli_query($conection,"INSERT INTO producto(proveedor,descripcion,precio,existencia,usuario_id,foto)
                                             VALUES('$proveedor','$producto','$precio','$cantidad','$usuario_id','$imgProducto')");

 if($query_insert)
    {
// si es verdadero si se inserto o eso creo 0.o :(
      if($nombre_foto != ''){
        move_uploaded_file($url_temp,$src);
      }
    $alert = '<p class="msg_save">Producto guardado correctamente.</p>';
     }// fin de if query_insert
     else
     {
        $alert = '<p class="msg_error">Error al guardar el producto.</p>';

      }// fin de else  en donde si el NIP ya existe entonces si se insertara la informacion 
   }// fin de else de empty
  // mysqli_close($conection);
 }




?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <?php 
  include "includes/scripts.php";
  ?>
    
  <title>HPA | Registro producto</title>
  
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
        <h1><i class="fab fa-product-hunt"></i> Registro de producto</h1>
        <hr>
        <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
  

        <form action="" method="post" enctype="multipart/form-data">
        <label for="proveedor">Proveedor</label>
        <?php 

          $query_proveedor = mysqli_query($conection, "SELECT codproveedor, proveedor FROM proveedor WHERE estatus = 1 ORDER BY proveedor ASC");

          $result_proveedor = mysqli_num_rows($query_proveedor);
          mysqli_close($conection);
         ?>
        <select name="proveedor" id="proveedor">
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
        <input type ="text" name="producto" id="producto" placeholder="Nombre del producto"></input>
        <label for="usuario">Pecio</label>
        <input type ="number" name="precio" id="precio" placeholder="Precio del producto"></input>
       <label for="clave">Cantidad</label>
        <input type ="number" name="cantidad" id="cantidad" placeholder="Cantidad del producto"></input>
        <div class="photo">
       <label for="foto">Foto</label>
        <div class="prevPhoto">
        <span class="delPhoto notBlock">X</span>
        <label for="foto"></label>
        </div>
        <div class="upimg">
        <input type="file" name="foto" id="foto" accept="image/png, .jpeg, .jpg, image/gif" >
        </div>
        <div id="form_alert"></div>
        </div>

     <center> <button type="submit" class="btn_save"><i class="fas fa-user-plus"></i> Guardar producto</button></center>
    <!-- <center> <a type="submit"  class="btn_save" href="lista_cliente.php">Regresar a lista de clientes </a></center>-->
       </form>
        </div>
      
      
       
        
  </section>

  <?php 
  include "includes/footer.php"
  ?>
</body>
</html>