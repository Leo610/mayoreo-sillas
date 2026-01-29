<?php
/* @var $this Catalogos_recurrentesController */
?>

<div class="modal fade" id="formmodal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="formModalLabel"><?=$this->pageTitle?></h4>
			</div>
			<div class="modal-body">
				<div class="form">

				<?php $form=$this->beginWidget('CActiveForm', array(
					'id'=>'Catalogos_recurrentes-form',
					'action'=>Yii::app()->createUrl('catalogos_recurrentes/createorupdate'),
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
							<?php echo $form->labelEx($model,'nombre'); ?>
							<?php echo $form->textField($model,'nombre',array('class'=>'form-control')); ?>
							<?php echo $form->error($model,'nombre'); ?>
						</div>
						<div class="col-md-6">
							<?php echo $form->labelEx($model,'num'); ?>
							<?php echo $form->textField($model,'num',array('class'=>'form-control')); ?>
							<?php echo $form->error($model,'num'); ?>
						</div>
					</div>
				<div class="row">
						<div class="col-md-12">
							<?php echo $form->labelEx($model,'descripcion'); ?>
							<?php echo $form->textArea($model,'descripcion',array('class'=>'form-control')); ?>
							<?php echo $form->error($model,'descripcion'); ?>
						</div>
					</div>

					
					<div class="row buttons">
		<div class="col-md-12 center">
			<hr>
			<?php echo CHtml::submitButton('Guardar',array('class'=>'btn btn-success')); ?>
			<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
		</div>
	</div>
					<?php echo $form->hiddenField($model,'id_grupo_recurrente',array('value'=>$Datos->id_grupo_recurrente)); ?>
					<?php echo $form->hiddenField($model,'id_catalogo_recurrente'); ?>
				<?php $this->endWidget(); ?>
				</div><!-- form -->
			</div>
		</div>
	</div>
</div>
