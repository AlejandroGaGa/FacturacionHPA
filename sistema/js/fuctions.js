
$(document).ready(function(){

    //--------------------- SELECCIONAR FOTO PRODUCTO ---------------------
    $("#foto").on("change",function(){
        var uploadFoto = document.getElementById("foto").value;
        var foto       = document.getElementById("foto").files;
        var nav = window.URL || window.webkitURL;
        var contactAlert = document.getElementById('form_alert');
        
            if(uploadFoto !='')
            {
                var type = foto[0].type;
                var name = foto[0].name;
                if(type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png')
                {
                    contactAlert.innerHTML = '<p class="errorArchivo">El archivo no es válido.</p>';                        
                    $("#img").remove();
                    $(".delPhoto").addClass('notBlock');
                    $('#foto').val('');
                    return false;
                }else{  
                        contactAlert.innerHTML='';
                        $("#img").remove();
                        $(".delPhoto").removeClass('notBlock');
                        var objeto_url = nav.createObjectURL(this.files[0]);
                        $(".prevPhoto").append("<img id='img' src="+objeto_url+">");
                        $(".upimg label").remove();
                        
                    }
              }else{
                alert("No selecciono foto");
                $("#img").remove();
              }              
    });

    $('.delPhoto').click(function(){
        $('#foto').val('');
        $(".delPhoto").addClass('notBlock');
        $("#img").remove();
        
         if($("#foto_actual") && ("#foto_remove"))
         {
            $("#foto_remove").val('sinimagen.jpg');
         }
    });

    // MODAL FORM PARA AGREGAR EL PRODUCTO
    // CREAR EL EVENTO CLICK 
    $('.add_product').click(function(e)
    {
 e.preventDefault();
 var producto = $(this).attr('product');
 var action = 'infoProducto';

 $.ajax({
     url: 'ajax.php',
     type: 'POST',
     async:true,
     data: {action:action,producto:producto},

         success: function(response){
            
            if(response != 'error'){
             var info = JSON.parse(response);
             

             //$('#producto_id').val(info.codproducto);
             //$('.nameProducto').html(info.descripcion);
             $('.bodyModal').html('<form action="" method="post" name="form_add_product" id="form_add_product" onsubmit="event.preventDefault(); sendDataProduct();">'+
                                    '<center>'+
                                    '<h1><i class="fas fa-ellipsis-h"></i> <br>Agrega mas productos</h1>'+
                                    '<h2 class="nameProducto">'+info.descripcion+'</h2><br>'+
                                    '</center>'+
                                    '<input type="number" name="cantidad" id="txtcantidad" placeholder="Cantidad del producto" required><br>'+
                                    '<input type="text" name="precio" id="txtprecio" placeholder="precio del producto" required>'+
                                    '<input type="hidden" name="producto_id" id="producto_id"  value="'+info.codproducto+'" required>'+
                                    '<input type="hidden" name="action" value="addProduct" required>'+
                                    '<div class="alert alertAddProduct"></div>'+
                                    '<button  type= "submit" class="btn_new"><i class="fas fa-ellipsis-h"></i> Agregar</button>'+
                                    '<a href="#" class="btn_ok closeModal" onclick="closeModal();"><i class="fas fa-ban"></i> Cerrar</a>'+
                                    '</form>');
            }// fin de if error
         },
         error: function(error)
         {

         
         }

 });
 
 // mando a traer mi modal
  $('.modal').fadeIn();
    });

      // MODAL FORM ELIMINAR EL  PRODUCTO
    // CREAR EL EVENTO CLICK 
    $('.del_product').click(function(e)
    {
 e.preventDefault();
 var producto = $(this).attr('product');
 var action = 'infoProducto';

 $.ajax({
     url: 'ajax.php',
     type: 'POST',
     async:true,
     data: {action:action,producto:producto},

         success: function(response){
            
            if(response != 'error'){
             var info = JSON.parse(response);
             

             //$('#producto_id').val(info.codproducto);
             //$('.nameProducto').html(info.descripcion);
             $('.bodyModal').html('<form action="" method="post" name="form_del_product" id="form_del_product" onsubmit="event.preventDefault(); delProduct();">'+
                                    '<center>'+
                                    '<h1><i class="fas fa-trash "></i> <br>Eliminar Producto</h1>'+
                                   ' <p>¿Estas seguro de eliminar el siguiente producto?</p>'+
                                    '<h2 class="nameProducto">'+info.descripcion+'</h2><br>'+
                                    '<input type="hidden" name="producto_id" id="producto_id"  value="'+info.codproducto+'" required>'+
                                    '<input type="hidden" name="action" value = "delProduct" required>'+
                                    '<div class="alert alertAddProduct"></div>'+
                                    '<button type="submit" class="btn_cancel"><i class="fas fa-trash "></i> Eliminar</button>'+
                                    '<a href="#" class="btn_ok"  onclick="closeModal();">Cancelar</a>'+
                                    '</center>'+
                                    '</form>');
            }// fin de if error
         },
         error: function(error)
         {

         
         }

 });
 
 // mando a traer mi modal
  $('.modal').fadeIn();
    });

    $('#search_proveedor').change(function(e){
        e.preventDefault();
        var sistema = getUrl();
        //alert(sistema);
        location.href = sistema+'buscar_producto.php?proveedor='+$(this).val();
        //alert(URLactual);
    });

    // funcion para activar campos
    $('.btn_new_cliente').click(function(e){
        e.preventDefault();
        $('#nom_cliente').removeAttr('disabled');
        $('#tel_cliente').removeAttr('disabled');
        $('#dir_cliente').removeAttr('disabled');
        $('#div_registro_cliente').slideDown();
    });


// buscar cliente
$('#nit_cliente').keyup(function(e){
    e.preventDefault();

    var cl = $(this).val();
    var action = 'searchCliente';

    $.ajax({
        url: 'ajax.php',
        type: "POST",
        async : true,
        data: {action:action,cliente:cl},

        success: function(response)
        {
         if(response == 0)
            {
                
                $('#idcliente').val('');
                $('#nom_cliente').val('');
                $('#tel_cliente').val('');
                $('#dir_cliente').val('');
                //mostrar boton agregar
                $('.btn_new_cliente').slideDown();
               } else{
                var data = $.parseJSON(response);
                $('#idcliente').val(data.idcliente);
                $('#nom_cliente').val(data.nombre);
                $('#tel_cliente').val(data.telefono);
                $('#dir_cliente').val(data.direccion);
                 //ocultar boton agregar
                $('.btn_new_cliente').slideUp();

                //bloquear campos 
                $('#nom_cliente').attr('disabled','disabled');
                $('#tel_cliente').attr('disabled','disabled');
                $('#dir_cliente').attr('disabled','disabled');
                // ocultar el boton de guardar 
                $('#div_registro_cliente').slideUp();
            }
        },
        error: function(error)
        {

        }// fin de error
    });
});

// crear cliente por medio de las ventas

$('#form_new_cliente_venta').submit(function(e) {
    /* Act on the event */
    e.preventDefault();
    $.ajax({
        url: 'ajax.php',
        type: "POST",
        async : true,
        data: $('#form_new_cliente_venta').serialize(),

        success: function(response)
        {
            if(response != 'error'){
                $('#idcliente').val(response);
                $('#nom_cliente').attr('disabled','disabled');
                $('#tel_cliente').attr('disabled','disabled');
                $('#dir_cliente').attr('disabled','disabled');
                
                $('.btn_new_cliente').slideUp();
                $('#div_registro_cliente').slideUp();
            }
           
        },
        error: function(error)
        {

        }// fin de error
    });
});
  
  // buscar producto desde venta 
$('#txt_cod_producto').keyup(function(e) {
    /* Act on the event */
    e.preventDefault();
     var producto = $(this).val();
     var action = 'infoProducto';
   if(producto != '')
   {
 $.ajax({
        url: 'ajax.php',
        type: "POST",
        async : true,
        data: {action:action, producto:producto},

        success: function(response)
        { 
            if(response != '\r\nerror'){
                var info = JSON.parse(response);
                $('#txt_descripcion').html(info.descripcion);
                $('#txt_existencia').html(info.existencia);
                $('#txt_cant_producto').val('1');
                $('#txt_precio').html(info.precio);
                $('#txt_precio_total').html(info.precio);

                // activar cantidad
                $('#txt_cant_producto').removeAttr('disabled');
                // most6rar boton de agregar
                $('#add_product_venta').slideDown();
            }else{

                $('#txt_descripcion').html('-');
                $('#txt_existencia').html('-');
                $('#txt_cant_producto').val('0');
                $('#txt_precio').html('0.00');
                $('#txt_precio_total').html('0.00');

                 // ocultar cantidad/ bloquearla
                $('#txt_cant_producto').attr('disabled','disabled');
                // ocultarel boton 
                $('#add_product_venta').slideUp();
            }
        },
        error: function(error)
        {

        }// fin de error
    });
   }
   
});
// fin de buscar producto en venta

// funcion paracalcular el precio total por cantidad de productos
$('#txt_cant_producto').keyup(function(e){
e.preventDefault();
var precio_total = ($(this).val() * $('#txt_precio').html()).toFixed(2);
var existencia = parseInt($('#txt_existencia').html());
$('#txt_precio_total').html(precio_total);
// oculta el boton de agregar si la cantidad es menor que 1
if(  ($(this).val() < 1 || isNaN($(this).val())) || ($(this).val() > existencia )){
    if(($(this).val() > existencia )){
    $('#txt_precio_total').html('No hay productos suficientes');
}
    $('#add_product_venta').slideUp();
}else{
   $('#add_product_venta').slideDown(); 
}
 
});
// fin de calculo producto

// agregar producto al detalle
$('#add_product_venta').click(function(e){
e.preventDefault();
if($('#txt_cant_producto').val()>0){
    var codproducto = $('#txt_cod_producto').val();
      var cantidad = $('#txt_cant_producto').val();
      var action = 'addProductoDetalle';
      
      $.ajax({
 url: 'ajax.php',
 type:"POST",
 async:true,
 data: { action:action, producto:codproducto, cantidad:cantidad},
 success: function(response){
   if(response != 'error'){
    var info = JSON.parse(response);
   $('#detalle_venta').html(info.detalle);
   $('#detalle_totales').html(info.totales);

   $('#txt_cod_producto').val('');
   $('#txt_descripcion').html('-');
   $('#txt_existencia').html('-');
   $('#txt_cant_producto').val('0');
   $('#txt_precio').html('0.00');
   $('#txt_precio_total').html('0.00');

   // bloquear cantidad   
   $('#txt_cant_producto').attr('disabled','disabled');
   // ocultar boton agregar 
   $('#add_product_venta').slideUp();

   }else{
    console.log('No hay productos seleccionados');
   }
 },  // fin de success   
 error: function(error){

 }// fin de error
});
 
}
});
// fin de agrear producto al detalle

// anular venta 
$('#btn_anular_venta').click(function(e){
e.preventDefault();
var rows = $('#detalle_venta tr').length;
if(rows >0){
  var action = 'anularVenta';

  $.ajax({
    url: 'ajax.php',
    type: "POST",
    async: true,
    data: {action:action},

    success: function(response){
      console.log(response);
      if(response != 'error')
      {
        location.reload();
      }
    },
    error: function(error) {
      /* Act on the event */
    }
  });
}

});
// fin de anular venta 

//btn_facturar_venta para generar la factura
$('#btn_facturar_venta').click(function(e){
e.preventDefault();

var rows = $('#detalle_venta tr').length;
if(rows > 0){
  var action = 'procesarVenta';
  var codcliente = $('#idcliente').val();

  $.ajax({
    url: 'ajax.php',
    type: "POST",
    async: true,
    data: {action:action,codcliente:codcliente},

    success: function(response)
    {
     
      if(response != 'error')
     {
      var info =JSON.parse(response);
      //console.log(info);
       
       generarPDF(info.codcliente, info.nofactura);
       location.reload();
      }else{
        console.log('no data');
      }
    },
    error: function(error) {
      /* Act on the event */
    }
  });
}

});

// fin de generar factura

// inicio de eliminar factura

$('.anular_factura').click(function(e)
    {
 e.preventDefault();
 var nofactura = $(this).attr('fac');
 var action = 'infoFactura';

 $.ajax({
     url: 'ajax.php',
     type: 'POST',
     async:true,
     data: {action:action,nofactura:nofactura},

         success: function(response){
            
            if(response != 'error'){
             var info = JSON.parse(response);
           
            $('.bodyModal').html('<form action="" method="post" name="form_anular_factura" id="form_anular_factura" onsubmit="event.preventDefault(); anularFactura();">'+
                                    '<center>'+
                                    '<h1><i class="fas fa-trash "></i> <br>Anular Factura</h1>'+
                                   ' <p>¿Estas seguro de Anular la siguiente Factura?</p>'+
                                    
                                    '<p>Realmente desea anular la factura</p>'+
                                    '<p><strong>No. '+info.nofactura+'</strong></p>'+
                                    '<p><strong>Monto. $ '+info.totalfactura+'</strong></p>'+
                                    '<p><strong>Fecha. '+info.fecha+'</strong></p>'+
                                     '<p><strong>Id cliente. '+info.codcliente+'</strong></p>'+
                                    '<input type = "hidden" name="action" value="anularFactura">'+
                                    '<input type = "hidden" name="no_factura" id="no_factura" value="'+info.nofactura+'" required>'+

                                    '<div class="alert alertAddProduct"></div>'+
                                    '<button type="submit" class="btn_cancel"><i class="fas fa-trash "></i> Anular</button>'+
                                    '<a href="#" class="btn_ok"  onclick="closeModal();">Cerrar</a>'+
                                    '</center>'+
                                    '</form>');
            }// fin de if error
         },
         error: function(error)
         {

         
         }

 });
 
 // mando a traer mi modal
  $('.modal').fadeIn();
    });
// fin de eliminar facura con modal 

// ver factura 
$('.view_factura').click(function(e) {
  e.preventDefault();
   var codCliente = $(this).attr('cl');
   var noFactura = $(this).attr('f');
   generarPDF(codCliente,noFactura);

});
// fin de ver factura
// cambiar contraseña

$('.newPass').keyup(function() {
    
    /* Act on the event */
    validPass();
});
// fin de cabiar cotraseña


//FORM CAMBIAR CONTRASEÑA
$('#frmChangePass').submit(function(e) {
    /* Act on the event */
    e.preventDefault();
     var passActual = $('#txtPassUser').val();
     var passNuevo = $('#txtNewPassUser').val();
      var confirmPassNuevo = $('#txtPassConfirm').val();
      var action = "changePassword";

      if(passNuevo != confirmPassNuevo){
        $('.alertChangePass').html('<p style="color: red;">Las contraseñas no son iguales</p>');
        $('.alertChangePass').slideDown();
        return false;
    }
    if(passNuevo.length < 4){
        $('.alertChangePass').html('<p>La nueva contraseña debe tener almenos 4 caracteres</p>');
        $('.alertChangePass').slideDown();
        return false;
    }
      $.ajax({
    url: 'ajax.php',
    type: "POST",
    async: true,
    data: {action:action,passActual:passActual,passNuevo:passNuevo},

    success: function(response)
    {
     if(response != 'error'){
         var info = JSON.parse(response);
         if(info.cod =='00'){
            $('.alertChangePass').html('<p style= "color:green;">'+info.msg+'</p>');
            $('#frmChangePass')[0].reset();
         }else{
            $('.alertChangePass').html('<p style="color:red;">'+info.msg+'</p>');
         }
         $('.alertChangePass').slideDown();
      }
    },
    error: function(error) {
      /* Act on the event */
    }
  });
});
// FIN DE FORM CAMBIAR CONTRASEÑA
});// fin de ready

function validPass(){
    var passNuevo = $('#txtNewPassUser').val();
    var confirmPassNuevo = $('#txtPassConfirm').val();
    if(passNuevo != confirmPassNuevo){
        $('.alertChangePass').html('<p style="color: red;">Las contraseñas no son iguales</p>');
        $('.alertChangePass').slideDown();
        return false;
    }
    if(passNuevo.length < 4){
        $('.alertChangePass').html('<p>La nueva contraseña debe tener almenos 4 caracteres</p>');
        $('.alertChangePass').slideDown();
        return false;
    }
    $('.alertChangePass').html('');
      $('.alertChangePass').slideUp();
}
// anular factura
function anularFactura(){
  var noFactura = $('#no_factura').val();
  var action = 'anularFactura';
  $.ajax({
    url: 'ajax.php',
    type: "POST",
    async: true,
    data: {action:action,noFactura:noFactura},

    success: function(response){
     if(response == 'error'){
   $('.alertAddProduct').html('<p style="color:red;">Error al anular la factura.</p>');
     }else{
      $('#row_'+noFactura+' .estado').html('<span class="anulada">Anulada</span>');
      $('#form_anular_factura .btn_cancel').remove();
      $('#row_'+noFactura+' .div_factura').html('<button type="button" class="btn_anular inactive"><i class="fas fa-ban"></i></button>');
      $('.alertAddProduct').html('<p>Factura anulada.</p>');
     }
    },
    error: function(error){
     
    }
  });  
}

// fin de anular factura 

// nuevo script para generar PDF

function generarPDF(cliente,factura){
  var ancho = 1000;
  var alto = 800;
  // calcular posicionx,y para centrar la ventana
  var x = parseInt((window.screen.width/2) - (ancho / 2));
  var y = parseInt((window.screen.height/2) - (alto / 2));

  $url = 'factura/generaFactura.php?cl='+cliente+'&f='+factura;
  window.open($url,"Factura","left="+x+",top="+y+",height="+alto+",width="+ancho+",scrollbar=si,location=no,resizable=si,menubar=no");
}
// fin de nuevo script para agenerar pdf 


// funcion para eliminar producto del detalle 

 function del_product_detalle(correlativo){
 var action  = 'delProductoDetalle';
     var id_detalle = correlativo;

   $.ajax({
 url: 'ajax.php',
 type:"POST",
 async:true,
 data: { action:action, id_detalle:id_detalle},
 success: function(response){
    console.log(response);
   if(response != '\r\nerror'){
     var info = JSON.parse(response);

   $('#detalle_venta').html(info.detalle);
   $('#detalle_totales').html(info.totales);

   $('#txt_cod_producto').val('');
   $('#txt_descripcion').html('-');
   $('#txt_existencia').html('-');
   $('#txt_cant_producto').val('0');
   $('#txt_precio').html('0.00');
   $('#txt_precio_total').html('0.00');

   // bloquear cantidad   
   $('#txt_cant_producto').attr('disabled','disabled');
   // ocultar boton agregar 
   $('#add_product_venta').slideUp();

   }else{

    $('#detalle_venta').html('');
    $('#detalle_totales').html('');

   }

 },  // fin de success   
 error: function(error){

 }// fin de error
});
 }

// fin de la funcion para eliminar producto del ready 


// nuvea funcion fuera del ready para mantener los datos despues delpost back
 function searchForDetalle(id){
     var action  = 'searchForDetalle';
     var user = id;

   $.ajax({
 url: 'ajax.php',
 type:"POST",
 async:true,
 data: { action:action, user:user},
 success: function(response){
     if(response != '\r\nerror'){
    var info = JSON.parse(response);
   $('#detalle_venta').html(info.detalle);
   $('#detalle_totales').html(info.totales);


   }else{
    console.log('No hay productos seleccionados');
   }
 },  // fin de success   
 error: function(error){

 }// fin de error
});
 }// fin de function search for detalle




// inicio de funcion para designarle valor a la busqueda por proveedor
function getUrl(){
var loc = window.location;
var pathname = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathname.length));
}// fin de funciòn Geturl

