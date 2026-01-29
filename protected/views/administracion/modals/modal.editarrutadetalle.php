<?php
/* @var $this Rutas_detallesController */
?>
<script type="text/javascript">

    function Actualizar(id){
       
    }
</script>
<div class="modal fade" id="formmodaldetalle" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="formModalLabel">Rutas Detalles Formulario</h4>
			</div>
			<div class="modal-body">
				<div class="form">

					<?php $form=$this->beginWidget('CActiveForm', array(
						'id'=>'RutasDetalles-form',
						'action'=>Yii::app()->createUrl('Rutas_detalles/Actualizarrutadetalle'),
						'enableClientValidation'=>true,
						'clientOptions' => array('validateOnSubmit'=>true, 'validateOnType'=>true),
					)); ?>

						<div class="row">
							<div class="col-md-12">
							<p class="note">Actualizar el detalle.</p>
								<?php echo $form->errorSummary($model); ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?php echo $form->labelEx($model,'estatus'); ?>
								<?php echo $form->dropDownList($model,'estatus',array('PROGRAMADO'=>'PROGRAMADO','REALIZADO'=>'REALIZADO','NO REALIZADO'=>'NO REALIZADO'),array('class'=>'form-control')); ?>
								<?php echo $form->error($model,'estatus'); ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?php echo $form->labelEx($model,'resultado'); ?>
								<?php echo $form->textArea($model,'resultado',array('class'=>'form-control')); ?>
								<?php echo $form->error($model,'resultado'); ?>
							</div>
						</div>
						
						
						<div class="row buttons">
							<div class="col-md-12 center">
								<hr>
								<?php echo $form->hiddenField($model,'id_ruta',array('class'=>'form-control','value'=>$Ruta->id)); ?>
								<?php echo $form->hiddenField($model,'id',array('class'=>'form-control')); ?>
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
