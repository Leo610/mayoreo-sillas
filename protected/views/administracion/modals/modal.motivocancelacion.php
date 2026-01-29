<?php
/* @var $this Crm_oportunidadesController */
?>

<div class="modal fade" id="formmodalmotivocancelacion" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="formModalLabel"><?=$this->pageTitle?></h4>
			</div>
			<div class="modal-body">
				<div class="form">

				<?php $form=$this->beginWidget('CActiveForm', array(
					'id'=>'Crm_oportunidades-form',
					'action'=>Yii::app()->createUrl('crm_oportunidades/Cancelacion'),
					'enableClientValidation'=>true,
					'clientOptions' => array('validateOnSubmit'=>true, 'validateOnType'=>true),
				)); ?>

					<div class="row">
						<div class="col-md-12">
						<p class="note">Llene los campos para dar de baja</p>
							<?php echo $form->errorSummary($DatosOportunidad); ?>
						</div>
					</div>

					<div class="row">
					<div class="col-md-12">
							<?php echo $form->labelEx($DatosOportunidad,'motivo_perdido'); ?>
							<?php echo $form->dropDownList($DatosOportunidad,'motivo_perdido', $ListaCancelacion, array('empty'=>'-- Seleccione --','class'=>'form-control')); ?>
							<?php echo $form->error($DatosOportunidad,'motivo_perdido'); ?>
							</div>
						</div>	
						<div class="row">
							<div class="col-md-12">
								<?php echo $form->labelEx($DatosOportunidad,'comentarios_perdido'); ?>
								<?php echo $form->textArea($DatosOportunidad,'comentarios_perdido',array('class'=>'form-control')); ?>
								<?php echo $form->error($DatosOportunidad,'comentarios_perdido'); ?>
							</div>
						</div>
				

					
					<div class="row buttons">
		<div class="col-md-12 center">
			<hr>
			<?php echo CHtml::submitButton('Guardar',array('class'=>'btn btn-success')); ?>
			<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
		</div>
	</div>
					<?php echo $form->hiddenField($DatosOportunidad,'id'); ?>
				<?php $this->endWidget(); ?>
				</div><!-- form -->
			</div>
		</div>
	</div>
</div>
