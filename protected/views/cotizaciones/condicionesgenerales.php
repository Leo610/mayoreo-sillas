<?php
/* @var $this CotizacionesController */
$this->pageTitle = 'Condiciones Generales';

$this->breadcrumbs = array(
	'Cotizaciones' => array('/cotizaciones'),
	'Cot Productos' => array('cotizaciones/Actualizarcotizacion/' . $DatosCotizacion->id_cotizacion),
	'Cot Condiciones'
);
?>
<script type="text/javascript">
	function Guardarcambios() {
		var condiciones_de_pago = CKEDITOR.instances.Cotizaciones_condiciones_de_pago ? CKEDITOR.instances.Cotizaciones_condiciones_de_pago.getData() : '';

		var tiempo_fabricacion = CKEDITOR.instances.Cotizaciones_tiempo_fabricacion ? CKEDITOR.instances.Cotizaciones_tiempo_fabricacion.getData() : '';
		var exclusiones = CKEDITOR.instances.Cotizaciones_exclusiones ? CKEDITOR.instances.Cotizaciones_exclusiones.getData() : '';
		var vigencia_propuesta = CKEDITOR.instances.Cotizaciones_vigencia_propuesta ? CKEDITOR.instances.Cotizaciones_vigencia_propuesta.getData() : '';
		var cotizacion_comentario = CKEDITOR.instances.Cotizaciones_cotizacion_comentario ? CKEDITOR.instances.Cotizaciones_cotizacion_comentario.getData() : '';
		// var nombre_encargado = CKEDITOR.instances.Cotizaciones_nombre_encargado ? CKEDITOR.instances.Cotizaciones_nombre_encargado.getData() : '';
		var cotizacion_condiciones_generales = CKEDITOR.instances.Cotizaciones_cotizacion_condiciones_generales ? CKEDITOR.instances.Cotizaciones_cotizacion_condiciones_generales.getData() : '';
		// console.log('aca');

		var jqxhr = $.ajax({
			url: "<?php echo $this->createUrl("cotizaciones/guardar"); ?>",
			type: "POST",
			dataType: "json",
			timeout: (120 * 1000),
			data: {
				Cotizaciones: {
					id_cotizacion: <?= $DatosCotizacion->id_cotizacion ?>,
					condiciones_de_pago: condiciones_de_pago,
					tiempo_fabricacion: tiempo_fabricacion,
					exclusiones: exclusiones,
					vigencia_propuesta: vigencia_propuesta,
					cotizacion_comentario: cotizacion_comentario,
					// nombre_encargado: nombre_encargado,
					cotizacion_condiciones_generales: cotizacion_condiciones_generales,
				},
				ajax: 1,
			},
			success: function (Response, newValue) {
				if (Response.requestresult == 'ok') {
					$('#Cotizacionesproductos_cotizacion_producto_unitario').val(Response.precio);
					$('#Cotizacionesproductos_cotizacion_producto_cantidad').val(1);
					$.notify(Response.message, 'success');
					ActualizarPrecio();
				} else {
					$('#Cotizacionesproductos_cotizacion_producto_unitario').val('');
					$("#Cotizacionesproductos_id_producto").val('');
					$.notify(Response.message, 'error');
				}
			},
			error: function (e) {
				$.notify('Ocurrio un error inesperado', 'error');
			}
		});
	}
</script>
<div class="row">
	<div class="col-md-12">
		<H1>Actualizar cotización
			<?php echo $DatosCotizacion->id_cotizacion ?>
		</H1>
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
					<?= $DatosCotizacion['rl_lista_precio']['listaprecio_nombre']; ?>
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
					echo $Tipoprecio['label'];
					?>
				</strong><br>

			</div>
		</fieldset>
	</div>
