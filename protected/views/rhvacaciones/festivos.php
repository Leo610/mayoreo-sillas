<?php
/* @var $this RhvacacionesController */
$this->pageTitle = 'Dias Festivos';
$this->breadcrumbs = array(
	'RH' => Yii::app()->createUrl('rhempleados/admin'),
	'Dias Festivos',
);
?>

<script type="text/javascript">
$(document).ready(function() {
	$('#tablaFestivos').DataTable({
		pageLength: 25,
		order: [[0, 'asc']],
		dom: 'Bfrtip',
		buttons: [
			{ extend: 'csv', text: '<i class="fa fa-download"></i> CSV', className: 'btn btn-default btn-sm', filename: 'dias_festivos', bom: true },
			{ extend: 'excel', text: '<i class="fa fa-file-excel-o"></i> Excel', className: 'btn btn-default btn-sm', filename: 'dias_festivos' },
			{ extend: 'print', text: '<i class="fa fa-print"></i> Imprimir', className: 'btn btn-default btn-sm' }
		],
		language: {
			url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-MX.json'
		}
	});
});

function AgregarFestivo(){
	var fecha = $('#festivo_fecha').val();
	var desc = $('#festivo_descripcion').val();

	if(fecha == '' || desc == ''){
		$.notify('Complete todos los campos', 'error');
		return;
	}

	$.ajax({
		url: "<?php echo $this->createUrl('Rhvacaciones/Crearfestivo'); ?>",
		type: "POST",
		dataType: "json",
		data: { festivo_fecha: fecha, festivo_descripcion: desc },
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

function EliminarFestivo(id){
	if(!confirm('Esta seguro que desea eliminar este dia festivo?')) return;
	$.ajax({
		url: "<?php echo $this->createUrl('Rhvacaciones/Eliminarfestivo'); ?>",
		type: "POST",
		dataType: "json",
		data: { id: id },
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
	<div class="col-md-12">
		<h1>Dias Festivos</h1>
	</div>
</div>

<!-- Filtro por anio -->
<div class="row" style="margin-bottom:15px;">
	<form method="GET" action="<?= Yii::app()->createUrl('rhvacaciones/festivos'); ?>">
		<div class="col-md-2">
			<label>Anio:</label>
			<select name="anio" class="form-control" onchange="this.form.submit()">
				<option value="">-- Todos --</option>
				<?php for ($y = date('Y') + 1; $y >= 2024; $y--) { ?>
					<option value="<?= $y; ?>" <?= $filtro_anio == $y ? 'selected' : ''; ?>><?= $y; ?></option>
				<?php } ?>
			</select>
		</div>
	</form>
</div>

<!-- Formulario agregar -->
<div class="row">
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading"><strong><i class="fa fa-plus"></i> Agregar Dia Festivo</strong></div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-4">
						<label>Fecha *</label>
						<input type="date" id="festivo_fecha" class="form-control">
					</div>
					<div class="col-md-5">
						<label>Descripcion *</label>
						<input type="text" id="festivo_descripcion" class="form-control" placeholder="Ej. Dia del Trabajo">
					</div>
					<div class="col-md-3">
						<label>&nbsp;</label><br>
						<button class="btn btn-success" onclick="AgregarFestivo()">
							<i class="fa fa-plus"></i> Agregar
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<hr>

<div class="table-responsive">
<table id="tablaFestivos" class="table table-bordered table-hover">
	<thead>
		<tr>
			<th>Fecha</th>
			<th>Descripcion</th>
			<th>Anio</th>
			<th>Acciones</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($lista as $f) { ?>
		<tr>
			<td><?= date('d/m/Y', strtotime($f->festivo_fecha)); ?></td>
			<td><?= $f->festivo_descripcion; ?></td>
			<td><?= $f->festivo_anio; ?></td>
			<td>
				<button class="btn btn-xs btn-danger" onclick="EliminarFestivo(<?= $f->id_dia_festivo; ?>)" title="Eliminar">
					<i class="fa fa-trash"></i> Eliminar
				</button>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
</div>
