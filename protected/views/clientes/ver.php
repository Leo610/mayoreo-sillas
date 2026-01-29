<?php
/* @var $this ClientesController */
$this->pageTitle=$datoscliente->cliente_nombre;

$this->breadcrumbs=array(
	'Clientes'=>array('/clientes'),
	'Ver',
);
?>
<?php 
// Incluimos el modal de clientes, de otra vista
Yii::app()->controller->renderFile(Yii::app()->basePath.'/views/clientes/modal/_form.php',
		array(
			'model'=>$model,
			'arraylistaprecios'=>$arraylistaprecios
		));

?>

<div class="row">
	<div class="col-md-3">
		<fieldset>
			<legend>Datos</legend>
		<p class="mb-none">
			Nombre:<br><strong><?=$datoscliente->cliente_nombre; ?></strong><br>
			Teléfono:<br><strong><?=$datoscliente->cliente_telefono; ?></strong><br>
			Email:<br><strong><?=$datoscliente->cliente_email; ?></strong><br>
			Razón Social:<br><strong><?=$datoscliente->cliente_razonsocial; ?></strong><br>
			RFC:<br><strong><?=$datoscliente->cliente_rfc; ?></strong><br>
			Calle:<br><strong><?=$datoscliente->cliente_calle; ?></strong><br>
			Colonia:<br><strong><?=$datoscliente->cliente_colonia; ?></strong><br>
			Número Interior:<br><strong><?=$datoscliente->cliente_numeroexterior; ?> <?=$datoscliente->cliente_numerointerior; ?></strong><br>
			Código Postal:<br><strong><?=$datoscliente->cliente_codigopostal; ?></strong><br>
			Municipio:<br><strong><?=$datoscliente->cliente_municipio; ?></strong><br>
			Entidad:<br><strong><?=$datoscliente->cliente_entidad; ?></strong><br>
			País:<br><strong><?=$datoscliente->cliente_pais; ?></strong><br>
		</p>
		<?php
			echo CHtml::link('<i class="fa fa-pencil fa-lg"></i> Editar', "javascript:;", array(
	          'style' => 'cursor: pointer;',
	          "onclick" => "Actualizar(".$datoscliente->id_cliente."); return false;",
	          "class"=>'btn btn-primary mt-none btn-sm pull-right'
	    ));
		?>
		</fieldset>
	</div>
	<div class="col-md-9">
			<a href="<?php echo Yii::app()->createUrl('cotizaciones/crear/'.$datoscliente->id_cliente)?>" class="btn btn-secondary pull-right">
			   <i class="fa fa-list-ol"></i> Crear cotización
			</a>
		<div class="col-md-12">
			<fieldset>
				<legend>Agregar acción</legend>
					<div class="form">

				
				<?php $form=$this->beginWidget('CActiveForm', array(
					'id'=>'agregaraccion-form',
					'action'=>Yii::app()->createUrl('crmdetalles/Crearaccion'),
					'enableClientValidation'=>true,
					'clientOptions' => array('validateOnSubmit'=>true, 'validateOnType'=>true),
				)); ?>
				 

				<?php echo CHtml::errorSummary(array($modelCrmdetalles)); ?>

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
							       'options'=>array('dateFormat'=>'yy-mm-dd',), // jquery plugin options
							        'htmlOptions'=>array('class'=>'form-control'),
							        
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
							<?php echo $form->hiddenField($modelCrmdetalles,'id_cliente',array('class'=>'form-control','value'=>$datoscliente->id_cliente)); ?>
					
					
					<div class="row buttons mt-sm">
						<div class="col-md-12">
							<?php echo CHtml::submitButton('Agregar',array('class'=>'btn btn-secondary btn-sm pull-right')); ?>
						</div>
					</div>
				<?php $this->endWidget(); ?>
				</div><!-- form -->
    </div>
    <div class="col-md-12">
  		<fieldset>
				<legend style="margin-bottom:10px;">Lista de acciones del cliente</legend>
				<div class="table-responsive">
	      <table id="listadetallescliente" class="table table-striped table-bordered">
	          <thead>
	              <tr>
	                  <th>ID</th>
	                  <th>Acción</th> 
	                  <th style="width:50%;">Comentarios</th>                 
	                  <th>Fecha</th>
	                 	<th>Agente</th>
	              </tr>
	          </thead>
	          <tbody>
	          		<?php 
	          		foreach ($listadetallescliente as $rows){ ?>
	              <tr>
	              		<td><?=$rows->id_crm_detalle?></td>
	                  <td><?=$rows->rl_crmaccion->crm_acciones_nombre?></td>
	                  <td><?=$rows->crm_detalles_comentarios?></td>
	                  <td><?=$rows->crm_detalles_fecha?></td>
	                  <td><?=$rows->rl_usuarios->Usuario_Nombre?></td>
	                 
	             </tr>
	             <?php } ?>
	         </tbody>
	      </table>
	      </div>
    	</fieldset>
    </div>
    <div class="col-md-12">
  		<fieldset>
				<legend style="margin-bottom:10px;">Lista de cotizaciones</legend>
				<div class="table-responsive">
	      <table id="listacotizaciones" class="table table-striped table-bordered">
	          <thead>
	              <tr>
	                  <th>Num</th>
	                  <th>Subtotal</th> 
	                  <th>Iva</th> 
	                  <th>Total</th> 
	                  <th>Agente</th>
	                  <th>Fecha Alta</th>
	                  <th></th>
	              </tr>
	          </thead>
	          <tbody>
	          		<?php 
	          		foreach ($listacotizaciones as $rows){ ?>
	              <tr>
	              		<td><?=$rows->id_cotizacion?></td>
	              		<td><?=$rows->cotizacion_total?></td>
	              		<td><?=($rows->cotizacion_total)*.16?></td>
	                  <td><?=$rows->cotizacion_total*1.16?></td>
	                  <td><?=$rows->rl_usuarios->Usuario_Nombre?></td>
	                  <td><?=$rows->cotizacion_fecha_alta?></td>
	                  <td>
	                  	<?php 
                   	    echo CHtml::link('<i class="fa fa-search"></i> Ver',array('cotizaciones/ver/'.$rows->id_cotizacion),array('class'=>"btn btn-secondary"));
    									?> - 
    									<?php 
                   	    echo CHtml::link('<i class="fa fa-file-pdf-o"></i>
 Ver',array('cotizaciones/pdf/'.$rows->id_cotizacion),array('class'=>"btn btn-danger",'target'=>'_blank'));
    									?>
    									 - 
    									<?php 
                   	     echo CHtml::link('<i class="fa fa-check"></i> Crear proyecto', "javascript:;", array(
                   	     				'class'=> 'btn btn-success',
                                'style' => 'cursor: pointer;',
                                "onclick" => "CrearProyecto(".$rows->id_cotizacion."); return false;"
                            ));
    									?>
	                  </td>
	                 
	             </tr>
	             <?php } ?>
	         </tbody>
	      </table>
	      </div>
    	</fieldset>
    </div>
  </div>

