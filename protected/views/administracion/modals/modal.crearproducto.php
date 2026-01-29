
<div class="modal fade" id="formmodalcrearproducto" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			
			<div class="modal-body">
				<div class="form">

					<?php $form=$this->beginWidget('CActiveForm', array(
						'id'=>'ClientesProductos-form',
						'action'=>Yii::app()->createUrl('Clientes_productos/Crear'),
						'enableClientValidation'=>true,
						'clientOptions' => array('validateOnSubmit'=>true, 'validateOnType'=>true),
					)); ?>

						<div class="row">
							<div class="col-md-12">
							<p class="note mb-none">Agregar producto al prospecto</p>
								<?php echo $form->errorSummary($agregarproducto); ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?php echo $form->labelEx($agregarproducto,'id_producto'); ?>
								 <?php 
					    			$this->widget('zii.widgets.jui.CJuiAutoComplete',array(
						              'name'=>'id_producto_auto',
						              'source'=>$this->createUrl('productos/Productosajax'),
						            	// Opciones javascript adicionales para el plugin
						              'options'=>array(
						                  'minLength'=>'3',
						                  'select'=>'js:function(event, ui) {
							                  	$("#ClientesProductos_id_producto").val(ui.item.id);
							                  	$("#id_producto_auto").val(ui.item.value);
						                 	}',
						                  'focus'=>'js:function(event, ui) {
						                      return false;
						                  }'
						              ),
						              'htmlOptions'=>array(
						                  'class'=>'form-control',
						                  'placeholder'=>'Busqueda del producto'
						              )
						            ));
						          ?>
						        <?php echo $form->hiddenField($agregarproducto,'id_producto'); ?>
						        <?php echo $form->error($agregarproducto,'id_producto'); ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?php echo $form->labelEx($agregarproducto,'comentarios'); ?>
								<?php echo $form->textArea($agregarproducto,'comentarios',array('class'=>'form-control')); ?>
								<?php echo $form->error($agregarproducto,'comentarios'); ?>
							</div>
						</div>
						<?php echo $form->hiddenField($agregarproducto,'id_cliente',array('value'=>$datoscliente->id_cliente)); ?>
						
						
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

