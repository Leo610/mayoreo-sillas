<?php
/* @var $this RhvacacionesController */
$this->pageTitle = 'Vacaciones';
$this->breadcrumbs = array(
	'RH' => Yii::app()->createUrl('rhempleados/admin'),
	'Vacaciones',
);
?>

<!-- Modal Registrar Vacaciones -->
<div class="modal fade" id="modalRegistrar" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
				<h4 class="modal-title"><i class="fa fa-calendar-plus-o"></i> Registrar Vacaciones</h4>
			</div>
			<div class="modal-body">
				<form id="formVacaciones">
					<div class="form-group">
						<label>Empleado *</label>
						<select id="vac_empleado" class="form-control" required>
							<option value="">-- Seleccione Empleado --</option>
							<?php foreach ($empleados as $emp) {
								$diasDerecho = $emp->getDiasVacaciones();
								$sinDerecho = ($diasDerecho <= 0);
							?>
								<option value="<?= $emp->id_empleado; ?>"
									data-dias-derecho="<?= $diasDerecho; ?>"
									data-dias-tomados="<?= $emp->getTotalDiasTomados(); ?>"
									data-dias-disponibles="<?= $emp->getDiasPendientes(); ?>"
									<?= $sinDerecho ? 'disabled style="color:#ccc;"' : ''; ?>>
									<?= $emp->empleado_nombre; ?><?= $sinDerecho ? ' (sin derecho)' : ''; ?>
								</option>
							<?php } ?>
						</select>
					</div>

					<div id="infoEmpleado" style="display:none; margin-bottom:15px;">
						<div class="row">
							<div class="col-md-4">
								<div class="panel panel-info" style="margin-bottom:5px;">
									<div class="panel-body text-center" style="padding:8px;">
										<strong id="lblDerecho">0</strong><br><small>Dias Derecho</small>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="panel panel-warning" style="margin-bottom:5px;">
									<div class="panel-body text-center" style="padding:8px;">
										<strong id="lblTomados">0</strong><br><small>Dias Tomados</small>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="panel panel-success" style="margin-bottom:5px;">
									<div class="panel-body text-center" style="padding:8px;">
										<strong id="lblDisponibles">0</strong><br><small>Dias Disponibles</small>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Fecha Inicio *</label>
								<input type="date" id="vac_fecha_inicio" class="form-control" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Fecha Fin *</label>
								<input type="date" id="vac_fecha_fin" class="form-control" required>
							</div>
						</div>
					</div>

					<div id="previewDias" style="display:none; margin-bottom:15px;">
						<div class="alert alert-info" style="margin-bottom:0; padding:8px 12px;">
							<i class="fa fa-calendar"></i> Dias habiles: <strong id="lblDiasCalc">0</strong>
						</div>
					</div>

					<div class="form-group">
						<label>Observaciones</label>
						<textarea id="vac_observaciones" class="form-control" rows="2"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-success" onclick="GuardarVacacion()">
					<i class="fa fa-check"></i> Registrar Vacaciones
				</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	$('#tablaVacaciones').DataTable({
		pageLength: 25,
		order: [[1, 'desc']],
		dom: 'Bfrtip',
		buttons: [
			{ extend: 'csv', text: '<i class="fa fa-download"></i> CSV', className: 'btn btn-default btn-sm', filename: 'vacaciones', bom: true },
			{ extend: 'excel', text: '<i class="fa fa-file-excel-o"></i> Excel', className: 'btn btn-default btn-sm', filename: 'vacaciones' },
			{ extend: 'print', text: '<i class="fa fa-print"></i> Imprimir', className: 'btn btn-default btn-sm' }
		],
		language: {
			url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-MX.json'
		}
	});

	// Auto-abrir modal si viene con ?registrar=1&empleado=X
	<?php if (!empty($_GET['registrar']) && !empty($filtro_empleado)): ?>
	setTimeout(function(){ AbrirModalRegistrar(<?= (int)$filtro_empleado; ?>); }, 300);
	<?php endif; ?>

	// Al cambiar empleado mostrar info
	$('#vac_empleado').change(function(){
		var sel = $(this).find(':selected');
		if(sel.val() != ''){
			$('#lblDerecho').text(sel.data('dias-derecho'));
			$('#lblTomados').text(sel.data('dias-tomados'));
			$('#lblDisponibles').text(sel.data('dias-disponibles'));
			$('#infoEmpleado').show();
		} else {
			$('#infoEmpleado').hide();
		}
	});

	// Calcular dias al cambiar fechas
	$('#vac_fecha_inicio, #vac_fecha_fin').change(function(){
		CalcularDias();
	});

});

