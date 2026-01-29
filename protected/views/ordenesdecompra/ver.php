<?php
/* @var $this CotizacionesController */
$this->pageTitle='Orden de compra '.$DatosOrden->id_orden_de_compra;
$this->breadcrumbs=array(
	'Ordenes de compra'=>array('/ordenesdecompra/lista'),
	'Orden de compra '.$DatosOrden->id_orden_de_compra ,
);
?>
<script>
$(document).ready(function() {
    $('#productos').DataTable( {
        "paging":   false,
        "ordering": false,
        "info":     false,
        "searching":     false
    });

	/*
	*	METODO PARA ASIGNAR LA MONEDA A LA OC
	*	CREADO POR DANIEL VILLARREAL EL 09 DE MARZO DEL 2016
	*/	
   $( "#moneda" ).change(function() {
		var jqxhr = $.ajax({
		    url: "<?php echo $this->createUrl("Ordenesdecompra/Actualizarmoneda"); ?>",
		    type: "POST",
		    dataType : "json",
		    timeout : (120 * 1000),
		    data: {
		        id_orden_de_compra: <?=$DatosOrden->id_orden_de_compra; ?>,
		        moneda: $(this).val(),
		        total_peso: $('#total_peso').val()
		    },
		    success : function(Response, newValue) {
		        if (Response.requestresult == 'ok') {
		        	$.notify(Response.message, "success");
		        }else{
		        	$.notify(Response.message, "error");
		        }
		    },
		    error: function(e){
		          $.notify("Verifica los campos e intente de nuevo", "error");
		        }
		    });
		});

  });
/*
	*	METODO PARA OBTENER EL PRECIO DEL PRODUCTO, EN BASE A LA LISTA DE PRECIOS DEL CLIENTE
	*	CREADO POR DANIEL VILLARREAL EL 31 DE ENERO DEL 2016
	*/	
	function GetPrice(id_producto,id_proveedor){
		
		var jqxhr = $.ajax({
    url: "<?php echo $this->createUrl("Ordenesdecompradetalle/Obtenerprecio"); ?>",
    type: "POST",
    dataType : "json",
    timeout : (120 * 1000),
    data: {
        id_producto: id_producto,
        id_proveedor: id_proveedor
    },
    success : function(Response, newValue) {
        if (Response.requestresult == 'ok') {
        		$('#Ordenesdecompradetalle_ordenes_de_compra_detalle_unitario').val(Response.precio);
        		$('#Ordenesdecompradetalle_ordenes_de_compra_detalle_cantidad').val(1);
        		ActualizarPrecio();
        }else{
            $('#Ordenesdecompradetalle_ordenes_de_compra_detalle_unitario').val('');
        }
    },
    error: function(e){
          $.notify('Ocurrio un error inesperado','error');
        }
    });
	}
	/*
	*	METODO PARA MULTIPLICAR EL COSTO UNITARIO POR LA CANTIDAD Y MOSTRAR EL TOTAL
	*	CREADO POR DANIEL VILLARREAL EL 31 DE ENERO DEL 2016
	*/
	function ActualizarPrecio(){
		var cantidad = $('#Ordenesdecompradetalle_ordenes_de_compra_detalle_cantidad').val();
		var precio = $('#Ordenesdecompradetalle_ordenes_de_compra_detalle_unitario').val();
		var preciototal = cantidad*precio;
		$('#Ordenesdecompradetalle_ordenes_de_compre_detalle_total').val(preciototal);
	}

	/*
	*	METODO PARA TERMINAR LA ORDEN DE COMPRA
	*	CREADO POR DANIEL VILLARREAL EL 31 DE ENERO DEL 2016
	*/	
	function TerminarOrdenCompra(estatus){
		var confirmar = confirm("Seguro lo que deseas realizar?");
		if(estatus==2 || estatus==4 )
		{
			// Verificamos que tenga seleccionada moneda
			if($('#moneda').val() == '')
			{
				 $.notify("Seleccione la moneda", "error");
				return false;
			}
			if($('#ordendecompra_numero_factura').val() == '')
			{
				 $.notify("Ingrese el numero de factura", "error");
				return false;
			}
			
		}
		
		if (confirmar == true) 
		{
			var jqxhr = $.ajax({
	    url: "<?php echo $this->createUrl("Ordenesdecompra/Actualizarordendecompra"); ?>",
	    type: "POST",
	    dataType : "json",
	    timeout : (120 * 1000),
	    data: {
	        id_orden_de_compra: <?=$DatosOrden->id_orden_de_compra; ?>,
	        ordendecompra_estatus: estatus,
	        ordendecompra_numero_factura: $('#ordendecompra_numero_factura').val()
	    },
	    success : function(Response, newValue) {
	        if (Response.requestresult == 'ok') {
	        	window.location.replace("<?=$this->createUrl('ordenesdecompra/ver/'.$DatosOrden->id_orden_de_compra)?>");
	        }else{
	        	$.notify(Response.message, "error");
	         window.location.replace("<?=$this->createUrl('ordenesdecompra/ver/'.$DatosOrden->id_orden_de_compra)?>");
	        }
	    },
	    error: function(e){
	          $.notify("Verifica los campos e intente de nuevo", "error");
	        }
	    });
	  }
	}

	
