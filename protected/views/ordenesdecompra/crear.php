<?php
/* @var $this CotizacionesController */
$this->pageTitle='Crear orden de compra';

$this->breadcrumbs=array(
	'Ordenes de compra'=>array('/ordenesdecompra/lista'),
	'Crear',
);
?>
<script>
$(document).ready(function() {
    $('#productos').DataTable( {
        "paging":   false,
        "ordering": false,
        "info":     false,
        "searching":     false
    });

	
});
	

	
</script>
<div class="row">
	<div class="col-md-12">
		<H1>Crear Orden de compra</H1>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<fieldset>
			<legend>Generar orden de compra</legend>
			<div class="form">
					<?php $form=$this->beginWidget('CActiveForm', array(
						'id'=>'oc-form',
						'action'=>Yii::app()->createUrl('Ordenesdecompra/Crear'),
						'enableClientValidation'=>true,
						'clientOptions' => array('validateOnSubmit'=>true, 'validateOnType'=>true),
						'htmlOptions' => array(
					        'enctype' => 'multipart/form-data',
					    ),
					)); ?>

						<div class="row">
							<div class="col-md-12">
							<p class="note">Llene los campos a continuación para dar de alta</p>
								<?php echo $form->errorSummary($oc); ?>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-12">
								<?php echo $form->labelEx($oc,'id_proveedor'); ?>
								<?php echo $form->dropDownList($oc,'id_proveedor', $Listaprov, array('class'=>'form-control')); ?>
								<?php echo $form->error($oc,'id_proveedor'); ?>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-12">
								<?php echo $form->labelEx($oc,'ordendecompra_comentarios'); ?>
								<?php echo $form->textArea($oc,'ordendecompra_comentarios',array('class'=>'form-control')); ?>
								<?php echo $form->error($oc,'ordendecompra_comentarios'); ?>
							</div>
						</div>
						<div class="row buttons">
							<div class="col-md-12">
								<hr>
								<?php echo CHtml::submitButton('Crear OC',array('class'=>'btn btn-secondary btn-lg')); ?>
							</div>
						</div>
					<?php $this->endWidget(); ?>
					</div><!-- form -->
		</fieldset>
	</div>
</div>