function AbrirModalRegistrar(idEmpleado){
	$('#formVacaciones')[0].reset();
	$('#infoEmpleado').hide();
	$('#previewDias').hide();
	if(idEmpleado){
		$('#vac_empleado').val(idEmpleado).trigger('change');
	}
	$('#modalRegistrar').modal('show');
}

function CalcularDias(){
	var fi = $('#vac_fecha_inicio').val();
	var ff = $('#vac_fecha_fin').val();
	if(fi == '' || ff == '') {
		$('#previewDias').hide();
		return;
	}
	if(fi > ff){
		$('#previewDias').hide();
		$.notify('La fecha inicio no puede ser mayor a la fecha fin', 'error');
		return;
	}
	$.ajax({
		url: "<?php echo $this->createUrl('Rhvacaciones/Calculardias'); ?>",
		type: "POST",
		dataType: "json",
		data: { fecha_inicio: fi, fecha_fin: ff },
		success: function(r){
			if(r.requestresult == 'ok'){
				$('#lblDiasCalc').text(r.dias);
				$('#previewDias').show();
			}
		}
	});
}

function GuardarVacacion(){
	var emp = $('#vac_empleado').val();
	var fi = $('#vac_fecha_inicio').val();
	var ff = $('#vac_fecha_fin').val();
	var obs = $('#vac_observaciones').val();

	if(emp == '' || fi == '' || ff == ''){
		$.notify('Complete los campos obligatorios', 'error');
		return;
	}
	if(fi > ff){
		$.notify('La fecha inicio no puede ser mayor a la fecha fin', 'error');
		return;
	}

	$.ajax({
		url: "<?php echo $this->createUrl('Rhvacaciones/Registrar'); ?>",
		type: "POST",
		dataType: "json",
		data: {
			id_empleado: emp,
			fecha_inicio: fi,
			fecha_fin: ff,
			observaciones: obs
		},
		success: function(r){
			if(r.requestresult == 'ok'){
				$.notify(r.message, 'success');
				$('#modalRegistrar').modal('hide');
				setTimeout(function(){ location.reload(); }, 1000);
			} else {
				$.notify(r.message, 'error');
			}
		},
		error: function(){
			$.notify('Ocurrio un error inesperado', 'error');
		}
	});
}

function AprobarVacacion(id){
	if(!confirm('Aprobar esta solicitud de vacaciones?')) return;
	$.ajax({
		url: "<?php echo $this->createUrl('Rhvacaciones/Aprobar'); ?>",
		type: "POST",
		dataType: "json",
		data: { id_vacacion: id },
		success: function(r){
			if(r.requestresult == 'ok'){
				$.notify(r.message, 'success');
				setTimeout(function(){ location.reload(); }, 1000);
			} else {
				$.notify(r.message, 'error');
			}
		},
		error: function(){
			$.notify('Ocurrio un error inesperado', 'error');
		}
	});
}

function RechazarVacacion(id){
	if(!confirm('Rechazar esta solicitud de vacaciones?')) return;
	$.ajax({
		url: "<?php echo $this->createUrl('Rhvacaciones/Rechazar'); ?>",
		type: "POST",
		dataType: "json",
		data: { id_vacacion: id },
		success: function(r){
			if(r.requestresult == 'ok'){
				$.notify(r.message, 'success');
				setTimeout(function(){ location.reload(); }, 1000);
			} else {
				$.notify(r.message, 'error');
			}
		},
		error: function(){
			$.notify('Ocurrio un error inesperado', 'error');
		}
	});
}

function CancelarVacacion(id){
	if(!confirm('Esta seguro que desea cancelar esta vacacion?')) return;
	$.ajax({
		url: "<?php echo $this->createUrl('Rhvacaciones/Cancelar'); ?>",
		type: "POST",
		dataType: "json",
		data: { id_vacacion: id },
		success: function(r){
			if(r.requestresult == 'ok'){
				$.notify(r.message, 'success');
				setTimeout(function(){ location.reload(); }, 1000);
			} else {
				$.notify(r.message, 'error');
			}
		},
		error: function(){
			$.notify('Ocurrio un error inesperado', 'error');
		}
	});
}
</script>

<div class="row">
	<div class="col-md-8">
		<h1>Vacaciones</h1>
	</div>
	<div class="col-md-4 text-right" style="padding-top:20px;">
		<button class="btn btn-success" onclick="AbrirModalRegistrar()">
			<i class="fa fa-plus"></i> Registrar Vacaciones
		</button>
	</div>
</div>

