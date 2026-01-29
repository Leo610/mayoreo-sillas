<?php
/* @var $this AdministracionController */
?>
<style>
	.ocultar {
		display: none;
	}
</style>
<script type="text/javascript">
	function GetColonias(codigopostal) {
		var jqxhr = $.ajax({
			url: "<?php echo $this->createUrl("administracion/Obtenercolonias"); ?>",
			type: "POST",
			dataType: "json",
			timeout: (120 * 1000),
			data: {
				codigopostal: codigopostal
			},
			success: function (Response, newValue) {
				if (Response.requestresult == 'ok') {
					$.notify(Response.message, "success");

					$('#Clientes_cliente_entidad').empty().append(Response.op_entidades);
					$('#Clientes_cliente_municipio').empty().append(Response.op_municipios);
					$('#Clientes_cliente_colonia').empty().append(Response.op_colonias);

				} else {
					$.notify(Response.message, "error");
				}
			},
			error: function (e) {
				$.notify('Ocurrio un error inesperado', "error");
			}
		});
	}
	function direccionenvio(e) {
		// subir los inputs con la clase inputusa y a los otros colcarles la dirmex
		if (e.value == 2) {
			document.querySelectorAll('.dirmex').forEach(function (element) {
				element.classList.add('ocultar');
			});

			document.querySelectorAll('.inputusa').forEach(function (element) {
				element.classList.remove('ocultar');
			});
		} else {
			document.querySelectorAll('.dirmex').forEach(function (element) {
				element.classList.remove('ocultar');
			});

			document.querySelectorAll('.inputusa').forEach(function (element) {
				element.classList.add('ocultar');
			});
		}
	}

