<?php
/* @var $this RhfiniquitosController */
$this->pageTitle = 'Finiquitos';
$this->breadcrumbs = array(
	'RH' => Yii::app()->createUrl('rhempleados/admin'),
	'Finiquitos',
);
?>

<script type="text/javascript">
$(document).ready(function() {
	$('#tablaFiniquitos').DataTable({
		pageLength: 25,
		order: [[0, 'desc']],
		dom: 'Bfrtip',
		buttons: [
			{ extend: 'csv', text: '<i class="fa fa-download"></i> CSV', className: 'btn btn-default btn-sm', filename: 'finiquitos', bom: true },
			{ extend: 'excel', text: '<i class="fa fa-file-excel-o"></i> Excel', className: 'btn btn-default btn-sm', filename: 'finiquitos' },
			{ extend: 'print', text: '<i class="fa fa-print"></i> Imprimir', className: 'btn btn-default btn-sm' }
		],
		language: {
			url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-MX.json'
		}
	});

	// Pre-seleccionar empleado si viene por URL
	var empPre = '<?= $id_empleado_pre; ?>';
	if (empPre) {
		$('#sel_empleado').val(empPre).trigger('change');
	}

	// Al seleccionar empleado, auto-llenar datos y fecha aguinaldo
	$('#sel_empleado').on('change', function(){
		var opt = $(this).find('option:selected');
		var integrado = parseFloat(opt.data('integrado')) || 0;
		var sueldoReal = parseFloat(opt.data('sueldo-real')) || 0;

		// Mostrar info de sueldos (VBA: IMSS = INTEGRADO*7, REAL = sueldo_semanal_real)
		if (integrado > 0 && sueldoReal > 0) {
			var imss = (integrado * 7).toFixed(2);
			$('#info_sueldos').html(
				'<span class="label label-warning">IMSS: $' + imss + '/sem (Integrado: $' + integrado.toFixed(2) + '/dia)</span> ' +
				'<span class="label label-success">Real: $' + sueldoReal.toFixed(2) + '/sem</span>'
			).show();
		} else {
			$('#info_sueldos').hide();
		}

		// Auto-llenar dias pendientes
		var pendientes = opt.data('pendientes');
		if (pendientes !== undefined && pendientes !== '') {
			$('#txt_dias_pendientes').val(pendientes);
		} else {
			$('#txt_dias_pendientes').val('');
		}

		calcularFechaAguinaldo();
	});

	$('#txt_fecha_renuncia').on('change', function(){
		calcularFechaAguinaldo();
	});
});

function calcularFechaAguinaldo(){
	var idEmp = $('#sel_empleado').val();
	var fechaRenuncia = $('#txt_fecha_renuncia').val();
	if (!idEmp || !fechaRenuncia) return;

	var fechaIngreso = $('#sel_empleado option:selected').data('ingreso');
	if (!fechaIngreso) return;

	var anioRenuncia = parseInt(fechaRenuncia.substring(0, 4));
	var anioIngreso = parseInt(fechaIngreso.substring(0, 4));

	// VBA: Si ingreso el mismo anio de la renuncia → fecha de ingreso
	// Si no → 1 de enero del anio de renuncia
	if (anioIngreso == anioRenuncia) {
		$('#txt_fecha_aguinaldo').val(fechaIngreso);
	} else {
		$('#txt_fecha_aguinaldo').val(anioRenuncia + '-01-01');
	}
}

function CalcularFiniquito(){
	var idEmp = $('#sel_empleado').val();
	var fechaRenuncia = $('#txt_fecha_renuncia').val();
	var fechaAguinaldo = $('#txt_fecha_aguinaldo').val();
	var diasPendientes = $('#txt_dias_pendientes').val();
	var observaciones = $('#txt_observaciones').val();

	if (!idEmp) { $.notify('Seleccione un empleado', 'error'); return; }
	if (!fechaRenuncia) { $.notify('Ingrese la fecha de renuncia', 'error'); return; }
	if (!fechaAguinaldo) { $.notify('Ingrese la fecha de inicio de aguinaldo', 'error'); return; }

	if (!confirm('Calcular el finiquito para el empleado seleccionado?')) return;

	$.ajax({
		url: "<?php echo $this->createUrl('Rhfiniquitos/Calcular'); ?>",
		type: "POST",
		dataType: "json",
		data: {
			id_empleado: idEmp,
			fecha_renuncia: fechaRenuncia,
			fecha_aguinaldo: fechaAguinaldo,
			dias_pendientes: diasPendientes,
			observaciones: observaciones
		},
		success: function(r){
			if (r.requestresult == 'ok') {
				$.notify(r.message, 'success');
				setTimeout(function(){ location.reload(); }, 2000);
			} else {
				$.notify(r.message, 'error');
			}
		},
		error: function(){
			$.notify('Ocurrio un error inesperado', 'error');
		}
	});
}

