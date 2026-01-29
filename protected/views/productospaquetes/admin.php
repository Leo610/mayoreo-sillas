  <?php
/* @var $this ProductospaquetesdetallesController */
/* @var $model Productosdetallesdetalles */


$this->pageTitle='Administración de Paquetes';
$this->breadcrumbs=array(
  'Modulos'=> Yii::app()->createUrl('administracion/modulos'),
  'Productos'=> Yii::app()->createUrl('productos/admin'),
	'Productos paquetes',
);


$Paquete = (isset($_GET['productopaquete']))?$_GET['productopaquete']:0;
?>
<script type="text/javascript">
$( document ).ready(function() {

    // Funcion para ordenar la lista de resultados
    $('#lista').DataTable({paging: false});

    // Al cargar la pagina, calculamos los costos
    Calcularprecioventa();

    // Metodo para actualizar el costo de paquete.
    $( "#actualizarprecioventa" ).click(function() {
      var jqxhr = $.ajax({
        url: "<?php echo $this->createUrl("productospaquetes/Actualizarprecioventa"); ?>",
        type: "POST",
        dataType : "json",
        timeout : (120 * 1000),
        data: {
            id_producto : $('#id_producto').val(),
            porc_gastos_indirectos : $('#porc_gastos_indirectos').val(),
            total_gastos_indirectos : $('#total_gastos_indirectos').val(),
            porc_utilidad : $('#porc_utilidad').val(),
            total_utilidad : $('#total_utilidad').val(),
            gastos_fabricacion : $('#gastos_fabricacion').val(),
            producto_precio_venta_default : $('#producto_precio_venta_default').val(),
        },
        success : function(Response, newValue) {
            if (Response.requestresult == 'ok') {
              $.notify(Response.message,'success');
            }else{
              $.notify(Response.message,'error');
            }
        },
        error: function(e){
              $.notify('Ocurrio un error inesperado','error');
            }
        });
    });
   
 });

    // funcion para calcular los porcentajes y costo de venta.
    function Calcularprecioventa()
    {
        var porc_gastos_indirectos = parseFloat($('#porc_gastos_indirectos').val() / 100);
        var porc_utilidad = parseFloat($('#porc_utilidad').val() / 100);
        var gastos_fabricacion = parseFloat($('#gastos_fabricacion').val());
         $('#sumatorias').val(gastos_fabricacion);
        // Primero en base al costo de fabricacion obtenemos el total de gastos indirectos
        if($.isNumeric(porc_gastos_indirectos))
        {
            var total_gastos_indirectos = parseFloat(gastos_fabricacion * porc_gastos_indirectos);
            $('#total_gastos_indirectos').val(myFixed(total_gastos_indirectos,2));
            // lo sumamos en sumatorias
            $('#sumatorias').val(gastos_fabricacion + total_gastos_indirectos);
        }else{
            var total_gastos_indirectos =0;
        }
        
        // Obtenemos en base al porcentaje de utilidad, el total de utilidad
        if($.isNumeric(porc_utilidad) )
        {   
            var total_utilidad = parseFloat(porc_utilidad * $('#sumatorias').val());
            $('#total_utilidad').val(myFixed(total_utilidad,2));
            // lo sumamos en sumatorias
            var sumatoriant = $('#sumatorias').val();
            $('#sumatorias').val(myFixed(sumatoriant + total_utilidad,2));
        }else{
             var total_utilidad = 0;
        }
       
        // actualizamos el costo de venta
        $('#producto_precio_venta_default').val(myFixed(gastos_fabricacion+total_gastos_indirectos+total_utilidad,2));
    }
    
    function myFixed(x, d) {
        var x = parseFloat(x);
        if (!d) return x.toFixed(d); // don't go wrong if no decimal
        return x.toFixed(d).replace(/\.?0+$/, '');
    }

    function Actualizar(id){
        var jqxhr = $.ajax({
        url: "<?php echo $this->createUrl("Productos/datos"); ?>",
        type: "POST",
        dataType : "json",
        timeout : (120 * 1000),
        data: {
            id: id,
        },
        success : function(Response, newValue) {
            if (Response.requestresult == 'ok') {

               // Si el resultado es correcto, agregamos los datos al form del modal
               $("#Productos_producto_nombre").val(Response.Datos.producto_nombre);
               $("#Productos_producto_clave").val(Response.Datos.producto_clave);
               $("#Productos_producto_costo_compra").val(Response.Datos.producto_costo_compra);
               $("#Productos_producto_precio_venta_default").val(Response.Datos.producto_precio_venta_default);
               $("#Productos_producto_tiempo_elaboracion").val(Response.Datos.producto_tiempo_elaboracion);
               $("#Productos_producto_descripcion").val(Response.Datos.proveedor_producto_descripcion);
               $("#Productos_producto_tipo").val(Response.Datos.producto_tipo);
               $("#Productos_id_lista_precio").val(Response.Datos.id_lista_precio);
               $("#Productos_productoafectaalmacen").val(Response.Datos.productoafectaalmacen);
                $("#Productos_producto_horashombre").val(Response.Datos.producto_horashombre);
               $("#Productos_id_producto").val(Response.Datos.id_producto);
               $("#Productos_id_unidades_medida").val(Response.Datos.id_unidades_medida);
              $("#Productos_cantidad_horas").val(Response.Datos.cantidad_horas);

               //mostramos la imagen
               $("#imagenpro").attr("src","../archivos/"+Response.Datos.producto_imagen);
               $("#imagenoriginal").val(Response.Datos.producto_imagen);
               
               $("#formmodal").modal('show');
            }else{
                
            }
        },
        error: function(e){
              $.notify('Ocurrio un error inesperado','error');
            }
        });
    }