</script>
<div class="row">
	<div class="col-md-12">
		<H1>Ver orden de compra <?=$DatosOrden->id_orden_de_compra?> | <small><?=$this->ObtenerEstatusOC($DatosOrden->ordendecompra_estatus)?></small></H1>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<fieldset>
			<legend>Datos del proveedor</legend>
			<div class="col-md-4">
				<p class="mb-none">
					Nombre:<br><strong><?=$DatosProveedores->proveedor_nombre; ?></strong><br>
					Teléfono:<br><strong><?=$DatosProveedores->proveedor_telefono; ?></strong><br>
					Email:<br><strong><?=$DatosProveedores->proveedor_email; ?></strong><br>
					Razón Social:<br><strong><?=$DatosProveedores->proveedor_razonsocial; ?></strong><br>
				</p>
			</div>
			<div class="col-md-4">
				<p class="mb-none">
					RFC:<br><strong><?=$DatosProveedores->proveedor_rfc; ?></strong><br>
					Calle:<br><strong><?=$DatosProveedores->proveedor_calle; ?> <?=$DatosProveedores->proveedor_numeroexterior; ?> <?=$DatosProveedores->proveedor_numerointerior; ?></strong><br>
					Colonia:<br><strong><?=$DatosProveedores->proveedor_colonia; ?></strong><br>
				</p>
			</div>
			<div class="col-md-4">
				<p class="mb-none">
					Código Postal:<br><strong><?=$DatosProveedores->proveedor_codigopostal; ?></strong><br>
					Municipio:<br><strong><?=$DatosProveedores->proveedor_municipio; ?></strong><br>
					Entidad:<br><strong><?=$DatosProveedores->proveedor_entidad; ?></strong><br>
					País:<br><strong><?=$DatosProveedores->proveedor_pais; ?></strong><br>
				</p>
	    </div>
		</fieldset>
	</div>
</div>
<?php 
	// Si la orden de compra esta abierta, podemos agregar un producto
	if($DatosOrden->ordendecompra_estatus==1){ ?>
	<div class="row">
		<div class="col-md-12">
			<fieldset>
				<legend>Agregar producto</legend>
				<?php
				/* @var $this ProveedoresController */
				/* @var $model Proveedores */
				/* @var $form CActiveForm */
				?>

				<div class="form">

				<?php $form=$this->beginWidget('CActiveForm', array(
				    'id'=>'ocproductos-form',
						'action'=>Yii::app()->createUrl('Ordenesdecompradetalle/Agregaproducto'),
						'enableClientValidation'=>true,
						'clientOptions' => array('validateOnSubmit'=>true, 'validateOnType'=>true),
					)); ?>

			    

			    <?php echo $form->errorSummary($model); ?>

			    <div class="col-md-4">
			        <?php 
			    			$this->widget('zii.widgets.jui.CJuiAutoComplete',array(
	              'name'=>'id_producto_auto',
	              'source'=>$this->createUrl('productosproveedores/Productosproveedoresajax/'.$DatosProveedores->id_proveedor.'/'),
	            	// Opciones javascript adicionales para el plugin
	              'options'=>array(
	                  'minLength'=>'3',
	                  'select'=>'js:function(event, ui) {
	                  	$("#Ordenesdecompradetalle_id_producto").val(ui.item.id);
	                  	$("#id_producto_auto").val(ui.item.value);
	                  	GetPrice(ui.item.id,'.$DatosProveedores->id_proveedor.');
	                 	}',
	                  'focus'=>'js:function(event, ui) {
	                      return false;
	                  }'
	              ),
	              'htmlOptions'=>array(
	                  'class'=>'form-control',
	                  'placeholder'=>'Seleccione el producto'
	              )
	            ));
	          ?>
			        <?php echo $form->hiddenField($model,'id_producto'); ?>
			        <?php echo $form->error($model,'id_producto'); ?>
			    </div>

			    <div class="col-md-2">
			        <?php echo $form->textField($model,'ordenes_de_compra_detalle_cantidad',array('class'=>'form-control','placeholder'=>'Cantidad','onchange'=>'ActualizarPrecio()')); ?>
			        <?php echo $form->error($model,'ordenes_de_compra_detalle_cantidad'); ?>
			    </div>

			      <?php echo $form->hiddenField($model,'id_ordenes_de_compra',array('value'=>$DatosOrden->id_orden_de_compra)); ?>

			    <div class="col-md-2">
			        <?php echo $form->textField($model,'ordenes_de_compra_detalle_unitario',array('class'=>'form-control','placeholder'=>'Precio Unitario','readonly'=>true)); ?>
			        <?php echo $form->error($model,'ordenes_de_compra_detalle_unitario'); ?>
			    </div>

			    <div class="col-md-2">
			        <?php echo $form->textField($model,'ordenes_de_compre_detalle_total',array('class'=>'form-control','placeholder'=>'Precio Total','readonly'=>true)); ?>
			        <?php echo $form->error($model,'ordenes_de_compre_detalle_total'); ?>
			    </div>


			    <div class="col-md-1">
			        <?php echo CHtml::submitButton('Agregar producto',array('class'=>'btn btn-primary')); ?>
			    </div>

				<?php $this->endWidget(); ?>
		         </tbody>
		      </table>
			</fieldset>
		</div>
	</div>
	<?php } ?>
