<?php
/* @var $this ModulosController */
?>

<div class="modal fade" id="formmodal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="formModalLabel">Modulos Formulario</h4>
			</div>
			<div class="modal-body">
				<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'Modulos-form',
	'action'=>Yii::app()->createUrl('Modulos/Createorupdate'),
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
			<?php echo $form->labelEx($model,'modulos_nombre'); ?>
			<?php echo $form->textField($model,'modulos_nombre',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'modulos_nombre'); ?>
		</div>
	</div>


	<div class="row buttons">
		<div class="col-md-12">
			<hr>
			<?php echo CHtml::submitButton('Guardar',array('class'=>'btn btn-secondary btn-lg')); ?>
		</div>
	</div>
	<?php echo $form->hiddenField($model,'id_modulos'); ?>
<?php $this->endWidget(); ?>
</div><!-- form -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>