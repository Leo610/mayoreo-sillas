<?php
/* @var $this ProyectosController */
$this->pageTitle='Surtir pedido '.$DatosProyecto->proyecto_nombre;

$this->breadcrumbs=array(
	'Proyectos'=>array('/proyectos/ver/'.$DatosProyecto->id_proyecto),
	'Surtir proyecto '.$DatosProyecto->proyecto_nombre,
);
?>
<script type="text/javascript">
$(document).ready(function() {
    // Funcion para ordenar la lista de resultados, lo ordena por la columna 4, ya que empieza 0,1,2,3,4 y muestra  5 resultados, solamente	
    $('#listaproductos').DataTable( {
        "paging":   false,
        "ordering": false,
        "info":     false,
        "searching":     false,
    } );


   // Proceso para generar orden de compra de los proveedores - Generarocproyecto
		$( "body" ).on( "click","#agregarproducto", function() {	
		var id_proyecto = $('#id_proyecto').val();
		var id_producto = $('#id_producto').val();
		var id_almacen = $('#id_almacen').val();
		var cantidad = $('#proyectos_productos_cantidad').val();

		if(id_almacen=='' || id_producto=='' || id_proyecto=='' || cantidad=='')
		{
			$.notify('Verifique el producto a agregar','error');
			return false;
		}
		// Enviamos la solicitud por metodo ajax
		var jqxhr = $.ajax({
	    url: "<?php echo $this->createUrl("Proyectoproductos/Agregarproducto"); ?>",
	    type: "POST",
	    dataType : "json",
	    timeout : (120 * 1000),
	    data: {
	        id_producto: id_producto,
	        id_proyecto: id_proyecto,
	        id_almacen:id_almacen,
	        cantidad:cantidad
	    },
	    success : function(Response, newValue) {
	        if (Response.requestresult == 'ok') {
	        		$("#listaproductos").dataTable().fnDestroy();
	        		$.notify(Response.message, "success");
	        		// agregamos la tr
		        	$('#listaproductos tr:last').after(Response.fila)
		        	// reiniciamos la datatable
		        	 $('#listaproductos').DataTable( {
				         "paging":   false,
				        "ordering": false,
				        "info":     false,
				        "searching":     false
				    } );
				    $('#id_product_auto').val('');
				    $('#id_producto').val('');
	        }else{
	        	  $('#generarodenesdecompra').prop( "disabled", false );
	            $.notify(Response.message, "error");
	        }
	    },
	    error: function(e){
	         	$.notify('Ocurrio un error inesperado', "error");
	         	$('#generarodenesdecompra').prop( "disabled", false );
	        }
	   });


	}); // end $( "#agregarproducto" ).click(function() {

    // METODO PARA CALCULAR EL STOCK
   $( "body" ).on( "change",".calcularstock", function() {	
	  var id_producto = $(this).data('idprod');
	  var id_productoproyecto = $(this).data('idproyprod');
	  var id_ubicacion = $('#id_almacenes_ubicaciones'+id_productoproyecto).val();

	  if(id_producto=='')
	  {
	  	$.notify('Seleccione el producto', "error");
	  	return false;
	  }
	  if(id_ubicacion=='')
	  {
	  	$.notify('Seleccione la ubicación', "error");
	  	return false;
	  }

	  var necesario = parseFloat($('#necesario'+id_productoproyecto).val());
   	if(isNaN(necesario) || necesario < 0)
 		{
 			$.notify('Ingrese la cantidad correctamente','error');
 			return false;
 		}

	   var jqxhr = $.ajax({
	    url: "<?php echo $this->createUrl("Proyectos/CantidadstockJS"); ?>",
	    type: "POST",
	    dataType : "json",
	    timeout : (120 * 1000),
	    data: {
	        id_ubicacion: id_ubicacion,
	        id_producto: id_producto
	    },
	    success : function(Response, newValue) {
	        if (Response.requestresult == 'ok') {
	        		$.notify(Response.message, "success");
	        		$('#stock'+id_productoproyecto).val(Response.stock)
	        		// Actualizamos la cantidad del faltante
	        		$('#faltante'+id_productoproyecto).val(necesario - Response.stock)

	        		if($('#faltante'+id_productoproyecto).val()<0){ $('#faltante'+id_productoproyecto).val(0) }
	        }else{
	            $.notify(Response.message, "error");
	            $('#stock'+id_productoproyecto).val(Response.stock)
	            $('#faltante'+id_productoproyecto).val(necesario - Response.stock)
	            
	            if($('#faltante'+id_productoproyecto).val()<0){$('#faltante'+id_productoproyecto).val(0)}
	        }
	     		if($('#preciounitario'+id_productoproyecto).val()>0)
	     		{
					var preciototalprod = parseFloat($('#preciounitario'+id_productoproyecto).val()) * parseFloat($('#faltante'+id_productoproyecto).val());
					$('#preciototal'+id_productoproyecto).val(preciototalprod);
	     		}
	     		
	    },
	    error: function(e){
	         	$.notify('Ocurrio un error inesperado', "error");
	        }
	   });
	  
	});

	// Calculamos el precio en base a id producto - id proveedor
	$( "body" ).on( "change",".calcularprecio", function() {	
	  var id_producto = $(this).data('idprod');
	  var id_proveedor = $(this).val();
	  var id_productoproyecto = $(this).data('idproyprod');
	  if(id_producto=='')
	  {
	  	$.notify('Seleccione el producto', "error");
	  	return false;
	  }
	  if(id_proveedor=='')
	  {
	  	$.notify('Seleccione el proveedor', "error");
	  	return false;
	  }
	   var jqxhr = $.ajax({
	    url: "<?php echo $this->createUrl("Productosproveedores/Obtenerprecioproducto"); ?>",
	    type: "POST",
	    dataType : "json",
	    timeout : (120 * 1000),
	    data: {
	        id_proveedor: id_proveedor,
	        id_producto: id_producto
	    },
	    success : function(Response, newValue) {
	        if (Response.requestresult == 'ok') {
	        		$.notify(Response.message, "success");	
					$('#preciounitario'+id_productoproyecto).val(Response.precio);
					var preciototalprod = parseFloat(Response.precio) * parseFloat($('#faltante'+id_productoproyecto).val());
					$('#preciototal'+id_productoproyecto).val(preciototalprod);
	        		
	        }else{
	            $.notify(Response.message, "error");
	        }
	    },
	    error: function(e){
	         	$.notify('Ocurrio un error inesperado', "error");
	        }
	   });
	  
	});
	// Proceso para generar orden de compra de los proveedores - Generarocproyecto
	$( "body" ).on( "click","#generarodenesdecompra", function() {	

		var id_proyecto = $('#id_proyecto').val();
		$('#generarodenesdecompra').prop( "disabled", true );
		// Recorremos fila por fila
		var productos = [];
		var productosacerrar = [];
		$("#listaproductos tbody tr").each(function (index,element) 
      {
      	var id_producto = $(element).data('idprod');
      	var ubicacion = $(element).find('select[name$="id_almacenes_ubicaciones"]').val(); 
      	var cantidadstock = $(element).find('input[name$="stock"]').val(); 
      	var cantidadsolicitada = $(element).find('input[name$="necesario"]').val(); 
      	var cantidad = $(element).find('input[name$="faltante"]').val();
      	var proveedor = $(element).find('select[name$="id_proveedor"]').val();
      	var id_proyectos_productos = $(element).data('idproyprod');
      	if(cantidad > 0 && proveedor>0)
      	{	//parse_str("name=Peter&age=43");
      		productos.push('id_producto='+id_producto+'&cantidad='+cantidad+'&id_proveedor='+proveedor+'&id_proyectos_productos='+id_proyectos_productos);
      	}
      	// Verificamos si la cantidad solicitada es menor a la cantidad en stock, para cerrar el producto.
      	if(cantidadstock >= cantidadsolicitada)
      	{
      		productosacerrar.push('id_producto='+id_producto+'&cantidad='+cantidad+'&id_proveedor='+proveedor+'&id_proyectos_productos='+id_proyectos_productos+'ubicacion='+ubicacion);
      	}
      })// $("#listaproductos tbody tr").each(function (index,element) 
      // Imprimimos el array
      var elementos = 0;
      $.each(productos, function(index, value) {
      	elementos = 1;
		});
		$.each(productosacerrar, function(index, value) {
      	elementos = 1;
		});
		if(elementos==0)
		{
			$('#generarodenesdecompra').prop( "disabled", false );
			$.notify('Seleccione almenos una ubicacion y proveedor', "error");
			return false;
		}
		// Enviamos la solicitud por metodo ajax
		var jqxhr = $.ajax({
	    url: "<?php echo $this->createUrl("Ordenesdecompra/Generarocproyecto"); ?>",
	    type: "POST",
	    dataType : "json",
	    timeout : (120 * 1000),
	    data: {
	        productos: productos,
	        productosacerrar: productosacerrar,
	        id_proyecto: id_proyecto
	    },
	    success : function(Response, newValue) {
	        if (Response.requestresult == 'ok') {
	        		$.notify(Response.message, "success");
	        		$('#generarodenesdecompra').prop( "disabled", false );
	        		window.location.replace("<?php echo $this->createUrl('proyectos/ver/'); ?>/"+id_proyecto);
	        }else{
	        	  $('#generarodenesdecompra').prop( "disabled", false );
	            $.notify(Response.message, "error");
	        }
	    },
	    error: function(e){
	    	
	         	$.notify('Ocurrio un error inesperado', "error");
	         	$('#generarodenesdecompra').prop( "disabled", false );
	        }
	   });


	}); // end $( "#generarodenesdecompra" ).click(function() {


	/**
	 * METODO PARA ELIMINAR EL PRODUCTO DEL PROYECTO, SOLO ABIERTOS
	 */
	$( "body" ).on( "click",".eliminarproducto", function() {

		var id = $(this).data('id');
		// Confirmamos que valide que si
   	var r = confirm("Favor de confirmar");
		if (r == false) {
		    return false;
		}
		var jqxhr = $.ajax({
	    url: "<?php echo $this->createUrl("Proyectoproductos/Eliminarproducto"); ?>",
	    type: "POST",
	    dataType : "json",
	    timeout : (120 * 1000),
	    data: {
				id_proyectos_productos : id
	    },
	    success : function(Response, newValue) {
	        if (Response.requestresult == 'ok') {
	        	$.notify(Response.message, "success");
	        	// Eliminamos la Fila
	        	$('#'+id).hide();

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
    
</script>

<div class="row">
<input type="hidden" name="id_proyecto" value='<?=$DatosProyecto->id_proyecto?>' id="id_proyecto">
	<div class="col-md-12">
		<h1><?=$DatosProyecto->id_proyecto?> <?=$DatosProyecto->proyecto_nombre?> | 
				<a href="<?=Yii::app()->createUrl('proyectos/ver/'.$DatosProyecto->id_proyecto);?>" class="btn btn-success">
				Regresar al proyecto
				</a></h1>
		<div class="col-md-12">
			<fieldset>
				<legend>Encabezado</legend>
				<div class="col-md-4">Supervisor:<h3 class="mb-none"><?=$DatosProyecto->rl_supervisor->empleado_nombre?></h3></div>
				<div class="col-md-4">Cot. Referencia: <h3 class="mb-none"><?=$DatosProyecto->id_cotizacion?></h3></div>
				<div class="col-md-4">Fecha Alta: <h3 class="mb-none"><?=$DatosProyecto->proyecto_fecha_alta?></h3></div>
				<div class="col-md-4">Total: <h3 class="mb-none">$ <?=number_format($DatosProyecto->proyecto_total,2)?></h3></div>
				<div class="col-md-4">Recibido: <h3 class="mb-none">$ <?=number_format($DatosProyecto->proyecto_totalpagado,2)?></h3></div>
				<div class="col-md-4">Pendiente: <h3 class="mb-none">$ <?=number_format($DatosProyecto->proyecto_totalpendiente,2)?></h3></div>
				<div class="col-md-4">Estatus: <h3 class="mb-none" style="font-weight:bold;"><?=$this->ObtenerEstatus($DatosProyecto->proyecto_estatus)?></h3></div>
				<div class="col-md-4">Moneda: <h3 class="mb-none" style="font-weight:bold;"><?=$DatosProyecto->rl_moneda->moneda_nombre?></h3></div>
				<div class="col-md-4">Almacen: <h3 class="mb-none" style="font-weight:bold;"><?=$DatosProyecto->rl_almacen->almacen_nombre?></h3></div>
				<div class="col-md-12">Comentarios <h3 class="mb-none"><?=$DatosProyecto->proyecto_comentarios?></h3></div>
				
    </div>
    <div class="col-md-12">
  		<fieldset>
			<legend style="margin-bottom:10px;">Agregar producto</legend>
			<div class="col-md-4">
		     <?php 
		    	$this->widget('zii.widgets.jui.CJuiAutoComplete',array(
					'name'=>'id_producto_auto',
					'source'=>$this->createUrl('productos/Productosajax'),
					// Opciones javascript adicionales para el plugin
					'options'=>array(
					   'minLength'=>'3',
					   'select'=>'js:function(event, ui) {
					   	$("#id_producto").val(ui.item.id);
					   	$("#id_producto_auto").val(ui.item.value);
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
          <input type="hidden" name="id_producto" id="id_producto">
	      </div>
	      <div class="col-md-2">
	      	<input type="text" name="proyectos_productos_cantidad" value="" placeholder="Ingrese la cantidad" id="proyectos_productos_cantidad" class="form-control">
	      </div>
	      <div class="col-md-1">
	      	<button type="button"  class="btn btn-success" id="agregarproducto">Agregar producto</button>
	      </div>
    	</fieldset>
    </div>
    <div class="col-md-12">
  		<fieldset>
			<legend style="margin-bottom:10px;">Lista de Productos</legend>
			<div class="table-responsive">
	      <table id="listaproductos" class="table  table-bordered table-hover">
	          <thead>
	              <tr>
	                  <th style="width:200px;">Producto</th> 
	                  <th>Ubicación</th>
	                  <th >Cantidad del proyecto</th>
	                  <th >Cantidad en Stock</th> 
	                  <th >Cantidad faltante</th>
	                  <th >Proveedor</th>
	                  <th >Costo unitario</th>
	                  <th >Total</th>
	                  <th></th>
	              </tr>
	          </thead>
	          <tbody>
	          		<?php 
	          		$a=0;
	          		foreach ($Productosproyecto as $rows){
	          		
	          			// Verificamos que el producto afecte almacen
	          			if($rows->rl_producto->productoafectaalmacen==1)
	          			{
	          				$a++;
	          			?>
			              <tr id="<?=$rows->id_proyectos_productos?>" data-idprod="<?=$rows->id_producto?>" data-idproyprod="<?=$rows->id_proyectos_productos?>">
			              		<td><?=$rows->rl_producto->producto_nombre?></td>
		              			<td>
			              			<select name="id_almacenes_ubicaciones" class="calcularstock form-control" data-idprod="<?=$rows->id_producto?>" data-idproyprod="<?=$rows->id_proyectos_productos?>" id="id_almacenes_ubicaciones<?=$rows->id_proyectos_productos?>">
			              				<option value="">-- Seleccione ubicación --</option>
			              				<?php
			              				$ubicacionesalmacen = Almacenesubicaciones::model()->findall('id_almacen='.$rows->id_almacen);
		              					foreach ($ubicacionesalmacen as $rowsa)
			              				{
			              					echo '<option value="'.$rowsa->id_almacenes_ubicaciones.'">
			              						'.$rowsa->almacenesubicaciones_nombre.'
			              					</option>';
			              				}
			              				?>
			              			</select>
		              			</td>
		              			
		              			<td>
		              				<input type="text" name="necesario" id="necesario<?=$rows->id_proyectos_productos?>" class="form-control calcularstock" value="<?=$rows->proyectos_productos_cantidad?>" data-idprod="<?=$rows->id_producto?>" data-idproyprod="<?=$rows->id_proyectos_productos?>" readonly="TRUE">
		              			</td>
		              			<td>
		              				<input type="text" readonly="true" name="stock" id="stock<?=$rows->id_proyectos_productos?>" class="form-control">
		              			</td>
		              			<td>
		              				<input type="text" readonly="true" name="faltante" id="faltante<?=$rows->id_proyectos_productos?>" class="form-control" value="">
		              			</td>
		              			<td>
		              				<select name="id_proveedor" class="form-control calcularprecio" data-idprod="<?=$rows->id_producto?>" data-idproyprod="<?=$rows->id_proyectos_productos?>">
			              				<option value="">-- Seleccione proveedor --</option>
			              				<?php
			              				$productosproveedores = Productosproveedores::model()->findall('id_producto='.$rows->id_producto);
		              					foreach ($productosproveedores as $rowsp)
			              				{
			              					echo '<option value="'.$rowsp->id_proveedor.'">
			              						'.$rowsp->rl_proveedor->proveedor_razonsocial.'
			              					</option>';
			              				}
			              				?>
			              			</select>		              				
		              			</td>
		              			<td><input type="text" name="preciounitario" id="preciounitario<?=$rows->id_proyectos_productos?>" class="form-control"  readonly="true"></td>
		              			<td><input type="text" name="preciototal" id="preciototal<?=$rows->id_proyectos_productos?>" class="form-control" readonly="true"></td>
		              			<td>
				                  <?php if($rows->abierto==1){ ?>
				                  <button type="button" class="btn btn-danger btn-sm eliminarproducto" data-id="<?=$rows->id_proyectos_productos?>" id="botoneliminar_<?=$rows->id_proyectos_productos?>">Eliminar </button>
				                  <?php } ?>
			                  </td>
			             </tr>
	             <?php }
	             } ?>
	         </tbody>
	      </table>
	      </div>
	      <center><input type="hidden" name="id_almacen" id="id_almacen" value="<?=$rows['id_almacen']?>">
	      <?php if($a>0)
	      {?>
	      <button class="btn btn-success mt-lg" id="generarodenesdecompra">Generar Ordenes de Compra</button><br>
	      <small>* Se genera una orden de compra por proveedor.</small>
	      <?php }
	      else{
	      	echo '<p class="lead mt-lg">El proyecto ya surtio todos sus productos.</p>';
	      }
	      ?>
	      </center>
    	</fieldset>
    </div>
  </div>
</div>