<!-- Filtros -->
<div class="row" style="margin-bottom:15px;">
	<form method="GET" action="<?= Yii::app()->createUrl('rhvacaciones/index'); ?>">
		<div class="col-md-3">
			<label>Empleado:</label>
			<select name="empleado" class="form-control" onchange="this.form.submit()">
				<option value="">-- Todos --</option>
				<?php foreach ($empleados as $emp) { ?>
					<option value="<?= $emp->id_empleado; ?>" <?= $filtro_empleado == $emp->id_empleado ? 'selected' : ''; ?>>
						<?= $emp->empleado_nombre; ?>
					</option>
				<?php } ?>
			</select>
		</div>
		<div class="col-md-2">
			<label>Anio:</label>
			<select name="anio" class="form-control" onchange="this.form.submit()">
				<option value="">-- Todos --</option>
				<?php for ($y = date('Y'); $y >= 2020; $y--) { ?>
					<option value="<?= $y; ?>" <?= $filtro_anio == $y ? 'selected' : ''; ?>><?= $y; ?></option>
				<?php } ?>
			</select>
		</div>
		<div class="col-md-2">
			<label>Estatus:</label>
			<select name="estatus" class="form-control" onchange="this.form.submit()">
				<option value="">-- Todos --</option>
				<option value="PENDIENTE" <?= $filtro_estatus == 'PENDIENTE' ? 'selected' : ''; ?>>PENDIENTE</option>
				<option value="APROBADA" <?= $filtro_estatus == 'APROBADA' ? 'selected' : ''; ?>>APROBADA</option>
				<option value="RECHAZADA" <?= $filtro_estatus == 'RECHAZADA' ? 'selected' : ''; ?>>RECHAZADA</option>
				<option value="CANCELADA" <?= $filtro_estatus == 'CANCELADA' ? 'selected' : ''; ?>>CANCELADA</option>
			</select>
		</div>
	</form>
</div>

<hr>

<div class="table-responsive">
<table id="tablaVacaciones" class="table table-bordered table-hover">
	<thead>
		<tr>
			<th>Empleado</th>
			<th>Fecha Inicio</th>
			<th>Fecha Fin</th>
			<th>Dias</th>
			<th>Estatus</th>
			<th>Observaciones</th>
			<th>Registrado por</th>
			<th>Acciones</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($lista as $vac) { ?>
		<tr>
			<td>
				<a href="<?= Yii::app()->createUrl('rhempleados/detalles', array('id_empleado' => $vac->id_empleado)); ?>">
					<?= !empty($vac->rl_empleado) ? $vac->rl_empleado->empleado_nombre : ''; ?>
				</a>
			</td>
			<td><?= date('d/m/Y', strtotime($vac->vacacion_fecha_inicio)); ?></td>
			<td><?= date('d/m/Y', strtotime($vac->vacacion_fecha_fin)); ?></td>
			<td class="text-center"><strong><?= $vac->vacacion_dias; ?></strong></td>
			<td>
				<?php if ($vac->vacacion_estatus == 'PENDIENTE'): ?>
					<span class="label label-warning">PENDIENTE</span>
				<?php elseif ($vac->vacacion_estatus == 'APROBADA'): ?>
					<span class="label label-success">APROBADA</span>
				<?php elseif ($vac->vacacion_estatus == 'RECHAZADA'): ?>
					<span class="label label-default">RECHAZADA</span>
				<?php else: ?>
					<span class="label label-danger">CANCELADA</span>
				<?php endif; ?>
			</td>
			<td><?= $vac->vacacion_observaciones; ?></td>
			<td>
				<?= !empty($vac->rl_usuario) ? $vac->rl_usuario->Usuario_Nombre : ''; ?>
				<br><small><?= date('d/m/Y H:i', strtotime($vac->vacacion_fecha_registro)); ?></small>
			</td>
			<td>
					<a href="<?= Yii::app()->createUrl('rhvacaciones/formato', array('id' => $vac->id_vacacion)); ?>" target="_blank" class="btn btn-xs btn-default" title="PDF">
					<i class="fa fa-file-pdf-o"></i>
				</a>
				<?php if ($vac->vacacion_estatus == 'PENDIENTE'): ?>
					<button class="btn btn-xs btn-success" onclick="AprobarVacacion(<?= $vac->id_vacacion; ?>)" title="Aprobar">
						<i class="fa fa-check"></i> Aprobar
					</button>
					<button class="btn btn-xs btn-danger" onclick="RechazarVacacion(<?= $vac->id_vacacion; ?>)" title="Rechazar">
						<i class="fa fa-times"></i> Rechazar
					</button>
				<?php endif; ?>
				<?php if ($vac->vacacion_estatus == 'APROBADA'): ?>
					<button class="btn btn-xs btn-danger" onclick="CancelarVacacion(<?= $vac->id_vacacion; ?>)" title="Cancelar">
						<i class="fa fa-times"></i>
					</button>
				<?php endif; ?>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
</div>