</script>
<?php 
// Incluimos el modal de clientes, de otra vista
if(!empty($datosproducto))
{
Yii::app()->controller->renderFile(Yii::app()->basePath.'/views/productos/modal/_form.php',
        array(
            'model'=>$datosproducto,
            'arraylistaunidadesmedidas'=>$arraylistaunidadesmedidas
        ));
}else{
?>

<div class="row">
    <div class="col-md-12">
        <fieldset>
            <legend>Seleccione el paquete</legend>
            <form method="get">
            <?php 
            $_datosautocompletar = array ();
            foreach ($listapaquetes as $key=>$value ){ 
                $_datosautocompletar[$key] = $value;
            } 

            $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
                'name'=>'productopaquete_auto',
                'source'=>$_datosautocompletar,
                'source' => array_map(function($key, $value) {
                                return array('label' => $value, 'value' => $key);
                            }, array_keys($_datosautocompletar), $_datosautocompletar),
              // Opciones javascript adicionales para el plugin
                'options'=>array(
                    'minLength'=>'3',
	                   'select'=>'js:function(event, ui) {
	                        $("#productopaquete").val(ui.item.value);
	                        $("#productopaquete_auto").val(ui.item.label);
	                        return false; 

	                    }',
                    'focus'=>'js:function(event, ui) {
                        return false;
                    }'
                ),
                'htmlOptions'=>array(
                    'class'=>'form-control',
                    'placeholder'=>'Buscar producto...'
                )
            ));
          ?>
          <input type="hidden" name="productopaquete" id="productopaquete">
            </form>
        </fieldset>
    </div>
