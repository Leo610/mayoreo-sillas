<?php
/* @var $this AdministracionController */
?>
<script type="text/javascript">
	$(document).ready(function() {


 $( "#actualizardetalle" ).click(function() {

 	var estatus = $('#estatus').val();
	var comentario_realizado = $('#comentario_realizado').val();
	var id_crm_detalle = $('#id_crm_detalle').val();


	if(estatus=='0' || comentario_realizado=='' || id_crm_detalle=='')
	{
		$.notify('Favor de verificar los campos');
		return false;
	}
   	var id_op_detalle =  $(this).data('idopdetalle');
		var jqxhr = $.ajax({
		    url: "<?php echo $this->createUrl("crmdetalles/actualizar"); ?>",
		    type: "POST",
		    dataType : "json",
		    timeout : (120 * 1000),
		    data: {
		        estatus:estatus,
		        comentario_realizado: comentario_realizado,
		        id_crm_detalle:id_crm_detalle
		    },
		    success : function(Response, newValue) {
		        if (Response.requestresult == 'ok') {
		        	location.reload();
		        }else{
		        	$.notify(Response.message, "error");
		        }
		    },
		    error: function(e){
		          $.notify("Verifica los campos e intente de nuevo", "error");
		        }
		    });
		});
});
</script>
<div class="modal fade" id="detallesaccionmodal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="formModalLabel">Agregar Acción</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="form">
						<div class="col-md-12">
							<label>
								Estatus
							</label>
							<select class="form-control" name="estatus" id="estatus">
								<option value="0">-- Seleccione --</option>
								<option value="NO REALIZADO">NO REALIZADO</option>
								<option value="REALIZADO">REALIZADO</option>
							</select>
						</div>
						<div class="col-md-12">
							<label>
								Comentarios
							</label>
							<textarea name="comentario_realizado" class="form-control" id="comentario_realizado"></textarea>
						</div>
						<div class="col-md-12">
							<label>
								Agente Realizo
							</label>
							<input type="text" name="" id="id_usuario_realizado" disabled="" class="form-control" value="">
						</div>
						<div class="col-md-12">
							<label>
								Fecha realizado
							</label>
							<input type="text" name="" id="fecha_realizado" disabled="" class="form-control" value="">
						</div>
						<input type="hidden" name="id_crm_detalle" id="id_crm_detalle">
						<div class="col-md-12 center mt-md">
							<button type="button" id="actualizardetalle" class="btn btn-success">Guardar cambios</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
						</div>
					</div><!-- form -->
				</div>
			</div>
		</div>
	</div>
</div>
