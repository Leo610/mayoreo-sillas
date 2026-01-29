<?php
/* @var $this AdministracionController */
?>

<div class="modal fade" id="agregaraccion" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="formModalLabel">Agregar Acción</h4>
			</div>
			<div class="modal-body">
				<div class="form">

				
				<?php $form=$this->beginWidget('CActiveForm', array(
					'id'=>'agregaraccion-form',
					'action'=>Yii::app()->createUrl('crmdetalles/Crearaccion'),
					'enableClientValidation'=>true,
					'clientOptions' => array('validateOnSubmit'=>true, 'validateOnType'=>true),
				)); ?>
				 

				<?php echo CHtml::errorSummary(array($modelCrmdetalles)); ?>

					<div class="row">
						<div class="col-md-12">
							<?php echo $form->labelEx($modelCrmdetalles,'id_oportunidad'); ?>
							<?php echo $form->hiddenField($modelCrmdetalles,'id_oportunidad'); ?>
							<?php 
		            $_datosautocompletar = array ();
		            foreach ($listaoportunidades as $rows ){ 
		                $_datosautocompletar[$rows->id] = $rows->nombre.' - '.$rows->rl_clientes->cliente_nombre;
		            } 
		            $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
		                'name'=>'id_oportunidad_autocompletar',
		                'source'=>$_datosautocompletar,
		                'source' => array_map(function($key, $value) {
		                                return array('label' => $value, 'value' => $key);
		                            }, array_keys($_datosautocompletar), $_datosautocompletar),
		                // Opciones javascript adicionales para el plugin
		                'options'=>array(
		                    'minLength'=>'3',
		                   'select'=>'js:function(event, ui) {
		                        $("#Crmdetalles_id_oportunidad").val(ui.item.value);
		                        $("#id_oportunidad_autocompletar").val(ui.item.label);
		                        return false; 

		                    }',
		                    'focus'=>'js:function(event, ui) {
		                        return false;
		                    }'
		                ),
		                'htmlOptions'=>array(
		                    'class'=>'form-control',
		                ),
		            ));
		          ?>
							<?php echo $form->error($modelCrmdetalles,'id_oportunidad'); ?>
						</div>
					</div>
				<div class="row">
						<div class="col-md-6">
							<?php echo $form->labelEx($modelCrmdetalles,'id_crm_acciones'); ?>
							<?php echo $form->dropDownList($modelCrmdetalles,'id_crm_acciones',$arraylistacrmacciones,array('class'=>'form-control')); ?>
							<?php echo $form->error($modelCrmdetalles,'id_crm_acciones'); ?>
						</div>
						<div class="col-md-6">
							<?php echo $form->labelEx($modelCrmdetalles,'crm_detalles_fecha'); ?>
							<?php Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
							    $this->widget('CJuiDateTimePicker',array(
							        'model'=>$modelCrmdetalles, //Model object
							        'attribute'=>'crm_detalles_fecha', //attribute name
							        'mode'=>'datetime', //use "time","date" or "datetime" (default)
							        'options'=>array(
							        		'dateFormat'=>'yy-mm-dd',
							        	), // jquery plugin options
							        'htmlOptions'=>array('class'=>'form-control','readonly'=>'true','value'=>date('Y-m-d H:i:s')),
							    ));
							?>
							<?php echo $form->error($modelCrmdetalles,'crm_detalles_fecha'); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<?php echo $form->labelEx($modelCrmdetalles,'crm_detalles_comentarios'); ?>
							<?php echo $form->textArea($modelCrmdetalles,'crm_detalles_comentarios',array('class'=>'form-control')); ?>
							<?php echo $form->error($modelCrmdetalles,'crm_detalles_comentarios'); ?>
						</div>
					</div>
					
					<div class="row buttons">
						<div class="col-md-12 center mt-md">
							<?php echo CHtml::submitButton('Agregar',array('class'=>'btn btn-success')); ?>
							<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

						</div>
					</div>
				<?php $this->endWidget(); ?>
				</div><!-- form -->
			</div>
		</div>
	</div>
</div>
