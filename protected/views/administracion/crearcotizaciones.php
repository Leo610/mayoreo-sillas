<?php
/* @var $this CrearcotizacionesController */
$this->pageTitle = 'Crear cotización';
$this->breadcrumbs = array(
	'Administracion' => array('/administracion'),
	'Crear cotización',
);

?>
<script>
	$(document).ready(function () {
		$('#productoscotizacion').DataTable({
			"paging": false,
			"ordering": false,
			"info": false,
			"searching": false
		});
		$('#Cotizaciones_tipo_precio').change(function () {
			// Aquí colocas el código que quieres ejecutar cuando el dropdown cambia.
			var nuevoprecio = $(this).val();
			var id_cliente = <?= isset($_GET['id']) ? $_GET['id'] : '' ?>;
			// ajax para cambiar la lista del precio del cliente
			var jqxhr = $.ajax({
				url: "<?php echo $this->createUrl("administracion/cambiarlistapreciocliente"); ?>",
				type: "POST",
				dataType: "json",
				timeout: (120 * 1000),
				data: {
					nuevoprecio: nuevoprecio,
					id: id_cliente
				},
				success: function (Response, newValue) {
					if (Response.requestresult == 'ok') {
						$.notify(Response.message, "success");
					} else {
						$.notify(Response.message, "error");
					}
				},
				error: function (e) {
					$.notify('Ocurrio un error inesperado', "error");
				}
			});
		});
	});

</script>
<div class="row">
	<div class="col-md-12">
		<fieldset>
			<legend>Seleccionar cliente</legend>
			<form method="GET"
				action="<?php echo Yii::app()->createUrl('administracion/crearcotizaciones/crear?cliente&id=0/') ?>">
				<div class="col-md-9">
					<?php
					$this->widget(
						'zii.widgets.jui.CJuiAutoComplete',
						array(
							'name' => 'cliente_auto',
							'source' => $this->createUrl('clientes/clientesajax'),

							// Opciones javascript adicionales para el plugin
							'options' => array(
								'minLength' => '3',
								'select' => 'js:function(event, ui) {
				              	$("#id_cliente").val(ui.item.id);
				              	$("#cliente_auto").val(ui.item.value);
				              	this.form.submit();
			             	}',
								'focus' => 'js:function(event, ui) {
			                  return false;
			              }'
							),
							'htmlOptions' => array(
								'class' => 'form-control',
								'placeholder' => 'Cliente'
							)
						)
					);
					?>

				</div>
				<!-- <div class="col-md-3">
					<?= CHTML::submitButton('Seleccionar cliente', array('class' => 'btn btn-success')) ?>
				</div> -->
				<input type="hidden" value="" name="id" id="id_cliente">
			</form>

		</fieldset>
	</div>
</div>

<?php if (!empty($DatosCliente)) {

	?>

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
						<? //= $DatosCliente['rl_cliente_tipo_clasificacion']['nombre']; ?>
					</strong><br>
					Como Trabajarlo:<br><strong>
						<? //= $DatosCliente['rl_cliente_como_trabajarlo']['nombre']; ?>
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
						<!-- <? //= (!empty($DatosCliente['rl_listaprecios'])?$DatosCliente['rl_listaprecios']['listaprecio_nombre']:'')?> -->
						<?php
						$Tipoprecio = $this->ObtenerTipoPrecio($DatosCliente['id_lista_precio']);
						if (!empty($Tipoprecio)) {
							echo $Tipoprecio['label'];
						}
						?>
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
				</div>
			</fieldset>
		</div>

	</div>
	<div class="row">
		<div class="col-md-12">
			<fieldset>
				<div class="form">
					<?php $form = $this->beginWidget(

						'CActiveForm',
						array(
							'id' => 'cotizaciones-form',
							'action' => Yii::app()->createUrl('administracion/Crearcotizaciones'),
							'enableClientValidation' => true,
							'clientOptions' => array('validateOnSubmit' => true, 'validateOnType' => true),
							'htmlOptions' => array('enctype' => 'multipart/form-data'),
						)
					); ?>


					<div class="row">
						<div class="col-md-12">
							<p class="note">Llene los campos a continuación para dar de alta</p>
							<?php echo $form->errorSummary($model); ?>
						</div>
					</div>
					<div class="row">
						<!-- <div class="col-md-12">
							<? //php echo $form->labelEx($model, 'cotizacion_nombre'); ?>
							<? //php echo $form->textField($model, 'cotizacion_nombre', array('class' => 'form-control', 'value' => $DatosOportunidad['nombre'])); ?>
							<? //php echo $form->error($model, 'cotizacion_nombre'); ?>
						</div> -->
						<div class="col-md-12 " style="display: none;">
							<?php echo $form->labelEx($model, 'id_lista_precio'); ?>
							<?php echo $form->dropDownList($model, 'id_lista_precio', $arraylistaprecios, array('class' => 'form-control')); ?>
							<?php echo $form->error($model, 'id_lista_precio'); ?>
						</div>

						<div class="col-md-12">
							<?php echo $form->labelEx($model, 'tipo_precio'); ?>
							<?php echo $form->dropDownList($model, 'tipo_precio', $arraytipoprecio, array(
								'class' => 'form-control',
								'options' => [
									$DatosCliente['id_lista_precio'] => ['selected' => 'selected']
								]
							)
							); ?>
							<?php echo $form->error($model, 'tipo_precio'); ?>
						</div>

						<!-- <div class="col-md-12">
							<? //php echo $form->labelEx($model, 'id_tipo_proyecto'); ?>
							<? //php echo $form->dropDownList($model, 'id_tipo_proyecto', $arraylistatipoproyecto, array('empty' => '-----Seleccione-----', 'class' => 'form-control')); ?>
							<? //php echo $form->error($model, 'id_tipo_proyecto'); ?>
						</div> -->
					</div>
					<div class="row">
						<!-- <div class="col-md-12">
							<? //php echo $form->labelEx($model, 'cotizacion_comentario'); ?>
							<? //php echo $form->textArea($model, 'cotizacion_comentario', array('class' => 'form-control ')); ?>
							<? //php echo $form->error($model, 'cotizacion_comentario'); ?>
						</div> -->
					</div>
					<div class="row">
						<div class="col-md-12">
							<?php
							echo $form->hiddenField($model_cot_arch, 'cotizacion_archivo[]', array('multiple' => 'true'));
							echo $form->error($model_cot_arch, 'cotizacion_archivo');
							?>
						</div>
					</div>

					<div class="row buttons">
						<div class="col-md-12">
							<hr>
							<?php echo CHtml::submitButton('Crear Cotización', array('class' => 'btn btn-success btn-lg')); ?>
						</div>
					</div>
					<?php echo $form->hiddenField($model, 'id_cliente', array('value' => $DatosCliente->id_cliente)); ?>
					<?php echo $form->hiddenField($model, 'id_oportunidad', array('value' => $id_oportunidad)); ?>
					<?php $this->endWidget(); ?>
				</div><!-- form -->
			</fieldset>
		</div>
	</div>
<?php }