</div>
<?php } ?>
        <?php if (!empty($datosproducto['producto_nombre']))
        { ?>
        <div class="row">
            <div class="col-md-12">
            	<fieldset>
            		<legend>Datos del Paquete |  <?php  echo CHtml::link('Actualizar', "javascript:;", array(
                            'class'=>'btn btn-success btn-sm',
                                'style' => 'cursor: pointer;',
                                "onclick" => "Actualizar(".$datosproducto['id_producto']."); return false;"
                            )); ?></legend>
            		<div class="col-md-12">
                        <p class="lead">
            			Nombre: <b><?=$datosproducto['producto_nombre']?></b>
            		    <br>
            			Clave: <b><?=$datosproducto['producto_clave']?></b>
            	        <br>
            			Descripción: <b><?=$datosproducto['producto_descripcion']?></b></b>
                        </p>
            		</div>
            	</fieldset>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
            	<fieldset>
            		<legend>Agregar producto</legend>
            		<div class="form">

						<?php $form=$this->beginWidget('CActiveForm', array(
							'id'=>'productospaquetes-agregar',
							'action'=>Yii::app()->createUrl('Productospaquetes/AgregarProducto'),
							'enableClientValidation'=>true,
							'clientOptions' => array('validateOnSubmit'=>true, 'validateOnType'=>true),
						)); ?>

						    <?php echo $form->errorSummary($Productopaquete); ?>
								<?php echo $form->hiddenField($Productopaquete,'id_producto',array('value'=>$datosproducto['id_producto'])); ?>
								<?php echo $form->hiddenField($Productopaquete,'pp_estatus',array('value'=>1)); ?>

						    <div class="row">
						    	<div class="col-md-7">
						    		<?php
							    			$this->widget('zii.widgets.jui.CJuiAutoComplete',array(
				                'name'=>'id_productoincluido_auto',
				                'source'=>$this->createUrl('productos/Productosajax'),
				                
				              	// Opciones javascript adicionales para el plugin
				                'options'=>array(
				                    'minLength'=>'3',
					                  'select'=>'js:function(event, ui) {
					                  	$("#Productospaquetes_id_productoincluido").val(ui.item.id);
					                 	}',
				                    'focus'=>'js:function(event, ui) {
				                        return false;
				                    }'
				                ),
				                'htmlOptions'=>array(
				                    'class'=>'form-control',
				                    'placeholder'=>'Producto',
                                    'autofocus'=>'true'
				                )
					            ));
					          ?>
						        <?php echo $form->hiddenField($Productopaquete,'id_productoincluido'); ?>
						        <?php echo $form->error($Productopaquete,'id_productoincluido'); ?>
						      </div>
						      <div class="col-md-3">
						        <?php echo $form->textField($Productopaquete,'pp_cantidad',array('class'=>'form-control','placeholder'=>'Cantidad')); ?>
						        <?php echo $form->error($Productopaquete,'pp_cantidad'); ?>
						      </div>
						      <div class="col-md-2">
						      	<?php echo CHtml::submitButton('Agregar',array('class'=>'btn btn-primary')); ?>
						      </div>
						    </div>

						<?php $this->endWidget(); ?>

					</div><!-- form -->
            	</fieldset>
            </div>
        </div>
        
