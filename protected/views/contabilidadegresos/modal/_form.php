<?php
/* @var $this AlmacenesController */
?>

<div class="modal fade" id="formmodal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="formModalLabel">Agregar pago</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						Nombre: <input type="text" id="nombre" readonly="true" class="form-control">
						Total: <input type="text" id="total" readonly="true" class="form-control">
						Total pagado: <input type="text" id="totalpagado" readonly="true" class="form-control">
						Total pendiente: <input type="text" id="totalpendiente" readonly="true" class="form-control">
					</div>
				</div>
				<?php
				/* @var $this ContabilidadegresosController */
				/* @var $model Contabilidadegresos */
				/* @var $form CActiveForm */
				?>

				<div class="form">

				<?php $form=$this->beginWidget('CActiveForm', array(
					'id'=>'contabilidadegresos-form',
					'action'=>Yii::app()->createUrl('Contabilidadegresos/Agregar'),
					'enableClientValidation'=>true,
					'clientOptions' => array('validateOnSubmit'=>true, 'validateOnType'=>true),
				)); ?>

				    <?php echo $form->errorSummary($model); ?>

				    <?php echo $form->error($model,'contabilidad_egresos_identificador'); ?>

				    <div class="row">
				    	<div class="col-md-4">
				        <?php echo $form->labelEx($model,'id_formapago'); ?>
				        <?php echo $form->dropDownList($model,'id_formapago', $ListaFormasPago, array('class'=>'form-control')); ?>
				        <?php echo $form->error($model,'id_formapago'); ?>
				      </div>
				      <div class="col-md-4">
				        <?php echo $form->labelEx($model,'id_banco'); ?>
				        <?php echo $form->dropDownList($model,'id_banco', $ListaBancos, array('class'=>'form-control')); ?>
				        <?php echo $form->error($model,'id_banco'); ?>
				      </div>
				      <div class="col-md-4">
				        <?php echo $form->labelEx($model,'id_moneda'); ?>
				        <select name="id_moneda_editable" id="id_moneda_editable" class="form-control">
				       	<?php foreach($ListaMonedas as $key=>$value)
				       	{?>
				       		<option value="<?=$key?>"><?=$value?></option>
				       	<?php }?>
				        </select>
				        <?php echo $form->hiddenField($model,'id_moneda', array('class'=>'form-control')); ?>
				        <?php echo $form->error($model,'id_moneda'); ?>
				      </div>
				      
				    </div>

				    <div class="row">
				    	<div class="col-md-12">
				        <?php echo $form->labelEx($model,'contabilidad_egresos_cantidad'); ?>
				        <?php echo $form->textField($model,'contabilidad_egresos_cantidad', array('class'=>'form-control')); ?>
				        <?php echo $form->error($model,'contabilidad_egresos_cantidad'); ?>
				      </div>
				    </div>

				    <?php echo $form->hiddenField($model,'contabilidad_egresos_identificador'); ?>

				    <div class="row mt-lg">
				    	<div class="col-md-12">
				        <?php echo CHtml::submitButton('Agregar pago',array('class'=>'btn btn-primary')); ?>
				      </div>
				    </div>

				<?php $this->endWidget(); ?>

				</div><!-- form -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>
