<?php
/* @var $this RhempleadosController */
/* @var $Datos Empleados */

$this->pageTitle = 'Detalles del Empleado';
$this->breadcrumbs = array(
	'RH Empleados' => Yii::app()->createUrl('rhempleados/admin'),
	'Detalles',
);
?>

<?php include 'modal/_baja.php'; ?>

<script type="text/javascript">
$(document).ready(function() {
	$('#tablaHistorial').DataTable({
		pageLength: 25,
		order: [[0, 'desc']],
		language: {
			url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-MX.json'
		}
	});
});

function Reactivar(id){
	if(!confirm('Esta seguro que desea reactivar a este empleado?')) return;
	$.ajax({
		url: "<?php echo $this->createUrl('Rhempleados/Reactivar'); ?>",
		type: "POST",
		dataType: "json",
		data: { id_empleado: id },
		success: function(Response){
			if(Response.requestresult == 'ok'){
				$.notify(Response.message, 'success');
				setTimeout(function(){ location.reload(); }, 1000);
			} else {
				$.notify(Response.message, 'error');
			}
		},
		error: function(){
			$.notify('Ocurrio un error inesperado', 'error');
		}
	});
}
</script>

<div class="row">
	<div class="col-md-12">
		<a href="<?= Yii::app()->createUrl('rhempleados/admin'); ?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> Regresar a lista</a>
		<hr>
	</div>
</div>

<?php foreach(Yii::app()->user->getFlashes() as $key => $message){ ?>
<div class="alert alert-<?= $key; ?> alert-dismissible" role="alert">
	<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
	<?= $message; ?>
</div>
<?php } ?>

<!-- HEADER -->
<div class="row">
	<div class="col-md-8">
		<h2><?= $Datos->empleado_nombre; ?>
			<?php if($Datos->empleado_estatus == 'ACTIVO'): ?>
				<span class="label label-success">ACTIVO</span>
			<?php else: ?>
				<span class="label label-danger">INACTIVO</span>
			<?php endif; ?>
		</h2>
		<p style="font-size:14px;color:#666;">
			<?php if(!empty($Datos->empleado_puesto)): ?>
				<strong>Puesto:</strong> <?= $Datos->empleado_puesto; ?> |
			<?php endif; ?>
			<?php if(!empty($Datos->empleado_rfc)): ?>
				<strong>RFC:</strong> <?= $Datos->empleado_rfc; ?>
			<?php endif; ?>
		</p>
	</div>
	<div class="col-md-4 text-right" style="padding-top:20px;">
		<?php if($Datos->empleado_estatus == 'ACTIVO'): ?>
			<a href="<?= Yii::app()->createUrl('rhfiniquitos/index', array('empleado' => $Datos->id_empleado)); ?>" class="btn btn-warning">
				<i class="fa fa-calculator"></i> Calcular Finiquito
			</a>
			<button class="btn btn-danger" onclick="AbrirModalBaja(<?= $Datos->id_empleado; ?>, '<?= $Datos->empleado_nombre; ?>')">
				<i class="fa fa-times-circle"></i> Dar de Baja
			</button>
		<?php else: ?>
			<button class="btn btn-success" onclick="Reactivar(<?= $Datos->id_empleado; ?>)">
				<i class="fa fa-check-circle"></i> Reactivar
			</button>
		<?php endif; ?>
	</div>
</div>

<!-- CARDS RESUMEN -->
<div class="row" style="margin-top:15px;">
	<div class="col-md-2">
		<div class="panel panel-info">
			<div class="panel-heading text-center"><strong>Antiguedad</strong></div>
			<div class="panel-body text-center">
				<h3 style="margin:0;"><?= $Datos->getAntiguedad(); ?></h3>
				<small>anios</small>
			</div>
		</div>
	</div>
	<div class="col-md-2">
		<div class="panel panel-info">
			<div class="panel-heading text-center"><strong>Dias Vacaciones</strong></div>
			<div class="panel-body text-center">
				<h3 style="margin:0;"><?= $Datos->getDiasVacaciones(); ?></h3>
				<small>dias/anio</small>
			</div>
		</div>
	</div>
	<div class="col-md-2">
		<div class="panel panel-info">
			<div class="panel-heading text-center"><strong>Proporcionales</strong></div>
			<div class="panel-body text-center">
				<h3 style="margin:0;"><?= $Datos->getDiasVacacionesProporcionales(); ?></h3>
				<small>dias</small>
			</div>
		</div>
	</div>
	<div class="col-md-2">
		<div class="panel panel-success">
			<div class="panel-heading text-center"><strong>Sueldo IMSS</strong></div>
			<div class="panel-body text-center">
				<h3 style="margin:0;">$<?= number_format($Datos->empleado_sueldo_semanal, 2); ?></h3>
				<small>semanal</small>
			</div>
		</div>
	</div>
	<div class="col-md-2">
		<div class="panel panel-warning">
			<div class="panel-heading text-center"><strong>Sueldo Real</strong></div>
			<div class="panel-body text-center">
				<h3 style="margin:0;">$<?= number_format($Datos->empleado_sueldo_semanal_real, 2); ?></h3>
				<small>semanal</small>
			</div>
		</div>
	</div>
