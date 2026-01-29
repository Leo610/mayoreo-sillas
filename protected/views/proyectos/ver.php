<?php
/* @var $this ProyectosController */
$this->pageTitle = 'Pedido ' . $DatosProyecto->proyecto_nombre;
$this->breadcrumbs = array(
	'Pedidos' => array('/proyectos/lista'),
	$DatosProyecto->proyecto_nombre,
);



?>
<script type="text/javascript">
	$(document).ready(function () {

		$(".fechaentrega").change(function () {
			var fecha = $(this).val();
			var id = $(this).data("id");
			console.log('fecha de entrega ', fecha, 'id ', id);

			var jqxhr = $.ajax({
				url: "<?php echo $this->createUrl('productos/actualizarfecha'); ?>",
				type: "POST",
				dataType: "json",
				timeout: (120 * 1000),
				data: {
					fecha: fecha,
					id: id
				},
				success: function (Response) {
					if (Response.requestresult == 'ok') {
						$.notify(Response.message, "success");
						setTimeout(function () {
							location.reload();
						}, 500);
					} else {
						$.notify(Response.message, "error");
					}
				},
				error: function (e) {
					$.notify(Response.message, "error");
				}
			});
		});

		// Funcion para mostrar el modal
		// Función para mostrar el modal
		$(".abrir-modal").click(function () {
			var modalId = $(this).data('id'); // Obtén el data-id del botón clicado
			$("#modalped").modal('show');
			console.log(modalId);

			$("#modalId").val(modalId);

			var jqxhr = $.ajax({
				url: "<?php echo $this->createUrl("Proyectos/datosaeditar"); ?>", // Reemplaza con la URL correcta
				type: "POST",
				dataType: "json",
				timeout: (120 * 1000),
				data: {
					id: modalId
				}, // Pasa el ID como parámetro
				success: function (data) {
					var valorDeseado = data['bode'];
					$("#color").val(data['color']);
					$("#colortapi").val(data['colortapi']);
					$("#desc").val(data['descipcion']);
					$("#esext").val(data['espext']);
					// $("#bodega").val(data['bode'])
					$("#bodega option").each(function () {
						if ($(this).val() == valorDeseado) {
							$(this).prop("selected", true);
						}
					});
				},
				error: function (jqXHR, textStatus, errorThrown) {
					console.log('Error en la llamada AJAX: ' + textStatus);
				}
			});
			// Aquí puedes usar modalId para identificar el elemento específico a editar en el modal
			// $('#Clientes-form')[0].reset();
		});

		// Funcion para mostrar el modal
		$(".abrirmodaldc").click(function () {
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
				success: function (data) {
					var cambios = data.cambios;
					var tabla = $("#listaproductoscamb tbody");

					tabla.empty(); // Limpia la tabla antes de llenarla con los nuevos datos

					$.each(cambios, function (index, cambio) {
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
				error: function (jqXHR, textStatus, errorThrown) {
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

		$(".btnmostrar").click(function () {
			/*$('.mostrarocultar').toggle();*/
			$(".mostrarocultar").toggle("slow", function () {
				// Animation complete.
			});
		});



		/*
		 *	METODO PARA ASIGNAR EL ESTATUS DEL PROYECTO
		 *	CREADO POR DANIEL VILLARREAL EL 09 DE MARZO DEL 2016
		 */
		$("#id_estatus").change(function () {
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
				success: function (Response, newValue) {
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
				error: function (e) {
					$.notify("Verifica los campos e intente de nuevo", "error");
				}
			});
		});

		//Metodo para guradrar el estatus del Producto

		$('.estatuspp').on("change", function () {
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
				success: function (Response) {
					if (Response.requestresult == 'ok') {
						$.notify(Response.message, "success");
					} else {
						$.notify(Response.message, "error");
					}
				},
				error: function (e) {
					$.notify("Verifica los campos e intente de nuevo", "error");
				}
			});
		});


		// metodo para guardar la bodega 
		// $('.bodegaselect').on("change", function () {
		// 	var newValue = $(this).val();
		// 	var field = 'bodega';
		// 	var id = $(this).data("id");
		// 	// console.log(selectValue);
		// 	var jqxhr = $.ajax({
		// 		url: "//php echo $this->createUrl("proyectos/actualizarcolorydesc"); ",
		// 		method: "POST",
		// 		dataType: "json",
		// 		timeout: (120 * 1000),
		// 		data: { field: field, value: newValue, id: id },
		// 		success: function (Response) {
		// 			if (Response.requestresult == 'ok') {
		// 				$.notify(Response.message, "success");
		// 			} else {
		// 				$.notify(Response.message, "error");
		// 			}
		// 		},
		// 		error: function (e) {
		// 			$.notify("Verifica los campos e intente de nuevo", "error");
		// 		}
		// 	});
		// });

		// ajax para guardar los cambios y recargar la pagina 

		// metodo para actualizar descripcion y color ->lars 29/09/23
		// $(".editable").on("blur", function () {
		// 	var field = $(this).data("field");
		// 	var newValue = $(this).val();
		// 	var id = $(this).data("id");
		// 	console.log(id);
		// 	// Realizar una llamada AJAX para actualizar el valor en el servidor
		// 	var jqxhr = $.ajax({
		// 		url: "//php echo $this->createUrl("proyectos/actualizarcolorydesc"); ",
		// 		method: "POST",
		// 		dataType: "json",
		// 		timeout: (120 * 1000),
		// 		data: { field: field, value: newValue, id: id },
		// 		success: function (Response) {
		// 			if (Response.requestresult == 'ok') {
		// 				$.notify(Response.message, "success");

		// 			} else {
		// 				$.notify(Response.message, "error");
		// 			}
		// 			location.reload();
		// 		},
		// 		error: function (e) {
		// 			$.notify("Verifica los campos e intente de nuevo", "error");
		// 		}
		// 	});
		// });


		/**
		 * METODO PARA AGREGAR UN EMPLEADO AL PROYECTO
		 */

		$("body").on("click", "#agregarempleado", function () {
			var jqxhr = $.ajax({
				url: "<?php echo $this->createUrl("Proyectoempleados/Agregarempleado"); ?>",
				type: "POST",
				dataType: "json",
				timeout: (120 * 1000),
				data: {
					id_proyecto: <?= $DatosProyecto->id_proyecto ?>
				},
				success: function (Response, newValue) {
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
				error: function (e) {
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
			success: function (Response, newValue) {
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
			error: function (e) {
				$.notify("Verifica los campos e intente de nuevo", "error");
			}
		});
	}
</script>
<?php

include 'modal/agregarcomentario.php';
?>


<div class="row">
	<div class="col-md-3">
		<fieldset>
			<legend>Datos Contacto <button class="btn btn-default btnmostrar">
					<i class="fa fa-caret-square-o-down " aria-hidden="true"></i>
				</button></legend>
			<div class="mostrarocultar" style="display: block">
				<p class="mb-none ">
					Nombre:<br><strong>
						<?= $datoscliente->cliente_nombre; ?>
					</strong><br>
					Cliente Tipo:<br><strong>
						<?= $datoscliente['rl_cliente_tipo']['nombre']; ?>
					</strong><br>
					Teléfono:<br><strong>
						<?= $datoscliente->cliente_telefono; ?>
					</strong><br>
					Email:<br><strong>
						<?= $datoscliente->cliente_email; ?>
					</strong><br>
					Calle:<br><strong>
						<?= $datoscliente->cliente_calle; ?>
					</strong><br>
					Colonia:<br><strong>
						<?= $datoscliente->cliente_colonia; ?>
					</strong><br>

					Número Interior:<br><strong>
						<?= $datoscliente->cliente_numeroexterior; ?>
						<?= $datoscliente->cliente_numerointerior; ?>
					</strong><br>
					Código Postal:<br><strong>
						<?= $datoscliente->cliente_codigopostal; ?>
					</strong><br>
					Municipio:<br><strong>
						<?= $datoscliente->cliente_municipio; ?>
					</strong><br>
					Entidad:<br><strong>
						<?= $datoscliente->cliente_entidad; ?>
					</strong><br>
					País:<br><strong>
						<?= $datoscliente->cliente_pais; ?>
					</strong><br>

					Empresa:<br><strong>
						<?= $datoscliente['rl_empresas']['empresa']; ?>
					</strong><br>
				</p>

				<?php
				echo CHtml::link(
					'<i class="fa fa-pencil fa-lg"></i> Editar',
					"javascript:;",
					array(
						'style' => 'cursor: pointer;',
						"onclick" => "Actualizar(" . $datoscliente->id_cliente . "); return false;",
						"class" => 'btn btn-success mt-none btn-sm pull-right'
					)
				);
				?>
			</div>
		</fieldset>
	</div>

	<div class="col-md-9">
		<h1 class="mb-none">Pedido
			<?= $DatosProyecto->id_proyecto ?>
			<?= $DatosProyecto->proyecto_nombre ?>
			<?php if ($DatosProyecto->ingresosConfirmados($DatosProyecto['id_proyecto']) && $DatosProyecto['proyecto_totalpendiente'] == 0) { ?>
				|
				<?php echo CHtml::link('<i class="fa fa-file-pdf-o" style="padding-right:5px"></i> VER REMISION', array('proyectos/pdf/' . $DatosProyecto->id_proyecto), array('class' => "btn btn-danger", 'target' => '_blank')); ?>
			<?php } ?>
			|
			<?php echo CHtml::link('<i class="fa fa-file-pdf-o" style="padding-right:5px"></i> VER PDF PEDIDO', array('proyectos/pdfpedido/' . $DatosProyecto->id_proyecto), array('class' => "btn btn-danger", 'target' => '_blank')); ?>
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
							<!-- <option value="1" <?= ($DatosProyecto->proyecto_estatus == 1) ? 'selected' : ''; ?>>Por Surtir</option> -->
							<option value="2" <?= ($DatosProyecto->proyecto_estatus == 2) ? 'selected' : ''; ?>>En
								Producción</option>
							<!-- <option value="4" <?= ($DatosProyecto->proyecto_estatus == 4) ? 'selected' : ''; ?>>Pagado</option> -->
							<option value="6" <?= ($DatosProyecto->proyecto_estatus == 6) ? 'selected' : ''; ?>>Empacado
							</option>
							<option value="8" <?= ($DatosProyecto->proyecto_estatus == 8) ? 'selected' : ''; ?>>Entregado
							</option>
							<option value="7" <?= ($DatosProyecto->proyecto_estatus == 7) ? 'selected' : ''; ?>>Cancelado
							</option>
						</select>
					</h3>
				</div>
				<div class="col-md-5">localidad de entrega
					<h3 class="mb-none">
						<?= $DatosProyecto->localidad ?>
					</h3>
				</div>

				<div class="col-md-12">Comentarios <h3 class="mb-none">
						<?= $DatosProyecto->proyecto_comentarios ?>
					</h3>
				</div>
			</fieldset>
		</div>
		<div class="col-md-12 mb-sm">
			<div class="col-md-4 text-center">Total: <h3 class="mb-none bold " style="color:#000">$
					<?= number_format($DatosProyecto->proyecto_total, 2) ?>
				</h3>
			</div>
			<div class="col-md-4 text-center">Total pagado: <h3 class="mb-none bold " style="color:green">$
					<?= number_format($DatosProyecto->proyecto_totalpagado, 2) ?>
				</h3>
			</div>
			<div class="col-md-4 text-center">Total pendiente: <h3 class="mb-none bold " style="color:red">$
					<?= number_format($DatosProyecto->proyecto_totalpendiente, 2) ?>
				</h3>
			</div>
		</div>

		<!-- COMENTARIOS DEL PROYECTO -->
		<div class="col-md-12">
			<fieldset>
				<legend style="margin-bottom:10px;">Observaciones | <button
						class="btn btn-success btn-xs agregarcomentario" type="button">Agregar observacion para
						remision</button>
				</legend>
				<div class="table-responsive">
					<table id="listacometnarios" class="table  table-bordered table-hover">
						<thead>
							<tr>
								<!-- <th>Usuario</th> -->
								<th>Observaciones</th>
								<!-- <th>Fecha Alta</th> -->
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($listacomentarios as $rows) { ?>
								<tr>
									<!-- <td>
										<? //= $rows['rl_usuarios']['Usuario_Nombre'] ?>
									</td> -->
									<td>
										<?= $rows['descripcion'] ?>
									</td>
									<!-- <td>
										<? //= $rows['fecha_alta'] ?>
									</td> -->
								</tr>
							<?php } ?>
						</tbody>
					</table>
			</fieldset>
		</div>
		<!-- -->
		<div class="col-md-12">
			<fieldset>
				<legend style="margin-bottom:10px;">Lista de Productos </legend>
				<div class="table-responsive">
					<table id="listaproductos" class="table  table-bordered table-hover">
						<thead>
							<tr>
								<th>Producto</th>
								<th>Descripción</th>
								<th>Detalles</th>
								<!-- <th>Color de Estructura</th>
								<th>Color Tapicería</th>
								<th>Cantidad</th>
								<th>Bodega</th>
								<th>Unidad de Medida</th>
								<th>Estatus</th>
								<th>Fecha de entrega</th> -->
								<th></th>
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
										<?php
										if (!empty($rows['especificaciones_extras'])) { ?>
											<br>
											<br>
											<p><b>Especificaciones extras</b></p>
											<p><b>
													<?= $rows['especificaciones_extras'] ?>
												</b></p>
										<?php } ?>

									</td>
									<!-- td del detalle -->
									<td>
										Color de estructura: <b>
											<?= $rows['color'] ?>
										</b><br>
										Color tapiceria: <b>
											<?= $rows['color_tapiceria'] ?>
										</b><br>
										Cantidad: <b>
											<?= $rows['proyectos_productos_cantidad'] ?>
										</b><br>
										Bodega : <b>
											<?= $rows['bodega'] ?>
										</b> <br>
										Unidad de medida : <b>
											<?= $rows['rl_producto']['rl_unidadesdemedida']['unidades_medida_nombre'] ?>
										</b> <br>
										<!-- Estatus : <select class="estatuspp form-control"
											data-id="<? //= $rows['id_proyectos_productos'] 
												?> ">
											<option value="218" <? //= ($rows->id_etapa == 218) ? 'selected' : ''; 
												?>>Pedido
												Pendeinte
											</option>
											<option value="219" <? //= ($rows->id_etapa == 219) ? 'selected' : ''; 
												?>>Proceso en
												corte
											</option>
											<option value="220" <? //= ($rows->id_etapa == 220) ? 'selected' : ''; 
												?>>Proceso de
												Maquinado
											</option>
											<option value="248" <? //= ($rows->id_etapa == 248) ? 'selected' : ''; 
												?>>Proceso de
												soldadura
											</option>
											<option value="413" <? //= ($rows->id_etapa == 413) ? 'selected' : ''; 
												?>>Proceso de
												pintura
											</option>
											<option value="414" <? //= ($rows->id_etapa == 414) ? 'selected' : ''; 
												?>>Proceso de
												empaque
											</option>
											<option value="415" <? //= ($rows->id_etapa == 415) ? 'selected' : ''; 
												?>>pedido
												terminado
											</option>
										</select>
										<br> -->
										Fecha de entrega: <div>

											<?php $this->widget(
												'zii.widgets.jui.CJuiDatePicker',
												array(
													'name' => 'fecha_de_entrega',
													'language' => 'es',
													'htmlOptions' => array(
														// 'readonly' => "readonly",
														'class' => 'fechaentrega form-control stopa',
														'data-id' => $rows['id_proyectos_productos'],
														'id' => 'fecha_entrega_' . $rows['id_proyectos_productos'] . ''
													),
													'options' => array(
														'dateFormat' => 'yy-mm-dd',
													),
													'value' => $rows['fecha_de_entrega']
												)
											); ?>
										</div>
									</td>
									<td class="text-center">
										<span class="glyphicon glyphicon-info-sign infoenmodal abrirmodaldc"
											data-id="<?= $rows['id_proyectos_productos'] ?>" aria-hidden="true"></span>
									</td>
									<td class="text-center">
										<a href="#" class="btn btn-success abrir-modal"
											data-id="<?= $rows['id_proyectos_productos'] ?>">
											Editar
										</a>
									</td>

								</tr>
							<?php } ?>
						</tbody>
						<!-- <tfoot>
							<tr>

								<td style="border:0"></td>
								<td style="border:0"></td>
								<td style="border:0"></td>

								<td colspan="2" style="border-top: 0; text-align:center;">
									<?php
									// echo CHtml::link('Generacion de producción', array('productos/Pizarraproductos/' . $DatosProyecto['id_proyecto']), array('class' => "btn btn-primary"));
									?>
								</td>

							</tr>
						</tfoot> -->
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

<!-- ****Modal para editar los campos **** -->


<div class="modal fade" id="modalped" tabindex="-1" role="dialog" aria-labelledby="modalpedLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="modalpedLabel">Editar Campos </h4>
			</div>

			<div class="modal-body">
				<form action="<?php echo $this->createUrl("proyectos/actualizarcolorydesc"); ?>" method="POST">
					<div class="row">
						<div class="col-md-6">
							<label for="">Color tapiceria</label>
							<input id="colortapi" name="colortapi" class="editable form-control" data-field="colortapi">
						</div>

						<div class="col-md-6">
							<label for="">Color Estructura</label>
							<select id="color" name="color" class="editable form-control" data-field="color">
								<?php foreach ($color_estructura as $ce) { ?>
									<option value="<?= $ce['nombre'] ?>">
										<?= $ce['nombre'] ?>
									</option>
								<?php } ?>
							</select>
							<!-- <input id="color" name="color" class="editable form-control" data-field="color" value=""> -->
						</div>

						<div class="col-md-6">
							<label for="">Bodega</label>
							<select id="bodega" name="bodega" class="bodegaselect form-control">
								<?php foreach ($bodegas as $bodega) { ?>
									<option value="<?= $bodega['id_catalogo_recurrente'] ?>">
										<?= $bodega['nombre'] ?>
									</option>
								<?php } ?>
							</select>
						</div>

						<div class="col-md-12">
							<label for="">Descripción</label>
							<textarea id="desc" name="desc" class="editable form-control"
								data-field="descripcion"></textarea>
						</div>
						<div class="col-md-12">
							<label for="">Expecificaciones extras</label>
							<textarea id="esext" name="esext" class="editable form-control" data-field="esext"
								data-id="<?= $rows['id_proyectos_productos'] ?>">
							</textarea>
						</div>
						<input type="hidden" name="id" id="modalId" value="">

					</div>
					<br>

					<input style="margin-left: 10px;" class="btn btn-success" type="submit" value="Guardar">


				</form>

			</div>
		</div>
	</div>
</div>