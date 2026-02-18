<?php
/* @var $this RhvacacionesController */
$this->pageTitle = 'Historial de Empleados';
$this->breadcrumbs = array(
	'RH' => Yii::app()->createUrl('rhempleados/admin'),
	'Historial de Empleados',
);
?>

<script type="text/javascript">
$(document).ready(function() {
	$('#tablaHistorial').DataTable({
		pageLength: 50,
		order: [[7, 'desc']],
		dom: 'Bfrtip',
		buttons: [
			{
				extend: 'csv',
				text: '<i class="fa fa-download"></i> CSV',
				className: 'btn btn-default btn-sm',
				filename: 'historial_empleados',
				bom: true
			},
			{
				extend: 'excel',
				text: '<i class="fa fa-file-excel-o"></i> Excel',
				className: 'btn btn-default btn-sm',
				filename: 'historial_empleados'
			},
			{
				extend: 'print',
				text: '<i class="fa fa-print"></i> Imprimir',
				className: 'btn btn-default btn-sm'
			}
		],
		language: {
			url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-MX.json'
		}
	});
});
</script>

<div class="row">
	<div class="col-md-12">
		<h1>Historial de Empleados</h1>
	</div>
</div>

<!-- Filtros -->
<div class="row" style="margin-bottom:15px;">
	<form method="GET" action="<?= Yii::app()->createUrl('rhvacaciones/historial'); ?>">
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
			<label>Tipo Movimiento:</label>
			<select name="tipo" class="form-control" onchange="this.form.submit()">
				<option value="">-- Todos --</option>
				<option value="ALTA" <?= $filtro_tipo == 'ALTA' ? 'selected' : ''; ?>>ALTA</option>
				<option value="BAJA" <?= $filtro_tipo == 'BAJA' ? 'selected' : ''; ?>>BAJA</option>
				<option value="REINGRESO" <?= $filtro_tipo == 'REINGRESO' ? 'selected' : ''; ?>>REINGRESO</option>
				<option value="MODIFICACION" <?= $filtro_tipo == 'MODIFICACION' ? 'selected' : ''; ?>>MODIFICACION</option>
			</select>
		</div>
	</form>
</div>

<hr>

<div class="table-responsive">
<table id="tablaHistorial" class="table table-bordered table-hover">
	<thead>
		<tr>
			<th>NUM.EMPLEADO</th>
			<th>NOMBRE</th>
			<th>NSS</th>
			<th>FECHA INGRESO</th>
			<th>PUESTO</th>
			<th>SUELDO SEMANAL</th>
			<th>MOVIMIENTO</th>
			<th>FECHA MOVIMIENTO</th>
			<th>ESTADO</th>
			<th>NOTA DE EMPLEADO</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($lista as $hist) {
			$emp = $hist->rl_empleado;
			if (empty($emp)) continue;
		?>
		<tr>
			<td><?= $emp->empleado_num_empleado; ?></td>
			<td style="font-weight:bold;"><?= $emp->empleado_nombre; ?></td>
			<td><?= $emp->empleado_seguro_social; ?></td>
			<td><?= !empty($emp->empleado_fecha_ingreso) ? date('d/m/Y', strtotime($emp->empleado_fecha_ingreso)) : ''; ?></td>
			<td><?= $emp->empleado_puesto; ?></td>
			<td style="text-align:right;">$ <?= number_format($emp->empleado_sueldo_semanal, 2); ?></td>
			<td>
				<?php
					switch ($hist->historial_tipo) {
						case 'ALTA':
							echo '<span class="label label-success">ALTA</span>';
							break;
						case 'BAJA':
							echo '<span class="label label-danger">BAJA</span>';
							break;
						case 'REINGRESO':
							echo '<span class="label label-info">REINGRESO</span>';
							break;
						case 'MODIFICACION':
							echo '<span class="label label-warning">MODIFICACION</span>';
							break;
						default:
							echo $hist->historial_tipo;
					}
				?>
			</td>
			<td><?= !empty($hist->historial_fecha_movimiento) ? date('d/m/Y', strtotime($hist->historial_fecha_movimiento)) : ''; ?></td>
			<td>
				<?php if ($hist->historial_estatus_nuevo == 'ACTIVO'): ?>
					<span class="label label-success">ACTIVO</span>
				<?php elseif ($hist->historial_estatus_nuevo == 'INACTIVO'): ?>
					<span class="label label-danger">INACTIVO</span>
				<?php else: ?>
					<?= $hist->historial_estatus_nuevo; ?>
				<?php endif; ?>
			</td>
			<td style="white-space:pre-line; font-size:12px;"><?= CHtml::encode($hist->historial_observaciones); ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
</div>
