<?php
/* @var $this ClientesFrecuenciasController */
?>

<div class="modal fade" id="formmodal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="formModalLabel">Clientes Frecuencias Formulario</h4>
			</div>
			<div class="modal-body">
				<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ClientesFrecuencias-form',
	'action'=>Yii::app()->createUrl('Clientes_frecuencias/Createorupdate'),
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
			<?php echo $form->labelEx($model,'id_cliente'); ?>
			<?php echo $form->dropDownList($model,'id_cliente', $ListaClientes, array('empty'=>'-- Seleccione --','class'=>'form-control')); ?>
			<?php echo $form->error($model,'id_cliente'); ?>
		</div>
	</div>
<div class="row">
		<div class="col-md-12">
			<?php echo $form->labelEx($model,'id_frecuencia'); ?>
			<?php echo $form->dropDownList($model,'id_frecuencia', $ListaFrecuencia, array('empty'=>'-- Seleccione --','class'=>'form-control')); ?>
			<?php echo $form->error($model,'id_frecuencia'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<?php echo $form->labelEx($model,'nombre_dia'); ?>
			<?php echo $form->dropDownList($model,'nombre_dia',array('DOMINGO'=>'DOMINGO','LUNES'=>'LUNES','MARTES'=>'MARTES','MIERCOLES'=>'MIERCOLES','JUEVES'=>'JUEVES','VIERNES'=>'VIERNES','SABADO'=>'SABADO'),array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'nombre_dia'); ?>
		</div>
	</div>

	<div class="row buttons">
		<div class="col-md-12 center">
			<hr>
			<?php echo CHtml::submitButton('Guardar',array('class'=>'btn btn-success')); ?>
			<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
		</div>
	</div>
	<?php echo $form->hiddenField($model,'id'); ?>
<?php $this->endWidget(); ?>
</div><!-- form -->
			</div>
		</div>
	</div>
</div>
