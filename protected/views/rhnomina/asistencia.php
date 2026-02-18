<?php
/* @var $this RhnominaController */
$this->pageTitle = 'Asistencia - ' . $periodo->getEtiqueta();
$this->breadcrumbs = array(
	'RH' => Yii::app()->createUrl('rhempleados/admin'),
	'Periodos' => Yii::app()->createUrl('rhnomina/periodos'),
	'Asistencia',
);

$diasNombres = array(1 => 'Lu', 2 => 'Ma', 3 => 'Mi', 4 => 'Ju', 5 => 'Vi', 6 => 'Sa', 7 => 'Do');
?>

<!-- Modal Editar Asistencia Empleado -->
<div class="modal fade" id="modalAsistencia" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
				<h4 class="modal-title"><i class="fa fa-clock-o"></i> Asistencia de: <span id="modalNombreEmpleado"></span></h4>
			</div>
			<div class="modal-body">
				<table class="table table-bordered table-condensed" id="tablaModalAsistencia">
					<thead>
						<tr>
							<th>Dia</th>
							<th>Fecha</th>
							<th>Tipo</th>
							<th>Entrada</th>
							<th>Salida</th>
							<th>Horas</th>
							<th>Retardo</th>
						</tr>
					</thead>
					<tbody id="modalAsistenciaBody">
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-success" onclick="GuardarAsistenciaEmpleado()">
					<i class="fa fa-check"></i> Guardar Todo
				</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal Importar CSV -->
<div class="modal fade" id="modalImportar" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
				<h4 class="modal-title"><i class="fa fa-upload"></i> Importar Asistencia (CSV)</h4>
			</div>
			<div class="modal-body">
				<p>Pegue los datos en formato CSV. Una linea por registro:</p>
				<p><code>NumEmpleado,Fecha,Entrada,Salida</code></p>
				<p><small>Ejemplo: <code>3034,2026-02-03,07:44,18:00</code></small></p>
				<textarea id="importDatos" class="form-control" rows="10" placeholder="3034,2026-02-03,07:44,18:00&#10;3034,2026-02-04,07:55,18:00&#10;..."></textarea>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-success" onclick="ImportarAsistencia()">
					<i class="fa fa-upload"></i> Importar
				</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal Importar Checador Excel -->
<div class="modal fade" id="modalChecador" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
				<h4 class="modal-title"><i class="fa fa-file-excel-o"></i> Importar desde Reloj Checador</h4>
			</div>
			<div class="modal-body">
				<p>Suba el archivo <strong>.xls</strong> exportado del reloj checador. Se importaran solo los dias que correspondan a este periodo (<strong><?= date('d/m/Y', strtotime($periodo->periodo_fecha_inicio)); ?> - <?= date('d/m/Y', strtotime($periodo->periodo_fecha_fin)); ?></strong>).</p>
				<div class="form-group">
					<label>Archivo Excel (.xls)</label>
					<input type="file" id="archivoChecador" accept=".xls,.xlsx">
				</div>
				<div id="checadorProgreso" style="display:none;">
					<div class="progress">
						<div class="progress-bar progress-bar-striped active" style="width:100%">Procesando...</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-success" id="btnImportarChecador" onclick="ImportarChecador()">
					<i class="fa fa-upload"></i> Importar
				</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal Bono Condicional -->
<div class="modal fade" id="modalBonoCondicional" tabindex="-1" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" style="background:#fff3e0;">
				<h4 class="modal-title"><i class="fa fa-question-circle" style="color:#e67e00;"></i> Bono Condicional</h4>
			</div>
			<div class="modal-body">
				<p>Los siguientes empleados tienen <strong>retardos</strong> pero <strong>no faltas</strong>. ¿Les otorga el bono?</p>
				<table class="table table-bordered table-condensed" id="tablaBonoCondicional">
					<thead>
						<tr>
							<th>Empleado</th>
							<th class="text-center">Retardos</th>
							<th class="text-center">Min. Retardo</th>
							<th class="text-center">¿Otorgar Bono?</th>
						</tr>
					</thead>
					<tbody id="bonoCondicionalBody">
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" onclick="GuardarBonoCondicional()">
					<i class="fa fa-check"></i> Guardar Decisiones
				</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
