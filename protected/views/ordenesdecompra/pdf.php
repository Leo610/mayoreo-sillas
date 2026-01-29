<!DOCTYPE html>
<html>
<head>
    <title>Orden de Compra <?=$Datos->id_orden_de_compra;?>/<?=date('Y')?></title>
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
            border-spacing:0;
            border-collapse: collapse; 
             
        }
        .logotipo{ width:70mm; }
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
    </style>
</head>
<body>
<div id="wrapper">
    <br /><br>
    <table class="heading" style="width:100%;">
      <tr>
          <td style="width:70mm;border:none !important;">
             <img src="<?php echo Yii::app()->baseUrl; ?>/images/logo.png" class="logotipo">
          </td>
          <td style="width:110mm;border:none !important;">
          </td>
          <td style="width:70mm;border:none !important;">
             <table>
                  <tr><td>Orden de Compra : </td><td><strong class="numcot"><?=$Datos->id_orden_de_compra;?>/<?=date('Y')?></strong></td></tr>
                  <tr><td>Fecha : </td><td><strong class="fechcot"><?php
             	$dt = new DateTime($Datos->ordendecompra_fecha_alta);
							$date = $dt->format('Y-m-d');
             echo $date;?></strong></td></tr>
                  <tr><td>Moneda : </td><td> <strong class="moneda"><?=$Datos->rl_moneda->moneda_nombre?></strong></td></tr>
              </table>
          </td>
      </tr>
    </table>
    <br>
    <table class="heading" style="width:100%;">
      <tr>
          <td style="width:90mm;">
        		<p><strong>Datos Emisor:</strong></p>
            <h1 class="heading">Conveyors y Cadenas, S.A. de C.V.</h1>
            <h2 class="heading">
                Océano Pacífico #626,Col: La Fe<br>
	              San Nicolás de los Garza, Nuevo León<br>
	              México. CP 66477<br>
                E-mail : contacto@cycsa.com.mx<br />
                Teléfono : +52(81)82408751 y +52(81)82408752
            </h2>
          </td>
          <td style="width:30mm; border-bottom:none; border-top:none !important;">
          		
          </td>
          <td style="width:90mm;">
          	<p><strong>Datos Proveeedor:</strong></p>
            <h1 class="heading"><?=$DatosProveedores->proveedor_razonsocial;?></h1>
            <h2 class="heading">
                <?=$DatosProveedores->proveedor_calle;?>,<?=$DatosProveedores->proveedor_colonia;?><br>
		            <?=$DatosProveedores->proveedor_municipio;?>, <?=$DatosProveedores->proveedor_entidad;?><br>
		            <?=$DatosProveedores->proveedor_pais;?>. CP <?=$DatosProveedores->proveedor_codigopostal;?><br>
                E-mail : <?=$DatosProveedores->proveedor_email;?><br />
                Teléfono : <?=$DatosProveedores->proveedor_telefono;?>
            </h2>
          </td>
      </tr>
    </table>
    <br />
    <p>En base a su solicitud, le presentamos la siguiente orden de compra:</p>
    <div id="content">
        <div id="invoice_body">
            <table>
            <tr style="background:#eee;">
                <td style="width:8%;"><b>Partida</b></td>
                <td style="width:50%;"><b>Producto</b></td>
                <td style="width:15%;"><b>Unitario</b></td>
                <td style="width:15%;"><b>Cantidad</b></td>
                <td style="width:15%;"><b>Total</b></td>
            </tr>
            </table>
             
            <table>
            <?php 
          			// Definimos la partida
                $partida=1;
                	foreach ($Detalle as $rows){ ?>
                <tr>
                    <td ><?=$partida++;?></td>
                    <td>
                        <?=$rows['rl_producto']['producto_nombre']?><br />
                        <?=$rows['rl_producto']['producto_clave']?><br />
                        <?=$rows['rl_producto']['producto_descripcion']?>
                    </td>
                    <td >
                        $ <?=number_format($rows['ordenes_de_compra_detalle_unitario'],2)?>
                    </td>

                    <td >
                        <?=$rows['ordenes_de_compra_detalle_cantidad'];?>
                    </td>
                    
                    <td >
                        $ <?=number_format($rows['ordenes_de_compre_detalle_total'],2)?>
                    </td>
                </tr> 
                <?php 
                }
                ?>   
            <tr style="background:#FFF;">
                <td style="width:8%;border:none !important;"></td>
                <td style="width:50%; border:none !important;"></td>
                <td style="width:15%;border:none !important;"></td>
                <td style="width:15%;"><b>Tipo de cambio</b></td>
                <td style="width:15%;"><b>$ <?=number_format($Datos->tipo_cambio,2)?></b></td>
            </tr>
             <tr style="background:#FFF;">
                <td style="width:8%;border:none !important;"></td>
                <td style="width:50%; border:none !important;"></td>
                <td style="width:15%;border:none !important;"></td>
                <td style="width:15%;"><b>Total moneda MX</b></td>
                <td style="width:15%;"><b>$ <?=number_format($Datos->total_peso,2)?></b></td>
            </tr>
            <tr style="background:#FFF;">
                <td style="width:8%;border:none !important;"></td>
                <td style="width:50%; border:none !important;"></td>
                <td style="width:15%;border:none !important;"></td>
                <td style="width:15%;"><b>Sub Total</b></td>
                <td style="width:15%;"><b>$ <?=number_format($Datos->ordendecompra_total/1.16,2);?></b></td>
            </tr>
            <tr style="background:#FFF;">
                <td style="width:8%;border:none !important;"></td>
                <td style="width:50%; border:none !important;">Numero con letra:</td>
                <td style="width:15%;border:none !important;"></td>
                <td style="width:15%;"><b>IVA</b></td>
                <td style="width:15%;"><b>$ <?=number_format(($Datos->ordendecompra_total/1.16)*.16,2);?></b></td>
            </tr>
            <tr style="background:#FFF;">
                <td style="width:8%;border:none !important;"></td>
                <td style="width:50%; border:none !important;"><?=$this->numtoletras($Datos->ordendecompra_total,$Datos->rl_moneda->moneda_nombre);?> </td>
                <td style="width:15%;border:none !important;"></td>
                <td style="width:15%;"><b>Total</b></td>
                <td style="width:15%;"><b>$ <?=number_format($Datos->ordendecompra_total,2);?></b></td>
            </tr>
            </table>

        </div>
       
        <table style="width:100%; height:35mm;">
            <tr>
                <td style="width:100%;" valign="top">
                    Condiciones Generales:<br />
                    Pago en una sola exibición <br />
                    Orden de Compra vigente por 15 dias <br />
                </td>
            </tr>
        </table>
    </div>
     
    <br />
     
    </div>
     
    <htmlpagefooter name="footer">
        <hr />
        <div id="footer"> 
            <table>
                <tr><td>Desarrollado por s21sistemas.com.mx</td></tr>
            </table>
        </div>
    </htmlpagefooter>
    <sethtmlpagefooter name="footer" value="on" />
     
</body>
</html>