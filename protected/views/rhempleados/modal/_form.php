<?php
/* @var $this RhempleadosController */
?>

<div class="modal fade" id="formmodal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="formModalLabel">Empleado Formulario</h4>
			</div>
			<div class="modal-body">
				<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'Empleados-form',
	'action'=>Yii::app()->createUrl('Rhempleados/Createorupdate'),
	'enableClientValidation'=>true,
	'clientOptions' => array('validateOnSubmit'=>true, 'validateOnType'=>true),
)); ?>

	<div class="row">
		<div class="col-md-12">
		<p class="note">Llene los campos a continuacion para dar de alta</p>
			<?php echo $form->errorSummary($model); ?>
		</div>
	</div>

	<div class="row">
		<div class="col-md-3">
			<?php echo $form->labelEx($model,'empleado_num_reloj'); ?>
			<?php echo $form->textField($model,'empleado_num_reloj',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'empleado_num_reloj'); ?>
		</div>
		<div class="col-md-3">
			<?php echo $form->labelEx($model,'empleado_num_empleado'); ?>
			<?php echo $form->textField($model,'empleado_num_empleado',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'empleado_num_empleado'); ?>
		</div>
		<div class="col-md-6">
			<?php echo $form->labelEx($model,'empleado_nombre'); ?>
			<?php echo $form->textField($model,'empleado_nombre',array('class'=>'form-control','placeholder'=>'Nombre completo del empleado')); ?>
			<?php echo $form->error($model,'empleado_nombre'); ?>
		</div>
	</div>

	<div class="row">
		<div class="col-md-3">
			<?php echo $form->labelEx($model,'empleado_fecha_ingreso'); ?>
			<?php echo $form->dateField($model,'empleado_fecha_ingreso',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'empleado_fecha_ingreso'); ?>
		</div>
		<div class="col-md-2">
			<?php echo $form->labelEx($model,'empleado_estatus'); ?>
			<?php echo $form->dropDownList($model,'empleado_estatus', array('ACTIVO'=>'ACTIVO','INACTIVO'=>'INACTIVO'), array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'empleado_estatus'); ?>
		</div>
		<div class="col-md-3">
			<?php echo $form->labelEx($model,'empleado_seguro_social'); ?>
			<?php echo $form->textField($model,'empleado_seguro_social',array('class'=>'form-control','placeholder'=>'NSS')); ?>
			<?php echo $form->error($model,'empleado_seguro_social'); ?>
		</div>
		<div class="col-md-4">
			<?php echo $form->labelEx($model,'empleado_rfc'); ?>
			<?php echo $form->textField($model,'empleado_rfc',array('class'=>'form-control','placeholder'=>'RFC')); ?>
			<?php echo $form->error($model,'empleado_rfc'); ?>
			<div id="alertaRfcExiste" style="display:none; margin-top:5px;"></div>
		</div>
		<script>
		$(function(){
			$('#Empleados_empleado_rfc').on('blur change', function(){
				var rfc = $(this).val().trim();
				var idActual = $('#Empleados_id_empleado').val();
				$('#alertaRfcExiste').hide().html('');
				if(rfc.length < 10) return;
				$.ajax({
					url: "<?php echo Yii::app()->createUrl('rhempleados/buscarrfc'); ?>",
					type: "POST",
					dataType: "json",
					data: { rfc: rfc },
					success: function(r){
						if(r.encontrado && r.id_empleado != idActual){
							$('#alertaRfcExiste').html(
								'<div class="alert alert-warning" style="padding:6px 10px; margin:0; font-size:12px;">' +
								'<strong>Este empleado ya existe:</strong> ' + r.nombre + ' (' + r.estatus + ')' +
								' <a href="' + r.url_detalle + '" class="btn btn-xs btn-primary" target="_blank">Ver detalle</a>' +
								'</div>'
							).show();
						}
					}
				});
			});
		});
		</script>
	</div>

	<div class="row">
		<div class="col-md-4">
			<?php echo $form->labelEx($model,'empleado_puesto'); ?>
			<?php echo $form->textField($model,'empleado_puesto',array('class'=>'form-control','placeholder'=>'Puesto')); ?>
			<?php echo $form->error($model,'empleado_puesto'); ?>
		</div>
		<div class="col-md-4">
			<?php echo $form->labelEx($model,'empleado_tarjeta_efectivale'); ?>
			<?php echo $form->dropDownList($model,'empleado_tarjeta_efectivale', array(''=>'-- Seleccione --','ASIGNADA'=>'ASIGNADA','NO ASIGNADA'=>'NO ASIGNADA'), array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'empleado_tarjeta_efectivale'); ?>
		</div>
		<div class="col-md-4">
			<?php echo $form->labelEx($model,'empleado_habitante_casa'); ?>
			<?php echo $form->textField($model,'empleado_habitante_casa',array('class'=>'form-control','placeholder'=>'Ej: LAS MALVINAS, LOS ALTOS, NO')); ?>
			<?php echo $form->error($model,'empleado_habitante_casa'); ?>
		</div>
	</div>

	<div class="row">
		<div class="col-md-2">
			<?php echo $form->labelEx($model,'empleado_sueldo_semanal'); ?>
			<?php echo $form->textField($model,'empleado_sueldo_semanal',array('class'=>'form-control','placeholder'=>'0.00')); ?>
			<?php echo $form->error($model,'empleado_sueldo_semanal'); ?>
		</div>
		<div class="col-md-2">
			<?php echo $form->labelEx($model,'empleado_sueldo_semanal_real'); ?>
			<?php echo $form->textField($model,'empleado_sueldo_semanal_real',array('class'=>'form-control','placeholder'=>'0.00')); ?>
			<?php echo $form->error($model,'empleado_sueldo_semanal_real'); ?>
		</div>
		<div class="col-md-2">
			<?php echo $form->labelEx($model,'empleado_sueldo_diario'); ?>
			<?php echo $form->textField($model,'empleado_sueldo_diario',array('class'=>'form-control','placeholder'=>'0.00')); ?>
			<?php echo $form->error($model,'empleado_sueldo_diario'); ?>
		</div>
		<div class="col-md-2">
			<?php echo $form->labelEx($model,'empleado_integrado'); ?>
			<?php echo $form->textField($model,'empleado_integrado',array('class'=>'form-control','placeholder'=>'0.00')); ?>
			<?php echo $form->error($model,'empleado_integrado'); ?>
		</div>
		<div class="col-md-2">
			<?php echo $form->labelEx($model,'empleado_costo_hora'); ?>
			<?php echo $form->textField($model,'empleado_costo_hora',array('class'=>'form-control','placeholder'=>'0.00')); ?>
			<?php echo $form->error($model,'empleado_costo_hora'); ?>
		</div>
	</div>

	<div class="row">
		<div class="col-md-3">
			<?php echo $form->labelEx($model,'empleado_bono_asistencia'); ?>
			<?php echo $form->textField($model,'empleado_bono_asistencia',array('class'=>'form-control','placeholder'=>'0.00')); ?>
			<?php echo $form->error($model,'empleado_bono_asistencia'); ?>
		</div>
		<div class="col-md-3">
			<?php echo $form->labelEx($model,'empleado_bono_puntualidad'); ?>
			<?php echo $form->textField($model,'empleado_bono_puntualidad',array('class'=>'form-control','placeholder'=>'0.00')); ?>
			<?php echo $form->error($model,'empleado_bono_puntualidad'); ?>
		</div>
		<div class="col-md-3">
			<?php echo $form->labelEx($model,'empleado_bono_productividad'); ?>
			<?php echo $form->textField($model,'empleado_bono_productividad',array('class'=>'form-control','placeholder'=>'0.00')); ?>
			<?php echo $form->error($model,'empleado_bono_productividad'); ?>
		</div>
		<div class="col-md-3">
			<?php echo $form->labelEx($model,'empleado_bono_condicional'); ?>
			<?php echo $form->textField($model,'empleado_bono_condicional',array('class'=>'form-control','placeholder'=>'0.00')); ?>
			<?php echo $form->error($model,'empleado_bono_condicional'); ?>
		</div>
	</div>

	<div class="row">
		<div class="col-md-3">
			<?php echo $form->labelEx($model,'empleado_dias_tomados'); ?>
			<?php echo $form->textField($model,'empleado_dias_tomados',array('class'=>'form-control','placeholder'=>'0')); ?>
			<?php echo $form->error($model,'empleado_dias_tomados'); ?>
		</div>
		<div class="col-md-3">
			<?php echo $form->labelEx($model,'empleado_dias_pendientes'); ?>
			<?php echo $form->textField($model,'empleado_dias_pendientes',array('class'=>'form-control','placeholder'=>'0')); ?>
			<?php echo $form->error($model,'empleado_dias_pendientes'); ?>
		</div>
		<div class="col-md-3">
			<?php echo $form->labelEx($model,'empleado_dias_prima_vacacional'); ?>
			<?php echo $form->textField($model,'empleado_dias_prima_vacacional',array('class'=>'form-control','placeholder'=>'0')); ?>
			<?php echo $form->error($model,'empleado_dias_prima_vacacional'); ?>
		</div>
	</div>

	<div class="row">
		<div class="col-md-3">
			<?php echo $form->labelEx($model,'empleado_imss'); ?>
			<?php echo $form->textField($model,'empleado_imss',array('class'=>'form-control','placeholder'=>'0.00')); ?>
			<?php echo $form->error($model,'empleado_imss'); ?>
		</div>
		<div class="col-md-3">
			<?php echo $form->labelEx($model,'empleado_infonavit'); ?>
			<?php echo $form->textField($model,'empleado_infonavit',array('class'=>'form-control','placeholder'=>'0.00')); ?>
			<?php echo $form->error($model,'empleado_infonavit'); ?>
		</div>
		<div class="col-md-3">
			<?php echo $form->labelEx($model,'empleado_isr'); ?>
			<?php echo $form->textField($model,'empleado_isr',array('class'=>'form-control','placeholder'=>'0.00')); ?>
			<?php echo $form->error($model,'empleado_isr'); ?>
		</div>
		<div class="col-md-3">
			<?php echo $form->labelEx($model,'empleado_rebaja_bono'); ?>
			<?php echo $form->dropDownList($model,'empleado_rebaja_bono', array('SI'=>'SI - Aplica rebaja','NO'=>'NO - Sin rebaja'), array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'empleado_rebaja_bono'); ?>
		</div>
	</div>

	<div class="row">
		<div class="col-md-3">
			<?php echo $form->labelEx($model,'empleado_requiere_checador'); ?>
			<?php echo $form->dropDownList($model,'empleado_requiere_checador', array('SI'=>'SI - Registra checador','NO'=>'NO - Sin checador'), array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'empleado_requiere_checador'); ?>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<?php echo $form->labelEx($model,'empleado_observaciones'); ?>
			<?php echo $form->textArea($model,'empleado_observaciones',array('class'=>'form-control','rows'=>3)); ?>
			<?php echo $form->error($model,'empleado_observaciones'); ?>
		</div>
	</div>

	<div class="row buttons">
		<div class="col-md-12 text-center">
			<hr>
			<?php echo CHtml::submitButton('Guardar',array('class'=>'btn btn-success')); ?>
			<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
		</div>
	</div>
	<?php echo $form->hiddenField($model,'id_empleado'); ?>
<?php $this->endWidget(); ?>
</div><!-- form -->
			</div>
		</div>
	</div>
</div>