var asistenciasData = {};

$(document).ready(function() {
	$('#tablaResumen').DataTable({
		pageLength: 50,
		order: [[1, 'asc']],
		paging: false,
		dom: 'Bfrtip',
		buttons: [
			{ extend: 'csv', text: '<i class="fa fa-download"></i> CSV', className: 'btn btn-default btn-sm', filename: 'asistencia', bom: true },
			{ extend: 'excel', text: '<i class="fa fa-file-excel-o"></i> Excel', className: 'btn btn-default btn-sm', filename: 'asistencia' },
			{ extend: 'print', text: '<i class="fa fa-print"></i> Imprimir', className: 'btn btn-default btn-sm' }
		],
		language: {
			url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-MX.json'
		}
	});
});

function AbrirModalAsistencia(idEmpleado, nombre){
	$('#modalNombreEmpleado').text(nombre);
	var body = '';

	// Buscar datos de asistencia para este empleado
	var datos = asistenciasData[idEmpleado] || [];
	for(var i = 0; i < datos.length; i++){
		var d = datos[i];
		var esDom = (d.dia_semana == 7);
		var disabledTime = (d.tipo != 'NORMAL') ? 'disabled' : '';

		body += '<tr data-id="' + d.id_asistencia + '">';
		body += '<td><strong>' + d.dia_nombre + '</strong></td>';
		body += '<td>' + d.fecha_fmt + '</td>';
		body += '<td><select class="form-control input-sm modal-tipo" style="width:110px;" ' + (esDom ? 'disabled' : '') + '>';
		body += '<option value="NORMAL"' + (d.tipo == 'NORMAL' ? ' selected' : '') + '>Normal</option>';
		body += '<option value="FALTA"' + (d.tipo == 'FALTA' ? ' selected' : '') + '>Falta</option>';
		body += '<option value="VACACION"' + (d.tipo == 'VACACION' ? ' selected' : '') + '>Vacacion</option>';
		body += '<option value="FESTIVO"' + (d.tipo == 'FESTIVO' ? ' selected' : '') + '>Festivo</option>';
		body += '<option value="DESCANSO"' + (d.tipo == 'DESCANSO' ? ' selected' : '') + '>Descanso</option>';
		body += '</select></td>';
		body += '<td><input type="time" class="form-control input-sm modal-entrada" value="' + (d.entrada || '') + '" style="width:110px;" ' + disabledTime + '></td>';
		body += '<td><input type="time" class="form-control input-sm modal-salida" value="' + (d.salida || '') + '" style="width:110px;" ' + disabledTime + '></td>';
		body += '<td class="modal-horas text-center">' + d.horas + '</td>';
		body += '<td class="modal-retardo text-center">' + (d.retardo ? '<span class="label label-warning">' + d.min_retardo + ' min</span>' : '-') + '</td>';
		body += '</tr>';
	}

	$('#modalAsistenciaBody').html(body);

	// Evento para habilitar/deshabilitar campos segun tipo
	$('.modal-tipo').off('change').on('change', function(){
		var tr = $(this).closest('tr');
		var tipo = $(this).val();
		if(tipo == 'NORMAL'){
			tr.find('.modal-entrada, .modal-salida').prop('disabled', false);
		} else {
			tr.find('.modal-entrada, .modal-salida').val('').prop('disabled', true);
		}
	});

	$('#modalAsistencia').modal('show');
}

