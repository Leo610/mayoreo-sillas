
<div class="modal fade" id="modalagregaragente" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">
			
			<div class="modal-body">
				<div class="form">

					<?php $form=$this->beginWidget('CActiveForm', array(
						'id'=>'agentesform',
						'action'=>Yii::app()->createUrl('crmoportunidadesinvolucrados/Crear'),
						'enableClientValidation'=>true,
						'clientOptions' => array('validateOnSubmit'=>true, 'validateOnType'=>true),
					)); ?>

						<div class="row">
							<div class="col-md-12">
							<p class="note mb-none">Agregar agentes a la oportunidad</p>
								<?php echo $form->errorSummary($agregarCOI); ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?php echo $form->labelEx($agregarCOI,'id_usuario'); ?>
								<?php echo $form->dropDownList($agregarCOI,'id_usuario', $ListaAgentes, array('empty'=>'-- Seleccione --','class'=>'form-control')); ?>
						        <?php echo $form->error($agregarCOI,'id_usuario'); ?>
							</div>
						</div>
						<?php echo $form->hiddenField($agregarCOI,'id_oportunidad',array('value'=>$DatosOportunidad['id'])); ?>
						
						
							<div class="row buttons">
								<div class="col-md-12 mt-md center">
									<?php echo CHtml::submitButton('Guardar',array('class'=>'btn btn-success')); ?>
									<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
								</div>
							</div>
					<?php $this->endWidget(); ?>
				</div><!-- form -->
			</div>
		</div>
	</div>
</div>

