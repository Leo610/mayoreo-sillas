<!DOCTYPE html>
<html>
<head>
    <title>Hoja Salida pedido <?=$Datos->id_proyecto;?></title>
    <style>
        *
        {
            margin:0;
            padding:0;
            font-family:Arial;
            font-size:10pt;
            color:#000;
        }
        body
        {
            width:100%;
            font-family:Arial;
            font-size:10pt;
            margin:0;
            padding:0;
        }
         
        p
        {
            margin:0;
            padding:0;
        }
         
        #wrapper
        {
            width:180mm;
            margin:15mm 15mm;
        }
         
        .page
        {
            height:297mm;
            width:210mm;
            page-break-after:always;
        }
 
        table
        {
            display:block;
            clear: both;
            border-spacing:0;
            border-collapse: collapse; 
             
        }
        .logotipo{ width:70mm;}
        table td 
        {	border-left: 1px solid #ccc;
            border-top: 1px solid #ccc;
            border-right: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            padding: 2mm;
        }
         
        table.heading
        {
            height:50mm;
        }
         
        h1.heading
        {
            font-size:14pt;
            color:#000;
            font-weight:normal;
        }
         
        h2.heading
        {
            font-size:9pt;
            color:#000;
            font-weight:normal;
        }
         
        hr
        {
            color:#ccc;
            background:#ccc;
        }
        .fechcot,.numcot,.moneda{ font-size:13pt;}
        #invoice_body
        {
            height: auto;
            margin-bottom:5mm;
        }
         
        #invoice_body , #invoice_total
        {   
            width:100%;
        }
        #invoice_body table , #invoice_total table
        {
            width:100%;
            border-left: 1px solid #ccc;
            border-top: 1px solid #ccc;
     
            border-spacing:0;
            border-collapse: collapse; 
             
            margin-top:5mm;
        }
         
        #invoice_body table td , #invoice_total table td
        {
            text-align:center;
            font-size:9pt;
            border-right: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            padding:2mm 0;
        }
         
        #invoice_body table td.mono  , #invoice_total table td.mono
        {
            font-family:monospace;
            text-align:right;
            padding-right:3mm;
            font-size:10pt;
        }
         
        #footer
        {   
            width:180mm;
            margin:0 15mm;
            padding-bottom:3mm;
        }
        #footer table
        {
            width:100%;
            border-left: 1px solid #ccc;
            border-top: 1px solid #ccc;
             
            background:#eee;
             
            border-spacing:0;
            border-collapse: collapse; 
        }
        #footer table td
        {
            width:25%;
            text-align:center;
            font-size:9pt;
            border-right: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
        }
        @page{
           margin-top: 20mm;
           margin-bottom: 20mm;
        }  
        @page:first{
           margin-top: 10mm !important;
           margin-bottom: 20mm;
        } 
       
    </style> 
    
