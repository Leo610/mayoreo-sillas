<?php
$this->pageTitle = 'Transferencias';
$this->pageDescription = '';
$this->breadcrumbs = array(
	$this->pageTitle
);
$this->opcionestitulo = '<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#creartransferencia">Crear transferencia</button>';
// menu de inventario
$this->renderpartial('//inventario/menu', array('op_menu' => 5));
// incluimos la transferencia
include 'modal/agregartransferencia.php';

?>
<div class="row">
	<div class="col-md-8">
		<h1>
			<?= $this->pageTitle ?> | <a class="btn btn-success btn-sm" data-toggle="modal"
				data-target="#creartransferencia">
				<i class="fa fa-list-ol"></i> Crear transferencia
			</a>
		</h1>
	</div>
</div>
<div class="panel">
	<form method="get">
		<div class="row mb-2">
			<div class="col-md-2">
				<label>Desde</label>
				<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
					'name' => 'fecha_desde',
					'language' => 'es',
					'htmlOptions' => array(
						'dateFormat' => 'yy-mm-dd',
						'class' => 'form-control',
						'size' => '30',
						// textdomain(text_domain)tField size
						'maxlength' => '10',
						// textField maxlength
						'placeholder' => 'Fecha desde',
						'onchange' => 'this.form.submit()'
					),
					'value' => $fecha_desde,
					'options' => array(
						'dateFormat' => 'yy-mm-dd',
						'onClose' => 'js:function(selectedDate) { $("#fecha_hasta").datepicker("option", "minDate", selectedDate); }',
					),
				)
				);
				?>
			</div>
			<div class="col-md-2">
				<label>Hasta</label>
				<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
					'name' => 'fecha_hasta',
					'language' => 'es',
					'htmlOptions' => array(
						'dateFormat' => 'yy-mm-dd',
						'class' => 'form-control',
						'size' => '30',
						// textdomain(text_domain)tField size
						'maxlength' => '10',
						// textField maxlength
						'placeholder' => 'Fecha desde',
						'onchange' => 'this.form.submit()'
					),
					'value' => $fecha_hasta,
					'options' => array(
						'dateFormat' => 'yy-mm-dd',
						'onClose' => 'js:function(selectedDate) { $("#fecha_desde").datepicker("option", "maxDate", selectedDate); }',
					),
				)
				);
				?>
			</div>
			<div class="col-md-2">
				<label>Estatus</label>
				<select name="estatus" class="form-control" onchange="this.form.submit()">
					<option value="0">-- Todos los estatus --</option>
					<?php foreach ($estatustr as $key => $value) { ?>
						<option value="<?= $key ?>" <?= ($estatus == $key) ? 'selected' : ''; ?>>
							<?= $value ?>
						</option>
					<?php } ?>
				</select>
			</div>
			<div class="col-md-2">
				<label>Sucursal Origen</label>
				<select name="sucursal_origen" class="form-control" onchange="this.form.submit()">
					<option value="0">-- Todas las sucursales --</option>
					<?php foreach ($sucursalesdropdown as $key => $value) { ?>
						<option value="<?= $key ?>" <?= ($sucursal_origen == $key) ? 'selected' : ''; ?>>
							<?= $value ?>
						</option>
					<?php } ?>
				</select>
			</div>
			<div class="col-md-2">
				<label>Sucursal Destino</label>
				<select name="sucursal_destino" class="form-control" onchange="this.form.submit()">
					<option value="0">-- Todas las sucursales --</option>
					<?php foreach ($sucursalesdropdown as $key => $value) { ?>
						<option value="<?= $key ?>" <?= ($sucursal_destino == $key) ? 'selected' : ''; ?>>
							<?= $value ?>
						</option>
					<?php } ?>
				</select>
			</div>
			<div class="col-md-2">
				<label>Prioridad</label>
				<select name="tipos" class="form-control" onchange="this.form.submit()">
					<option value="9">-- Todas las prioridades --</option>
					<option value="0" <?= ($tipos == 0) ? 'selected' : ''; ?>>Normales</option>
					<option value="1" <?= ($tipos == 1) ? 'selected' : ''; ?>>Urgentes</option>
				</select>
			</div>
		</div>
	</form>
	<div class="col-md-12">
		<div class="table-responsive">
			<table id="resultado" class="table table-sm table-hover datatable">
				<thead>
					<tr>
						<th># TR</th>
						<th>Fecha</th>
						<th>Sucursal Origen</th>
						<th>Sucursal Destino</th>
						<th>Solicitante</th>
						<th>Tipo</th>
						<th>Estatus</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($transferencias as $rows) { ?>
						<tr>
							<td>
								<?= $rows['id'] ?>
							</td>
							<td>
								<?= $rows['fecha_solicitud']; ?>
							</td>
							<td>
								<?= $rows['idSucursalorigen']['nombre'] ?>
							</td>
							<td>
								<?= $rows['idSucursaldestino']['nombre'] ?>
							</td>
							<td>
								<?= $rows['idUsuariosolicita']['Usuario_Nombre'] ?>
							</td>
							<td>
								<?php if ($rows['tipo'] == 0) {
									echo '<span class="badge badge-default">NORMAL</span>';
								} elseif ($rows['tipo'] == 1) {
									echo '<span class="badge badge-danger">URGENTE</span>';
								}
								?>
							</td>
							<td>
								<?php
								$estatus = $this->EstatusTR($rows['estatus']);
								echo $estatus['badge'] ?>
							</td>
							<td>
								<?php
								echo CHtml::link($estatus['boton'], yii::app()->createurl('transferencias/detalles/' . $rows['id']), array(
									'style' => 'margin-right:3px;',
									'class' => 'btn btn-default btn-xs',
								)
								);
								echo '<a href="' . Yii::app()->createUrl('transferencias/pdf', array('id' => $rows['id'])) . '" target="_blank" class="btn btn-danger btn-xs"><i class="fa fa-print" aria-hidden="true"></i></a>';
								?>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>