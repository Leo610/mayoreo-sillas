<?php
/* @var $this ProductosController */
?>
<script type="text/javascript">
	$( document ).ready(function() {
		$('#btn-calcular').click(function() {
			$('#camposcalcular').toggle( "slow", function() {
			    // Animation complete.
			});
			//
			
		});
	});
	
</script>

<div class="modal fade" id="formmodal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="formModalLabel">Productos Formulario</h4>
			</div>
			<div class="modal-body">
				<div class="form">
					<?php $form=$this->beginWidget('CActiveForm', array(
						'id'=>'Productos-form',
						'action'=>Yii::app()->createUrl('Productos/Createorupdate'),
						'enableClientValidation'=>true,
						'clientOptions' => array('validateOnSubmit'=>true, 'validateOnType'=>true),
						'htmlOptions' => array(
					        'enctype' => 'multipart/form-data',
					    ),
					)); ?>

						<div class="row">
							<div class="col-md-12">
							<p class="note">Llene los campos a continuación para dar de alta</p>
								<?php echo $form->errorSummary($model); ?>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-6">
								<?php echo $form->labelEx($model,'producto_nombre'); ?>
								<?php echo $form->textField($model,'producto_nombre',array('class'=>'form-control')); ?>
								<?php echo $form->error($model,'producto_nombre'); ?>
							</div>
							<div class="col-md-6">
								<?php echo $form->labelEx($model,'producto_clave'); ?>
								<?php echo $form->textField($model,'producto_clave',array('class'=>'form-control')); ?>
								<?php echo $form->error($model,'producto_clave'); ?>
							</div>
						</div>
						<div class="row" id="camposcalcular" style="display:none;">
							<div class="col-md-4">
								<label>Largo</label>
								<input type="text" name="" id="f-largo" class="form-control" onchange="calcularprecioventa()">
							</div>
							<div class="col-md-4">
								<label>Complejidad</label>
								<input type="text" name="" id="f-complejidad" class="form-control" onchange="calcularprecioventa()">
							</div>
							<div class="col-md-4">
								<label>Costo personal</label>
								<input type="text" name="" id="f-cpersonal" class="form-control" onchange="calcularprecioventa()">
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<?php echo $form->labelEx($model,'producto_imagen'); ?>
								<?php echo $form->FileField($model,'producto_imagen',array('class'=>'form-control')); ?>
								<?php echo $form->error($model,'producto_imagen'); ?>
							</div>
							<div class="col-md-6">
								<?php echo $form->labelEx($model,'id_categoria'); ?>
								<?php echo $form->dropDownList($model,'id_categoria', $listacategorias, array('empty'=>'-- Seleccione --','class'=>'form-control')); ?>
								<?php echo $form->error($model,'id_categoria'); ?>
							 </div>
						  </div>
						<div class="row">
						  <div class="col-md-4">
								<?php echo $form->labelEx($model,'id_subcategoria'); ?>
								<?php echo $form->dropDownList($model,'id_subcategoria', $listasubcategoria, array('empty'=>'-- Seleccione --','class'=>'form-control')); ?>
								<?php echo $form->error($model,'id_subcategoria'); ?>
							 </div>
						  <!--<div class="col-md-4">-->
