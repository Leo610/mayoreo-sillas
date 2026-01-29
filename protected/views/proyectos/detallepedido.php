<?php
/* @var $this ProyectosController */
$this->pageTitle = 'Pedido ' . $DatosProyecto->proyecto_nombre;
$this->breadcrumbs = array(
	'Pedidos' => array('/proyectos/lista'),
	$DatosProyecto->proyecto_nombre,
);
?>

<script type="text/javascript">
	$(document).ready(function() {



		// Funcion para mostrar el modal
		$(".abrirmodaldc").click(function() {
			$("#infomodal").modal('show');

			var id = $(this).data("id");

			console.log(id);

			var jqxhr = $.ajax({
				url: "<?php echo $this->createUrl("Proyectos/Cambiosprodcutover"); ?>", // Reemplaza con la URL correcta
				type: "POST",
				dataType: "json",
				timeout: (120 * 1000),
				data: {
					id: id
				}, // Pasa el ID como parámetro
				success: function(data) {
					var cambios = data.cambios;
					var tabla = $("#listaproductoscamb tbody");

					tabla.empty(); // Limpia la tabla antes de llenarla con los nuevos datos

					$.each(cambios, function(index, cambio) {
						var newRow = "<tr>";
						newRow += "<td>" + cambio.fecha_alta + "</td>";
						newRow += "<td>" + cambio.nombre + "</td>";
						newRow += "<td>" + cambio.descripcion + "</td>";
						newRow += "<td>" + cambio.update + "</td>";
						newRow += "<td>" + cambio.id_usuario + "</td>";
						newRow += "</tr>";

						tabla.append(newRow); // Agrega la fila a la tabla
					});
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log('Error en la llamada AJAX: ' + textStatus);
				}
			});


		});


		// Funcion para ordenar la lista de resultados, lo ordena por la columna 4, ya que empieza 0,1,2,3,4 y muestra  5 resultados, solamente	
		$('#listaproductos').DataTable({
			"order": [
				[0, "desc"]
			],
			"iDisplayLength": 5
		});
		$('#listaordenesdecompra').DataTable({
			"order": [
				[5, "desc"]
			],
			"iDisplayLength": 5
		});
		$('#listaempleados').DataTable({
			"paging": false,
			"ordering": false,
			"info": false,
			"searching": false
		});
		$('#archivos').DataTable({
			"paging": false,
			"ordering": false,
			"info": false,
			"searching": false
		});

		$(".btnmostrar").click(function() {
			/*$('.mostrarocultar').toggle();*/
			$(".mostrarocultar").toggle("slow", function() {
				// Animation complete.
			});
		});



		/*
		 *	METODO PARA ASIGNAR EL ESTATUS DEL PROYECTO
		 *	CREADO POR DANIEL VILLARREAL EL 09 DE MARZO DEL 2016
		 */
		$("#id_estatus").change(function() {
			var idestatus = $(this).val();
			var jqxhr = $.ajax({
				url: "<?php echo $this->createUrl("Proyectos/Actualizarestatus"); ?>",
				type: "POST",
				dataType: "json",
				timeout: (120 * 1000),
				data: {
					id_orden_de_compra: <?= $DatosProyecto->id_proyecto; ?>,
					id_estatus: idestatus
				},
				success: function(Response, newValue) {
					if (Response.requestresult == 'ok') {
						$.notify(Response.message, "success");
						if (idestatus != 0) {
							$('#botonactualizarestatus').hide();
						}
						if (idestatus == 0) {
							$('#botonactualizarestatus').show();
						}
					} else {
						$.notify(Response.message, "error");
					}
				},
				error: function(e) {
					$.notify("Verifica los campos e intente de nuevo", "error");
				}
			});
		});

		//Metodo para guradrar el estatus del Producto
		$("#listaproductos").on("change", ".estatuspp", function() {

			var newValue = $(this).val();
			// var field = 'bodega';
			var id = $(this).data("id");
			console.log(newValue);
			var jqxhr = $.ajax({
				url: "<?php echo $this->createUrl("proyectos/actualizarestatusp"); ?>",
				method: "POST",
				dataType: "json",
				timeout: (120 * 1000),
				data: {
					value: newValue,
					id: id
				},
				success: function(Response) {
					if (Response.requestresult == 'ok') {
						$.notify(Response.message, "success");
					} else {
						$.notify(Response.message, "error");
					}
				},
				error: function(e) {
					$.notify("Verifica los campos e intente de nuevo", "error");
				}
			});
		});


		// metodo para guardar la bodega 
		$("#listaproductos").on("change", ".bodegaselect", function() {
			var newValue = $(this).val();
			var field = 'bodega';
			var id = $(this).data("id");
			// console.log(selectValue);
			var jqxhr = $.ajax({
				url: "<?php echo $this->createUrl("proyectos/actualizarcolorydesc"); ?>",
				method: "POST",
				dataType: "json",
				timeout: (120 * 1000),
				data: {
					field: field,
					value: newValue,
					id: id
				},
				success: function(Response) {
					if (Response.requestresult == 'ok') {
						$.notify(Response.message, "success");
					} else {
						$.notify(Response.message, "error");
					}
				},
				error: function(e) {
					$.notify("Verifica los campos e intente de nuevo", "error");
				}
			});
		});


		// metodo para actualizar descripcion y color ->lars 29/09/23
		$("#listaproductos").on("blur", ".editable", function() {

			var field = $(this).data("field");
			var newValue = $(this).val();
			var id = $(this).data("id");

			console.log('field ', field, ' newValue: ', newValue, ' id: ', id);
			// Realizar una llamada AJAX para actualizar el valor en el servidor
			var jqxhr = $.ajax({
				url: "<?php echo $this->createUrl("proyectos/actualizarcolorydesc"); ?>",
				method: "POST",
				dataType: "json",
				timeout: (120 * 1000),
				data: {
					field: field,
					value: newValue,
					id: id
				},
				success: function(Response) {
					if (Response.requestresult == 'ok') {
						$.notify(Response.message, "success");
					} else {
						$.notify(Response.message, "error");
					}
				},
				error: function(e) {
					$.notify("Verifica los campos e intente de nuevo", "error");
				}
			});
		});


		/**
		 * METODO PARA AGREGAR UN EMPLEADO AL PROYECTO
		 */

		$("body").on("click", "#agregarempleado", function() {
			var jqxhr = $.ajax({
				url: "<?php echo $this->createUrl("Proyectoempleados/Agregarempleado"); ?>",
				type: "POST",
				dataType: "json",
				timeout: (120 * 1000),
				data: {
					id_proyecto: <?= $DatosProyecto->id_proyecto ?>
				},
				success: function(Response, newValue) {
					if (Response.requestresult == 'ok') {
						$("#listaempleados").dataTable().fnDestroy();
						$.notify(Response.message, "success");
						// agregamos la tr
						$('#listaempleados tr:last').after(Response.fila)
						// reiniciamos la datatable
						$('#listaempleados').DataTable({
							"paging": false,
							"ordering": false,
							"info": false,
							"searching": false
						});
					} else {
						$.notify(Response.message, "error");
					}
				},
				error: function(e) {
					$.notify("Verifica los campos e intente de nuevo", "error");
				}
			});
		});
	});

	/*
	 *	METODO PARA OBTENER LOS DATOS DEL CLIENTE, PARA EDITAR
	 *	POR DANIEL VILLARREAL EL 1 DE FEBRERO DEL 2016
	 */
	function Actualizar(id) {
		var jqxhr = $.ajax({
			url: "<?php echo $this->createUrl("Clientes/Clientesdatos"); ?>",
			type: "POST",
			dataType: "json",
			timeout: (120 * 1000),
			data: {
				id: id,
			},
			success: function(Response, newValue) {
				if (Response.requestresult == 'ok') {
					// Si el resultado es correcto, agregamos los datos al form del modal
					$("#Clientes_cliente_razonsocial").val(Response.Datos.cliente_razonsocial);
					$("#Clientes_cliente_rfc").val(Response.Datos.cliente_rfc);
					$("#Clientes_cliente_calle").val(Response.Datos.cliente_calle);
					$("#Clientes_cliente_colonia").val(Response.Datos.cliente_colonia);
					$("#Clientes_cliente_numerointerior").val(Response.Datos.cliente_numerointerior);
					$("#Clientes_cliente_numeroexterior").val(Response.Datos.cliente_numeroexterior);
					$("#Clientes_cliente_codigopostal").val(Response.Datos.cliente_codigopostal);
					$("#Clientes_cliente_municipio").val(Response.Datos.cliente_municipio);
					$("#Clientes_cliente_entidad").val(Response.Datos.cliente_entidad);
					$("#Clientes_cliente_pais").val(Response.Datos.cliente_pais);
					$("#Clientes_cliente_nombre").val(Response.Datos.cliente_nombre);
					$("#Clientes_cliente_email").val(Response.Datos.cliente_email);
					$("#Clientes_cliente_telefono").val(Response.Datos.cliente_telefono);
					$("#Clientes_cliente_tipo").val(Response.Datos.cliente_tipo);
					$("#Clientes_id_cliente").val(Response.Datos.id_cliente);
					$("#Clientes_id_empresa").val(Response.Datos.id_empresa);
					$("#Clientes_cliente_tipo").val(Response.Datos.cliente_tipo);
					$("#Clientes_cliente_tipo_clasificacion").val(Response.Datos.cliente_tipo_clasificacion);
					$("#Clientes_cliente_como_trabajarlo").val(Response.Datos.cliente_como_trabajarlo);
					// y posteriormente mostramos el modal 
					$("#formmodal").modal('show');

				} else {

				}
			},
			error: function(e) {
				$.notify("Verifica los campos e intente de nuevo", "error");
			}
		});
	}