function GuardarAsistenciaEmpleado(){
	var datos = [];
	$('#modalAsistenciaBody tr').each(function(){
		var tr = $(this);
		datos.push({
			id_asistencia: tr.data('id'),
			tipo: tr.find('.modal-tipo').val(),
			entrada: tr.find('.modal-entrada').val(),
			salida: tr.find('.modal-salida').val()
		});
	});

	$.ajax({
		url: "<?php echo $this->createUrl('Rhnomina/Guardarasistenciaempleado'); ?>",
		type: "POST",
		dataType: "json",
		data: { asistencias: datos },
		success: function(r){
			if(r.requestresult == 'ok'){
				$.notify(r.message, 'success');
				$('#modalAsistencia').modal('hide');
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

function ImportarAsistencia(){
	var datos = $('#importDatos').val();
	if(datos.trim() == ''){
		$.notify('Pegue los datos primero', 'error');
		return;
	}

	$.ajax({
		url: "<?php echo $this->createUrl('Rhnomina/Importarasistencia'); ?>",
		type: "POST",
		dataType: "json",
		data: { id_periodo: <?= $periodo->id_periodo; ?>, datos: datos },
		success: function(r){
			if(r.requestresult == 'ok'){
				$.notify(r.message, 'success');
				$('#modalImportar').modal('hide');
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

function ImportarChecador(){
	var archivo = $('#archivoChecador')[0].files[0];
	if(!archivo){
		$.notify('Seleccione un archivo Excel', 'error');
		return;
	}

	var formData = new FormData();
	formData.append('archivo_checador', archivo);
	formData.append('id_periodo', <?= $periodo->id_periodo; ?>);

	$('#checadorProgreso').show();
	$('#btnImportarChecador').prop('disabled', true);

	$.ajax({
		url: "<?php echo $this->createUrl('Rhnomina/Importarchecador'); ?>",
		type: "POST",
		dataType: "json",
		data: formData,
		processData: false,
		contentType: false,
		success: function(r){
			$('#checadorProgreso').hide();
			$('#btnImportarChecador').prop('disabled', false);
			if(r.requestresult == 'ok'){
				$.notify(r.message, 'success');
				$('#modalChecador').modal('hide');

				// Si hay empleados con retardos, mostrar modal de bono condicional
				if(r.empleados_retardo && r.empleados_retardo.length > 0){
					MostrarBonoCondicional(r.empleados_retardo);
				} else {
					setTimeout(function(){ location.reload(); }, 1500);
				}
			} else {
				$.notify(r.message, 'error');
			}
		},
		error: function(){
			$('#checadorProgreso').hide();
			$('#btnImportarChecador').prop('disabled', false);
			$.notify('Ocurrio un error inesperado', 'error');
		}
	});
}

function MostrarBonoCondicional(empleados){
	var body = '';
	for(var i = 0; i < empleados.length; i++){
		var emp = empleados[i];
		body += '<tr data-id="' + emp.id_empleado + '">';
		body += '<td><strong>' + emp.nombre + '</strong></td>';
		body += '<td class="text-center"><span class="label label-warning">' + emp.retardos + '</span></td>';
		body += '<td class="text-center">' + emp.minutos + ' min</td>';
		body += '<td class="text-center">';
		body += '<div class="btn-group btn-group-sm">';
		body += '<button type="button" class="btn btn-success btn-bono active" data-valor="SI" onclick="SeleccionarBono(this)"><i class="fa fa-check"></i> Si</button>';
		body += '<button type="button" class="btn btn-default btn-bono" data-valor="NO" onclick="SeleccionarBono(this)"><i class="fa fa-times"></i> No</button>';
		body += '</div>';
		body += '</td>';
		body += '</tr>';
	}
	$('#bonoCondicionalBody').html(body);
	$('#modalBonoCondicional').modal('show');
}

function SeleccionarBono(btn){
	var grupo = $(btn).closest('.btn-group');
	grupo.find('.btn-bono').removeClass('active btn-success btn-danger').addClass('btn-default');
	$(btn).removeClass('btn-default');
	if($(btn).data('valor') == 'SI'){
		$(btn).addClass('btn-success active');
	} else {
		$(btn).addClass('btn-danger active');
	}
}

function GuardarBonoCondicional(){
	var decisiones = [];
	$('#bonoCondicionalBody tr').each(function(){
		var tr = $(this);
		var idEmpleado = tr.data('id');
		var botonActivo = tr.find('.btn-bono.active');
		var valor = botonActivo.data('valor') || 'SI';
		decisiones.push({ id_empleado: idEmpleado, bono: valor });
	});

	$.ajax({
		url: "<?php echo $this->createUrl('Rhnomina/Guardarbonocondicional'); ?>",
		type: "POST",
		dataType: "json",
		data: { id_periodo: <?= $periodo->id_periodo; ?>, decisiones: decisiones },
		success: function(r){
			if(r.requestresult == 'ok'){
				$.notify(r.message, 'success');
				$('#modalBonoCondicional').modal('hide');
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

// Cargar datos de asistencia en JavaScript
<?php foreach ($empleados as $emp) {
	$idEmp = $emp->id_empleado;
	echo "asistenciasData[" . $idEmp . "] = [\n";
	foreach ($dias as $dia) {
		$a = isset($asistencias[$idEmp][$dia]) ? $asistencias[$idEmp][$dia] : null;
		if (empty($a)) continue;
		$diaSemana = date('N', strtotime($dia));
		$diaNombre = isset($diasNombres[$diaSemana]) ? $diasNombres[$diaSemana] : '';
		$fechaFmt = date('d/m', strtotime($dia));
		echo "{";
		echo "id_asistencia:" . $a->id_asistencia . ",";
		echo "fecha:'" . $dia . "',";
		echo "fecha_fmt:'" . $fechaFmt . "',";
		echo "dia_nombre:'" . $diaNombre . "',";
		echo "dia_semana:" . $diaSemana . ",";
		echo "tipo:'" . $a->asistencia_tipo . "',";
		echo "entrada:'" . ($a->asistencia_entrada ?: '') . "',";
		echo "salida:'" . ($a->asistencia_salida ?: '') . "',";
		echo "horas:" . ($a->asistencia_horas ?: 0) . ",";
		echo "retardo:" . ($a->asistencia_retardo ?: 0) . ",";
		echo "min_retardo:" . ($a->asistencia_minutos_retardo ?: 0);
		echo "},\n";
	}
	echo "];\n";
} ?>
</script>

<div class="row">
	<div class="col-md-8">
		<h1>Asistencia</h1>
		<p class="lead">Periodo: <strong><?= $periodo->getEtiqueta(); ?></strong>
		<?php if ($periodo->periodo_estatus == 'CERRADO'): ?>
			<span class="label label-default">CERRADO</span>
		<?php endif; ?>
		</p>
	</div>
	<div class="col-md-4 text-right" style="padding-top:20px;">
		<?php if ($periodo->periodo_estatus == 'ABIERTO'): ?>
			<button class="btn btn-success" data-toggle="modal" data-target="#modalChecador">
				<i class="fa fa-file-excel-o"></i> Importar Checador
			</button>
			<button class="btn btn-info" data-toggle="modal" data-target="#modalImportar">
				<i class="fa fa-upload"></i> Importar CSV
			</button>
		<?php endif; ?>
		<a href="<?= Yii::app()->createUrl('rhnomina/nomina', array('id' => $periodo->id_periodo)); ?>" class="btn btn-primary">
			<i class="fa fa-money"></i> Ir a Nomina
		</a>
	</div>
</div>

<hr>

<div class="table-responsive">
<table id="tablaResumen" class="table table-bordered table-hover table-condensed">
	<thead>
		<tr>
			<th>Num.</th>
			<th>Nombre</th>
			<?php foreach ($dias as $dia) {
				$ds = date('N', strtotime($dia));
				$dn = isset($diasNombres[$ds]) ? $diasNombres[$ds] : '';
				$df = date('d', strtotime($dia));
			?>
				<th class="text-center" style="min-width:70px;<?= $ds >= 6 ? 'background:#1a252f;' : ''; ?>"><?= $dn; ?><br><small><?= $df; ?></small></th>
			<?php } ?>
			<th class="text-center">Hrs</th>
			<th class="text-center">Faltas</th>
			<th class="text-center">Ret.</th>
			<th>Acciones</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($empleados as $emp) {
			$idEmp = $emp->id_empleado;
			$totalHoras = 0;
			$totalFaltas = 0;
			$totalRetardos = 0;
		?>
		<tr>
			<td><?= $emp->empleado_num_empleado; ?></td>
			<td style="white-space:nowrap;"><strong><?= $emp->empleado_nombre; ?></strong></td>
			<?php foreach ($dias as $dia) {
				$a = isset($asistencias[$idEmp][$dia]) ? $asistencias[$idEmp][$dia] : null;
				$ds = date('N', strtotime($dia));
				$bg = $ds >= 6 ? 'background:#f5f5f5;' : '';

				if (empty($a) || $a->asistencia_tipo == 'DESCANSO') {
					echo '<td class="text-center" style="' . $bg . 'color:#ccc;">-</td>';
				} elseif ($a->asistencia_tipo == 'FALTA') {
					echo '<td class="text-center" style="' . $bg . 'background:#ffe0e0;"><strong style="color:red;">F</strong></td>';
					$totalFaltas++;
				} elseif ($a->asistencia_tipo == 'VACACION') {
					echo '<td class="text-center" style="' . $bg . 'background:#e0f0ff;"><strong style="color:blue;">V</strong></td>';
				} elseif ($a->asistencia_tipo == 'FESTIVO') {
					echo '<td class="text-center" style="' . $bg . 'background:#fff3e0;"><strong style="color:orange;">FE</strong></td>';
				} elseif ($a->asistencia_tipo == 'NORMAL' && ($a->asistencia_horas > 0 || !empty($a->asistencia_entrada) || !empty($a->asistencia_salida))) {
					$esRetardo = $a->asistencia_retardo ? true : false;
					$bgRetardo = $esRetardo ? 'background:#fff3e0;' : '';
					$colorEntrada = $esRetardo ? 'color:#e67e00;font-weight:bold;' : 'color:#333;';
					$ent = !empty($a->asistencia_entrada) ? substr($a->asistencia_entrada, 0, 5) : '--:--';
					$sal = !empty($a->asistencia_salida) ? substr($a->asistencia_salida, 0, 5) : '--:--';
					echo '<td class="text-center" style="' . $bg . $bgRetardo . 'font-size:11px;line-height:1.3;padding:2px 3px;">';
					echo '<span style="' . $colorEntrada . '">' . $ent . '</span><br>';
					echo '<span style="color:#888;">' . $sal . '</span>';
					echo '</td>';
					$totalHoras += $a->asistencia_horas;
					if ($esRetardo) $totalRetardos++;
				} else {
					echo '<td class="text-center" style="' . $bg . 'background:#ffe0e0;"><strong style="color:red;">F</strong></td>';
					$totalFaltas++;
				}
			} ?>
			<td class="text-center"><strong><?= number_format($totalHoras, 1); ?></strong></td>
			<td class="text-center">
				<?= $totalFaltas > 0 ? '<span class="label label-danger">' . $totalFaltas . '</span>' : '<span class="text-success">0</span>'; ?>
			</td>
			<td class="text-center">
				<?= $totalRetardos > 0 ? '<span class="label label-warning">' . $totalRetardos . '</span>' : '<span class="text-success">0</span>'; ?>
			</td>
			<td>
				<?php if ($periodo->periodo_estatus == 'ABIERTO'): ?>
					<button class="btn btn-xs btn-warning" onclick="AbrirModalAsistencia(<?= $idEmp; ?>, '<?= addslashes($emp->empleado_nombre); ?>')">
						<i class="fa fa-pencil"></i>
					</button>
				<?php endif; ?>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
</div>

<div class="row" style="margin-top:10px;">
	<div class="col-md-12">
		<span class="label label-default" style="font-size:11px;">Leyenda:</span>
		<span style="margin-left:10px; font-size:12px;">
			<span style="color:#333;">07:44</span>/<span style="color:#888;">18:00</span> = Entrada/Salida &nbsp;|&nbsp;
			<span style="background:#fff3e0;padding:1px 4px;"><strong style="color:#e67e00;">08:15</strong></span> = Retardo &nbsp;|&nbsp;
			<strong style="color:red;">F</strong> = Falta &nbsp;|&nbsp;
			<strong style="color:blue;">V</strong> = Vacacion &nbsp;|&nbsp;
			<strong style="color:orange;">FE</strong> = Festivo &nbsp;|&nbsp;
			<span style="color:#ccc;">-</span> = Descanso
		</span>
	</div>
</div>
