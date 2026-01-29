<?php
/* @var $this ListapreciosController */
?>

<div class="modal fade" id="formmodal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="formModalLabel">Listaprecios Formulario</h4>
			</div>
			<div class="modal-body">
				<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'Listasprecio-form',
	'action'=>Yii::app()->createUrl('Listaprecios/Createorupdate'),
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
			<?php echo $form->labelEx($model,'listaprecio_nombre'); ?>
			<?php echo $form->textField($model,'listaprecio_nombre',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'listaprecio_nombre'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<?php echo $form->labelEx($model,'id_moneda'); ?>
			<?php echo $form->dropDownList($model,'id_moneda', $ListaMonedas, array('empty'=>'-- Seleccione --','class'=>'form-control')); ?>
			<?php echo $form->error($model,'id_moneda'); ?>
		</div>
		<div class="col-md-6">
			<?php echo $form->labelEx($model,'default'); ?>
			<?php echo $form->dropDownList($model,'default',array('0'=>'No','1'=>'Si'),array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'default'); ?>
		</div>
	</div>
	
	<div class="row buttons">
		<div class="col-md-12 center">
			<hr>
			<?php echo CHtml::submitButton('Guardar',array('class'=>'btn btn-success')); ?>
			<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
		</div>
	</div>
	<?php echo $form->hiddenField($model,'id_lista_precio'); ?>
<?php $this->endWidget(); ?>
</div><!-- form -->
			</div>
		</div>
	</div>
</div>

