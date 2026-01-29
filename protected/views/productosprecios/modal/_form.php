<?php
/* @var $this ProductospreciospreciosController */
?>

<div class="modal fade" id="formmodal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="formModalLabel">Productosprecios Formulario</h4>
			</div>
			<div class="modal-body">
				<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'Productosprecios-form',
	'action'=>Yii::app()->createUrl('Productosprecios/Createorupdate'),
	'enableClientValidation'=>true,
	'clientOptions' => array('validateOnSubmit'=>true, 'validateOnType'=>true),
	'htmlOptions' => array(
        'enctype' => 'multipart/form-data',
    ),
)); ?>

	<div class="row">
		<div class="col-md-12">
		<p class="note">Llene los campos a continuación para dar de alta</p>
			<?php echo $form->errorSummary($model); ?>
		</div>
	</div>
	
<div class="row">
		<div class="col-md-12">
			<?php echo $form->labelEx($model,'producto_nombre'); ?>
			<?php echo $form->textField($model,'producto_nombre',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'producto_nombre'); ?>
		</div>
	</div>	
		<div class="col-md-12">
			<?php echo $form->labelEx($model,'producto_clave'); ?>
			<?php echo $form->textField($model,'producto_clave',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'producto_clave'); ?>
		</div>
	</div>
<div class="row">
		<div class="col-md-12">
			<?php echo $form->labelEx($model,'producto_costo_compra'); ?>
			<?php echo $form->textField($model,'producto_costo_compra',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'producto_costo_compra'); ?>
		</div>
	</div>	
		<div class="col-md-12">
			<?php echo $form->labelEx($model,'producto_precio_venta_default'); ?>
			<?php echo $form->textField($model,'producto_precio_venta_default',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'producto_precio_venta_default'); ?>
		</div>
	</div>

	 <div class="row">
		<div class="col-md-12">
			<img id="imagenpro" style="max-height:45px;margin-top:5px">
		</div>
	</div>
	<div class="row buttons">
		<div class="col-md-12">
			<hr>
			<?php echo CHtml::submitButton('Guardar',array('class'=>'btn btn-secondary btn-lg')); ?>
		</div>
	</div>
	<?php echo $form->hiddenField($model,'id_producto'); ?>
<input type="hidden" name="imagenoriginal" id="imagenoriginal" >
<?php $this->endWidget(); ?>
</div><!-- form -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>

