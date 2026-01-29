<?php
/* @var $this AdministracionController */
?>

<div class="modal fade" id="agergaroportunidadmodal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="formModalLabel">Agregar Oportunindad</h4>
			</div>
			<div class="modal-body">
				<div class="form">


					<?php

					$form = $this->beginWidget(
						'CActiveForm',
						array(
							'id' => 'agregaroportunidad',
							'action' => Yii::app()->createUrl('administracion/crearoportunidad'),
							'enableClientValidation' => true,
							'clientOptions' => array('validateOnSubmit' => true, 'validateOnType' => true),
						)
					);

					?>
					<?php echo CHtml::errorSummary(array($crm_oportunidades)); ?>

					<div class="row">
						<div class="col-md-12">
							<?php echo $form->labelEx($crm_oportunidades, 'id_cliente'); ?>
							<?php echo $form->hiddenField($crm_oportunidades, 'id_cliente'); ?>
							<?php
							$this->widget(
								'zii.widgets.jui.CJuiAutoComplete',
								array(
									'name' => 'id_autocomplete',
									'source' => $this->createUrl('administracion/buscadorclienteajax'),
									// Opciones javascript adicionales para el plugin
									'options' => array(
										'minLength' => '2',
										'select' => 'js:function(event, ui) {
							      	$("#CrmOportunidades_id_cliente").val(ui.item.id);
							      	$("#id_autocomplete").val(ui.item.value);
							     	}',
										'focus' => 'js:function(event, ui) {
							          return false;
							      }'
									),
									'htmlOptions' => array(
										'class' => 'form-control',
										'placeholder' => 'Buscador de cliente',

									)
								)
							);
							?>
							<input type="hidden" name="id_cliente_auto" id="id_cliente_auto">
							<?php echo $form->error($crm_oportunidades, 'id_cliente'); ?>
						</div>
						<div class="col-md-12">
							<?php echo $form->labelEx($crm_oportunidades, 'nombre'); ?>
							<?php echo $form->textField($crm_oportunidades, 'nombre', array('class' => 'form-control')); ?>
							<?php echo $form->error($crm_oportunidades, 'nombre'); ?>
						</div>
						<div class="col-md-12">
							<?php echo $form->labelEx($crm_oportunidades, 'valor_negocio'); ?>
							<?php echo $form->textField($crm_oportunidades, 'valor_negocio', array('class' => 'form-control')); ?>
							<?php echo $form->error($crm_oportunidades, 'valor_negocio'); ?>
						</div>
						<div class="col-md-12">
							<?php echo $form->labelEx($crm_oportunidades, 'fecha_tentativa_cierre'); ?>
							<?php Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
							$this->widget(
								'CJuiDateTimePicker',
								array(
									'id' => 'fecha_tentativa_cierre',
									'model' => $crm_oportunidades,
									//Model object
									'attribute' => 'fecha_tentativa_cierre',
									//attribute name
									'mode' => 'datetime',
									//use "time","date" or "datetime" (default)
									'options' => array('dateFormat' => 'yy-mm-dd', ),
									// jquery plugin options
									'htmlOptions' => array('class' => 'form-control', 'readonly' => 'true', 'value' => date('Y-m-d H:i:s')),
								)
							);
							?>
							<?php echo $form->error($crm_oportunidades, 'fecha_tentativa_cierre'); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<?php echo $form->labelEx($crm_oportunidades, 'id_etapa'); ?>
							<?php echo $form->dropDownList($crm_oportunidades, 'id_etapa', $listaetapa, array('empty' => '-- Seleccione --', 'class' => 'form-control')); ?>
							<?php echo $form->error($crm_oportunidades, 'id_etapa'); ?>
						</div>
					</div>



					<div class="row buttons">
						<div class="col-md-12 center mt-md">
							<hr>
							<?php echo CHtml::submitButton('Agregar', array('class' => 'btn btn-success')); ?>
							<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button><br>
							<button style="margin-top: 5px;" type="button" class="clientenvo btn btn-primary">Agregar
								Cliente
								Nuevo</button>
						</div>
					</div>
					<?php $this->endWidget(); ?>
				</div><!-- form -->
			</div>
		</div>
	</div>
</div>


<script>
	document.querySelector('.clientenvo').addEventListener('click', () => {

		// ocultamos el modal actual
		$('#agergaroportunidadmodal').modal('hide');

		$('#formmodal').modal('show');



	});
</script>