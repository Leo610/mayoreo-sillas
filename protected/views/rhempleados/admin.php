<?php
/* @var $this RhempleadosController */
/* @var $model Empleados */

$this->pageTitle = 'Administracion de Empleados';
$this->breadcrumbs = array(
	'Modulos' => Yii::app()->createUrl('administracion/modulos'),
	'RH Empleados',
);
?>

<script type="text/javascript">
$(document).ready(function() {
	$("#abrirmodal").click(function() {
		$("#formmodal").modal('show');
		$('#Empleados-form')[0].reset();
		$("#Empleados_id_empleado").val('');
		$("#Empleados_empleado_estatus").val('ACTIVO');
	});

	$('#lista').DataTable({
		pageLength: 50,
		order: [[2, 'asc']],
		scrollX: true,
		dom: 'Bfrtip',
		buttons: [
			{ extend: 'csv', text: '<i class="fa fa-download"></i> CSV', className: 'btn btn-default btn-sm', filename: 'empleados', bom: true },
			{ extend: 'excel', text: '<i class="fa fa-file-excel-o"></i> Excel', className: 'btn btn-default btn-sm', filename: 'empleados' },
			{ extend: 'print', text: '<i class="fa fa-print"></i> Imprimir', className: 'btn btn-default btn-sm' }
		],
		language: {
			url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-MX.json'
		}
	});
});

function Actualizar(id){
	$.ajax({
		url: "<?php echo $this->createUrl('Rhempleados/Datos'); ?>",
		type: "POST",
		dataType: "json",
		timeout: (120 * 1000),
		data: { id: id },
		success: function(Response) {
			if (Response.requestresult == 'ok') {
				$("#Empleados_id_empleado").val(Response.Datos.id_empleado);
				$("#Empleados_empleado_num_reloj").val(Response.Datos.empleado_num_reloj);
				$("#Empleados_empleado_num_empleado").val(Response.Datos.empleado_num_empleado);
				$("#Empleados_empleado_nombre").val(Response.Datos.empleado_nombre);
				$("#Empleados_empleado_fecha_ingreso").val(Response.Datos.empleado_fecha_ingreso);
				$("#Empleados_empleado_estatus").val(Response.Datos.empleado_estatus);
				$("#Empleados_empleado_seguro_social").val(Response.Datos.empleado_seguro_social);
				$("#Empleados_empleado_sueldo_semanal").val(Response.Datos.empleado_sueldo_semanal);
				$("#Empleados_empleado_sueldo_semanal_real").val(Response.Datos.empleado_sueldo_semanal_real);
				$("#Empleados_empleado_sueldo_diario").val(Response.Datos.empleado_sueldo_diario);
				$("#Empleados_empleado_integrado").val(Response.Datos.empleado_integrado);
				$("#Empleados_empleado_costo_hora").val(Response.Datos.empleado_costo_hora);
				$("#Empleados_empleado_imss").val(Response.Datos.empleado_imss);
				$("#Empleados_empleado_infonavit").val(Response.Datos.empleado_infonavit);
				$("#Empleados_empleado_isr").val(Response.Datos.empleado_isr);
				$("#Empleados_empleado_rebaja_bono").val(Response.Datos.empleado_rebaja_bono);
				$("#Empleados_empleado_requiere_checador").val(Response.Datos.empleado_requiere_checador || 'SI');
				$("#Empleados_empleado_observaciones").val(Response.Datos.empleado_observaciones);
				$("#Empleados_empleado_rfc").val(Response.Datos.empleado_rfc);
				$("#Empleados_empleado_puesto").val(Response.Datos.empleado_puesto);
				$("#Empleados_empleado_tarjeta_efectivale").val(Response.Datos.empleado_tarjeta_efectivale);
				$("#Empleados_empleado_habitante_casa").val(Response.Datos.empleado_habitante_casa);
				$("#Empleados_empleado_bono_asistencia").val(Response.Datos.empleado_bono_asistencia);
				$("#Empleados_empleado_bono_puntualidad").val(Response.Datos.empleado_bono_puntualidad);
				$("#Empleados_empleado_bono_productividad").val(Response.Datos.empleado_bono_productividad);
				$("#Empleados_empleado_bono_condicional").val(Response.Datos.empleado_bono_condicional);
				$("#Empleados_empleado_dias_tomados").val(Response.Datos.empleado_dias_tomados);
				$("#Empleados_empleado_dias_pendientes").val(Response.Datos.empleado_dias_pendientes);
				$("#Empleados_empleado_dias_prima_vacacional").val(Response.Datos.empleado_dias_prima_vacacional);
				$("#formmodal").modal('show');
			}
		},
		error: function(){
			$.notify('Ocurrio un error inesperado','error');
		}
	});
}
</script>

<?php include 'modal/_form.php'; ?>
<?php include 'modal/_baja.php'; ?>

