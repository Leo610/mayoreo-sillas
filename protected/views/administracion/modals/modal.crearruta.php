<?php
/* @var $this RutasController */
?>
<div class="modal fade" id="formmodalcrearruta" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="formModalLabel">Rutas Formulario</h4>
			</div>
			<div class="modal-body">
				<div class="form">

					<?php $form=$this->beginWidget('CActiveForm', array(
						'id'=>'Rutas-form',
						'action'=>Yii::app()->createUrl('Rutas/crearruta'),
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
								<?php echo $form->labelEx($model,'nombre'); ?>
								<?php echo $form->textField($model,'nombre',array('class'=>'form-control')); ?>
								<?php echo $form->error($model,'nombre'); ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<?php echo $form->labelEx($model,'fecha_desde'); ?>
			<?php Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
							    $this->widget('CJuiDateTimePicker',array(
							        'model'=>$model, //Model object
							        'attribute'=>'fecha_desde', //attribute name
							        'mode'=>'date', //use "time","date" or "datetime" (default)
							        'options'=>array('dateFormat'=>'yy-mm-dd','showButtonPanel'=>true), // jquery plugin options
							        'htmlOptions'=>array('class'=>'form-control','readonly'=>'true'),
							    ));
							?>
			<?php echo $form->error($model,'fecha_desde'); ?>
							</div>
							<div class="col-md-6">
								<?php echo $form->labelEx($model,'fecha_hasta'); ?>
			<?php Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
							    $this->widget('CJuiDateTimePicker',array(
							        'model'=>$model, //Model object
							        'attribute'=>'fecha_hasta', //attribute name
							        'mode'=>'date', //use "time","date" or "datetime" (default)
							        'options'=>array('dateFormat'=>'yy-mm-dd','showButtonPanel'=>true), // jquery plugin options
							        'htmlOptions'=>array('class'=>'form-control','readonly'=>'true'),
							    ));
							?>
			<?php echo $form->error($model,'fecha_hasta'); ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?php echo $form->labelEx($model,'comentarios'); ?>
								<?php echo $form->textArea($model,'comentarios',array('class'=>'form-control')); ?>
								<?php echo $form->error($model,'comentarios'); ?>
							</div>
						</div>
						
						<div class="row buttons">
							<div class="col-md-12 center">
								<hr>
								<?php echo $form->hiddenField($model,'estatus',array('class'=>'form-control','value'=>'PROGRAMADA')); ?>
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
