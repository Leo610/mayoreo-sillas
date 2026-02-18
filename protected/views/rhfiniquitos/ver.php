<?php
/* @var $this RhfiniquitosController */
/* @var $finiquito RhFiniquitos */

$emp = $finiquito->rl_empleado;
$this->pageTitle = 'Detalle Finiquito - ' . ($emp ? $emp->empleado_nombre : '');
$this->breadcrumbs = array(
	'RH' => Yii::app()->createUrl('rhempleados/admin'),
	'Finiquitos' => Yii::app()->createUrl('rhfiniquitos/index'),
	'Detalle #' . $finiquito->id_finiquito,
);

$diarioImss = (float)$finiquito->finiquito_sueldo_semanal_imss > 0 ? round((float)$finiquito->finiquito_sueldo_semanal_imss / 7, 2) : 0;
$diarioReal = (float)$finiquito->finiquito_sueldo_diario;

// Compensaciones individuales (REAL - IMSS)
$compAguinaldo = (float)$finiquito->finiquito_aguinaldo - (float)$finiquito->finiquito_aguinaldo_imss;
$compVacaciones = (float)$finiquito->finiquito_pago_vacaciones - (float)$finiquito->finiquito_vacaciones_imss;
$compPrima = (float)$finiquito->finiquito_prima_vacacional - (float)$finiquito->finiquito_prima_imss;
?>

<div class="row">
	<div class="col-md-12">
		<a href="<?= Yii::app()->createUrl('rhfiniquitos/index'); ?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> Regresar a lista</a>
		<a href="<?= Yii::app()->createUrl('rhfiniquitos/formato', array('id' => $finiquito->id_finiquito)); ?>" class="btn btn-primary pull-right" target="_blank">
			<i class="fa fa-file-pdf-o"></i> Generar PDF
		</a>
		<hr>
	</div>
</div>

<!-- DATOS DEL EMPLEADO -->
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-info">
			<div class="panel-heading"><strong><i class="fa fa-user"></i> Datos del Empleado</strong></div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-3">
						<p><strong>Nombre:</strong> <?= $emp ? CHtml::encode($emp->empleado_nombre) : 'N/A'; ?></p>
					</div>
					<div class="col-md-2">
						<p><strong>No. Empleado:</strong> <?= $emp ? CHtml::encode($emp->empleado_num_empleado) : ''; ?></p>
					</div>
					<div class="col-md-2">
						<p><strong>NSS:</strong> <?= $emp ? CHtml::encode($emp->empleado_seguro_social) : ''; ?></p>
					</div>
					<div class="col-md-2">
						<p><strong>Fecha Ingreso:</strong> <?= $emp ? date('d/m/Y', strtotime($emp->empleado_fecha_ingreso)) : ''; ?></p>
					</div>
					<div class="col-md-3">
						<p><strong>Fecha Renuncia:</strong> <?= date('d/m/Y', strtotime($finiquito->finiquito_fecha_renuncia)); ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- PARAMETROS -->
<div class="row">
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading text-center"><strong>Dias Proporcionales</strong></div>
			<div class="panel-body text-center">
				<h3 style="margin:0;"><?= (int)$finiquito->finiquito_dias_proporcionales; ?></h3>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading text-center"><strong>Dias Aguinaldo</strong></div>
			<div class="panel-body text-center">
				<h3 style="margin:0;"><?= number_format((float)$finiquito->finiquito_aguinaldo_dias, 2); ?></h3>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading text-center"><strong>Dias Vacaciones</strong></div>
			<div class="panel-body text-center">
				<h3 style="margin:0;"><?= number_format((float)$finiquito->finiquito_dias_vacaciones, 2); ?></h3>
				<small><?= number_format((float)$finiquito->finiquito_dias_pendientes, 0); ?> pendientes</small>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-heading text-center"><strong>Fecha Aguinaldo</strong></div>
			<div class="panel-body text-center">
				<h3 style="margin:0;font-size:18px;"><?= date('d/m/Y', strtotime($finiquito->finiquito_fecha_aguinaldo)); ?></h3>
			</div>
		</div>
	</div>
</div>

