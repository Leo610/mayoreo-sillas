<?php
/* @var $this SliderController */
?>

<div class="modal fade" id="formmodaleditar" tabindex="-1" role="dialog" aria-labelledby="formModalLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="formModalLabel">Usuarios Formulario</h4>
			</div>
			<div class="modal-body">
				<div class="form">

					<?php $form = $this->beginWidget(
						'CActiveForm',
						array(
							'id' => 'usuarioseditar-form',
							'action' => Yii::app()->createUrl('Usuarios/Createorupdate'),
							'enableClientValidation' => true,
							'clientOptions' => array('validateOnSubmit' => true, 'validateOnType' => true),
						)
					); ?>

					<div class="row">
						<div class="col-md-12">
							<p class="note">Llene los campos a continuación para editar un usuario</p>
							<?php echo $form->errorSummary($model); ?>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<?php echo $form->labelEx($model, 'Usuario_Nombre'); ?>
							<?php echo $form->textField($model, 'Usuario_Nombre', array('class' => 'form-control')); ?>
							<?php echo $form->error($model, 'Usuario_Nombre'); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<?php echo $form->labelEx($model, 'Usuario_Email'); ?>
							<?php echo $form->textField($model, 'Usuario_Email', array('class' => 'form-control')); ?>
							<?php echo $form->error($model, 'Usuario_Email'); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<?php echo $form->labelEx($model, 'tel'); ?>
							<?php echo $form->textField($model, 'tel', array('class' => 'form-control')); ?>
							<?php echo $form->error($model, 'tel'); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<?php echo $form->labelEx($model, 'id_perfil'); ?>
							<?php echo $form->dropDownList($model, 'id_perfil', $ListaPerfiles, array('empty' => '-- Seleccione --', 'class' => 'form-control')); ?>
							<?php echo $form->error($model, 'id_perfil'); ?>
						</div>
						<div class="col-md-4">
							<?php echo $form->labelEx($model, 'zona'); ?>
							<?php echo $form->dropDownList($model, 'zona', $listazona, array('empty' => '-- Seleccione --', 'class' => 'form-control')); ?>
							<?php echo $form->error($model, 'zona'); ?>
						</div>
						<div class="col-md-4">
							<?php echo $form->labelEx($model, 'id_usuario_padre'); ?>
							<?php echo $form->dropDownList($model, 'id_usuario_padre', $ListaUsuarios, array('empty' => '-- Seleccione --', 'class' => 'form-control')); ?>
							<?php echo $form->error($model, 'id_usuario_padre'); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<?php echo $form->labelEx($model, 'equipo_venta'); ?>
							<?php echo $form->dropDownList($model, 'equipo_venta', $listaequipo, array('empty' => '-- Seleccione --', 'class' => 'form-control')); ?>
							<?php echo $form->error($model, 'equipo_venta'); ?>
						</div>
						<div class="col-md-4">
							<?php echo $form->labelEx($model, 'mercado'); ?>
							<?php echo $form->dropDownList($model, 'mercado', $listamercado, array('empty' => '-- Seleccione --', 'class' => 'form-control')); ?>
							<?php echo $form->error($model, 'mercado'); ?>
						</div>
						<div class="col-md-4">
							<?php echo $form->labelEx($model, 'ubicacion'); ?>
							<?php echo $form->dropDownList($model, 'ubicacion', $listaubicacion, array('empty' => '-- Seleccione --', 'class' => 'form-control')); ?>
							<?php echo $form->error($model, 'ubicacion'); ?>
						</div>
						<div class="col-md-4">
							<?php echo $form->labelEx($model, 'bodega'); ?>
							<?php echo $form->dropDownList($model, 'bodega', $bodega, array('empty' => '-- Seleccione --', 'class' => 'form-control')); ?>
							<?php echo $form->error($model, 'bodega'); ?>
						</div>
					</div>

					<div class="row buttons">
						<div class="col-md-12 center">
							<hr>
							<?php echo CHtml::submitButton('Guardar', array('class' => 'btn btn-success')); ?>
							<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
						</div>
					</div>
					<?php echo $form->hiddenField($model, 'ID_Usuario'); ?>
					<?php $this->endWidget(); ?>
				</div><!-- form -->



			</div>
		</div>
	</div>
</div>