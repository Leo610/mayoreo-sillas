<?php
/* @var $this RhempleadosController */
?>

<div class="modal fade" id="modalBaja" tabindex="-1" role="dialog" aria-labelledby="modalBajaLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="modalBajaLabel"><i class="fa fa-exclamation-triangle"></i> Dar de Baja Empleado</h4>
			</div>
			<div class="modal-body">
				<p id="baja_nombre_empleado" style="font-weight:bold;font-size:16px;"></p>
				<div class="row">
					<div class="col-md-12">
						<label>Fecha de Baja <span class="required">*</span></label>
						<input type="date" id="baja_fecha" class="form-control" required>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-12">
						<label>Observaciones / Motivo de Baja <span class="required">*</span></label>
						<textarea id="baja_observaciones" class="form-control" rows="3" placeholder="Ingrese el motivo de la baja" required></textarea>
					</div>
				</div>
				<input type="hidden" id="baja_id_empleado" value="">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-danger" onclick="ConfirmarBaja()"><i class="fa fa-times-circle"></i> Confirmar Baja</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
function AbrirModalBaja(id, nombre){
	$("#baja_id_empleado").val(id);
	$("#baja_nombre_empleado").text(nombre);
	$("#baja_fecha").val('');
	$("#baja_observaciones").val('');
	$("#modalBaja").modal('show');
}

function ConfirmarBaja(){
	var id = $("#baja_id_empleado").val();
	var fecha = $("#baja_fecha").val();
	var obs = $("#baja_observaciones").val();

	if(fecha == '' || obs == ''){
		alert('Debe llenar la fecha y las observaciones.');
		return;
	}

	$.ajax({
		url: "<?php echo $this->createUrl('Rhempleados/Dardebaja'); ?>",
		type: "POST",
		dataType: "json",
		data: {
			id_empleado: id,
			fecha_baja: fecha,
			observaciones: obs
		},
		success: function(Response){
			if(Response.requestresult == 'ok'){
				$.notify(Response.message, 'success');
				if(Response.id_finiquito){
					var urlPdf = "<?php echo $this->createUrl('Rhfiniquitos/formato', array('id' => '__ID__')); ?>".replace('__ID__', Response.id_finiquito);
					var urlVer = "<?php echo $this->createUrl('Rhfiniquitos/ver', array('id' => '__ID__')); ?>".replace('__ID__', Response.id_finiquito);
					$('#modalBaja .modal-body').html(
						'<div class="alert alert-success">' +
						'<h4><i class="fa fa-check-circle"></i> Baja registrada y finiquito calculado</h4>' +
						'<p style="font-size:16px;margin:10px 0;">' + Response.message + '</p>' +
						'<a href="' + urlVer + '" class="btn btn-info"><i class="fa fa-eye"></i> Ver Detalle</a> ' +
						'<a href="' + urlPdf + '" target="_blank" class="btn btn-danger"><i class="fa fa-file-pdf-o"></i> Descargar PDF</a>' +
						'</div>'
					);
					$('#modalBaja .modal-footer').html(
						'<button type="button" class="btn btn-default" onclick="location.reload()">Cerrar</button>'
					);
				} else {
					$("#modalBaja").modal('hide');
					setTimeout(function(){ location.reload(); }, 1000);
				}
			} else {
				$.notify(Response.message, 'error');
			}
		},
		error: function(){
			$.notify('Ocurrio un error inesperado', 'error');
		}
	});
}
</script>