function sendDataProduct()
{
$('.alertAddProduct').html('');

//inicio de ajax 
 $.ajax({
     url: 'ajax.php',
     type: 'POST',
     async:true,
     data: $('#form_add_product').serialize(),

         success: function(response){
            console.log(response);
           if(response == 'error')
           {
            $('.alertAddProduct').html('<p style = "color: red;">Error al agregar nuevos productos</p>');
           }// fin de if de  response error
           else
           {
        
            var info = JSON.parse(response);
            $('.row'+info.producto_id+'.celPrecio').html(info.nuevo_precio);
               $('.row'+info.producto_id+'.celExistencia').html(info.nueva_existencia);
             $('#txtcantidad').val('');
           $('#txtprecio').val('');
            $('.alertAddProduct').html('<p>Producto Guardado Correctamente</p>');
           }//fin de else para el alert de error y response
        
         },
         error: function(error)
         {
         console.log(error);
        
         }

 });// fin de ajax segundo
}



// funcion para eliminar producto 
function delProduct()
{
var pr  = $('#producto_id').val();

$('.alertAddProduct').html('');

//inicio de ajax 
 $.ajax({
     url: 'ajax.php',
     type: 'POST',
     async:true,
     data: $('#form_del_product').serialize(),

         success: function(response){
            
          if(response == 'error')
          {
           $('.alertAddProduct').html('<p style = "color: red;">Error al eliminar  producto</p>');
          }// fin de if de  response error
          else
          {
        
           $('.row'+pr).remove();
           $('#form_del_product .btn_cancel').remove();
           $('.alertAddProduct').html('<p>Producto Eliminado Correctamente</p>');
          }//fin de else para el alert de error y response
        
         },
         error: function(error)
         {
         console.log(error);
         }

 });// fin de ajax segundo
}

function closeModal()
{
    $('.alertAddProduct').html('');
    $('#txtcantidad').val('');
    $('#txtprecio').val('');
    $('.modal').fadeOut();
}