</div>

<!-- FORMULARIO EDITABLE -->
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading"><strong><i class="fa fa-user"></i> Datos del Empleado</strong></div>
			<div class="panel-body">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'Empleados-detalles-form',
	'action'=>Yii::app()->createUrl('Rhempleados/Createorupdate'),
	'enableClientValidation'=>true,
	'clientOptions' => array('validateOnSubmit'=>true),
)); ?>

	<div class="row">
		<div class="col-md-1">
			<?php echo $form->labelEx($model,'empleado_num_reloj'); ?>
			<?php echo $form->textField($model,'empleado_num_reloj',array('class'=>'form-control','value'=>$Datos->empleado_num_reloj)); ?>
		</div>
		<div class="col-md-1">
			<?php echo $form->labelEx($model,'empleado_num_empleado'); ?>
			<?php echo $form->textField($model,'empleado_num_empleado',array('class'=>'form-control','value'=>$Datos->empleado_num_empleado)); ?>
		</div>
		<div class="col-md-3">
			<?php echo $form->labelEx($model,'empleado_nombre'); ?>
			<?php echo $form->textField($model,'empleado_nombre',array('class'=>'form-control','value'=>$Datos->empleado_nombre)); ?>
		</div>
		<div class="col-md-2">
			<?php echo $form->labelEx($model,'empleado_fecha_ingreso'); ?>
			<?php echo $form->dateField($model,'empleado_fecha_ingreso',array('class'=>'form-control','value'=>$Datos->empleado_fecha_ingreso)); ?>
		</div>
		<div class="col-md-2">
			<?php echo $form->labelEx($model,'empleado_seguro_social'); ?>
			<?php echo $form->textField($model,'empleado_seguro_social',array('class'=>'form-control','value'=>$Datos->empleado_seguro_social)); ?>
		</div>
		<div class="col-md-3">
			<?php echo $form->labelEx($model,'empleado_rfc'); ?>
			<?php echo $form->textField($model,'empleado_rfc',array('class'=>'form-control','value'=>$Datos->empleado_rfc)); ?>
		</div>
	</div>

	<div class="row" style="margin-top:10px;">
		<div class="col-md-3">
			<?php echo $form->labelEx($model,'empleado_puesto'); ?>
			<?php echo $form->textField($model,'empleado_puesto',array('class'=>'form-control','value'=>$Datos->empleado_puesto)); ?>
		</div>
		<div class="col-md-3">
			<?php echo $form->labelEx($model,'empleado_tarjeta_efectivale'); ?>
			<?php echo $form->dropDownList($model,'empleado_tarjeta_efectivale', array(''=>'-- Seleccione --','ASIGNADA'=>'ASIGNADA','NO ASIGNADA'=>'NO ASIGNADA'), array('class'=>'form-control','options'=>array($Datos->empleado_tarjeta_efectivale=>array('selected'=>true)))); ?>
		</div>
		<div class="col-md-3">
			<?php echo $form->labelEx($model,'empleado_habitante_casa'); ?>
			<?php echo $form->textField($model,'empleado_habitante_casa',array('class'=>'form-control','value'=>$Datos->empleado_habitante_casa)); ?>
		</div>
	</div>

	<div class="row" style="margin-top:10px;">
		<div class="col-md-2">
			<?php echo $form->labelEx($model,'empleado_sueldo_semanal'); ?>
			<?php echo $form->textField($model,'empleado_sueldo_semanal',array('class'=>'form-control','value'=>$Datos->empleado_sueldo_semanal)); ?>
		</div>
		<div class="col-md-2">
			<?php echo $form->labelEx($model,'empleado_sueldo_semanal_real'); ?>
			<?php echo $form->textField($model,'empleado_sueldo_semanal_real',array('class'=>'form-control','value'=>$Datos->empleado_sueldo_semanal_real)); ?>
		</div>
		<div class="col-md-2">
			<?php echo $form->labelEx($model,'empleado_sueldo_diario'); ?>
			<?php echo $form->textField($model,'empleado_sueldo_diario',array('class'=>'form-control','value'=>$Datos->empleado_sueldo_diario)); ?>
		</div>
		<div class="col-md-2">
			<?php echo $form->labelEx($model,'empleado_integrado'); ?>
			<?php echo $form->textField($model,'empleado_integrado',array('class'=>'form-control','value'=>$Datos->empleado_integrado)); ?>
		</div>
	</div>

	<div class="row" style="margin-top:10px;">
		<div class="col-md-2">
			<?php echo $form->labelEx($model,'empleado_imss'); ?>
			<?php echo $form->textField($model,'empleado_imss',array('class'=>'form-control','value'=>$Datos->empleado_imss)); ?>
		</div>
		<div class="col-md-2">
			<?php echo $form->labelEx($model,'empleado_infonavit'); ?>
			<?php echo $form->textField($model,'empleado_infonavit',array('class'=>'form-control','value'=>$Datos->empleado_infonavit)); ?>
		</div>
		<div class="col-md-3">
			<?php echo $form->labelEx($model,'empleado_requiere_checador'); ?>
			<?php echo $form->dropDownList($model,'empleado_requiere_checador', array('SI'=>'SI - Registra checador','NO'=>'NO - Sin checador'), array('class'=>'form-control','options'=>array($Datos->empleado_requiere_checador=>array('selected'=>true)))); ?>
		</div>
	</div>

	<div class="row" style="margin-top:10px;">
		<div class="col-md-3">
			<?php echo $form->labelEx($model,'empleado_bono_asistencia'); ?>
			<?php echo $form->textField($model,'empleado_bono_asistencia',array('class'=>'form-control','value'=>$Datos->empleado_bono_asistencia)); ?>
		</div>
		<div class="col-md-3">
			<?php echo $form->labelEx($model,'empleado_bono_puntualidad'); ?>
			<?php echo $form->textField($model,'empleado_bono_puntualidad',array('class'=>'form-control','value'=>$Datos->empleado_bono_puntualidad)); ?>
		</div>
		<div class="col-md-3">
			<?php echo $form->labelEx($model,'empleado_bono_productividad'); ?>
			<?php echo $form->textField($model,'empleado_bono_productividad',array('class'=>'form-control','value'=>$Datos->empleado_bono_productividad)); ?>
		</div>
		<div class="col-md-3">
			<?php echo $form->labelEx($model,'empleado_bono_condicional'); ?>
			<?php echo $form->textField($model,'empleado_bono_condicional',array('class'=>'form-control','value'=>$Datos->empleado_bono_condicional)); ?>
		</div>
	</div>

	<div class="row" style="margin-top:10px;">
		<div class="col-md-12">
			<?php echo $form->labelEx($model,'empleado_observaciones'); ?>
			<?php echo $form->textArea($model,'empleado_observaciones',array('class'=>'form-control','rows'=>3,'value'=>$Datos->empleado_observaciones)); ?>
		</div>
	</div>

	<?php if(!empty($Datos->empleado_fecha_baja)): ?>
	<div class="row" style="margin-top:10px;">
		<div class="col-md-4">
			<label>Fecha de Baja</label>
			<p class="form-control-static" style="color:red;font-weight:bold;"><?= date('d/m/Y', strtotime($Datos->empleado_fecha_baja)); ?></p>
		</div>
	</div>
	<?php endif; ?>

	<div class="row" style="margin-top:15px;">
		<div class="col-md-12 text-center">
			<?php echo $form->hiddenField($model,'id_empleado', array('value'=>$Datos->id_empleado)); ?>
			<?php echo CHtml::submitButton('Guardar Cambios',array('class'=>'btn btn-success btn-lg')); ?>
		</div>
	</div>

