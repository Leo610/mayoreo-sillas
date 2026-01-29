<?php
/* @var $this CotizacionesController */
$this->pageTitle = 'Detalles de la cotización ' . $DatosCotizacion->id_cotizacion;

$this->breadcrumbs = array(
	'Cotizaciones' => array('/cotizaciones/lista'),
	'Actualizar',
);

// variable para determinar si tiene permiso o no de cambiar el precio|
$readonly = ($this->VerificarAcceso(9, Yii::app()->user->id)) == 1 ? false : true;
// variable para verificar si puede agregar desuentos
$descuentos = ($this->VerificarAcceso(8, Yii::app()->user->id)) == 1 ? false : true;


?>
<script type="text/javascript">
	$(document).ready(function () {
		var selected = document.getElementById('agregariva');

		if (selected) {
			var selectedValue = $('#agregariva').val();
			document.getElementById('iva').value = selectedValue;

		}

		$('#agregariva').on('change', function () {
			var selectedValue = $(this).val(); // Obtener el valor seleccionado
			var idcot = <?= $DatosCotizacion->id_cotizacion ?>

			// Realizar la solicitud AJAX
			console.log('cambio el iva');
			var jqxhr = $.ajax({
				url: "<?php echo $this->createUrl("cotizaciones/modificariva"); ?>",
				type: 'POST', // Método de solicitud
				dataType: "json",
				// timeout: (120 * 1000),
				data: { value: selectedValue, idcot: idcot }, // Datos a enviar
				success: function (response) { // Callback cuando la solicitud es exitosa
					$.notify(response.message, "success"); // Actualizar el contenido de 'result' con la respuesta
					setTimeout(() => {
						window.location.reload();

					}, 1000);
				}
			});
		});


		$('#productoscotizacion').DataTable({
			"paging": false,
			"ordering": false,
			"info": false,
			"searching": false
		});
		$('#cotarchivos').DataTable({
			"paging": false,
			"ordering": false,
			"info": false,
			"searching": false
		});


		$('.actproddesc').click(function () {
			$("#proddescmodal").modal('show');
			var id = $(this).data("id");

			// madnamos un ajax para obtener los datos acutales del prodcuto de acuerdo al id que tenemos
			var jqxhr = $.ajax({
				url: "<?php echo $this->createUrl("Cotizacionesproductos/obtenerdatosprodcot"); ?>",
				type: "POST",
				dataType: "json",
				timeout: (120 * 1000),
				data: {
					id: id,
				},
				success: function (Response, newValue) {
					if (Response.requestresult == 'ok') {
						// mio
						console.log(Response);
						$('#Cotizacionesproductos_cotizacion_producto_cantidad2').val(Response.cant);
						$('#Cotizacionesproductos_cotizacion_producto_unitario2').val(Response.unitario);
						$('#Cotizacionesproductos_color_tapiceria').val(Response.colortapi);
						$('#Cotizacionesproductos_cotizacion_producto_descripcion2').val(Response.desc);
						$('#Cotizacionesproductos_especificaciones_extras2').val(Response.espext);
						$('#Cotizacionesproductos_color').val(Response.color);
						$('#Cotizacionesproductos_id_cotizacion_producto').val(Response.id);
						$('#Cotizacionesproductos_descuento2').val(Response.desc2);
						$('#Cotizacionesproductos_tipo_descuetno2').val(Response.tdesc);

						$.notify(Response.message, 'success');

					} else {

						$.notify(Response.message, 'error');
					}
				},
				error: function (e) {
					$.notify('Ocurrio un error inesperado', 'error');
				}
			});

		});
	});





	/*
	 *	METODO PARA OBTENER EL PRECIO DEL PRODUCTO, EN BASE A LA LISTA DE PRECIOS DEL CLIENTE
	 *	CREADO POR DANIEL VILLARREAL EL 31 DE ENERO DEL 2016
	 */
	function GetPrice(id_producto, id_listaprecio) {

		var jqxhr = $.ajax({
			url: "<?php echo $this->createUrl("Cotizacionesproductos/Obtenerprecio"); ?>",
			type: "POST",
			dataType: "json",
			timeout: (120 * 1000),
			data: {
				id_producto: id_producto,
				id_listaprecio: id_listaprecio,
				tipo_precio: <?= $DatosCotizacion->tipo_precio ?>,

			},
			success: function (Response, newValue) {
				if (Response.requestresult == 'ok') {
					$('#Cotizacionesproductos_cotizacion_producto_unitario').val(Response.precio);
					$('#Cotizacionesproductos_cotizacion_producto_cantidad').val(1);
					$.notify(Response.message, 'success');
					ActualizarPrecio();
				} else {
					$('#Cotizacionesproductos_cotizacion_producto_unitario2').val('');
					$("#Cotizacionesproductos_id_producto").val('');
					$.notify(Response.message, 'error');
				}
			},
			error: function (e) {
				$.notify('Ocurrio un error inesperado', 'error');
			}
		});
	}

	/*
	 *	METODO PARA MULTIPLICAR EL COSTO UNITARIO POR LA CANTIDAD Y MOSTRAR EL TOTAL
	 *	CREADO POR DANIEL VILLARREAL EL 31 DE ENERO DEL 2016
	 */
	function ActualizarPrecio() {

		var cantidad = $('#Cotizacionesproductos_cotizacion_producto_cantidad').val();
		var precio = $('#Cotizacionesproductos_cotizacion_producto_unitario').val();
		var tipo_desceunto = $('#Cotizacionesproductos_tipo_descuetno').val();
		var descuetno = $('#Cotizacionesproductos_descuento').val();

		var preciototalsinfactor = cantidad * precio;
		// ifs para validar que el porcentaje no puede ser mayor al total
		$('#btnAgregarProducto').prop('disabled', false);



		if (tipo_desceunto === 'monto' && descuetno > preciototalsinfactor) {

			$.notify('¡El monto de descuento no puede ser mayor al precio unitario!', 'error');
			preciototalsinfactor = 0
			$('#Cotizacionesproductos_cotizacion_producto_total').val(preciototalsinfactor);
			$('#btnAgregarProducto').prop('disabled', true);
			return;

		} else if (tipo_desceunto === 'porcentaje' && descuetno > 100) {
			$.notify('¡El porcentaje de descuento no puede ser mayor al 100%!', 'error');
			preciototalsinfactor = 0
			$('#Cotizacionesproductos_cotizacion_producto_total').val(preciototalsinfactor);
			$('#btnAgregarProducto').prop('disabled', true);
			return;
		}

		// if para aplicar descuento en el unitario
		if (tipo_desceunto != '' && descuetno != '') {
			if (tipo_desceunto === 'porcentaje') {
				var valorporciento = precio * (descuetno / 100);
				var precioun = precio - valorporciento
				var preciototalsinfactor = cantidad * precioun;
			} else if (tipo_desceunto === 'monto') {
				var precioun = precio - descuetno;
				var preciototalsinfactor = cantidad * precioun;
			}
		} else {
			var preciototalsinfactor = cantidad * precio;
		}

		$('#Cotizacionesproductos_cotizacion_producto_total').val(preciototalsinfactor);
	}