function EliminarFiniquito(id){
	if (!confirm('Esta seguro de eliminar este finiquito?')) return;
	$.ajax({
		url: "<?php echo $this->createUrl('Rhfiniquitos/Eliminar'); ?>",
		type: "POST",
		dataType: "json",
		data: { id_finiquito: id },
		success: function(r){
			if (r.requestresult == 'ok') {
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
		<h1>Finiquitos</h1>
	</div>
</div>

<hr>

<!-- FORMULARIO CALCULAR FINIQUITO -->
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary">
			<div class="panel-heading"><strong><i class="fa fa-calculator"></i> Calcular Nuevo Finiquito</strong></div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label>Empleado *</label>
							<select id="sel_empleado" class="form-control">
								<option value="">-- Seleccione Empleado --</option>
								<?php foreach ($empleados as $emp): ?>
								<option value="<?= $emp->id_empleado; ?>"
									data-ingreso="<?= $emp->empleado_fecha_ingreso; ?>"
									data-integrado="<?= $emp->empleado_integrado; ?>"
									data-sueldo-real="<?= $emp->empleado_sueldo_semanal_real; ?>"
									data-pendientes="<?= $emp->getDiasPendientesCalculado(); ?>">
									<?= CHtml::encode($emp->empleado_nombre); ?>
									(<?= $emp->empleado_estatus; ?>)
								</option>
								<?php endforeach; ?>
							</select>
							<div id="info_sueldos" style="margin-top:5px; display:none;"></div>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label>Fecha de Renuncia *</label>
							<input type="date" id="txt_fecha_renuncia" class="form-control" value="<?= date('Y-m-d'); ?>">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label>Fecha Inicio Aguinaldo *</label>
							<input type="date" id="txt_fecha_aguinaldo" class="form-control">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label>Dias Vac. Pendientes</label>
							<input type="number" step="0.01" id="txt_dias_pendientes" class="form-control" placeholder="Auto">
							<small class="text-muted">Se lee del empleado. Editar solo si es necesario.</small>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-8">
						<div class="form-group">
							<label>Observaciones</label>
							<input type="text" id="txt_observaciones" class="form-control" placeholder="Motivo de baja, notas...">
						</div>
					</div>
					<div class="col-md-4 text-right" style="padding-top:22px;">
						<button class="btn btn-success btn-lg" onclick="CalcularFiniquito()">
							<i class="fa fa-calculator"></i> Calcular Finiquito
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- TABLA DE FINIQUITOS -->
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading"><strong><i class="fa fa-list"></i> Finiquitos Registrados</strong></div>
			<div class="panel-body">
				<div class="table-responsive">
				<table id="tablaFiniquitos" class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>#</th>
							<th>Empleado</th>
							<th>Fecha Renuncia</th>
							<th>Aguinaldo</th>
							<th>Vacaciones</th>
							<th>Prima Vac.</th>
							<th>Compensacion</th>
							<th>Total Real</th>
							<th>Acciones</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($lista as $fin): ?>
						<tr>
							<td><?= $fin->id_finiquito; ?></td>
							<td><?= !empty($fin->rl_empleado) ? CHtml::encode($fin->rl_empleado->empleado_nombre) : 'N/A'; ?></td>
							<td><?= date('d/m/Y', strtotime($fin->finiquito_fecha_renuncia)); ?></td>
							<td class="text-right">$<?= number_format((float)$fin->finiquito_aguinaldo, 2); ?></td>
							<td class="text-right">$<?= number_format((float)$fin->finiquito_pago_vacaciones, 2); ?></td>
							<td class="text-right">$<?= number_format((float)$fin->finiquito_prima_vacacional, 2); ?></td>
							<td class="text-right">$<?= number_format((float)$fin->finiquito_compensacion, 2); ?></td>
							<td class="text-right"><strong>$<?= number_format((float)$fin->finiquito_total, 2); ?></strong></td>
							<td>
								<a href="<?= Yii::app()->createUrl('rhfiniquitos/ver', array('id' => $fin->id_finiquito)); ?>" class="btn btn-xs btn-info" title="Ver Detalle">
									<i class="fa fa-eye"></i>
								</a>
								<a href="<?= Yii::app()->createUrl('rhfiniquitos/formato', array('id' => $fin->id_finiquito)); ?>" class="btn btn-xs btn-default" target="_blank" title="PDF">
									<i class="fa fa-file-pdf-o"></i>
								</a>
								<button class="btn btn-xs btn-danger" onclick="EliminarFiniquito(<?= $fin->id_finiquito; ?>)" title="Eliminar">
									<i class="fa fa-trash"></i>
								</button>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				</div>
			</div>
		</div>
	</div>
</div>