>								<?php //echo $form->labelEx($model,'id_grupo'); ?>
								<?php //echo $form->dropDownList($model,'id_grupo', $listagrupo, array('empty'=>'-- Seleccione --','class'=>'form-control')); ?>
								<?php //echo $form->error($model,'id_grupo'); ?>
						 <!--</div>
						  <div class="col-md-4">-->
								<?php //echo $form->labelEx($model,'id_subgrupo'); ?>
								<?php //echo $form->dropDownList($model,'id_subgrupo', $listasubgrupo, array('empty'=>'-- Seleccione --','class'=>'form-control')); ?>
								<?php //echo $form->error($model,'id_subgrupo'); ?>
							<!-- </div>
						  </div>
						  <div class="row">
						  <div class="col-md-4">-->
								<?php //echo $form->labelEx($model,'id_linea'); ?>
								<?php //echo $form->dropDownList($model,'id_linea', $listalinea, array('empty'=>'-- Seleccione --','class'=>'form-control')); ?>
								<?php //echo $form->error($model,'id_linea'); ?>
							 <!--</div>
						  <div class="col-md-4">-->
								<?php //echo $form->labelEx($model,'id_sublinea'); ?>
								<?php //echo $form->dropDownList($model,'id_sublinea', $listasublinea, array('empty'=>'-- Seleccione --','class'=>'form-control')); ?>
								<?php //echo $form->error($model,'id_sublinea'); ?>
							 <!-- </div>
						  <div class="col-md-4">-->
								<?php //echo $form->labelEx($model,'id_segmento'); ?>
								<?php //echo $form->dropDownList($model,'id_segmento', $listasegmento, array('empty'=>'-- Seleccione --','class'=>'form-control')); ?>
								<?php //echo $form->error($model,'id_segmento'); ?>
							 <!--</div>
						  </div>
						  <div class="row">
						  <div class="col-md-4">-->
								<?php //echo $form->labelEx($model,'id_subsegmento'); ?>
								<?php //echo $form->dropDownList($model,'id_subsegmento', $listasubsegmento, array('empty'=>'-- Seleccione --','class'=>'form-control')); ?>
								<?php //echo $form->error($model,'id_subsegmento'); ?>
							  <!--</div>
						  <div class="col-md-4">-->
								<?php //echo $form->labelEx($model,'id_marca'); ?>
								<?php //echo $form->dropDownList($model,'id_marca', $listamarca, array('empty'=>'-- Seleccione --','class'=>'form-control')); ?>
								<?php //echo $form->error($model,'id_marca'); ?>
							 <!--</div>
						  <div class="col-md-4">-->
								<?php //echo $form->labelEx($model,'id_submarca'); ?>
								<?php //echo $form->dropDownList($model,'id_submarca', $listasubmarca, array('empty'=>'-- Seleccione --','class'=>'form-control')); ?>
								<?php //echo $form->error($model,'id_submarca'); ?>
							 <!--</div>
						  </div>
						  <div class="row">
						  <div class="col-md-4">-->
								<?php //echo $form->labelEx($model,'id_modelo'); ?>
								<?php //echo $form->dropDownList($model,'id_modelo', $listamodelo, array('empty'=>'-- Seleccione --','class'=>'form-control')); ?>
								<?php //echo $form->error($model,'id_modelo'); ?>
							 <!--</div>-->
						  <div class="col-md-4">
								<?php echo $form->labelEx($model,'id_submodelo'); ?>
								<?php echo $form->dropDownList($model,'id_submodelo', $listasubmodelo, array('empty'=>'-- Seleccione --','class'=>'form-control')); ?>
								<?php echo $form->error($model,'id_submodelo'); ?>
							 </div>
						  <div class="col-md-4">
								<?php echo $form->labelEx($model,'id_posicion'); ?>
								<?php echo $form->dropDownList($model,'id_posicion', $listaposicion, array('empty'=>'-- Seleccione --','class'=>'form-control')); ?>
								<?php echo $form->error($model,'id_posicion'); ?>
							 </div>
						  </div>
						  <div class="row">
						  <div class="col-md-4">
								<?php echo $form->labelEx($model,'id_subposicion'); ?>
								<?php echo $form->dropDownList($model,'id_subposicion', $listasubposicion, array('empty'=>'-- Seleccione --','class'=>'form-control')); ?>
								<?php echo $form->error($model,'id_subposicion'); ?>
							 </div>
						  <div class="col-md-4">
								<?php echo $form->labelEx($model,'id_proveedor'); ?>
								<?php echo $form->dropDownList($model,'id_proveedor', $ListaProveedor, array('empty'=>'-- Seleccione --','class'=>'form-control')); ?>
								<?php echo $form->error($model,'id_proveedor'); ?>
							 </div>
							 <div class="col-md-4">
								<?php echo $form->labelEx($model,'id_unidades_medida'); ?>
								<?php echo $form->dropDownList($model,'id_unidades_medida', $arraylistaunidadesmedidas, array('empty'=>'-- Seleccione --','class'=>'form-control')); ?>
								<?php echo $form->error($model,'id_unidades_medida'); ?>
							 </div>
						  </div>

						<div class="row">
							<div class="col-md-12">
								<?php echo $form->labelEx($model,'producto_descripcion'); ?>
								<?php echo $form->textArea($model,'producto_descripcion',array('class'=>'form-control')); ?>
								<?php echo $form->error($model,'producto_descripcion'); ?>
							</div>
						</div>
				
						
						
						 <div class="row">
							<div class="col-md-12" style="display:none;" id="imagendiv">
								<img id="imagenpro" style="max-height:45px;margin-top:5px">
							</div>
						</div>
						<div class="row buttons">
		<div class="col-md-12 center">
			<hr>
			<?php echo CHtml::submitButton('Guardar',array('class'=>'btn btn-success')); ?>
			<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
		</div>
	</div>
						<?php echo $form->hiddenField($model,'id_producto'); ?>
					<input type="hidden" name="imagenoriginal" id="imagenoriginal" >
					<?php $this->endWidget(); ?>
					</div><!-- form -->
			</div>
			
		</div>
	</div>
</div>

