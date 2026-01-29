
<div class="modal fade" id="formmodalcreardetalle" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
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
						'action'=>Yii::app()->createUrl('Rutas_detalles/Crearrutadetalle'),
						'enableClientValidation'=>true,
						'clientOptions' => array('validateOnSubmit'=>true, 'validateOnType'=>true),
						'htmlOptions' => array('enctype' => 'multipart/form-data'),
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
								<?php echo $form->dropDownList($model,'id_cliente', $listaclientes, array('empty'=>'-- Seleccione --','class'=>'form-control ')); ?>
								<?php echo $form->error($model,'id_cliente'); ?>
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
								<?php echo $form->labelEx($model,'fecha_visita'); ?>
			<?php Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
							    $this->widget('CJuiDateTimePicker',array(
							        'model'=>$model, //Model object
							        'attribute'=>'fecha_visita', //attribute name
							        'mode'=>'date', //use "time","date" or "datetime" (default)
							        'options'=>array('dateFormat'=>'yy-mm-dd','showButtonPanel'=>true), // jquery plugin options
							        'htmlOptions'=>array('class'=>'form-control','readonly'=>'true'),
							    ));
							?>
			<?php echo $form->error($model,'fecha_visita'); ?>
							</div>
							<div class="col-md-6">
								 <?php echo $form->labelEx($model,'hora_visita'); ?>
        <?php Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                $this->widget('CJuiDateTimePicker',array(
                    'model'=>$model, //Model object
                    'attribute'=>'hora_visita', //attribute name
                    'mode'=>'time', //use "time","date" or "datetime" (default)
                    'options'=>array('timeFormat' => 'hh:mm tt','showButtonPanel'=>true,'changeMonth'=>true,'changeYear'=>true,'ampm'=>true, 'hourMin' => 8, 'hourMax' => 20), // jquery plugin options
                    'htmlOptions'=>array('class'=>'form-control'),
                ));
        ?>
        <?php echo $form->error($model,'hora_visita'); ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?php echo $form->labelEx($model,'orden'); ?>
								<?php echo $form->textField($model,'orden',array('class'=>'form-control')); ?>
								<?php echo $form->error($model,'orden'); ?>
							</div>
						</div>
						
						<div class="row buttons">
							<div class="col-md-12 center">
								<hr>
								<?php echo $form->hiddenField($model,'id_ruta',array('class'=>'form-control','value'=>$Ruta->id)); ?>
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
