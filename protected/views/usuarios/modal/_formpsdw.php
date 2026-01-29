<?php
/* @var $this SliderController */
?>

<div class="modal fade" id="formmodalpswd" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="formModalLabel">Usuarios Formulario</h4>
			</div>
			<div class="modal-body">
				<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'usuariospswdform',
	'action'=>Yii::app()->createUrl('Usuarios/Actualizarpssword'),
	'enableClientValidation'=>true,
	'clientOptions' => array('validateOnSubmit'=>true, 'validateOnType'=>true),
)); ?>

	<div class="row">
		<div class="col-md-12">
		<p class="note">Cambiar contraseña de usuario</p>
			<?php echo $form->errorSummary($model); ?>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12">
			<?php echo $form->labelEx($model,'Usuario_Email'); ?>
			<?php echo $form->textField($model,'Usuario_Email',array('class'=>'form-control','readonly'=>'true')); ?>
			<?php echo $form->error($model,'Usuario_Email'); ?>
		</div>
		<div class="col-md-12">
			<?php echo $form->labelEx($model,'Usuario_Password'); ?>
			<?php echo $form->passwordfield($model,'Usuario_Password',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'Usuario_Password'); ?>
		</div>
		<div class="col-md-12">
			<?php echo $form->labelEx($model,'ID_Usuario'); ?>
			<?php echo $form->textField($model,'ID_Usuario',array('class'=>'form-control','readonly'=>'true')); ?>
			<?php echo $form->error($model,'ID_Usuario'); ?>
		</div>
	</div>
	
	<div class="row buttons">
		<div class="col-md-12 center">
			<hr>
			<?php echo CHtml::submitButton('Guardar',array('class'=>'btn btn-success')); ?>
			<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
		</div>
	</div>
<?php $this->endWidget(); ?>
</div><!-- form -->

			
				
			</div>
		</div>
	</div>
</div>

