<?php
/* @var $this ProveedoresController */
?>

<div class="modal fade" id="formmodal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="formModalLabel">Proveedores Formulario</h4>
			</div>
			<div class="modal-body">
				<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'Proveedores-form',
	'action'=>Yii::app()->createUrl('Proveedores/Createorupdate'),
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
		<div class="col-md-6">
			<?php echo $form->labelEx($model,'proveedor_nombre'); ?>
			<?php echo $form->textField($model,'proveedor_nombre',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'proveedor_nombre'); ?>
		</div>
		<div class="col-md-6">
			<?php echo $form->labelEx($model,'proveedor_email'); ?>
			<?php echo $form->textField($model,'proveedor_email',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'proveedor_email'); ?>
		</div>
	</div>
<div class="row">
		<div class="col-md-6">
			<?php echo $form->labelEx($model,'proveedor_telefono'); ?>
			<?php echo $form->textField($model,'proveedor_telefono',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'proveedor_telefono'); ?>
		</div>
		<div class="col-md-6">
			<?php echo $form->labelEx($model,'proveedor_razonsocial'); ?>
			<?php echo $form->textField($model,'proveedor_razonsocial',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'proveedor_razonsocial'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<?php echo $form->labelEx($model,'proveedor_rfc'); ?>
			<?php echo $form->textField($model,'proveedor_rfc',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'proveedor_rfc'); ?>
		</div>
		<div class="col-md-6">
			<?php echo $form->labelEx($model,'proveedor_calle'); ?>
			<?php echo $form->textField($model,'proveedor_calle',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'proveedor_calle'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<?php echo $form->labelEx($model,'proveedor_colonia'); ?>
			<?php echo $form->textField($model,'proveedor_colonia',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'proveedor_colonia'); ?>
		</div>
		<div class="col-md-6">
			<?php echo $form->labelEx($model,'proveedor_numerointerior'); ?>
			<?php echo $form->textField($model,'proveedor_numerointerior',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'proveedor_numerointerior'); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<?php echo $form->labelEx($model,'proveedor_numeroexterior'); ?>
			<?php echo $form->textField($model,'proveedor_numeroexterior',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'proveedor_numeroexterior'); ?>
		</div>
		<div class="col-md-6">
			<?php echo $form->labelEx($model,'proveedor_codigopostal'); ?>
			<?php echo $form->textField($model,'proveedor_codigopostal',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'proveedor_codigopostal'); ?>
		</div>
	</div>
<div class="row">
		<div class="col-md-4">
			<?php echo $form->labelEx($model,'proveedor_municipio'); ?>
			<?php echo $form->textField($model,'proveedor_municipio',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'proveedor_municipio'); ?>
		</div>
		<div class="col-md-4">
			<?php echo $form->labelEx($model,'proveedor_entidad'); ?>
			<?php echo $form->textField($model,'proveedor_entidad',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'proveedor_entidad'); ?>
		</div>
		<div class="col-md-4">
			<?php echo $form->labelEx($model,'proveedor_pais'); ?>
			<?php echo $form->textField($model,'proveedor_pais',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'proveedor_pais'); ?>
		</div>
	</div>

	<div class="row buttons">
		<div class="col-md-12 center">
			<hr>
			<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			<?php echo CHtml::submitButton('Guardar',array('class'=>'btn btn-success ')); ?>
		</div>
	</div>
	<?php echo $form->hiddenField($model,'id_proveedor'); ?>
<?php $this->endWidget(); ?>
</div><!-- form -->
			</div>
		</div>
	</div>
</div>