<div class="row">
	<div class="col-md-12">
		<h1>Administracion de Empleados | <a href="#" class="btn btn-success" id="abrirmodal">
			<i class="fa fa-plus"></i> Agregar Empleado
		</a></h1>

		<?php foreach(Yii::app()->user->getFlashes() as $key => $message){ ?>
		<div class="alert alert-<?= $key; ?> alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
			<?= $message; ?>
		</div>
		<?php } ?>

		<div class="row" style="margin-bottom:15px;">
			<div class="col-md-4">
				<form method="GET" action="<?= Yii::app()->createUrl('rhempleados/admin'); ?>">
					<label>Filtrar por estatus:</label>
					<select name="estatus" class="form-control" onchange="this.form.submit()">
						<option value="">-- Todos --</option>
						<option value="ACTIVO" <?= $filtro_estatus == 'ACTIVO' ? 'selected' : ''; ?>>ACTIVO</option>
						<option value="INACTIVO" <?= $filtro_estatus == 'INACTIVO' ? 'selected' : ''; ?>>INACTIVO</option>
					</select>
				</form>
			</div>
		</div>

		<hr>
		<div class="table-responsive">
		<table id="lista" class="table table-bordered table-hover table-condensed" style="font-size:12px;">
			<thead>
				<tr>
					<th>#</th>
					<th>Num. Emp.</th>
					<th>Nombre</th>
					<th>Puesto</th>
					<th>Habitante Casa</th>
					<th>Ingreso</th>
					<th>Antig.</th>
					<th>Estatus</th>
					<th>Sueldo IMSS</th>
					<th>Sueldo Real</th>
					<th>Vacaciones</th>
					<th>Proporcional</th>
					<th>Dias Tomados</th>
					<th>Dias Ptes.</th>
					<th>Bono Asist.</th>
					<th>Bono Punt.</th>
					<th>Bono Prod.</th>
					<th>Dias Prima</th>
					<th>Paga Prima</th>
					<th>Acciones</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$_esJueves = ((int)date('N') == 4);
				$cont = 1; foreach ($lista as $rows){
					$vacAcumuladas = $rows->getVacacionesAcumuladas();
					$proporcional = $rows->getProporcionalActual();
					$diasTomados = $rows->getTotalDiasTomados();
					$diasPtes = $rows->getDiasPendientes();
					// Dias Prima y Paga Prima solo se calculan los jueves (igual que el Excel)
					$diasPrima = 0;
					$pagaPrima = 0;
					$enPeriodo = false;
					if ($_esJueves) {
						$diasPrima = $rows->getDiasPrimaVacacional();
						$pagaPrima = $rows->getPagaPrima();
						$enPeriodo = $rows->isAniversarioEnPeriodo() && !$rows->isPrimaYaPagada();
					}
				?>
				<tr>
					<td><?= $cont++; ?></td>
					<td><?= $rows->empleado_num_empleado; ?></td>
					<td><?= CHtml::encode($rows->empleado_nombre); ?></td>
					<td><?= CHtml::encode($rows->empleado_puesto); ?></td>
					<td><?= CHtml::encode($rows->empleado_habitante_casa); ?></td>
					<td><?= !empty($rows->empleado_fecha_ingreso) ? date('d/m/Y', strtotime($rows->empleado_fecha_ingreso)) : ''; ?></td>
					<td><?= $rows->getAntiguedad(); ?></td>
					<td>
						<?php if($rows->empleado_estatus == 'ACTIVO'): ?>
							<span class="label label-success">ACT</span>
						<?php else: ?>
							<span class="label label-danger">INA</span>
						<?php endif; ?>
					</td>
					<td class="text-right">$<?= number_format($rows->empleado_sueldo_semanal, 2); ?></td>
					<td class="text-right">$<?= number_format($rows->empleado_sueldo_semanal_real, 2); ?></td>
					<td class="text-right"><?= $vacAcumuladas; ?></td>
					<td class="text-right"><?= number_format($proporcional, 2); ?></td>
					<td class="text-right"><?= number_format($diasTomados, 0); ?></td>
					<td class="text-right"><?= number_format($diasPtes, 0); ?></td>
					<td class="text-right">$<?= number_format($rows->empleado_bono_asistencia, 2); ?></td>
					<td class="text-right">$<?= number_format($rows->empleado_bono_puntualidad, 2); ?></td>
					<td class="text-right">$<?= number_format($rows->empleado_bono_productividad, 2); ?></td>
					<td class="text-right"><?= ($_esJueves && $enPeriodo && $diasPrima > 0) ? $diasPrima : ''; ?></td>
					<td class="text-right"><?php if ($_esJueves && $enPeriodo && $pagaPrima > 0): ?><span class="label label-warning">$<?= number_format($pagaPrima, 2); ?></span><?php endif; ?></td>
					<td style="white-space:nowrap;">
						<?php
							echo CHtml::link('<i class="fa fa-eye"></i>',
								array('Rhempleados/detalles', 'id_empleado' => $rows->id_empleado),
								array('class' => 'btn btn-xs btn-info', 'title' => 'Detalles')
							);
							echo ' ';
							echo CHtml::link('<i class="fa fa-pencil"></i>', "javascript:;", array(
								'class' => 'btn btn-xs btn-warning',
								'onclick' => "Actualizar(".$rows->id_empleado."); return false;",
								'title' => 'Editar'
							));
							if($rows->empleado_estatus == 'ACTIVO'){
								echo ' ';
								echo CHtml::link('<i class="fa fa-times"></i>', "javascript:;", array(
									'class' => 'btn btn-xs btn-danger',
									'onclick' => "AbrirModalBaja(".$rows->id_empleado.", '".addslashes($rows->empleado_nombre)."'); return false;",
									'title' => 'Dar de Baja'
								));
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