<!-- TABLA COMPARATIVA: IMSS vs REAL vs COMPENSACION (replica Excel) -->
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-success">
			<div class="panel-heading"><strong><i class="fa fa-money"></i> Desglose del Finiquito</strong></div>
			<div class="panel-body">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th style="width:28%;">Concepto</th>
							<th style="width:18%; text-align:right; background:#c0952a;">IMSS</th>
							<th style="width:18%; text-align:right; background:#27ae60;">Sueldo Real</th>
							<th style="width:18%; text-align:right; background:#2980b9;">Compensacion</th>
						</tr>
					</thead>
					<tbody>
						<!-- SUELDOS -->
						<tr>
							<td><strong>Salario Semanal</strong></td>
							<td class="text-right">$<?= number_format((float)$finiquito->finiquito_sueldo_semanal_imss, 2); ?></td>
							<td class="text-right">$<?= number_format((float)$finiquito->finiquito_sueldo_semanal_real, 2); ?></td>
							<td class="text-right"></td>
						</tr>
						<tr>
							<td><strong>Salario Diario</strong></td>
							<td class="text-right">$<?= number_format($diarioImss, 2); ?></td>
							<td class="text-right">$<?= number_format($diarioReal, 2); ?></td>
							<td class="text-right"></td>
						</tr>

						<!-- AGUINALDO -->
						<tr>
							<td><strong>Dias de Aguinaldo</strong></td>
							<td class="text-right"><?= number_format((float)$finiquito->finiquito_aguinaldo_dias, 2); ?></td>
							<td class="text-right"><?= number_format((float)$finiquito->finiquito_aguinaldo_dias, 2); ?></td>
							<td class="text-right"></td>
						</tr>

						<!-- VACACIONES -->
						<tr>
							<td><strong>Dias de Vacaciones</strong></td>
							<td class="text-right"><?= number_format((float)$finiquito->finiquito_dias_vacaciones, 2); ?></td>
							<td class="text-right"><?= number_format((float)$finiquito->finiquito_dias_vacaciones, 2); ?></td>
							<td class="text-right"></td>
						</tr>

						<!-- SEPARADOR FINIQUITO -->
						<tr style="background:#ecf0f1;">
							<td colspan="4"><strong>FINIQUITO</strong></td>
						</tr>

						<!-- AGUINALDO MONTO -->
						<tr>
							<td>AGUINALDO</td>
							<td class="text-right" style="background:#fffde7;"><strong>$<?= number_format((float)$finiquito->finiquito_aguinaldo_imss, 2); ?></strong></td>
							<td class="text-right" style="background:#e8f5e9;"><strong>$<?= number_format((float)$finiquito->finiquito_aguinaldo, 2); ?></strong></td>
							<td class="text-right" style="background:#e3f2fd;"><strong>$<?= number_format($compAguinaldo, 2); ?></strong></td>
						</tr>

						<!-- VACACIONES MONTO -->
						<tr>
							<td>VACACIONES</td>
							<td class="text-right" style="background:#fffde7;"><strong>$<?= number_format((float)$finiquito->finiquito_vacaciones_imss, 2); ?></strong></td>
							<td class="text-right" style="background:#e8f5e9;"><strong>$<?= number_format((float)$finiquito->finiquito_pago_vacaciones, 2); ?></strong></td>
							<td class="text-right" style="background:#e3f2fd;"><strong>$<?= number_format($compVacaciones, 2); ?></strong></td>
						</tr>

						<!-- PRIMA VACACIONAL -->
						<tr>
							<td>PRIMA VAC 25%</td>
							<td class="text-right" style="background:#fffde7;"><strong>$<?= number_format((float)$finiquito->finiquito_prima_imss, 2); ?></strong></td>
							<td class="text-right" style="background:#e8f5e9;"><strong>$<?= number_format((float)$finiquito->finiquito_prima_vacacional, 2); ?></strong></td>
							<td class="text-right" style="background:#e3f2fd;"><strong>$<?= number_format($compPrima, 2); ?></strong></td>
						</tr>
					</tbody>
					<tfoot>
						<!-- TOTALES -->
						<tr style="background:#f0f0f0; font-size:13pt;">
							<td><strong>FINIQUITO TOTAL</strong></td>
							<td class="text-right" style="background:#fff9c4;"><strong>$<?= number_format((float)$finiquito->finiquito_total_imss, 2); ?></strong></td>
							<td class="text-right" style="background:#c8e6c9;"><strong>$<?= number_format((float)$finiquito->finiquito_total, 2); ?></strong></td>
							<td class="text-right" style="background:#bbdefb;"><strong>$<?= number_format((float)$finiquito->finiquito_compensacion, 2); ?></strong></td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>

<?php if (!empty($finiquito->finiquito_observaciones)): ?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading"><strong>Observaciones</strong></div>
			<div class="panel-body"><?= CHtml::encode($finiquito->finiquito_observaciones); ?></div>
		</div>
	</div>
</div>
<?php endif; ?>

<div class="row">
	<div class="col-md-12 text-center">
		<small class="text-muted">
			Registrado el <?= !empty($finiquito->finiquito_fecha_registro) ? date('d/m/Y H:i', strtotime($finiquito->finiquito_fecha_registro)) : ''; ?>
			<?= !empty($finiquito->rl_usuario) ? ' por ' . CHtml::encode($finiquito->rl_usuario->Usuario_Nombre) : ''; ?>
		</small>
	</div>
</div>