<div class="row">
	<div class="col-md-12">
		<fieldset>
			<legend>Productos en la Orden de Compra</legend>
			<div class="table-responsive">
			<table id="productos" class="table table-striped table-bordered">
	          <thead>
	              <tr>
	                  <th>ID</th>
	                  <th>Producto</th> 
	                  <th>Clave</th> 
	                  <th>Unitario</th>                 
	                  <th>Cantidad</th>
	                 	<th>Total</th>
	                 	<th></th>
	              </tr>
	          </thead>
	          <tbody>
	          		<?php 
	          			// Definimos las variables de el subtotal, iva y total
		            	$Subtotal = '';
		            	$Iva = '';
		            	$Total = '';
	          		foreach ($DetalleOrden as $rows){ ?>
	              <tr>
	              		<td><?=$rows->id_producto?></td>
	                  <td><?=$rows->rl_producto->producto_nombre?></td>
	                  <td><?=$rows->rl_producto->producto_clave?></td>
	                  <td>$ <?=number_format($rows->ordenes_de_compra_detalle_unitario,2)?></td>
	                  <td>
	                  	<?php
	                  		// Si la orden de compra esta abierta, podemos agregar un producto
									if($DatosOrden->ordendecompra_estatus==1){ 
		                    	echo Chtml::link(
													  '<i class="fa fa-plus"></i>', 
													  'ordenesdecompradetalle/Actualizarproductos', 
													  array(
													       'submit'=>array('ordenesdecompradetalle/Actualizarproductos'), 
													       'params'=>array('id'=>$rows->id_ordenes_de_compra_detalle,'tipoactualizacion'=>1),
													       'class'=>'btn btn-default btn-sm mr-sm'
													  )
													); 
												}
												echo $rows->ordenes_de_compra_detalle_cantidad;
												if($rows->ordenes_de_compra_detalle_cantidad>1 and $DatosOrden->ordendecompra_estatus==1)
												{
													echo Chtml::link(
													   '<i class="fa fa-minus fa-lg"></i>', 
													    'ordenesdecompradetalle/Actualizarproductos', 
													    array(
													         'submit'=>array('ordenesdecompradetalle/Actualizarproductos'), 
													         'params'=>array('id'=>$rows->id_ordenes_de_compra_detalle,'tipoactualizacion'=>0),
													         'class'=>'btn btn-default btn-sm ml-sm'
													    )
													);
												} ?>
	                  </td>
	                  <td>$ <?=number_format($rows->ordenes_de_compre_detalle_total,2)?></td>
	                  <td><?php 
	                  		// Si la orden de compra esta abierta, podemos agregar un producto
												if($DatosOrden->ordendecompra_estatus==1){ 
	                           echo CHtml::link('<i class="fa fa-trash fa-lg"></i> Eliminar', array('ordenesdecompradetalle/Eliminarproducto', 'id'=>$rows->id_ordenes_de_compra_detalle),
					    						  array(
					    						    'submit'=>array('ordenesdecompradetalle/Eliminarproducto', 'id'=>$rows->id_ordenes_de_compra_detalle),
					    						    'class' => 'delete','confirm'=>'Seguro que lo deseas eliminar?'
					    						  )
					    						);
                         }
				    						?></td>
	              </tr>

	             <?php 
	             	// Obtenemos el subtotal, iva y total
	             	$Subtotal = $Subtotal + $rows->ordenes_de_compre_detalle_total;
	             }
								$Iva = ($Subtotal*.16);
	            	$Total = ($Subtotal+$Iva);
	            ?>
	          </tbody>
	       </table>
	       </div>
	  </fieldset>
   </div>