<div class="row">
    <div class="col-md-12">
        <fieldset>
            <legend>Productos en el paquete</legend>
            <div class="table-responsive">
            <table id="lista" class="table  table-bordered  table-hover ">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Clave</th>
                        <th>Costo compra</th>
                        <th style="width:280px;">Cantidad</th>
                        <th>UM</th>
                        <th>Total</th>
                        <th>Acciones</th>

                    </tr>
                </thead>
                <tbody>
                		<?php 
                        $subtotal = 0;
                		foreach ($lista as $rows){ ?>
                    <tr>
                        <td><?=$rows['id_productoincluido']?></td>
                        <td><?=$rows['rl_productoincluido']['producto_nombre']?></td>
                        <td><?=$rows['rl_productoincluido']['producto_clave']?></td>
                        <td>$ <?=number_format($rows['rl_productoincluido']['producto_costo_compra'],2)?></td>
                        <td>
                        	<?php

    	                    	echo Chtml::link(
								  '<i class="fa fa-plus"></i>', 
								  'productospaquetes/Actualizarproductospaquetes', 
								  array(
								       'submit'=>array('productospaquetes/Actualizarproductospaquetes'), 
								       'params'=>array('id_pp'=>$rows['id_pp'],'tipoactualizacion'=>1),
								       'class'=>'btn btn-default btn-sm mr-sm'
								  )
								); 
								echo $rows['pp_cantidad'];

                                if($rows['rl_productoincluido']['producto_horashombre']==1)
                                {
                                  echo ' (<strong>'.$rows['pp_cantidad']*$rows['rl_productoincluido']['cantidad_horas'].' horas</strong> )';  
                                } 
								if($rows['pp_cantidad']>1)
								{
									echo Chtml::link(
									   '<i class="fa fa-minus fa-lg"></i>', 
									    'productospaquetes/Actualizarproductospaquetes', 
									    array(
									         'submit'=>array('productospaquetes/Actualizarproductospaquetes'), 
									         'params'=>array('id_pp'=>$rows['id_pp'],'tipoactualizacion'=>0),
									         'class'=>'btn btn-default btn-sm ml-sm'
									    )
									);
								} 
                                ?>
                        </td>
                        <td><?=$rows['rl_productoincluido']['rl_unidadesdemedida']['unidades_medida_abreviatura']?></td>
                        <td>$ <?=number_format($rows['rl_productoincluido']['producto_costo_compra']* $rows['pp_cantidad'],2)?></td>

                       	<td class="">
                            <?php 
                            	if($rows['rl_productoincluido']['producto_tipo']==1)
                        	{
                        	 echo CHtml::link('<i class="fa fa-list-alt"></i> Productos <br>',array('productospaquetes/admin?productopaquete='.$rows['id_productoincluido']
                            ));
                        	 }
                        	 
                               echo CHtml::link('<i class="fa fa-trash fa-lg"></i> Eliminar', array('Productospaquetes/delete', 'id'=>$rows['id_pp']),
    				    						  array(
    				    						    'submit'=>array('Productospaquetes/delete', 'id'=>$rows['id_pp']),
    				    						    'class' => 'delete','confirm'=>'Seguro que lo deseas eliminar?'
    				    						  )
    				    						);
    				    						?>
                       </td>
                    </tr>
                    <?php 
                    $subtotal = $subtotal + ($rows['rl_productoincluido']['producto_costo_compra']* $rows['pp_cantidad']);
                    } ?>
                </tbody>
                
            </table>
            </div>
            <div class="table-responsive">
            <table class="table table-striped table-bordered dt-responsive nowrap">
                <tbody>
                    <tr>
                       
                        <td><h3 class="mb-none">Costo de Fabricación</h3></td>
                        <td><h3 class="mb-none">
                            <input type="text" class="form-control" name="gastos_fabricacion" id="gastos_fabricacion" value="<?=$subtotal?>" readonly="TRUE"></h3>
                            <input type="hidden" id="sumatorias" value="<?=$subtotal?>" name="sumatorias">
                        </td>
                    </tr>
                    <tr>
                        <td style="border-top:none;"><h3 class="mb-none">% Gastos Indirectos</h3></td>
                        <td style="border-top:none;" colspan="2">
                            <div class="form-inline">
                                <input type="text" class="form-control" name="porc_gastos_indirectos" id="porc_gastos_indirectos" onchange="Calcularprecioventa();" placeholder="Porcetaje de gastos indirectos" value="<?=$datosproducto->porc_gastos_indirectos?>">
                                <input type="text" class="form-control" name="total_gastos_indirectos" id="total_gastos_indirectos" readonly="TRUE" value="<?=$datosproducto->total_gastos_indirectos?>">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="border-top:none;"><h3 class="mb-none">% Utilidad</h3></td>
                        <td style="border-top:none;" colspan="2">
                            <div class="form-inline">
                                <input type="text" class="form-control" name="porc_utilidad" id="porc_utilidad" onchange="Calcularprecioventa();" placeholder="Porcentaje de utilidad" value="<?=$datosproducto->porc_utilidad?>">
                                <input type="text" class="form-control" name="total_utilidad" id="total_utilidad" readonly="TRUE" value="<?=$datosproducto->total_utilidad?>">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="border-top:none;"><h3 class="mb-none">Costo de Venta</h3></td>
                        <td style="border-top:none;" colspan="2">
                            <input type="text" class="form-control" name="producto_precio_venta_default" id="producto_precio_venta_default" value="<?=$datosproducto->producto_precio_venta_default?>">
                            <center>
                                <button type="button" id="actualizarprecioventa" class="btn btn-success mt-md"> Actualizar precio venta</button>
                            </center>
                        <input type="hidden" name="id_producto" id="id_producto" value="<?=$datosproducto->id_producto?>">
                        </td>
                    </tr>
                </tbody>
            </table>
            </div>
         </div>
</div>
  <?php }  ?>
   