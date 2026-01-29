<?php
/* @var $this CrmaccionesController */
?>

<div class="modal fade" id="formmodal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="formModalLabel">Crmacciones Formulario</h4>
			</div>
			<div class="modal-body">
				<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'Crmacciones-form',
	'action'=>Yii::app()->createUrl('Crmacciones/Createorupdate'),
	'enableClientValidation'=>true,
	'clientOptions' => array('validateOnSubmit'=>true, 'validateOnType'=>true),
)); ?>

	<div class="row">
		<div class="col-md-12">
		<p class="note">Llene los campos a continuación para dar de alta</p>
			<?php echo $form->errorSummary($model); ?>
		</div>
	</div>
	
<div class="row">
		<div class="col-md-12">
			<?php echo $form->labelEx($model,'crm_acciones_nombre'); ?>
			<?php echo $form->textField($model,'crm_acciones_nombre',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'crm_acciones_nombre'); ?>
		</div>
	</div>
<div class="row">
		<div class="col-md-12">
			<?php echo $form->labelEx($model,'crm_acciones_icono'); ?>
			<?php echo $form->textField($model,'crm_acciones_icono',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'crm_acciones_icono'); ?>
		</div>
	</div>
<div class="row">
		<div class="col-md-12">
			<?php echo $form->labelEx($model,'crm_acciones_color'); ?>
			<?php echo $form->textField($model,'crm_acciones_color',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'crm_acciones_color'); ?>
		</div>
	</div>

	<div class="row buttons">
		<div class="col-md-12 center">
			<hr>
			<?php echo CHtml::submitButton('Guardar',array('class'=>'btn btn-success')); ?>
			<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
		</div>
	</div>
	<?php echo $form->hiddenField($model,'id_crm_acciones'); ?>
<?php $this->endWidget(); ?>
</div><!-- form -->
			</div>
		</div>
	</div>
</div>