</script>
<?php include 'modal_actualizar_prod.php'; ?>
<div class="row">
	<div class="col-md-12">
		<!-- <H1>
			<? // php // echo $this->pageTitle 
			?>
		</H1> -->
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<fieldset>
			<legend>Datos Cliente</legend>
			<div class="col-md-3">
				Nombre:<br><strong>
					<?= $DatosCliente->cliente_nombre; ?>
				</strong><br>
				Cliente Tipo:<br><strong>
					<?= $DatosCliente['rl_cliente_tipo']['nombre']; ?>
				</strong><br>
				<!-- Clasificacion:<br><strong>
					<? //= $DatosCliente['rl_cliente_tipo_clasificacion']['nombre']; 
					?>
				</strong><br>
				Como Trabajarlo:<br><strong>
					<? //= $DatosCliente['rl_cliente_como_trabajarlo']['nombre']; 
					?>
				</strong><br> -->
			</div>
			<div class="col-md-3">

				Teléfono:<br><strong>
					<?= $DatosCliente['cliente_telefono']; ?>
				</strong><br>
				Email:<br><strong>
					<?= $DatosCliente['cliente_email']; ?>
				</strong><br>

				Calle:<br><strong>
					<?= $DatosCliente['cliente_calle']; ?>
				</strong><br>

				Empresa:<br><strong>

					<?= $DatosCliente['rl_empresas']['empresa']; ?>

				</strong><br>

			</div>
			<div class="col-md-3">
				Colonia:<br><strong>
					<?= $DatosCliente['cliente_colonia']; ?>
				</strong><br>
				Número Interior:<br><strong>
					<?= $DatosCliente['cliente_numeroexterior']; ?>
					<?= $DatosCliente['cliente_numerointerior']; ?>
				</strong><br>
				Código Postal:<br><strong>
					<?= $DatosCliente['cliente_codigopostal']; ?>
				</strong><br>

				Lista Precio:<br><strong>
					<?= (!empty($DatosCotizacion['rl_lista_precio']) ? $DatosCotizacion['rl_lista_precio']['listaprecio_nombre'] : '') ?>
				</strong><br>
			</div>

			<div class="col-md-3">
				Municipio:<br><strong>
					<?= $DatosCliente['cliente_municipio']; ?>
				</strong><br>
				Entidad:<br><strong>
					<?= $DatosCliente['cliente_entidad']; ?>
				</strong><br>
				País:<br><strong>
					<?= $DatosCliente['cliente_pais']; ?>
				</strong><br>

				Tipo de Precio:<br><strong>
					<?php
					$Tipoprecio = $this->ObtenerTipoPrecio($DatosCotizacion['tipo_precio']);
					// echo "<pre>";
					// print_r($Tipoprecio);
					// echo "</pre>";
					// exit();
					if (!empty($Tipoprecio)) {

						echo $Tipoprecio['label'];
					}
					?>
				</strong><br>

			</div>
		</fieldset>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<fieldset>
			<legend>Agregar producto</legend>
			<?php
			/* @var $this CotizacionesproductosController */
			/* @var $model Cotizacionesproductos */
			/* @var $form CActiveForm */
			?>

			<div class="form">

				<?php $form = $this->beginWidget(
					'CActiveForm',
					array(
						'id' => 'cotizacionesproductos-form',
						'action' => Yii::app()->createUrl('Cotizacionesproductos/Agregaproducto'),
						'enableClientValidation' => true,
						'clientOptions' => array('validateOnSubmit' => true, 'validateOnType' => true),
					)
				); ?>



				<?php echo $form->errorSummary($model); ?>

				<div class="col-md-3">
					Producto:
					<?php
					$this->widget(
						'zii.widgets.jui.CJuiAutoComplete',
						array(
							'name' => 'id_producto_auto',
							'source' => $this->createUrl('productos/Productosajax'),
							// Opciones javascript adicionales para el plugin
							'options' => array(
								// 'minLength' => '1',
								'select' => 'js:function(event, ui) {
                  	            $("#Cotizacionesproductos_id_producto").val(ui.item.id);
                  	            $("#id_producto_auto").val(ui.item.value);
                  	            $("#Cotizacionesproductos_cotizacion_producto_descripcion").val(ui.item.descripcion);
                  	            GetPrice(ui.item.id,"' . $DatosCotizacion['id_lista_precio'] . '");
                 	            }',
								'focus' => 'js:function(event, ui) {
                                 return false;
                                }'
							),
							'htmlOptions' => array(
								'class' => 'form-control',
								'placeholder' => 'Busque el producto'
							)
						)
					);
					?>
					<?php echo $form->hiddenField($model, 'id_producto'); ?>
					<?php echo $form->hiddenField($model, 'id_cotizacion', array('value' => $DatosCotizacion->id_cotizacion)); ?>
					<?php echo $form->error($model, 'id_producto'); ?>
				</div>

				<div class="col-md-3">
					Cantidad
					<?php echo $form->textField($model, 'cotizacion_producto_cantidad', array('class' => 'form-control', 'placeholder' => 'Cantidad', 'onchange' => 'ActualizarPrecio()')); ?>
					<?php echo $form->error($model, 'cotizacion_producto_cantidad'); ?>
				</div>

				<?php echo $form->hiddenField($model, 'id_cliente', array('value' => $DatosCliente->id_cliente)); ?>
				<?php echo $form->error($model, 'id_cliente'); ?>
				<?php echo $form->hiddenField($model, 'id_usuario', array('value' => Yii::app()->user->id)); ?>
				<?php echo $form->error($model, 'id_usuario'); ?>


				<div class="col-md-3">
					Precio Unitario
					<?php echo $form->numberField($model, 'cotizacion_producto_unitario', array('class' => 'form-control', 'placeholder' => 'Precio Unitario', 'step' => 'any', 'onchange' => 'ActualizarPrecio()', 'readonly' => $readonly)); ?>
					<?php echo $form->error($model, 'cotizacion_producto_unitario'); ?>
				</div>

				<div class="col-md-3">
					Estructura
					<?php echo $form->dropDownList($model, 'color', $colores, array('empty' => 'Color de Estructura', 'class' => 'form-control')); ?>
					<?php echo $form->error($model, 'color'); ?>
				</div>
				<div class="col-md-3">
					Tapiceria
					<?php echo $form->textField($model, 'color_tapiceria', array('empty' => 'Color Tapicería', 'class' => 'form-control', 'placeholder' => ' Color Tapiceria')); ?>
					<?php echo $form->error($model, 'color_tapiceria'); ?>
				</div>
				<div class="col-md-3">
					T. Descuento
					<?php echo $form->dropDownList($model, 'tipo_descuetno', array('porcentaje' => 'Porcentaje', 'monto' => 'Monto x Producto'), array('class' => 'form-control', 'prompt' => 'Tipo Descuento', 'disabled' => $descuentos)); ?>
					<?php echo $form->error($model, 'tipo_descuetno'); ?>
				</div>

				<div class="col-md-3">
					Descuento
					<?php echo $form->numberField($model, 'descuento', array('class' => 'form-control', 'placeholder' => 'Descuento', 'step' => 'any', 'onchange' => 'ActualizarPrecio()', 'readonly' => $descuentos)); ?>
					<?php echo $form->error($model, 'descuento'); ?>
				</div>

				<div class="col-md-3">
					Total
					<?php echo $form->textField($model, 'cotizacion_producto_total', array('class' => 'form-control', 'placeholder' => 'Precio Total', 'readonly' => true)); ?>
					<?php echo $form->error($model, 'cotizacion_producto_total'); ?>
				</div>
				<div class="col-md-12">
					<?php echo $form->textField($model, 'cotizacion_producto_descripcion', array('class' => 'form-control mt-xs', 'placeholder' => 'Descripción')); ?>
					<?php echo $form->error($model, 'cotizacion_producto_descripcion'); ?>
				</div>
				<div class="col-md-12">
					<?php echo $form->textField($model, 'especificaciones_extras', array('class' => 'form-control mt-xs', 'placeholder' => 'Especificaciones Extras')); ?>
					<?php echo $form->error($model, 'especificaciones_extras'); ?>
				</div>
				<div class="col-md-12" style="display: flex;justify-content: end;margin:2vmin 0">
					<?php echo CHtml::submitButton('Agregar producto', array('id' => 'btnAgregarProducto', 'class' => 'btn btn-primary')); ?>
				</div>
				<?php $this->endWidget(); ?>

			</div><!-- form -->
		</fieldset>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<fieldset>
			<legend>Productos en la cotización</legend>
			<div class="table-responsive">
				<table id="productoscotizacion" class="table table-striped table-bordered">
					<thead>
						<tr>
							<!--<th>Concepto</th>-->
							<th>ID</th>
							<th></th>
							<th style="width: 400px;">Producto</th>

							<th>Clave</th>
							<th>Unitario</th>
							<!-- <th>UM</th> -->
							<th>Cantidad</th>
							<!-- <th>Color de Estructura</th>
							<th>Color de Tapiceria</th> -->
							<!--<th>Costo Factor</th>-->
							<th>Total</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php
						// Definimos las variables de el subtotal, iva y total
						$Subtotal = 0;
						$Iva = 0;
						$Total = 0;
						foreach ($productoscotizacion as $rows) { ?>
							<tr>

								<td>
									<?= $rows->id_producto ?>
								</td>
								<td>
									<?php if ($rows['rl_producto']['producto_imagen'] != '') { ?>
										<img src="<?= Yii::app()->createUrl('archivos/' . $rows['rl_producto']['producto_imagen']) ?>"
											style="max-height:50px">
									<?php } ?>
								</td>
								<td>
									<?= $rows['rl_producto']['producto_nombre'] ?>
									<?php if (!empty($rows->cotizacion_producto_descripcion)) {
										echo '<br>' . $rows->cotizacion_producto_descripcion;
									} ?>



									<?php if (!empty($rows['especificaciones_extras'])) {
										echo '<br><br><b>Especificaciones extras</b>';
										echo '<br>' . '<b>' . $rows['especificaciones_extras'] . '</b>';
									} ?>
									<?php if (!empty($rows['color'])) {
										echo '<br><br><b>Color estructura:</b>' . ' ' . $rows['color'];
									} ?>
									<?php if (!empty($rows['color_tapiceria'])) {
										echo '<br><br><b>Color tapiceria: </b>' . ' ' . $rows['color_tapiceria'];
									} ?>
								</td>

								<td>
									<?= $rows['rl_producto']['producto_clave'] ?>
								</td>
								<td>
									<?php if (!$descuentos) {
										if (!empty($rows['tipo_descuetno'])) {
											if ($rows['tipo_descuetno'] == 'porcentaje') {
												$desc = '(Descuento del ' . $rows['descuento'] . '%)';
											} else {
												$desc = '(Descuento de $' . $rows['descuento'] . ')';
											} ?>
											$
											<?= number_format($rows->cotizacion_producto_unitario, 2) . ' ' . $desc ?>;
										<?php } else { ?>
											$
											<?= number_format($rows->cotizacion_producto_unitario, 2); ?>
										<?php }
										?>
									<?php } else { ?>
										$
										<?= number_format($rows->cotizacion_producto_unitario, 2) ?>
									<?php } ?>

								</td>
								<!-- <td>
									<? //= $rows['rl_producto']['rl_unidadesdemedida']['unidades_medida_nombre'] 
										?>
								</td> -->
								<td>
									<?php
									echo Chtml::link(
										'<i class="fa fa-plus"></i>',
										'cotizacionesproductos/Actualizarproductos',
										array(
											'submit' => array('cotizacionesproductos/Actualizarproductos'),
											'params' => array('id' => $rows->id_cotizacion_producto, 'tipoactualizacion' => 1),
											'class' => 'btn btn-default btn-sm mr-sm'
										)
									);
									echo $rows->cotizacion_producto_cantidad;
									if ($rows->cotizacion_producto_cantidad > 1) {
										echo Chtml::link(
											'<i class="fa fa-minus fa-lg"></i>',
											'cotizacionesproductos/Actualizarproductos',
											array(
												'submit' => array('cotizacionesproductos/Actualizarproductos'),
												'params' => array('id' => $rows->id_cotizacion_producto, 'tipoactualizacion' => 0),
												'class' => 'btn btn-default btn-sm ml-sm'
											)
										);
									} ?>
								</td>

								<td>$
									<?= number_format($rows->cotizacion_producto_total, 2) ?>
								</td>
								<td>
									<?php
									echo CHtml::link(
										'<i class="fa fa-trash fa-lg"></i> Eliminar',
										array('cotizacionesproductos/Eliminarproducto', 'id' => $rows->id_cotizacion_producto),
										array(
											'submit' => array('cotizacionesproductos/Eliminarproducto', 'id' => $rows->id_cotizacion_producto),
											'class' => 'delete',
											'confirm' => 'Seguro que lo deseas eliminar?'
										)
									);
									?>
									<br>
									<br>
									<!-- <div></div> -->
									<p class="actproddesc" style=" margin:0;color: #00264d; cursor: pointer;" ,
										data-id=<?= $rows->id_cotizacion_producto ?>><i
											class="glyphicon glyphicon-edit fa-lg"></i> Editar</p>
									<!-- <?php
									// echo CHtml::link(
									// 	'<i class="glyphicon glyphicon-edit fa-lg actproddesc"></i> Editar',
									// 	'#',
									// 	[
									// 		'class' => "actproddesc",
									// 		"data-id" => $rows->id_cotizacion_producto
									// 	]
									// );
									?> -->

								</td>


							</tr>
							<?php
							// Obtenemos el subtotal, iva y total
							$Subtotal = $Subtotal + $rows->cotizacion_producto_total;
						}
						if ($DatosCotizacion->sumar_iva == 1) {
							$Iva = ($Subtotal * .16);
						} else {
							$Iva = 0;
						}
						$Total = ($Subtotal + $Iva);
						?>
					</tbody>
				</table>
			</div>
		</fieldset>
	</div>
