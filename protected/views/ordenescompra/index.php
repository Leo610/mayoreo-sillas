<?php
$this->pageTitle = 'Ordenes de Compra';
$this->pageDescription = '';
$this->breadcrumbs = array(
	$this->pageTitle
);
$this->opcionestitulo = '<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#crearordenescompra">Crear OC</button>';
// menu de inventario
$this->renderpartial('//inventario/menu', array('op_menu' => 6));
// incluimos la orden de compra
include 'modal/agregarordencompra.php';
?>
<div class="row">
	<div class="col-md-8">
		<h1>
			<?= $this->pageTitle ?> | <a class="btn btn-success btn-sm" data-toggle="modal"
				data-target="#crearordenescompra" class="btn btn-success ">
				<i class="fa fa-list-ol"></i> Crear orden de compra
			</a>
		</h1>
	</div>
</div>
<div class="panel">
	<form method="get">
		<div class="row mb-2">
			<div class="col-md-2">
				<label>Desde</label>
				<?php $this->widget(
					'zii.widgets.jui.CJuiDatePicker',
					array(
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
				<?php $this->widget(
					'zii.widgets.jui.CJuiDatePicker',
					array(
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
					<?php foreach ($estatusoc as $key => $value) { ?>
						<option value="<?= $key ?>" <?= ($estatus == $key) ? 'selected' : ''; ?>>
							<?= $value ?>
						</option>
					<?php } ?>
				</select>
			</div>
			<?php if ($this->Verificaracceso(19, Yii::app()->user->id) == 1) { ?>
				<div class="col-md-2">
					<label>Sucursal</label>
					<select name="sucursal" class="form-control" onchange="this.form.submit()">
						<option value="0">-- Todas las sucursales --</option>
						<?php foreach ($sucursalesdropdown as $key => $value) { ?>
							<option value="<?= $key ?>" <?= ($sucursal == $key) ? 'selected' : ''; ?>>
								<?= $value ?>
							</option>
						<?php } ?>
					</select>
				</div>
			<?php } ?>
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
	<div class="row">
		<div class="col-md-12">
			<div class="table-responsive">
				<table id="resultado" class="table table-sm table-hover datatable">
					<thead>
						<tr>
							<th># OC</th>
							<th>Fecha</th>
							<th>Sucursal</th>
							<th>Solicitante</th>
							<th>Proveedor</th>
							<th>Total</th>
							<th>Total Pagado</th>
							<th>Total Pendiente</th>
							<th>Tipo</th>
							<th>Estatus</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($ordenescompra as $rows) { ?>
							<tr>
								<td>
									<?= $rows['id'] ?>
								</td>
								<td>
									<?= $rows['fecha_alta']; ?>
								</td>
								<td>
									<?= $rows['idSucursal']['nombre'] ?>
								</td>
								<td>
									<?= $rows['idUsuariosolicita']['Usuario_Nombre'] ?>
								</td>
								<td>
									<?= $rows['idProveedor']['proveedor_nombre'] ?>
								</td>
								<td>$
									<?= number_format($rows['total'], 2) ?>
								</td>
								<td>$
									<?= number_format($rows['total_pagado'], 2) ?>
								</td>
								<td>$
									<?= number_format($rows['total_pendiente'], 2) ?>
								</td>
								<td>
									<?php if ($rows['tipo_oc'] == 0) {
										echo '<span class="badge badge-default">NORMAL</span>';
									} elseif ($rows['tipo_oc'] == 1) {
										echo '<span class="badge badge-danger">URGENTE</span>';
									}
									?>
								</td>
								<td>
									<?php
									$estatus = $this->EstatusOC($rows['estatus']);
									echo $estatus['badge'] ?>
								</td>
								<td>
									<?php
									echo CHtml::link(
										$estatus['boton'],
										yii::app()->createurl('ordenescompra/detalles/' . $rows['id']),
										array(
											'style' => 'margin-right:3px;',
											'class' => 'btn btn-default btn-xs',
										)
									);

									/*echo CHtml::link('<i class="fa fa-money" aria-hidden="true"></i> Pagos',yii::app()->createurl('ordenescompra/pagar/',array('numero_oc'=>$rows['id'],'buscar'=>1)), array(
																																																							   'style' => 'margin-right:3px;',
																																																							   'class'=>'btn btn-default btn-xs',
																																																						   ));*/

									//
									if ($rows['estatus'] == 4 || $rows['estatus'] == 5) {
										echo '<a href="' . Yii::app()->createUrl('ordenescompra/pdf', array('id' => $rows['id'])) . '" target="_blank" class="btn btn-danger btn-xs"><i class="fa fa-print" aria-hidden="true"></i>  </a>';
									}
									?>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>