</head>
<body>
<div id="wrapper">
    <br><br>
    
    <table class="heading" style="width:100%; border-bottom:2px solid #f96b06 ;">
      <tr>
          <td style="width:210mm;border:none !important;">
             <center>
                <img src="<?php echo Yii::app()->baseUrl; ?>/images/logo.png" class="logotipo">
             </center>
          </td>
      </tr>
    </table><br>
     <table class="heading" style="width:100%;">
        <tr>
          <td style="width:210mm;border:none !important;">
             <center>
               <h1>HOJA DE SALIDA <?=$Datos->proyecto_nombre;?></h1><br>
               <p>Ref. Proyecto # <?=$Datos->numero_proyecto;?></p>
             </center>
          </td>
      </tr>
    </table> <br>
    
    <table class="heading" style="width:100%;">
      <tr>
          <td style="width:210mm;">
            
            <p>
                Fecha: <?=$Datos->proyecto_ultima_modificacion;?><br>
                Proyecto: <?=$Datos->proyecto_nombre;?><br>
                Empresa: <?=$DatosCliente->cliente_razonsocial;?><br>
                Planta: <?=$DatosCliente->cliente_municipio;?>,<?=$DatosCliente->cliente_entidad;?>,<?=$DatosCliente->cliente_pais;?><br>
                Contacto: <?=$DatosCliente->cliente_nombre;?><br>
                Teléfono: <?=$DatosCliente->cliente_telefono;?><br>
                E-mail: <?=$DatosCliente->cliente_email;?><br>
                Supervisor: <?=$Datos->rl_supervisor->empleado_nombre;?><br>
                Estatus: <?=$this->ObtenerEstatus($Datos->proyecto_estatus);?><br>
            </p>
            
          </td>
      </tr>
    </table>
    <br>
    <table class="heading" style="width:100%;">
      <tr>
          <td style="width:210mm;">
            <p><strong>Datos Empresa:</strong></p>
            <p>
                Empresa: <?=$DatosCliente->cliente_razonsocial;?><br>
                Dirección: <?=$DatosCliente->cliente_calle;?>,<?=$DatosCliente->cliente_numeroexterior;?>,<?=$DatosCliente->cliente_razonsocial;?>,<?=$DatosCliente->cliente_numerointerior;?>,<?=$DatosCliente->cliente_codigopostal;?><br>
                Ciudad: <?=$DatosCliente->cliente_municipio;?><br>
                Estado: <?=$DatosCliente->cliente_entidad;?><br>
                Pais: <?=$DatosCliente->cliente_pais;?><br>
                Teléfono: <?=$DatosCliente->cliente_telefono;?><br>
                E-mail: <?=$DatosCliente->cliente_email;?><br>
            </p>
            
          </td>
      </tr>
    </table>
    <br>
    <table class="heading" style="width:100%;">
      <tr>
          <td style="width:210mm;">
            <p>
                Empresa: <?=$DatosCliente->cliente_razonsocial;?><br>
                Dirección: <?=$DatosCliente->cliente_calle;?>,<?=$DatosCliente->cliente_numeroexterior;?>,<?=$DatosCliente->cliente_razonsocial;?>,<?=$DatosCliente->cliente_numerointerior;?>,<?=$DatosCliente->cliente_codigopostal;?><br>
                Ciudad: <?=$DatosCliente->cliente_municipio;?><br>
                Estado: <?=$DatosCliente->cliente_entidad;?><br>
                Pais: <?=$DatosCliente->cliente_pais;?><br>
                Teléfono: <?=$DatosCliente->cliente_telefono;?><br>
                E-mail: <?=$DatosCliente->cliente_email;?><br>
            </p>
            
          </td>
      </tr>
    </table>
    <br>
    
    <p>Productos</p>
    <table class="heading" style="width:100%;">
        <tr>
            <th>ID</th>            
            <th>Nombre</th>
            <th>Descripcion</th>
            <th>Almacen</th>
            <th>Cantidad</th>
            <th>Cantidad Surtida</th>
            <th>Cantidad faltante</th>
        </tr>
      <?php foreach ($Productosproyecto as $rows){
        if($rows->rl_producto->productoafectaalmacen==1)  
            {
        ?>
      <tr>
        <td><?=$rows->id_producto?></td>            
        <td><?=$rows->rl_producto->producto_nombre?></td>
        <td><?=$rows->rl_producto->producto_descripcion?></td>
        <td><?=$rows->rl_almacen->almacen_nombre?></td>
        <td><?=$rows->proyectos_productos_cantidad?></td>
        <td><?=$rows->proyectos_productos_cantidad_surtida?></td>
        <td><?=$rows->proyectos_productos_cantidad-$rows->proyectos_productos_cantidad_surtida?></td>
      </tr>
      <?php
            }
       } ?>
    </table>
     
    </div>

    <div id="footer"> 
            <table>
                <tr><td>Conveyors y Cadenas, S.A. de C.V.<br> Océano Pacífico #626,Col: La Fe<br>
                  San Nicolás de los Garza, Nuevo León<br>
                  México. CP 66477<br>
                E-mail : contacto@cycsa.com.mx<br />
                Teléfono : +52(81)82408751 y +52(81)82408752</td></tr>
            </table>
        </div>
     
    

</body>
</html>