</div>
<?php if($Total>0){ ?>
<div class="row">
	<div class="col-md-8">
		<fieldset>
			<legend>Total con Letra</legend>
			<p class="mt-lg text-center" style="font-weight:bold; font-size:1.3em;">"<?=$this->numtoletras($Total);?>"</p>
		
		</fieldset>
		<div class="col-md-12 text-center">
			<?php if($DatosOrden->ordendecompra_estatus==1){?>
			<a class="btn btn-success" onclick="TerminarOrdenCompra(2);">
				PASAR A RECIBIR LA OC
			</a>
			<a class="btn btn-warning" onclick="TerminarOrdenCompra(3);">
				CANCELAR LA OC
			</a>
			<?php }elseif($DatosOrden->ordendecompra_estatus==0){?>	
			<a class="btn btn-success" onclick="TerminarOrdenCompra(1);">
				LIBERAR LA OC
			</a>
			<?php }?>	
			<?php if($DatosOrden->ordendecompra_estatus!=4 && $DatosOrden->ordendecompra_estatus!=3){ ?>
			<a class="btn btn-success" onclick="TerminarOrdenCompra(4);">
				PASAR A PAGAR LA OC
			</a>
			<?php } ?>
				<a href="<?php echo Yii::app()->createUrl('ordenesdecompra/pdf/'.$DatosOrden->id_orden_de_compra) ?>" class="btn btn-danger" target="new" >
					<i class="fa fa-file-pdf-o"></i> DESCARGAR PDF
				</a>
		</div>
	</div>
	<div class="col-md-4">
		<fieldset>
			<legend>Totales</legend>
			<table id="totales" class="totales" cellspacing="0" width="100%">
				<tr>
					<td>Moneda:</td>
					<td>
						<select name="moneda" class="form-control" id="moneda" <?php if($DatosOrden->ordendecompra_estatus==4 || $DatosOrden->ordendecompra_estatus==2){ echo 'disabled="true"';} ?> >
							<option value="">-- Seleccione moneda --</option>
							<?php foreach ($listamonedas as $rows) {?>
								<option value="<?=$rows->id_moneda?>" <?=($rows->id_moneda==$DatosOrden->id_moneda)?'selected':'';?>><?=$rows->moneda_nombre?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Tipo de Cambio:</td>
					<td>
						<input type="text" name="tipo_cambio" id="tipo_cambio" readonly="true" value="<?=$DatosOrden->tipo_cambio?>" class="form-control">
					</td>
				</tr>
				<tr>
					<td>Total moneda mx:</td>
					<td>
						<input type="text" name="total_peso" id="total_peso" readonly="true" value="<?=$Subtotal?>" class="form-control">
					</td>
				</tr>

				<tr>
					<td>SubTotal</td>
					<td>
						<span style="font-weight:bold; font-size:1.2em;">
							$ <?=number_format($Subtotal/$DatosOrden->tipo_cambio,2)?>
						</span>
					</td>
				</tr>
				<tr>
					<td>Iva</td>
					<td>
						<span style="font-weight:bold; font-size:1.2em;">
							$ <?=number_format($Iva/$DatosOrden->tipo_cambio,2)?>
						</span>
					</td>
				</tr>
				<tr>
					<td>Total</td>
					<td><span class="heading-primary" style="font-weight:bold; font-size:1.8em;">$ <?=number_format($Total/$DatosOrden->tipo_cambio,2)?></span></td>
				</tr>
				<tr>
					<td>Referencia:</td>
					<td><input type="text" class="form-control" id="ordendecompra_numero_factura" name="ordendecompra_numero_factura" value="<?=$DatosOrden->ordendecompra_numero_factura?>" <?php if($DatosOrden->ordendecompra_estatus==4 || $DatosOrden->ordendecompra_estatus==2){ echo 'disabled="true"';} ?>></td>
				</tr>
			</table>
		</fieldset>
	</div>
</div>
<?php } ?>