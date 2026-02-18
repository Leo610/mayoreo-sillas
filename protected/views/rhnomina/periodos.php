<?php
/* @var $this RhnominaController */
$this->pageTitle = 'Periodos de Nomina';
$this->breadcrumbs = array(
	'RH' => Yii::app()->createUrl('rhempleados/admin'),
	'Periodos de Nomina',
);
?>

<!-- Modal Nuevo Periodo -->
<div class="modal fade" id="modalPeriodo" tabindex="-1">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
				<h4 class="modal-title"><i class="fa fa-plus"></i> Nuevo Periodo</h4>
			</div>
			<div class="modal-body">
				<p class="text-muted"><small>La semana laboral inicia Viernes y termina Jueves.</small></p>
				<div class="form-group">
					<label>Fecha Inicio (Viernes) *</label>
					<input type="date" id="per_fecha_inicio" class="form-control">
				</div>
				<div class="form-group">
					<label>Fecha Fin (Jueves) *</label>
					<input type="date" id="per_fecha_fin" class="form-control" readonly>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-success" onclick="CrearPeriodo()">
					<i class="fa fa-check"></i> Crear Periodo
				</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal Aguinaldo -->
<div class="modal fade" id="modalAguinaldo" tabindex="-1">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
				<h4 class="modal-title"><i class="fa fa-gift"></i> Recibos de Aguinaldo</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label>Año *</label>
					<input type="number" id="aguinaldo_anio" class="form-control" value="<?= date('Y'); ?>" min="2020" max="2050">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-warning" onclick="GenerarAguinaldoPDF()">
					<i class="fa fa-file-pdf-o"></i> Generar PDF
				</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	$('#tablaPeriodos').DataTable({
		pageLength: 25,
		order: [[1, 'desc']],
		dom: 'Bfrtip',
		buttons: [
			{ extend: 'csv', text: '<i class="fa fa-download"></i> CSV', className: 'btn btn-default btn-sm', filename: 'periodos_nomina', bom: true },
			{ extend: 'excel', text: '<i class="fa fa-file-excel-o"></i> Excel', className: 'btn btn-default btn-sm', filename: 'periodos_nomina' },
			{ extend: 'print', text: '<i class="fa fa-print"></i> Imprimir', className: 'btn btn-default btn-sm' }
		],
		language: {
			url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-MX.json'
		}
	});
});

// Al seleccionar fecha inicio, calcular fecha fin automaticamente (viernes + 6 dias = jueves)
$('#per_fecha_inicio').on('change', function(){
	var fi = $(this).val();
	if(fi == '') { $('#per_fecha_fin').val(''); return; }
	var d = new Date(fi + 'T12:00:00');
	var dia = d.getDay(); // 0=dom, 1=lun, ..., 5=vie, 6=sab
	if(dia != 5){
		$.notify('La fecha debe ser un VIERNES. Seleccionaste un dia diferente.', 'warn');
	}
	// Sumar 6 dias para llegar al jueves
	d.setDate(d.getDate() + 6);
	var ff = d.toISOString().split('T')[0];
	$('#per_fecha_fin').val(ff);
});

// Pre-calcular el proximo viernes
(function(){
	var hoy = new Date();
	var dia = hoy.getDay();
	var diff = (5 - dia + 7) % 7;
	if(diff == 0) diff = 0; // si hoy es viernes, usar hoy
	var proxViernes = new Date(hoy);
	proxViernes.setDate(hoy.getDate() + diff);
	$('#per_fecha_inicio').val(proxViernes.toISOString().split('T')[0]).trigger('change');
})();