</div>

<?php if ($Subtotal > 0) { ?>
	<div class="col-md-4 pull-right">

		<fieldset>
			<legend>Totales</legend>
			<table id="totales" class="totales" cellspacing="0" width="100%">
				<tr>
					<td>Productos con IVA</td>
					<td>
						<select name="agregariva" id="agregariva" class="form-control">
							<option value="0" <?php if ($DatosCotizacion->sumar_iva == 0)
								echo 'selected'; ?>>Sin Iva
							</option>
							<option value="1" <?php if ($DatosCotizacion->sumar_iva == 1)
								echo 'selected'; ?>>Agregar Iva
							</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Moneda</td>
					<td>
						<span style="font-weight:bold; font-size:1.2em;">
							<?= $DatosCotizacion->rl_lista_precio['rl_moneda']['moneda_nombre'] ?>
						</span>
					</td>
				</tr>

				<tr>
					<td>SubTotal</td>
					<td>
						<span style="font-weight:bold; font-size:1.2em;">
							$
							<?= number_format($Subtotal / $DatosCotizacion->tipo_cambio, 2) ?>
						</span>
					</td>
				</tr>
				<tr>
					<td>Iva</td>
					<td>
						<span style="font-weight:bold; font-size:1.2em;">
							$
							<?= number_format($Iva / $DatosCotizacion->tipo_cambio, 2) ?>
						</span>
					</td>
				</tr>
				<tr>
					<td>Total</td>
					<td><span class="heading-primary" style="font-weight:bold; font-size:1.8em;">$
							<?= number_format($Total / $DatosCotizacion->tipo_cambio, 2) ?>
						</span></td>
				</tr>
			</table>

		</fieldset>
	</div>
<?php } ?>
<div class="col-md-12 mb-lg">
	<?php
	if ($Subtotal > 0) { ?>
		<form method="post" action="<?php echo $this->createUrl("Cotizaciones/Actualizartotal"); ?>">
			<button type="submit" class="btn btn-success btn-lg mt-sm pull-right">Agregar Condiciones comerciales</button>
			<input type="hidden" value="<?= $DatosCotizacion->id_cotizacion; ?>" name="id_cotizacion" id="id_cotizacion">
			<input type="hidden" value="" name="iva" id="iva">
		</form>

		<?php
	} ?>
</div>
</div>