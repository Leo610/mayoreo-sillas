<?php
/* @var $this AdministracionController */
$this->pageTitle = $DatosOportunidad->id . ' Oportunidad ' . $DatosOportunidad->nombre;

$this->breadcrumbs = array(
	'Administracion' => array('/administracion'),
	'Crmver',
);
?>


<script type="text/javascript">

	// document.addEventListener("DOMContentLoaded", function () {

	// 	var contacto = document.querySelector('.btnmostrar');

	// 	contacto.addEventListener("click", function () {
	// 		console.log('click aqui');
	// 		$(".mostrarocultar").toggle("slow", function () {

	// 		});
	// 	});

	// });

	$(document).on('ready', function () {
		// Funcion para ordenar la lista de resultados, lo ordena por la columna 4, ya que empieza 0,1,2,3,4 y muestra  5 resultados, solamente	
		$('#listadetallescliente').DataTable({
			"order": [[0, "desc"]],
			// "iDisplayLength": 5
		});
		$('#listacotizaciones').DataTable({
			"order": [[0, "desc"]],
			// "iDisplayLength": 5
		});

		$(".btnmostrar").click(function () {
			console.log('click aqui');

			/*$('.mostrarocultar').toggle();*/
			$(".mostrarocultar").toggle("slow", function () {

			});
		});

		/* METODO PARA ABRIR UN MODAL*/
		$(".abrirmodal").click(function () {
			var modal = $(this).data('idmodal');
			$(modal).modal('show');
		});
		/* TERMINA */

		$(".abrirmodadetalleaccion").click(function () {
			console.log('aca');

			var id_op_detalle = $(this).data('idopdetalle');
			var jqxhr = $.ajax({
				url: "<?php echo $this->createUrl("crmdetalles/obtenerinformacion"); ?>",
				type: "POST",
				dataType: "json",
				timeout: (120 * 1000),
				data: {
					id_oportunidad: <?= $DatosOportunidad->id ?>,
					id_op_detalle: id_op_detalle
				},
				success: function (Response, newValue) {
					if (Response.requestresult == 'ok') {
						$.notify(Response.message, "success");
						$('#id_crm_detalle').val(Response.Datos.id_crm_detalle);
						$('#estatus').val(Response.Datos.estatus);
						$('#comentario_realizado').val(Response.Datos.comentario_realizado);

						// Mostrmoas DIV de realizado
						$('#fecha_realizado').val(Response.Datos.fecha_realizado);
						$('#id_usuario_realizado').val(Response.usuariorealizo);

						$("#detallesaccionmodal").modal('show');
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
					$("#Clientes_cliente_numerointerior").val(Response.Datos.cliente_numerointerior);
					$("#Clientes_cliente_numeroexterior").val(Response.Datos.cliente_numeroexterior);
					$("#cliente_codigopostal").val(Response.Datos.cliente_codigopostal);
					GetColonias(Response.Datos.cliente_codigopostal);
					setTimeout(function () {
						$("#Clientes_cliente_colonia").val(Response.Datos.cliente_colonia);
						$("#Clientes_cliente_municipio").val(Response.Datos.cliente_municipio);
						$("#Clientes_cliente_entidad").val(Response.Datos.cliente_entidad);
					}, 1000);
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
				$.notify('Ocurrio un error inesperado', 'error');
			}
		});
	}

	// Funcion para cambiar los campos dinamicamente
	function ActualizarCampo(campo, id, valor) {
		var jqxhr = $.ajax({
			url: "<?php echo $this->createUrl("administracion/Actualizarcampos"); ?>",
			type: "POST",
			dataType: "json",
			timeout: (120 * 1000),
			data: {
				id: id,
				campo: campo,
				valor: valor,
				modelo: 'CrmOportunidades'

			},
			success: function (Response, newValue) {
				if (Response.requestresult == 'ok') {
					$.notify(Response.message, "success");
				} else {
					$.notify(Response.message, "error");
				}
			},
			error: function (e) {
				$.notify('Ocurrio un error inesperado', "error");
			}
		});
	}

	function ActualizarCamposCliente(campo, valor) {
		var jqxhr = $.ajax({
			url: "<?php echo $this->createUrl("administracion/ActualizarcamposCliente"); ?>",
			type: "POST",
			dataType: "json",
			timeout: (120 * 1000),
			data: {
				campo: campo,
				valor: valor,
				id_cliente: <?= $datoscliente->id_cliente; ?>,
				modelo: 'ClientesFrecuencias'

			},
			success: function (Response, newValue) {
				if (Response.requestresult == 'ok') {
					$.notify(Response.message, "success");
				} else {
					$.notify(Response.message, "error");
				}
			},
			error: function (e) {
				$.notify('Ocurrio un error inesperado', "error");
			}
		});
	}
</script>

<?php
include_once 'modals/modal.crearproducto.php';
include_once 'modals/modal.motivocancelacion.php';
include_once 'modals/modal.agregaragente.php';

?>
<?php
// Incluimos el modal de clientes, de otra vista

Yii::app()->controller->renderFile(
	Yii::app()->basePath . '/views/clientes/modal/_form.php',
	array(
		'model' => $model,
		'arraylistaprecios' => $arraylistaprecios,
		'listatipo' => $listatipo,
		'ListaClasificacion' => $ListaClasificacion,
		'ListaComoTrabajarlo' => $ListaComoTrabajarlo,
		'ListaEmpresa' => $ListaEmpresa,
		'precio' => $precio
	)
);
include_once 'modals/modal.detallesaccion.php';
?>


<div class="row">
	<?php if ($DatosOportunidad->estatus == "PERDIDO") { ?>
		<div class="col-md-12">
			<div class="alert alert-danger">
				<strong class="lead">Oportunidad Perdida:
					<?= $DatosOportunidad['rl_motivo']['nombre']; ?> -
					<?= $DatosOportunidad['comentarios_perdido']; ?><br>
					<?= $DatosOportunidad['fecha_ultima_modificacion']; ?>
				</strong>
			</div>
		</div>
	<?php } ?>
	<div class="col-md-3">

		<fieldset>
			<legend>Datos Oportunidad</legend>

			<strong class="lead" style="color:#000">
				<?= $datoscliente->cliente_nombre; ?>

			</strong><br>
			<strong class="lead" style="color:#000">
				<?= $datoscliente->cliente_telefono; ?>
			</strong><br>
			<strong class="lead" style="color:#000">
				<?= $datoscliente->cliente_email; ?>

			</strong><br><br>
			<input type="text" class="form-control mb-sm" placeholder="Nombre Oportunidad"
				value="<?= $DatosOportunidad->nombre ?>"
				onblur="ActualizarCampo('nombre',<?= $DatosOportunidad['id'] ?>,this.value);">
			<input type="text" class="form-control mb-sm" placeholder="Valor del negocio"
				value="<?= $DatosOportunidad->valor_negocio ?>"
				onblur="ActualizarCampo('valor_negocio',<?= $DatosOportunidad['id'] ?>,this.value);">

			<select name="id_etapa" id="id_etapa" class="form-control mb-sm"
				onchange="ActualizarCampo('id_etapa',<?= $DatosOportunidad['id'] ?>,this.value);">
				<option>-- Seleccione Etapa --</option>
				<?php foreach ($listaetapa as $key => $value) { ?>
					<option value="<?= $key ?>" <?= ($DatosOportunidad->id_etapa == $key) ? 'selected' : ''; ?>>
						<?= $value ?>
					</option>
				<?php } ?>

			</select>
			<?php $this->widget(
				'zii.widgets.jui.CJuiDatePicker',
				array(
					'name' => 'fecha_tentativa_cierre',
					'language' => 'es',
					'htmlOptions' => array(
						'readonly' => "readonly",
						'class' => 'form-control  mb-sm',
						'onblur' => 'ActualizarCampo("fecha_tentativa_cierre",' . $DatosOportunidad["id"] . ',this.value)'
					),
					'options' => array(
						'dateFormat' => 'yy-mm-dd',
					),
					'value' => $DatosOportunidad->fecha_tentativa_cierre
				)
			); ?>
			<select name="id_frecuencia" id="id_frecuencia" class="form-control mb-sm"
				onchange="ActualizarCamposCliente('id_frecuencia',this.value);">
				<option>-- Seleccione tipo frecuencia --</option>
				<?php foreach ($ListaFrecuencia as $key => $value) { ?>
					<option value="<?= $key ?>" <?= ($DatosFrecuencia['id_frecuencia'] == $key) ? 'selected' : ''; ?>>
						<?= $value ?>
					</option>
				<?php } ?>

			</select>
			<select name="nombre_dia" id="nombre_dia" class="form-control mb-sm"
				onchange="ActualizarCamposCliente('nombre_dia',this.value);">
				<option>-- Seleccione día --</option>
				<option value="LUNES" <?= ($DatosFrecuencia['nombre_dia'] == "LUNES") ? 'selected' : ''; ?>>LUNES</option>
				<option value="MARTES" <?= ($DatosFrecuencia['nombre_dia'] == "MARTES") ? 'selected' : ''; ?>>MARTES
				</option>
				<option value="MIERCOLES" <?= ($DatosFrecuencia['nombre_dia'] == "MIERCOLES") ? 'selected' : ''; ?>>
					MIERCOLES</option>
				<option value="JUEVES" <?= ($DatosFrecuencia['nombre_dia'] == "JUEVES") ? 'selected' : ''; ?>>JUEVES
				</option>
				<option value="VIERNES" <?= ($DatosFrecuencia['nombre_dia'] == "VIERNES") ? 'selected' : ''; ?>>VIERNES
				</option>
				<option value="SABADO" <?= ($DatosFrecuencia['nombre_dia'] == "SABADO") ? 'selected' : ''; ?>>SABADO
				</option>


			</select>


		</fieldset>
		<fieldset>
			<legend>Datos Contacto <button class="btn btn-default btnmostrar">
					<i class="fa fa-caret-square-o-down " aria-hidden="true"></i>
				</button></legend>
			<div class="mostrarocultar" style="display: none">
				<p class="mb-none ">
					Nombre:<br><strong>
						<?= $datoscliente->cliente_nombre; ?>
					</strong><br>
					Cliente Tipo:<br><strong>
						<?= $datoscliente['rl_cliente_tipo']['nombre']; ?>
					</strong><br>
					Etapa:<br><strong>
						<?= $DatosOportunidad['rl_catalogo']['nombre']; ?>
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
					Clasificacion:<br><strong>
						<?= $datoscliente['rl_cliente_tipo_clasificacion']['nombre']; ?>
					</strong><br>
					Como Trabajarlo:<br><strong>
						<?= $datoscliente['rl_cliente_como_trabajarlo']['nombre']; ?>
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
		<fieldset style="display: none;">
			<legend>Productos | <button class="btn btn-success abrirmodal btn-xs" data-idmodal="#formmodalcrearproducto"
					id="abrirmodal">Agregar Producto</button></legend>
			<div class="row">
				<div class="col-md-12">
					<?php foreach ($listaclientesproductos as $rows) { ?>
						<p class="mb-none" style="color:#000">
							<?= $rows['rl_producto']['producto_nombre'] ?><br>
							<small style="color:#000">
								<?= $rows['comentarios'] ?>
							</small>
						</p>
					<?php } ?>
				</div>
			</div>
		</fieldset>
		<fieldset style="display: none;">
			<legend>Involucrados |
				<?= (Yii::app()->user->id == $DatosOportunidad['id_usuario']) ? '<button class="btn btn-success abrirmodal btn-xs" data-idmodal="#modalagregaragente" id="abrirmodalagente">Agregar Agente</button>' : ''; ?>
			</legend>
			<div class="row">
				<div class="col-md-12">
					<?php foreach ($ListaCOI as $rows) { ?>
						<p class="mb-none" style="color:#000">
							<?= $rows['rl_usuarios']['Usuario_Nombre'] ?>
							-
							<?php
							if (Yii::app()->user->id == $DatosOportunidad['id_usuario']) {
								echo CHtml::link(
									'<i class="fa fa-trash fa-lg"></i> Eliminar',
									array('crmoportunidadesinvolucrados/eliminar', 'id' => $rows['id'], 'id_oportunidad' => $rows['id_oportunidad']),
									array(
										'submit' => array('crmoportunidadesinvolucrados/eliminar', 'id' => $rows['id'], 'id_oportunidad' => $rows['id_oportunidad']),
										'class' => 'delete',
										'confirm' => 'Seguro que lo deseas eliminar?'
									)
								);
							}
							?>
						</p>
					<?php } ?>
				</div>
			</div>
		</fieldset>
	</div>
	<div class="col-md-9">
		<?php
		if ($DatosOportunidad->estatus == "SEGUIMIENTO") { ?>

			<button class="btn btn-danger abrirmodal btn-sm pull-right" data-idmodal="#formmodalmotivocancelacion"
				id="abrirmodal">
				<i class="fa fa-frown-o fa-2x"></i>
				PERDIDO
			</button>


			<h2 class="mb-none">
				<?= $this->pageTitle ?> | <a
					href="<?php echo Yii::app()->createUrl('administracion/crearcotizaciones/' . $datoscliente->id_cliente . '?id_oportunidad=' . $DatosOportunidad->id) ?>"
					class="btn btn-success ">
					<i class="fa fa-list-ol"></i> Crear cotización
				</a>
			</h2>



			<div class="col-md-12">
				<fieldset>
					<legend>Agregar acción</legend>
					<div class="form">
						<?php $form = $this->beginWidget(
							'CActiveForm',
							array(
								'id' => 'agregaraccion-form',
								'action' => Yii::app()->createUrl('crmdetalles/Crearaccion'),
								'enableClientValidation' => true,
								'clientOptions' => array('validateOnSubmit' => true, 'validateOnType' => true),
							)
						); ?>


						<?php echo CHtml::errorSummary(array($modelCrmdetalles)); ?>

						<div class="row">
							<div class="col-md-6">
								<?php echo $form->labelEx($modelCrmdetalles, 'id_crm_acciones'); ?>
								<?php echo $form->dropDownList($modelCrmdetalles, 'id_crm_acciones', $arraylistacrmacciones, array('class' => 'form-control')); ?>
								<?php echo $form->error($modelCrmdetalles, 'id_crm_acciones'); ?>
							</div>

							<div class="col-md-6">
								<?php echo $form->labelEx($modelCrmdetalles, 'crm_detalles_fecha'); ?>
								<?php Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
								$this->widget(
									'CJuiDateTimePicker',
									array(
										'id' => 'crm_detalles_fecha',
										'model' => $modelCrmdetalles,
										//Model object
										'attribute' => 'crm_detalles_fecha',
										//attribute name
										'mode' => 'datetime',
										//use "time","date" or "datetime" (default)
										'options' => array('dateFormat' => 'yy-mm-dd', 'controlType' => 'select', 'timeFormat' => 'hh:mm tt', 'hourMin' => 8, 'hourMax' => 20, 'stepMinute' => 5, 'minDate' => '-0'),
										'htmlOptions' => array('class' => 'form-control', 'readonly' => 'true', 'value' => date('Y-m-d H:i:s')),
									)
								);
								?>
								<?php echo $form->error($modelCrmdetalles, 'crm_detalles_fecha'); ?>
							</div>

						</div>
						<div class="row">
							<div class="col-md-12">
								<?php echo $form->labelEx($modelCrmdetalles, 'crm_detalles_comentarios'); ?>
								<?php echo $form->textArea($modelCrmdetalles, 'crm_detalles_comentarios', array('class' => 'form-control')); ?>
								<?php echo $form->error($modelCrmdetalles, 'crm_detalles_comentarios'); ?>
							</div>
						</div>
						<?php echo $form->hiddenField($modelCrmdetalles, 'id_cliente', array('class' => 'form-control', 'value' => $datoscliente->id_cliente)); ?>
						<?php echo $form->hiddenField($modelCrmdetalles, 'id_oportunidad', array('class' => 'form-control', 'value' => $DatosOportunidad->id)); ?>

						<div class="row buttons mt-sm">
							<div class="col-md-12">
								<?php echo CHtml::submitButton('Agregar', array('class' => 'btn btn-success btn-sm pull-right')); ?>
							</div>
						</div>
						<?php $this->endWidget(); ?>
					</div><!-- form -->
			</div>
		<?php } ?>


		<div class="col-md-12">
			<fieldset>
				<legend style="margin-bottom:10px;">Lista de acciones del cliente</legend>
				<div class="table-responsive">
					<table id="listadetallescliente" class="table  table-bordered table-hover">
						<thead>
							<tr>
								<th>ID</th>
								<th>Acción</th>
								<th>Comentarios</th>
								<th>Estatus</th>
								<th>Fecha</th>
								<th>Agente</th>
								<th></th>
							</tr>
						</thead>

						<tbody>
							<?php
							foreach ($listadetallescliente as $rows) {
								// Verificamos si la accion ya esta vencida, para mostrarlo en color rojo.
								if (strtotime($rows->crm_detalles_fecha) < strtotime(date('Y-m-d H:i:s')) && $rows->estatus == "NO REALIZADO") {
									$class = 'vencido';
								} else {
									$class = '';
								}
								?>
								<tr class="<?= $class ?>">
									<td>
										<?= $rows->id_crm_detalle ?>
									</td>
									<td>
										<?= $rows->rl_crmaccion->crm_acciones_nombre ?>
									</td>
									<td>
										<?= $rows->crm_detalles_comentarios ?>

										<?php if ($rows->comentario_realizado != '') {
											echo '<br><i>' . $rows->comentario_realizado . '</i>';
										} ?>
									</td>
									<td>
										<?= $rows->estatus ?>
									</td>
									<td>
										<?= $rows->crm_detalles_fecha ?>
									</td>
									<td>
										<?= $rows->rl_usuarios->Usuario_Nombre ?>
									</td>
									<td><button type="button" class="btn btn-success btn-xs abrirmodadetalleaccion"
											data-idopdetalle="<?= $rows->id_crm_detalle ?>">Detalles</button></td>

								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</fieldset>
		</div>

		<div class="col-md-12">
			<fieldset>
				<legend style="margin-bottom:10px;">Lista de cotizaciones</legend>
				<div class="table-responsive">
					<table id="listacotizaciones" class="table table-bordered table-hover ">
						<thead>
							<tr>
								<th>Num</th>
								<!-- <th>Nombre</th> -->
								<!-- <th>Comentarios</th> -->
								<th>Agente</th>
								<th>Fecha Alta</th>
								<th></th>

							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($listacotizaciones as $rows) { ?>
								<tr>
									<td>
										<?= $rows->id_cotizacion ?>
									</td><!-- 
									<td>
										<? //= $rows->cotizacion_nombre ?>
									</td> -->
									<!-- <td> 
									<? //= $rows->cotizacion_comentario ?>
									 </td> -->
									<td>
										<?= $rows->rl_usuarios->Usuario_Nombre ?>
									</td>
									<td>
										<?= $rows->cotizacion_fecha_alta ?>
									</td>
									<td>
										<a href="<?= Yii::app()->createUrl('cotizaciones/Actualizarcotizacion/' . $rows['id_cotizacion']) ?>"
											class="btn btn-success btn-xs">Ver Cotización</a><br>
										<a href="<?= Yii::app()->createUrl('cotizaciones/pdf/' . $rows['id_cotizacion']) ?>"
											target="_blank" class="btn btn-danger btn-xs mt-xs">Ver PDF</a>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</fieldset>
		</div>
	</div>