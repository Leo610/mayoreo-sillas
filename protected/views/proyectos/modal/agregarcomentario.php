<?php
/* @var $this ProveedoresController */
?>
<script type="text/javascript">
	$(document).ready(function () {

		/* METODO PARA ABRIR UN MODAL*/
		$(".agregarcomentario").click(function () {
			$('#formmodalcomentarios').modal('show');
		});
	});
</script>

<div class="modal fade" id="formmodalcomentarios" tabindex="-1" role="dialog" aria-labelledby="formModalLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="formModalLabel">Observaciones para remision</h4>
			</div>
			<div class="modal-body">
				<div class="form">

					<?php $form = $this->beginWidget(
						'CActiveForm',
						array(
							'id' => 'agregarcomentarioform',
							'action' => Yii::app()->createUrl('proyectos/agregarcomentario'),
							'enableClientValidation' => true,
							'clientOptions' => array('validateOnSubmit' => true, 'validateOnType' => true),
						)
					); ?>

					<!-- <div class="row">
						<div class="col-md-12">
							<p class="note">Llene los campos a continuación para dar de alta</p>
							<?php echo $form->errorSummary($Proyectoscomentarios); ?>
						</div>
					</div> -->

					<div class="row">
						<!-- <div class="col-md-12">
							<?php //echo $form->labelEx($Proyectoscomentarios, 'nombre'); ?>
							<?php //echo $form->textField($Proyectoscomentarios, 'nombre', array('class' => 'form-control')); ?>
							<?php //echo $form->error($Proyectoscomentarios, 'nombre'); ?>
						</div> -->
						<div class="col-md-12">
							<!-- <?php //echo $form->labelEx($Proyectoscomentarios, 'descripcion'); ?>
						 -->
							<label for="">Observaciones</label>
							<?php echo $form->textarea($Proyectoscomentarios, 'descripcion', array('class' => 'form-control')); ?>
							<?php echo $form->error($Proyectoscomentarios, 'descripcion'); ?>
						</div>
					</div>

					<div class="row buttons">
						<div class="col-md-12 center">
							<hr>
							<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
							<?php echo CHtml::submitButton('Guardar', array('class' => 'btn btn-success ')); ?>
						</div>
					</div>
					<?php echo $form->hiddenField($Proyectoscomentarios, 'id_usuario'); ?>
					<?php echo $form->hiddenField($Proyectoscomentarios, 'id_proyecto'); ?>
					<?php $this->endWidget(); ?>
				</div><!-- form -->
			</div>
		</div>
	</div>
</div>