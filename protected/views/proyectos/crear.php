<?php
/* @var $this CotizacionesController */
$this->pageTitle = 'Crear pedido';

$this->breadcrumbs = array(
	'Pedido' => array('/proyectos/lista'),
	'Crear',
);
?>
<script>
	$(document).ready(function () {
		$('#productos').DataTable({
			"paging": false,
			"ordering": false,
			"info": false,
			"searching": false
		});
	});

	function Crearproyecto() {
		var elemento = document.querySelector('#valorCotizacion');
		// Obtiene el texto dentro del elemento
		var valor = elemento.textContent;
		// Elimina el signo de dólar y los espacios en blanco y convierte el valor en un número
		// var valorNumerico = parseFloat(valor.replace('$', '').trim());
		var valorNumerico = parseFloat(valor.replace(/[$,]/g, ''));
		console.log(valorNumerico); // Esto mostrará el valor en la consola
		//$("input").prop('disabled', false);
		// Verificamos que seleccione el almacen
		// var tipoproyecto = $('#tipoproyecto').val();
		/*var almacen = $('#almacenproyecto').val();*/
		var proyecto_supervisor = $('#proyecto_supervisor').val();
		var proyecto_comentarios = $('#proyecto_comentarios').val();
		var localidad = $('#localidad').val();
		var fomapago = $('#formapago').val();
		var monto = $('#monto').val();
		var fecha = $('#fecha').val();
		var flete = $('#fletecoti').val();

		var proyecto_nombre = $('#nombreproyecto').val();
		console.log('valor numerico: ', valorNumerico);
		console.log('monto : ', monto);

		// obligatorio agregar anticipo
		if (monto == '') {
			return $.notify('Debe agregar anticipo', "error");
		}

		if (valorNumerico < monto) {
			// ajustamos el pago
			monto = valorNumerico;
			$('#monto').val(monto)
			//return $.notify('El monto no puede ser mayor al total', "error");
		}

		// if (tipoproyecto == '') {
		// 	$.notify("Seleccione tipo proyecto", "error");
		// 	return '';
		// }
		// console.log('si paso');
		// return;

		$("#generarproyecto").prop('disabled', true);
		var jqxhr = $.ajax({
			url: "<?php echo $this->createUrl("Proyectos/GenerarProyecto"); ?>",
			type: "POST",
			dataType: "json",
			timeout: (120 * 1000),
			data: {
				id_cotizacion: <?= $DatosCotizacion->id_cotizacion; ?>,
				// id_tipo_proyecto: tipoproyecto,
				/*id_almacen: almacen,*/
				proyecto_comentarios: proyecto_comentarios,
				localidad: localidad,
				fomapago: fomapago,
				monto: monto,
				fecha: fecha,
				flete: flete,
				/*proyecto_supervisor: proyecto_supervisor,*/
				proyecto_nombre: proyecto_nombre
			},
			success: function (Response, newValue) {
				if (Response.requestresult == 'ok') {
					$.notify(Response.message, "success");
					window.location.replace("<?= Yii::app()->createUrl('proyectos/ver'); ?>/" + Response.id_proyecto);
				} else {
					$.notify(Response.message, "error");
					$("#generarproyecto").prop('disabled', false);
				}
			},
			error: function (e) {
				$("#generarproyecto").prop('disabled', false);
				$.notify(Response.message, "error");
			}
		});
	}



