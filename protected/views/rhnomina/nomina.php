<?php
/* @var $this RhnominaController */
$this->pageTitle = 'Nomina - ' . $periodo->getEtiqueta();
$this->breadcrumbs = array(
	'RH' => Yii::app()->createUrl('rhempleados/admin'),
	'Periodos' => Yii::app()->createUrl('rhnomina/periodos'),
	'Nomina',
);
?>

<!-- Modal Editar Nomina -->
<div class="modal fade" id="modalEditar" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
				<h4 class="modal-title"><i class="fa fa-pencil"></i> Editar Nomina: <span id="editNombre"></span></h4>
			</div>
			<div class="modal-body">
				<input type="hidden" id="edit_id_detalle">
				<div class="row">
					<div class="col-md-6">
						<h5><strong>Percepciones</strong></h5>
						<div class="form-group">
							<label>Premio Asistencia</label>
							<input type="number" step="0.01" id="edit_premio_asistencia" class="form-control">
						</div>
						<div class="form-group">
							<label>Premio Puntualidad</label>
							<input type="number" step="0.01" id="edit_premio_puntualidad" class="form-control">
						</div>
						<div class="form-group">
							<label>Premio Productividad</label>
							<input type="number" step="0.01" id="edit_premio_productividad" class="form-control">
						</div>
						<div class="form-group">
							<label>Bono Condicional</label>
							<input type="number" step="0.01" id="edit_bono_condicional" class="form-control">
						</div>
					</div>
					<div class="col-md-6">
						<h5><strong>Deducciones</strong></h5>
						<div class="form-group">
							<label>ISR</label>
							<input type="number" step="0.01" id="edit_isr" class="form-control">
						</div>
						<div class="form-group">
							<label>Otra Deduccion</label>
							<input type="number" step="0.01" id="edit_otra_deduccion" class="form-control">
						</div>
						<div class="form-group">
							<label>Desc. Otra Deduccion</label>
							<input type="text" id="edit_otra_deduccion_desc" class="form-control" placeholder="Ej. Prestamo">
						</div>
						<div class="form-group">
							<label>Vales</label>
							<input type="number" step="0.01" id="edit_vales" class="form-control">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label>Observaciones</label>
					<textarea id="edit_observaciones" class="form-control" rows="2"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-success" onclick="GuardarNomina()">
					<i class="fa fa-check"></i> Guardar
				</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	$('#tablaNomina').DataTable({
		pageLength: 50,
		order: [[0, 'asc']],
		paging: false,
		autoWidth: false,
		dom: 'Bfrtip',
		buttons: [
			{ extend: 'csv', text: '<i class="fa fa-download"></i> CSV', className: 'btn btn-default btn-sm', filename: 'nomina', bom: true },
			{ extend: 'print', text: '<i class="fa fa-print"></i> Imprimir', className: 'btn btn-default btn-sm' }
		],
		language: {
			url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-MX.json'
		}
	});
});