</div>
<div class="col-md-12">
	<fieldset>
		<legend>Especificaciones</legend>
		<div class="col-md-12 mb-md">
			<form method="get">
				<label>Seleccione plantilla de cotización para cargar las condiciones comerciales</label>
				<select name="plantillacot" id="plantillacot" class="form-control" onchange="this.form.submit()">
					<!-- <option>-- Seleccione --</option> -->
					<?php foreach ($CotPlantillas as $key => $value) { ?>
						<option value="<?= $key ?>" <?= ($key == $idcotplant) ? 'selected' : ''; ?>>
							<?= $value ?>
						</option>
					<?php } ?>
				</select>
				<input type="hidden" name="id" value="<?= $DatosCotizacion->id_cotizacion ?>">
			</form>
		</div>
		<div class="form">
			<?php $form = $this->beginWidget(
				'CActiveForm',
				array(
					'id' => 'cotizaciones-form',
					'action' => Yii::app()->createUrl('cotizaciones/Guardar/'),
					'enableClientValidation' => true,
					'clientOptions' => array('validateOnSubmit' => true, 'validateOnType' => true),
					'htmlOptions' => array('enctype' => 'multipart/form-data'),
				)
			); ?>

			<div class="row">
				<div class="col-md-12">
					<p class="note">Ingrese los campos de las condiciones comerciales</p>
					<?php echo $form->errorSummary($DatosCotizacion); ?>
				</div>
			</div>
			<div class="row">
				<!-- <div class="col-md-12">
					<? //php echo $form->labelEx($DatosCotizacion, 'cotizacion_nombre'); 
					?>
					<? //php echo $form->textField($DatosCotizacion, 'cotizacion_nombre', array('class' => 'form-control', 'value' => $DatosCotizacion->cotizacion_nombre)); 
					?>
					<? //php echo $form->error($DatosCotizacion, 'cotizacion_nombre'); 
					?>
				</div> -->

			</div>
			<div class="row">
				<div class="col-md-12">
					<!-- <?php echo $form->labelEx($DatosCotizacion, 'condiciones_de_pago'); ?> -->
					<label for="">Condiciones Generales</label>
					<?php echo $form->textArea($DatosCotizacion, 'condiciones_de_pago', array('class' => 'form-control ckeditor', 'value' => ($DatosCotizacion->condiciones_de_pago != '') ? $DatosCotizacion->condiciones_de_pago : $DatosCotPlantilla['condiciones_pago'])); ?>
					<?php echo $form->error($DatosCotizacion, 'condiciones_de_pago'); ?>
				</div>
				<!-- <div class="col-md-12">
					<?php echo $form->labelEx($DatosCotizacion, 'tiempo_fabricacion'); ?>
					<?php echo $form->textArea($DatosCotizacion, 'tiempo_fabricacion', array('class' => 'form-control ckeditor', 'value' => ($DatosCotizacion->tiempo_fabricacion != '') ? $DatosCotizacion->tiempo_fabricacion : $DatosCotPlantilla['tiempo_fabricacion'])); ?>
					<?php echo $form->error($DatosCotizacion, 'tiempo_fabricacion'); ?>
				</div> -->
				<!-- <div class="col-md-12">
					<? //php echo $form->labelEx($DatosCotizacion, 'exclusiones'); 
					?>
					<? //php echo $form->textArea($DatosCotizacion, 'exclusiones', array('class' => 'form-control ckeditor', 'value' => ($DatosCotizacion->exclusiones != '') ? $DatosCotizacion->exclusiones : $DatosCotPlantilla['exclusiones'])); 
					?>
					<? //php echo $form->error($DatosCotizacion, 'exclusiones'); 
					?>
				</div> -->
				<!-- <div class="col-md-12">
					<? //php echo $form->labelEx($DatosCotizacion, 'vigencia_propuesta'); 
					?>
					<? //php echo $form->textArea($DatosCotizacion, 'vigencia_propuesta', array('class' => 'form-control ckeditor', 'value' => ($DatosCotizacion->vigencia_propuesta != '') ? $DatosCotizacion->vigencia_propuesta : $DatosCotPlantilla['vigencia_propuesta'])); 
					?>
					<? //php echo $form->error($DatosCotizacion, 'vigencia_propuesta'); 
					?>
				</div> -->
				<!-- <div class="col-md-12">
					<?php echo $form->labelEx($DatosCotizacion, 'cotizacion_comentario'); ?>
					<?php echo $form->textArea($DatosCotizacion, 'cotizacion_comentario', array('class' => 'form-control ckeditor', 'value' => ($DatosCotizacion->cotizacion_comentario != '') ? $DatosCotizacion->cotizacion_comentario : $DatosCotPlantilla['comentario'])); ?>
					<?php echo $form->error($DatosCotizacion, 'cotizacion_comentario'); ?>
				</div>
			</div> -->
				<!-- <div class="row">
				<div class="col-md-12">
					<?php echo $form->labelEx($DatosCotizacion, 'nombre_encargado'); ?>
					<?php echo $form->textArea($DatosCotizacion, 'nombre_encargado', array('class' => 'form-control ckeditor', 'value' => ($DatosCotizacion->nombre_encargado != '') ? $DatosCotizacion->nombre_encargado : $DatosCotPlantilla['nombre_encargado'])); ?>
					<?php echo $form->error($DatosCotizacion, 'nombre_encargado'); ?>
				</div>
			</div> -->
				<?php if ($DatosCotPlantilla['condiciones_generales'] != '') {
					$condicionesgenerales = $DatosCotPlantilla['condiciones_generales'];
				} else {
					$condicionesgenerales = 'Condiciones Generales:<br />
		            Pago en una sola exibición <br />
		            Cotización vigente por 15 dias <br />';
				} ?>
				<div class="row">
					<!-- <div class="col-md-12">
					<?php echo $form->labelEx($DatosCotizacion, 'cotizacion_condiciones_generales'); ?>
					<?php echo $form->textArea($DatosCotizacion, 'cotizacion_condiciones_generales', array('class' => 'form-control ckeditor', 'value' => $condicionesgenerales)); ?>
					<?php echo $form->error($DatosCotizacion, 'cotizacion_condiciones_generales'); ?>
				</div>
			</div> -->

					<input type="hidden" value="<?= $idcotplant ?>" name="plantillacot">
					<?php echo $form->hiddenField($DatosCotizacion, 'id_cotizacion', array('value' => $DatosCotizacion->id_cotizacion)); ?>
					<div class="row buttons">
						<div class="col-md-12">
							<hr>
							<?php
							if ($DatosCotizacion->cotizacion_estatus == 2) { ?>
								<p style="text-align: center; font-size: 20px;">La cotización esta cancelada</p>
							<?php } else { ?>
								<a href="<?php echo $this->createUrl("cotizaciones/Actualizarcotizacion/" . $DatosCotizacion->id_cotizacion); ?>"
									class="btn btn-default btn-lg mr-md">
									Regresar a los productos
								</a>
								<?php /*echo CHtml::submitButton('Guardar cambios',array('class'=>'btn btn-secondary btn-lg mr-md'));*/?>
								<button class="btn btn-secondary btn-lg mr-md" onclick="Guardarcambios();"
									type="button">Guardar
									Cambios</button>

								<a href="<?php echo $this->createUrl("cotizaciones/pdf/" . $DatosCotizacion->id_cotizacion); ?>"
									onclick="Guardarcambios();" class="btn btn-danger btn-lg mr-md" target="_blank">
									Ver pdf
								</a>
								<a href="<?php echo $this->createUrl("cotizaciones/enviarcotizacion/" . $DatosCotizacion->id_cotizacion); ?>"
									onclick="Guardarcambios();" class="btn btn-warning btn-lg mr-md">
									Enviar por correo
								</a>
								<!-- <a href="<? //php echo $this->createUrl("cotizaciones/cancelar/" . $DatosCotizacion->id_cotizacion); 
									?>"
							class="btn btn-danger btn-lg mr-md ">
							Cancelar cotización
						</a> -->
								<?php
								echo CHtml::link(
									'Cancelar cotización',
									array('cotizaciones/cancelar/' . $DatosCotizacion->id_cotizacion),
									array(
										'submit' => array('cotizaciones/cancelar/' . $DatosCotizacion->id_cotizacion),
										'class' => 'btn btn-danger btn-lg mr-md',
										'confirm' => 'Seguro que deseas cancelar?'
									)
								);
								?>
								<a href="<?php echo $this->createUrl("proyectos/crear/" . $DatosCotizacion->id_cotizacion); ?>"
									class="btn btn-success btn-lg mr-md pull-right">
									Crear Pedido
								</a>
							<?php } ?>
						</div>

					</div>

					<?php $this->endWidget(); ?>
				</div><!-- form -->
	</fieldset>
</div>