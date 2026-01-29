<?php
/* @var $this MonedasController */
?>

<div class="modal fade" id="formmodal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="formModalLabel">Monedas Formulario</h4>
			</div>
			<div class="modal-body">
				<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'Monedas-form',
	'action'=>Yii::app()->createUrl('Monedas/Createorupdate'),
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
			<?php echo $form->labelEx($model,'moneda_nombre'); ?>
			<?php echo $form->textField($model,'moneda_nombre',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'moneda_nombre'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<?php echo $form->labelEx($model,'moneda_abreviacion'); ?>
			<?php echo $form->textField($model,'moneda_abreviacion',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'moneda_abreviacion'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<?php echo $form->labelEx($model,'costo_compra'); ?>
			<?php echo $form->textField($model,'costo_compra',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'costo_compra'); ?>
		</div>
		<div class="col-md-6">
			<?php echo $form->labelEx($model,'costo_venta'); ?>
			<?php echo $form->textField($model,'costo_venta',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'costo_venta'); ?>
		</div>
	</div>


	<div class="row buttons">
		<div class="col-md-12 center">
			<hr>
			<?php echo CHtml::submitButton('Guardar',array('class'=>'btn btn-success')); ?>
			<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
		</div>
	</div>
	<?php echo $form->hiddenField($model,'id_moneda'); ?>
<?php $this->endWidget(); ?>
</div><!-- form -->
			</div>
		</div>
	</div>
</div>