</script>


<div class="row">

	<div class="col-md-12">
		<h1 class="mb-none"> Detalles del Pedido
			<?= $DatosProyecto->id_proyecto ?>
			<?= $DatosProyecto->proyecto_nombre ?> |
			| <?php echo CHtml::link('<i class="fa fa-file-pdf-o" style="padding-right:5px"></i> VER PDF PEDIDO', array('proyectos/pdfpedido/' . $DatosProyecto->id_proyecto), array('class' => "btn btn-danger", 'target' => '_blank')); ?>
		</h1>
		<div class="col-md-12">
			<fieldset class="mb-sm">
				<legend>Datos generales del pedido </legend>
				<div class="col-md-2">Cot. Referencia: <h3 class="mb-none">
						<?= $DatosProyecto->id_cotizacion ?>
					</h3>
				</div>
				<div class="col-md-4">Fecha Alta: <h3 class="mb-none">
						<?= $DatosProyecto->proyecto_fecha_alta ?>
					</h3>
				</div>
				<div class="col-md-5">Estatus: <h3 class="mb-none" style="font-weight:bold;">
						<select name="id_estatus" id="id_estatus">
							<option value="0" <?= ($DatosProyecto->proyecto_estatus == 0) ? 'selected' : ''; ?>>Generado
							</option>
							<option value="1" <?= ($DatosProyecto->proyecto_estatus == 1) ? 'selected' : ''; ?>>Por Surtir
							</option>
							<option value="2" <?= ($DatosProyecto->proyecto_estatus == 2) ? 'selected' : ''; ?>>En Proceso
							<option value="4" <?= ($DatosProyecto->proyecto_estatus == 4) ? 'selected' : ''; ?>>Pagado
							<option value="6" <?= ($DatosProyecto->proyecto_estatus == 6) ? 'selected' : ''; ?>>Pendiente
								de <br>
								fabricación</option>
							<option value="7" <?= ($DatosProyecto->proyecto_estatus == 7) ? 'selected' : ''; ?>>Eliminar
							</option>
							<option value="8" <?= ($DatosProyecto->proyecto_estatus == 8) ? 'selected' : ''; ?>>Terminado
							</option>
						</select>
					</h3>
				</div>
				<div class="col-md-12">Comentarios <h3 class="mb-none">
						<?= $DatosProyecto->proyecto_comentarios ?>
					</h3>
				</div>
				<div class="col-md-12 mb-sm">
					<br>
					<div class="col-md-4 text-left">Total: <h3 class="mb-none bold " style="color:#000">$
							<?= number_format($DatosProyecto->proyecto_total, 2) ?>
						</h3>
					</div>
					<div class="col-md-4 text-left">Total pagado: <h3 class="mb-none bold " style="color:green">$
							<?= number_format($DatosProyecto->proyecto_totalpagado, 2) ?>
						</h3>
					</div>
					<div class="col-md-4 text-left">Total pendiente: <h3 class="mb-none bold " style="color:red">$
							<?= number_format($DatosProyecto->proyecto_totalpendiente, 2) ?>
						</h3>
					</div>
				</div>
			</fieldset>
		</div>



		<div class="col-md-12">
			<fieldset>
				<legend style="margin-bottom:10px;">Lista de Productos </legend>
				<div class="table-responsive">
					<table id="listaproductos" class="table  table-bordered table-hover">
						<thead>
							<tr>
								<th>Producto</th>
								<th>Descripción</th>
								<th>Color</th>
								<th>Cantidad</th>
								<th>Bodega</th>
								<th>Unidad de Medida</th>
								<th>Estatus</th>
								<th>Fecha de entrega</th>
								<th></th>

							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($Productosproyecto as $rows) { ?>
								<tr>
									<td>
										<?= $rows['rl_producto']['producto_nombre'] ?>
									</td>
									<td>

										<?= $rows['proyectos_productos_descripcion'] ?>
									</td>
									<td>
										<?= $rows['color'] ?>
									</td>
									<td>
										<?= $rows['proyectos_productos_cantidad'] ?>
									</td>
									<td>
										<?= $rows['bodega'] ?>
									</td>
									<td>
										<?= $rows['rl_producto']['rl_unidadesdemedida']['unidades_medida_nombre'] ?>
									</td>
									<td>
										<?php $estatus = '';
										switch ($rows->id_etapa) {
											case 218:
												$estatus = 'En espera';
												break;
											case 219:
												$estatus = 'En fabricación';
												break;
											case 220:
												$estatus = 'Termiando';
												break;
											case 248:
												$estatus = 'Entregado';
												break;
											default;
												break;
										} ?>

										<?= $estatus ?>

									</td>
									<td>
										<?= $rows['fecha_de_entrega'] ?>
									</td>
									<td>
										<span class="glyphicon glyphicon-info-sign infoenmodal abrirmodaldc" data-id="<?= $rows['id_proyectos_productos'] ?>" aria-hidden="true"></span>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
			</fieldset>
		</div>
	</div>
</div>

<!-- modal para ver si hay actualizaciones -->


<div class="modal fade" id="infomodal" tabindex="-1" role="dialog" aria-labelledby="infomodalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="infomodalLabel">Modificaciones de la fila</h4>
			</div>
			<div class="modal-body">
				<table id="listaproductoscamb" class="table  table-bordered table-hover">
					<thead>
						<tr>
							<th>Fecha de actualización</th>
							<th>Cambio de </th>
							<th>Información anterior</th>
							<th>Información nueva</th>
							<th>Usuario que actualizo</th>
						</tr>
					</thead>
					<tbody>

					</tbody>
				</table>
			</div>

		</div>
	</div>
</div>