function CalcularNomina(){
	if(!confirm('Calcular la nomina para todos los empleados de este periodo?')) return;
	$.ajax({
		url: "<?php echo $this->createUrl('Rhnomina/Calcular'); ?>",
		type: "POST",
		dataType: "json",
		data: { id_periodo: <?= $periodo->id_periodo; ?> },
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

function AbrirModalEditar(id, nombre, data){
	$('#edit_id_detalle').val(id);
	$('#editNombre').text(nombre);
	$('#edit_premio_asistencia').val(data.premio_asistencia);
	$('#edit_premio_puntualidad').val(data.premio_puntualidad);
	$('#edit_premio_productividad').val(data.premio_productividad);
	$('#edit_bono_condicional').val(data.bono_condicional);
	$('#edit_isr').val(data.isr);
	$('#edit_otra_deduccion').val(data.otra_deduccion);
	$('#edit_otra_deduccion_desc').val(data.otra_deduccion_desc);
	$('#edit_vales').val(data.vales);
	$('#edit_observaciones').val(data.observaciones);
	$('#modalEditar').modal('show');
}

function GuardarNomina(){
	$.ajax({
		url: "<?php echo $this->createUrl('Rhnomina/Guardarnomina'); ?>",
		type: "POST",
		dataType: "json",
		data: {
			id_detalle: $('#edit_id_detalle').val(),
			premio_asistencia: $('#edit_premio_asistencia').val(),
			premio_puntualidad: $('#edit_premio_puntualidad').val(),
			premio_productividad: $('#edit_premio_productividad').val(),
			bono_condicional: $('#edit_bono_condicional').val(),
			isr: $('#edit_isr').val(),
			otra_deduccion: $('#edit_otra_deduccion').val(),
			otra_deduccion_desc: $('#edit_otra_deduccion_desc').val(),
			vales: $('#edit_vales').val(),
			nomina_observaciones: $('#edit_observaciones').val()
		},
		success: function(r){
			if(r.requestresult == 'ok'){
				$.notify(r.message, 'success');
				$('#modalEditar').modal('hide');
				setTimeout(function(){ location.reload(); }, 800);
			} else {
				$.notify(r.message, 'error');
			}
		},
		error: function(){
			$.notify('Ocurrio un error inesperado', 'error');
		}
	});
}

function AprobarBono(idDetalle, aprobar){
	$.ajax({
		url: "<?php echo $this->createUrl('Rhnomina/Aprobarbono'); ?>",
		type: "POST",
		dataType: "json",
		data: { id_detalle: idDetalle, aprobado: aprobar },
		success: function(r){
			if(r.requestresult == 'ok'){
				$.notify(r.message, 'success');
				setTimeout(function(){ location.reload(); }, 800);
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

<style>
#tablaNomina th, #tablaNomina td { padding: 4px 6px !important; vertical-align: middle !important; }
#tablaNomina .col-emp { min-width: 180px; }
#tablaNomina .col-num { width: 45px; }
#tablaNomina .col-money { width: 80px; }
#tablaNomina .col-total { width: 90px; }
</style>

<div class="row">
	<div class="col-md-6">
		<h1>Nomina</h1>
		<p class="lead">Periodo: <strong><?= $periodo->getEtiqueta(); ?></strong>
		<?php if ($periodo->periodo_estatus == 'CERRADO'): ?>
			<span class="label label-default">CERRADO</span>
		<?php endif; ?>
		</p>
	</div>
	<div class="col-md-6 text-right" style="padding-top:20px;">
		<a href="<?= Yii::app()->createUrl('rhnomina/asistencia', array('id' => $periodo->id_periodo)); ?>" class="btn btn-info">
			<i class="fa fa-clock-o"></i> Asistencia
		</a>
		<?php if ($periodo->periodo_estatus == 'ABIERTO'): ?>
			<button class="btn btn-warning" onclick="CalcularNomina()">
				<i class="fa fa-calculator"></i> Calcular Nomina
			</button>
		<?php endif; ?>
		<a href="<?= Yii::app()->createUrl('rhnomina/nominapdf', array('id' => $periodo->id_periodo)); ?>" target="_blank" class="btn btn-success">
			<i class="fa fa-file-text-o"></i> PDF Nomina
		</a>
		<a href="<?= Yii::app()->createUrl('rhnomina/recibostodos', array('id' => $periodo->id_periodo)); ?>" target="_blank" class="btn btn-danger">
			<i class="fa fa-file-pdf-o"></i> Todos los Recibos
		</a>
	</div>
</div>

<!-- Resumen -->
<?php
$totalPercepciones = 0;
$totalDeducciones = 0;
$totalNeto = 0;
foreach ($detalles as $d) {
	$totalPercepciones += $d->total_percepciones;
	$totalDeducciones += $d->total_deducciones;
	$totalNeto += $d->total_neto;
}
?>
<div class="row" style="margin-bottom:10px;">
	<div class="col-md-3">
		<div class="panel panel-info">
			<div class="panel-body text-center" style="padding:10px;">
				<h4 style="margin:0;">$<?= number_format($totalPercepciones, 2); ?></h4>
				<small>Total Percepciones</small>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel panel-warning">
			<div class="panel-body text-center" style="padding:10px;">
				<h4 style="margin:0;">$<?= number_format($totalDeducciones, 2); ?></h4>
				<small>Total Deducciones</small>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel panel-success">
			<div class="panel-body text-center" style="padding:10px;">
				<h4 style="margin:0;">$<?= number_format($totalNeto, 2); ?></h4>
				<small>Total Neto</small>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-body text-center" style="padding:10px;">
				<h4 style="margin:0;"><?= count($detalles); ?></h4>
				<small>Empleados</small>
			</div>
		</div>
	</div>
</div>

<hr>

<?php if (!empty($primaVacacional)): ?>
<div class="alert alert-info" style="font-size:13px;">
	<i class="fa fa-gift"></i> <strong>PRIMA VACACIONAL</strong>
	<?php foreach ($primaVacacional as $pv): ?>
		<br>PAGAR PRIMA VACACIONAL A <strong><?= CHtml::encode($pv['nombre']); ?></strong> POR <strong><?= $pv['anios']; ?></strong> AÑO(S) — <?= $pv['dias_vacaciones']; ?> días de vacaciones<?php if ($pv['prima'] > 0): ?> — <strong>$<?= number_format($pv['prima'], 2); ?></strong><?php endif; ?>
	<?php endforeach; ?>
</div>
<?php endif; ?>

<div class="table-responsive">
<table id="tablaNomina" class="table table-bordered table-hover table-condensed" style="font-size:11px;">
	<thead>
		<tr>
			<th class="col-emp">Empleado</th>
			<th class="text-center col-num">Dias</th>
			<th class="text-center col-num">Falt.</th>
			<th class="text-center col-num">Ret.</th>
			<th class="text-right col-money">Sueldo</th>
			<th class="text-right col-money">Asist.</th>
			<th class="text-right col-money">Punt.</th>
			<th class="text-right col-money">Prod.</th>
			<th class="text-center" style="width:60px;">Bono</th>
			<th class="text-right col-total" style="background:#27ae60;">Tot.Perc.</th>
			<th class="text-right col-money">ISR</th>
			<th class="text-right col-money">IMSS</th>
			<th class="text-right col-money">Infon.</th>
			<th class="text-right col-total" style="background:#e67e22;">Tot.Ded.</th>
			<th class="text-right col-total" style="background:#2980b9;"><strong>NETO</strong></th>
			<th style="width:60px;">Acc.</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($detalles as $det) {
			$emp = $det->rl_empleado;
			if (empty($emp)) continue;
			$necesitaAprobacion = ((int)$det->faltas == 0 && (int)$det->retardos > 0 && $det->bono_condicional_aprobado !== 'SI' && $det->bono_condicional_aprobado !== 'NO');
			$fueRechazado = ($det->bono_condicional_aprobado === 'NO');
			$fueAprobado = ($det->bono_condicional_aprobado === 'SI');
		?>
		<tr <?= $necesitaAprobacion ? 'style="background:#fff8e1;"' : ''; ?>>
			<td style="white-space:nowrap;">
				<strong><?= $emp->empleado_nombre; ?></strong>
				<br><small class="text-muted"><?= $emp->empleado_puesto; ?></small>
			</td>
			<td class="text-center"><?= (int)$det->dias_trabajados; ?></td>
			<td class="text-center">
				<?= (int)$det->faltas > 0 ? '<span class="label label-danger">' . (int)$det->faltas . '</span>' : '0'; ?>
			</td>
			<td class="text-center">
				<?php if ((int)$det->retardos > 0): ?>
					<span class="label label-warning"><?= (int)$det->retardos; ?></span>
					<?php if ((int)$det->minutos_retardo > 0): ?>
						<br><small class="text-muted"><?= (int)$det->minutos_retardo; ?>m</small>
					<?php endif; ?>
				<?php else: ?>
					0
				<?php endif; ?>
			</td>
			<td class="text-right">$<?= number_format((float)$det->sueldo_nominal, 2); ?></td>
			<td class="text-right"><?= (float)$det->premio_asistencia > 0 ? '$' . number_format((float)$det->premio_asistencia, 2) : '-'; ?></td>
			<td class="text-right"><?= (float)$det->premio_puntualidad > 0 ? '$' . number_format((float)$det->premio_puntualidad, 2) : '-'; ?></td>
			<td class="text-right"><?= (float)$det->premio_productividad > 0 ? '$' . number_format((float)$det->premio_productividad, 2) : '-'; ?></td>
			<td class="text-center">
				<?php if ($necesitaAprobacion): ?>
					<button class="btn btn-xs btn-success" onclick="AprobarBono(<?= $det->id_detalle; ?>, 1)" title="Aprobar bono"><i class="fa fa-check"></i></button>
					<button class="btn btn-xs btn-danger" onclick="AprobarBono(<?= $det->id_detalle; ?>, 0)" title="Denegar bono"><i class="fa fa-times"></i></button>
				<?php elseif ($fueAprobado): ?>
					<span class="label label-success" title="Bono aprobado"><i class="fa fa-check"></i> Si</span>
				<?php elseif ($fueRechazado): ?>
					<span class="label label-danger" title="Bono denegado"><i class="fa fa-times"></i> No</span>
				<?php else: ?>
					-
				<?php endif; ?>
			</td>
			<td class="text-right" style="background:#e8f5e9;"><strong>$<?= number_format((float)$det->total_percepciones, 2); ?></strong></td>
			<td class="text-right"><?= (float)$det->isr > 0 ? '$' . number_format((float)$det->isr, 2) : '-'; ?></td>
			<td class="text-right"><?= (float)$det->imss > 0 ? '$' . number_format((float)$det->imss, 2) : '-'; ?></td>
			<td class="text-right"><?= (float)$det->infonavit > 0 ? '$' . number_format((float)$det->infonavit, 2) : '-'; ?></td>
			<td class="text-right" style="background:#fff3e0;"><strong>$<?= number_format((float)$det->total_deducciones, 2); ?></strong></td>
			<td class="text-right" style="background:#e3f2fd;"><strong>$<?= number_format((float)$det->total_neto, 2); ?></strong></td>
			<td style="white-space:nowrap;">
				<a href="<?= Yii::app()->createUrl('rhnomina/recibo', array('id' => $det->id_detalle)); ?>" target="_blank" class="btn btn-xs btn-default" title="Recibo PDF">
					<i class="fa fa-file-pdf-o"></i>
				</a>
				<?php if ($periodo->periodo_estatus == 'ABIERTO'): ?>
					<button class="btn btn-xs btn-warning" onclick="AbrirModalEditar(<?= $det->id_detalle; ?>, '<?= addslashes($emp->empleado_nombre ?? ''); ?>', {
						premio_asistencia: <?= (float)$det->premio_asistencia; ?>,
						premio_puntualidad: <?= (float)$det->premio_puntualidad; ?>,
						premio_productividad: <?= (float)$det->premio_productividad; ?>,
						bono_condicional: <?= (float)$det->bono_condicional; ?>,
						isr: <?= (float)$det->isr; ?>,
						otra_deduccion: <?= (float)$det->otra_deduccion; ?>,
						otra_deduccion_desc: '<?= addslashes($det->otra_deduccion_desc ?? ''); ?>',
						vales: <?= (float)$det->vales; ?>,
						observaciones: '<?= addslashes($det->nomina_observaciones ?? ''); ?>'
					})" title="Editar">
						<i class="fa fa-pencil"></i>
					</button>
				<?php endif; ?>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
</div>