</script>
<div class="row">
	<div class="col-md-12">
		<fieldset>
			<legend>Datos Cliente</legend>
			<div class="col-md-4">
				Nombre:<br>
				<input type="text" name="razonsocialcliente" id="razonsocialcliente" class="form-control"
					value="<?= $DatosCliente->cliente_nombre ?>" readonly>
			</div>

			<div class="col-md-3">
				Teléfono:<br>
				<input type="text" name="telefonocliente" id="telefonocliente"
					value="<?= $DatosCliente->cliente_telefono ?>" class="form-control" readonly>
			</div>
			<div class="col-md-3">
				Email:<br>
				<input type="text" name="emailcliente" id="emailcliente" value="<?= $DatosCliente->cliente_email ?>"
					class="form-control" readonly>
			</div>

		</fieldset>
	</div>
	<div class="col-md-12">
		<fieldset>
			<legend>Productos</legend>
			<div class="table-responsive">
				<table id="productos" class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>ID</th>
							<th>Producto</th>
							<th>Unidad Medida</th>
							<th>Unitario</th>
							<th>Cantidad</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>
						<?php
						// Definimos las variables de el subtotal, iva y total
						$Subtotal = 0;
						$Iva = 0;
						$Total = 0;
						foreach ($ProductosCot as $rows) { ?>
							<tr>
								<td style="font-size:1.2em; font-weight:bold;">
									<?= $rows->id_producto ?>
								</td>
								<td style="font-size:1.2em; font-weight:bold;">
									<?= $rows->rl_producto->producto_nombre ?>
								</td>
								<td style="font-size:1.2em; font-weight:bold;">
									<?= $rows['rl_producto']['rl_unidadesdemedida']['unidades_medida_nombre'] ?>
								</td>
								<td style="font-size:1.2em; font-weight:bold;">
									<?php if (!$descuentos) {
										if (!empty($rows['tipo_descuetno'])) {
											if ($rows['tipo_descuetno'] == 'porcentaje') {
												$desc = '(Desceutno del ' . $rows['descuento'] . '%)';
											} else {
												$desc = '(Desceutno de $' . $rows['descuento'] . ')';
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
								<td style="font-size:1.2em; font-weight:bold;">
									<?= $rows->cotizacion_producto_cantidad ?>
								</td>
								<td style="font-size:1.2em; font-weight:bold;">$
									<?= $rows->cotizacion_producto_total ?>
								</td>
							</tr>
							<?php

							// Obtenemos el subtotal, iva y total
							$Subtotal = $Subtotal + $rows->cotizacion_producto_total;
						}
						$Iva = ($Subtotal * .16);
						$Total = ($Subtotal + $Iva);
						?>
					</tbody>
				</table>

		</fieldset>
	</div>
	<div class="col-md-12">
		<fieldset>
			<legend>Confirmación de Pedido</legend>
			<div class="table-responsive">
				<table class="table table-striped table-bordered">
					<!-- <tr>
						<td>
							<h3 class="mb-sm mt-sm">Nombre Pedido</h3>
						</td>
						<td><input type="text" name="nombreproyecto" id="nombreproyecto"
								value="<? //= $DatosCotizacion->cotizacion_nombre ?>" class="form-control input-lg"></td>
					</tr> -->
					<tr>
						<td>
							<h3 class="mb-sm mt-sm">Moneda</h3>
						</td>
						<td>
							<h3 class="mb-sm mt-sm">
								<?= $DatosCotizacion->rl_lista_precio['rl_moneda']['moneda_nombre'] ?>
							</h3>
						</td>
					</tr>

					<tr>
						<td>
							<h3 class="mb-sm mt-sm">Sub-Total</h3>
						</td>
						<td>
							<h3 class="mb-sm mt-sm">$
								<!-- <//?= number_format($DatosCotizacion->cotizacion_total / 1.16, 2) ?> -->
								<?= ($DatosCotizacion->sumar_iva == 1) ? number_format($DatosCotizacion->cotizacion_total / 1.16, 2) : number_format($DatosCotizacion->cotizacion_total, 2); ?>
							</h3>
						</td>
					</tr>
					<tr>
						<td>
							<h3 class="mb-sm mt-sm">IVA</h3>
						</td>
						<td>
							<h3 class="mb-sm mt-sm">$
								<!-- <//?= number_format(($DatosCotizacion->cotizacion_total / 1.16) * .16, 2) ?> -->
								<?= ($DatosCotizacion->sumar_iva == 1) ? number_format(($DatosCotizacion->cotizacion_total / 1.16) * .16, 2) : 0; ?>
							</h3>
						</td>
					</tr>
					<tr>
						<td>
							<h3 class="mb-sm mt-sm">Total</h3>
						</td>
						<td>
							<h3 id="valorCotizacion" class="mb-sm mt-sm">$
								<?= number_format($DatosCotizacion->cotizacion_total, 2) ?>
							</h3>
						</td>
					</tr>
					<!-- 
					<tr>
						<td>
							<h3 class="mb-sm mt-sm">Tipo Pedido:</h3>
						</td>
						<td>
							<select class="form-control input-lg" id="tipoproyecto">
								<option value="">-- Seleccione --</option>
								<? //php foreach ($tipoproyecto as $rows) { ?>
									<option value="<? //= $rows->id_tipo_proyecto ?>"
										<? //= ($rows->id_tipo_proyecto == $DatosCotizacion->id_tipo_proyecto) ? 'selected' : ''; ?>>
										<? //= $rows->nombre ?>
									</option>
								<? //php } ?>
							</select>
						</td>
					</tr> -->
					<tr>
						<td>
							<h3 class="mb-sm mt-sm">Localidad</h3>
						</td>
						<td><textarea name="localidad" id="localidad" class="form-control input-lg"
								placeholder="Estado, Municipio"></textarea>
						</td>
					</tr>
					<tr>
						<td>
							<h3 class="mb-sm mt-sm">Comentarios:</h3>
						</td>
						<td>
							<textarea id="proyecto_comentarios" class="form-control"></textarea>
						</td>
					</tr>
					<tr>

						<td>
							<h3 class="mb-sm mt-sm">Fecha de entrega:</h3>

						</td>
						<td>
							<?php $this->widget(
								'zii.widgets.jui.CJuiDatePicker',
								array(
									'name' => 'fecha_de_entrega',
									'language' => 'es',
									'htmlOptions' => array(
										'class' => ' form-control input-lg ',
										'id' => 'fecha'
									),
									'options' => array(
										'dateFormat' => 'yy-mm-dd',
									),
								)
							); ?>
						</td>

					</tr>
					<tr>
						<td>
							<h3 class="mb-sm mt-sm">Flete Cotizado</h3>
						</td>
						<td><input type="text" name="fletecoti" id="fletecoti" class="form-control input-lg"></td>
					</tr>
					<tr>
						<td colspan="2" class="text-center">
							<h3 class="mb-sm mt-sm">Agregar ingreso</h3>
						</td>

					</tr>
					<tr>
						<td>
							<h3 class="mb-sm mt-sm">Formas de pago:</h3>
						</td>
						<td>
							<select class="form-control input-lg" name="fomapago" id="formapago">
								<option value="">Seleccione una opción</option>
								<?php
								foreach ($ListaFormasPago as $fp) { ?>
									<option value="<?= $fp['id_formapago'] ?>">
										<?= $fp['formapago_nombre'] ?>
									</option>
								<?php } ?>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<h3 class="mb-sm mt-sm">Monto</h3>
						</td>
						<td><input type="number" name="monto" id="monto" class="form-control input-lg"></td>
					</tr>
				</table>
			</div>
			<br>
			<?php if ($DatosCotizacion->pedido != 1) { ?>
				<div class="text-center">
					<button type="button" class="btn btn-success btn-lg " onclick="Crearproyecto()" id="generarproyecto"><i
							class="fa fa-check"></i> Realizar Pedido</button>
				</div>
			<?php } else if ($DatosCotizacion->pedido == 1) { ?>

					<div class="text-center">
						<p>Esta cotizacion ya cuenta con pedido</p>
					</div>
			<?php } ?>
		</fieldset>
	</div>
</div>