<?php $this->endWidget(); ?>

			</div>
		</div>
	</div>
</div>

<!-- VACACIONES -->
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<strong><i class="fa fa-calendar"></i> Vacaciones</strong>
				<a href="<?= Yii::app()->createUrl('rhvacaciones/index', array('empleado' => $Datos->id_empleado)); ?>" class="btn btn-xs btn-default pull-right" style="margin-left:5px;">
					<i class="fa fa-list"></i> Ver todas
				</a>
				<?php if($Datos->empleado_estatus == 'ACTIVO' && $Datos->getDiasVacaciones() > 0): ?>
				<a href="<?= Yii::app()->createUrl('rhvacaciones/index', array('empleado' => $Datos->id_empleado, 'registrar' => 1)); ?>" class="btn btn-xs btn-success pull-right">
					<i class="fa fa-plus"></i> Registrar Vacaciones
				</a>
				<?php endif; ?>
			</div>
			<div class="panel-body">
				<!-- Cards resumen vacaciones -->
				<div class="row" style="margin-bottom:10px;">
					<div class="col-md-4">
						<div class="panel panel-info" style="margin-bottom:5px;">
							<div class="panel-body text-center" style="padding:8px;">
								<h4 style="margin:0;"><?= $Datos->getDiasVacaciones(); ?></h4>
								<small>Dias de Derecho</small>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="panel panel-warning" style="margin-bottom:5px;">
							<div class="panel-body text-center" style="padding:8px;">
								<h4 style="margin:0;"><?= $Datos->getDiasTomados(); ?></h4>
								<small>Dias Tomados</small>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="panel panel-success" style="margin-bottom:5px;">
							<div class="panel-body text-center" style="padding:8px;">
								<h4 style="margin:0;"><?= $Datos->getDiasDisponibles(); ?></h4>
								<small>Dias Disponibles</small>
							</div>
						</div>
					</div>
				</div>

				<?php if(!empty($vacaciones)): ?>
				<div class="table-responsive">
				<table class="table table-bordered table-hover" style="margin-bottom:0;">
					<thead>
						<tr>
							<th>Fecha Inicio</th>
							<th>Fecha Fin</th>
							<th>Dias</th>
							<th>Estatus</th>
							<th>Observaciones</th>
							<th>PDF</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($vacaciones as $vac) { ?>
						<tr>
							<td><?= date('d/m/Y', strtotime($vac->vacacion_fecha_inicio)); ?></td>
							<td><?= date('d/m/Y', strtotime($vac->vacacion_fecha_fin)); ?></td>
							<td class="text-center"><strong><?= $vac->vacacion_dias; ?></strong></td>
							<td><span class="label label-success">APROBADA</span></td>
							<td><?= $vac->vacacion_observaciones; ?></td>
							<td>
								<a href="<?= Yii::app()->createUrl('rhvacaciones/formato', array('id' => $vac->id_vacacion)); ?>" target="_blank" class="btn btn-xs btn-default">
									<i class="fa fa-file-pdf-o"></i>
								</a>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				</div>
				<?php else: ?>
					<p class="text-muted text-center">No hay vacaciones registradas</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<!-- HISTORIAL DE MOVIMIENTOS -->
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading"><strong><i class="fa fa-history"></i> Historial de Movimientos</strong></div>
			<div class="panel-body">
				<div class="table-responsive">
				<table id="tablaHistorial" class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>Fecha</th>
							<th>Tipo</th>
							<th>Estatus Anterior</th>
							<th>Estatus Nuevo</th>
							<th>Observaciones</th>
							<th>Usuario</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($historial as $h){ ?>
						<tr>
							<td><?= date('d/m/Y', strtotime($h->historial_fecha_movimiento)); ?></td>
							<td>
								<?php
								switch($h->historial_tipo){
									case 'ALTA': echo '<span class="label label-success">ALTA</span>'; break;
									case 'BAJA': echo '<span class="label label-danger">BAJA</span>'; break;
									case 'REINGRESO': echo '<span class="label label-info">REINGRESO</span>'; break;
									case 'MODIFICACION': echo '<span class="label label-warning">MODIFICACION</span>'; break;
								}
								?>
							</td>
							<td><?= $h->historial_estatus_anterior; ?></td>
							<td><?= $h->historial_estatus_nuevo; ?></td>
							<td style="white-space:pre-line; font-size:12px;"><?= CHtml::encode($h->historial_observaciones); ?></td>
							<td><?= !empty($h->rl_usuario) ? $h->rl_usuario->Usuario_Nombre : ''; ?></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				</div>
			</div>
		</div>
	</div>
</div>