function CrearPeriodo(){
	var fi = $('#per_fecha_inicio').val();
	var ff = $('#per_fecha_fin').val();
	if(fi == '' || ff == ''){
		$.notify('Complete las fechas', 'error');
		return;
	}

	$.ajax({
		url: "<?php echo $this->createUrl('Rhnomina/Crearperiodo'); ?>",
		type: "POST",
		dataType: "json",
		data: { fecha_inicio: fi, fecha_fin: ff },
		success: function(r){
			if(r.requestresult == 'ok'){
				$.notify(r.message, 'success');
				$('#modalPeriodo').modal('hide');
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

function EliminarPeriodo(id){
	if(!confirm('Esta seguro de eliminar este periodo? Se eliminaran todos los registros de asistencia y nomina asociados.')) return;
	$.ajax({
		url: "<?php echo $this->createUrl('Rhnomina/Eliminarperiodo'); ?>",
		type: "POST",
		dataType: "json",
		data: { id_periodo: id },
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

function GenerarAguinaldoPDF(){
	var anio = $('#aguinaldo_anio').val();
	if(anio == '' || anio < 2020){
		$.notify('Seleccione un año valido', 'error');
		return;
	}
	var url = "<?php echo $this->createUrl('Rhnomina/Aguinaldopdf', array('anio' => '__ANIO__')); ?>";
	window.open(url.replace('__ANIO__', anio) + '&t=' + Date.now(), '_blank');
	$('#modalAguinaldo').modal('hide');
}

function CerrarPeriodo(id){
	if(!confirm('Esta seguro de cerrar este periodo? Ya no se podra modificar.')) return;
	$.ajax({
		url: "<?php echo $this->createUrl('Rhnomina/Cerrarperiodo'); ?>",
		type: "POST",
		dataType: "json",
		data: { id_periodo: id },
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
		<h1>Periodos de Nomina</h1>
	</div>
	<div class="col-md-4 text-right" style="padding-top:20px;">
		<button class="btn btn-warning" data-toggle="modal" data-target="#modalAguinaldo">
			<i class="fa fa-gift"></i> Aguinaldo
		</button>
		<button class="btn btn-success" data-toggle="modal" data-target="#modalPeriodo">
			<i class="fa fa-plus"></i> Nuevo Periodo
		</button>
	</div>
</div>

<hr>

<div class="table-responsive">
<table id="tablaPeriodos" class="table table-bordered table-hover">
	<thead>
		<tr>
			<th>#</th>
			<th>Periodo</th>
			<th>Estatus</th>
			<th>Empleados</th>
			<th>Creado por</th>
			<th>Fecha Creacion</th>
			<th>Acciones</th>
		</tr>
	</thead>
	<tbody>
		<?php $cont = 1; foreach ($lista as $per) { ?>
		<tr>
			<td><?= $cont++; ?></td>
			<td>
				<strong><?= date('d/m/Y', strtotime($per->periodo_fecha_inicio)); ?></strong>
				al
				<strong><?= date('d/m/Y', strtotime($per->periodo_fecha_fin)); ?></strong>
			</td>
			<td>
				<?php if ($per->periodo_estatus == 'ABIERTO'): ?>
					<span class="label label-success">ABIERTO</span>
				<?php else: ?>
					<span class="label label-default">CERRADO</span>
				<?php endif; ?>
			</td>
			<td class="text-center"><?= $per->getTotalEmpleados(); ?></td>
			<td><?= !empty($per->rl_usuario) ? $per->rl_usuario->Usuario_Nombre : ''; ?></td>
			<td><?= !empty($per->periodo_fecha_registro) ? date('d/m/Y H:i', strtotime($per->periodo_fecha_registro)) : ''; ?></td>
			<td>
				<a href="<?= Yii::app()->createUrl('rhnomina/asistencia', array('id' => $per->id_periodo)); ?>" class="btn btn-xs btn-info" title="Asistencia">
					<i class="fa fa-clock-o"></i> Asistencia
				</a>
				<a href="<?= Yii::app()->createUrl('rhnomina/nomina', array('id' => $per->id_periodo)); ?>" class="btn btn-xs btn-primary" title="Nomina">
					<i class="fa fa-money"></i> Nomina
				</a>
				<?php if ($per->periodo_estatus == 'ABIERTO'): ?>
					<button class="btn btn-xs btn-warning" onclick="CerrarPeriodo(<?= $per->id_periodo; ?>)" title="Cerrar Periodo">
						<i class="fa fa-lock"></i>
					</button>
					<button class="btn btn-xs btn-danger" onclick="EliminarPeriodo(<?= $per->id_periodo; ?>)" title="Eliminar">
						<i class="fa fa-trash"></i>
					</button>
				<?php endif; ?>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
</div>