</script>
<div class="modal fade" id="agregarprospecto" tabindex="-1" role="dialog" aria-labelledby="formModalLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="formModalLabel">Agregar Prospecto</h4>
			</div>
			<div class="modal-body">
				<div class="form">

					<?php $form = $this->beginWidget(
						'CActiveForm',
						array(
							'id' => 'agregarprospecto-form',
							'action' => Yii::app()->createUrl('Administracion/Crearprospecto'),
							'enableClientValidation' => true,
							'clientOptions' => array('validateOnSubmit' => true, 'validateOnType' => true),
						)
					); ?>


					<?php echo CHtml::errorSummary(array($modelClientes)); ?>

					<div class="row">
						<div class="col-md-4">
							<?php echo $form->labelEx($model, 'pais'); ?>
							<?php echo $form->dropDownList($model, 'pais', [1 => 'México', 2 => 'USA'], array('class' => 'form-control', 'onchange' => 'direccionenvio(this)')); ?>
							<?php echo $form->error($model, 'pais'); ?>
						</div>
						<div class="col-md-12">
							<?php echo $form->labelEx($modelClientes, 'cliente_nombre'); ?>
							<?php echo $form->textField($modelClientes, 'cliente_nombre', array('class' => 'form-control')); ?>
							<?php echo $form->error($modelClientes, 'cliente_nombre'); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<?php echo $form->labelEx($modelClientes, 'cliente_email'); ?>
							<?php echo $form->textField($modelClientes, 'cliente_email', array('class' => 'form-control')); ?>
							<?php echo $form->error($modelClientes, 'cliente_email'); ?>
						</div>
						<div class="col-md-4">
							<?php echo $form->labelEx($modelClientes, 'cliente_telefono'); ?>
							<?php echo $form->textField($modelClientes, 'cliente_telefono', array('class' => 'form-control')); ?>
							<?php echo $form->error($modelClientes, 'cliente_telefono'); ?>
						</div>
						<div class="col-md-4">
							<?php echo $form->labelEx($modelClientes, 'id_lista_precio'); ?>
							<?php echo $form->dropDownList($modelClientes, 'id_lista_precio', $precio, array('empty' => '-- Seleccione --', 'class' => 'form-control')); ?>
							<?php echo $form->error($modelClientes, 'id_lista_precio'); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<?php echo $form->labelEx($modelClientes, 'id_empresa'); ?>
							<?php
							$this->widget(
								'zii.widgets.jui.CJuiAutoComplete',
								array(
									'name' => 'autocomplete',
									'source' => $this->createUrl('empresas/Autocompletejs'),
									// Opciones javascript adicionales para el plugin
									'options' => array(
										'minLength' => '3',
										'select' => 'js:function(event, ui) {
				                  	$("#Clientes_id_empresa").val(ui.item.id);
				                  	$("#autocomplete_empresa").val(ui.item.value);
				                 	}',
										'focus' => 'js:function(event, ui) {
				                      return false;
				                  }'
									),
									'htmlOptions' => array(
										'class' => 'form-control',
										'placeholder' => 'Busque o agrege la empresa'
									)
								)
							);
							?>
							<?php echo $form->hiddenfield($modelClientes, 'id_empresa'); ?>
							<?php echo $form->error($modelClientes, 'id_empresa'); ?>
						</div>
						<div class="col-md-4 dirmex">
							<label>Código Postal</label>
							<?php
							$this->widget(
								'zii.widgets.jui.CJuiAutoComplete',
								array(
									'name' => 'cliente_codigopostal',
									'source' => $this->createUrl('administracion/ObtenerCP'),
									// Opciones javascript adicionales para el plugin
									'options' => array(
										'minLength' => '3',
										'select' => 'js:function(event, ui) {
				                  	$("#cliente_codigopostal").val(ui.item.value);
				                  	GetColonias(ui.item.id);
				                 	}',
										'focus' => 'js:function(event, ui) {
				                      return false;
				                  }'
									),
									'htmlOptions' => array(
										'class' => 'form-control',
										'placeholder' => 'Ingrese el código postal'
									)
								)
							);
							?>
						</div>
						<div class="col-md-4 inputusa ocultar">
							<?php echo $form->labelEx($model, 'cliente_codigopostal'); ?>
							<?php echo $form->textField($model, 'cliente_codigopostal', array('class' => 'form-control', 'placeholder' => 'Introduzca el codigo postal')); ?>
							<?php echo $form->error($model, 'cliente_codigopostal'); ?>
						</div>
						<div class="col-md-4">
							<?php echo $form->labelEx($modelClientes, 'cliente_pais'); ?>
							<?php echo $form->textField($modelClientes, 'cliente_pais', array('class' => 'form-control', 'value' => 'México')); ?>
							<?php echo $form->error($modelClientes, 'cliente_pais'); ?>
						</div>
						<div class="col-md-4 dirmex">
							<?php echo $form->labelEx($modelClientes, 'cliente_entidad'); ?>
							<?php echo $form->dropDownList($modelClientes, 'cliente_entidad', array(), array('class' => 'form-control')); ?>
							<?php echo $form->error($modelClientes, 'cliente_entidad'); ?>
						</div>
						<div class="col-md-4 inputusa ocultar">
							<?php echo $form->labelEx($model, 'cliente_entidad'); ?>
							<?php echo $form->textField($model, 'cliente_entidad', array('class' => 'form-control', 'placeholder' => 'Introduzca el estado')); ?>
							<?php echo $form->error($model, 'cliente_entidad'); ?>
						</div>
						<div class="col-md-4 dirmex">
							<?php echo $form->labelEx($modelClientes, 'cliente_municipio'); ?>
							<?php echo $form->dropDownList($modelClientes, 'cliente_municipio', array(), array('class' => 'form-control')); ?>
							<?php echo $form->error($modelClientes, 'cliente_municipio'); ?>
						</div>
						<div class="col-md-4 inputusa ocultar">
							<?php echo $form->labelEx($model, 'cliente_municipio'); ?>
							<?php echo $form->textField($model, 'cliente_municipio', array('class' => 'form-control', 'placeholder' => 'Introduzca el municipio')); ?>
							<?php echo $form->error($model, 'cliente_municipio'); ?>
						</div>
						<div class="col-md-4 dirmex">
							<?php echo $form->labelEx($modelClientes, 'cliente_colonia'); ?>
							<?php echo $form->dropDownList($modelClientes, 'cliente_colonia', array(), array('class' => 'form-control')); ?>
							<?php echo $form->error($modelClientes, 'cliente_colonia'); ?>
						</div>
						<div class="col-md-4 inputusa ocultar">
							<?php echo $form->labelEx($model, 'cliente_colonia'); ?>
							<?php echo $form->textField($model, 'cliente_colonia', array('class' => 'form-control', 'placeholder' => 'Introduzca la colonia')); ?>
							<?php echo $form->error($model, 'cliente_colonia'); ?>
						</div>
						<div class="col-md-4">
							<?php echo $form->labelEx($modelClientes, 'cliente_calle'); ?>
							<?php echo $form->textField($modelClientes, 'cliente_calle', array('class' => 'form-control')); ?>
							<?php echo $form->error($modelClientes, 'cliente_calle'); ?>
						</div>

						<div class="col-md-4">
							<?php echo $form->labelEx($modelClientes, 'cliente_numeroexterior'); ?>
							<?php echo $form->textField($modelClientes, 'cliente_numeroexterior', array('class' => 'form-control')); ?>
							<?php echo $form->error($modelClientes, 'cliente_numeroexterior'); ?>
						</div>
						<div class="col-md-4">
							<?php echo $form->labelEx($modelClientes, 'cliente_numerointerior'); ?>
							<?php echo $form->textField($modelClientes, 'cliente_numerointerior', array('class' => 'form-control')); ?>
							<?php echo $form->error($modelClientes, 'cliente_numerointerior'); ?>
						</div>
					</div>
					<div class="row">
						<!-- <div class="col-md-4">
							<? //php echo $form->labelEx($modelClientes, 'cliente_tipo_clasificacion'); ?>
							<? //php echo $form->dropDownList($modelClientes, 'cliente_tipo_clasificacion', $cliente_tipo_clasificacion, array('empty' => '-- Seleccione --', 'class' => 'form-control select2')); ?>
							<? //php echo $form->error($modelClientes, 'cliente_tipo_clasificacion'); ?>
						</div>
						<div class="col-md-4">
							<? //php echo $form->labelEx($modelClientes, 'cliente_como_trabajarlo'); ?>
							<? //php echo $form->dropDownList($modelClientes, 'cliente_como_trabajarlo', $cliente_como_trabajarlo, array('empty' => '-- Seleccione --', 'class' => 'form-control select2')); ?>
							<? //php echo $form->error($modelClientes, 'cliente_como_trabajarlo'); ?>
						</div> -->
						<div class="col-md-4">
							<?php echo $form->labelEx($modelClientes, 'cliente_tipo'); ?>
							<?php echo $form->dropDownList($modelClientes, 'cliente_tipo', $cliente_tipo, array('empty' => '-- Seleccione --', 'class' => 'form-control select2')); ?>
							<?php echo $form->error($modelClientes, 'cliente_tipo'); ?>
						</div>
						<div class="col-md-4">
							<?php echo $form->labelEx($modelClientes, 'como_contacto'); ?>
							<?php echo $form->dropDownList($modelClientes, 'como_contacto', array(
								'Facebook' => 'Facebook',
								'Instagram' => 'Instagram',
								'WhatsApp' => 'WhatsApp',
								'Llamada a celular' => 'Llamada a celular',
								'Llamada a telefonos de oficina' => 'Llamada a telefonos de oficina',
								'Correo electronico' => 'Correo electronico',
								'TikTok' => 'TikTok',
								'Google business' => 'Google business',
							), array('empty' => '-- Seleccione --', 'class' => 'form-control')); ?>
							<?php echo $form->error($modelClientes, 'como_contacto'); ?>
						</div>
					</div>


					<div class="row buttons">
						<div class="col-md-12 mt-md center">
							<?php echo CHtml::submitButton('Guardar', array('class' => 'btn btn-success')); ?>
							<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
						</div>
					</div>
					<?php $this->endWidget(); ?>
				</div><!-- form -->
			</div>
		</div>
	</div>
</div>