<?php
$this->pageTitle = 'Lista de Sucursales';

$this->opcionestitulo = '<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#agregarregistromodal">Agregar sucursal</button>';

$this->pageDescription = '';
$this->breadcrumbs = array(
	$this->pageTitle
);
// incluimos el modal para agregar y editar registro
include 'modal/agregarregistro.php';
//
$this->renderpartial('//inventario/menu', array('op_menu' => 13));

?>
<!--<div class="container-fluid">
	<div class="row my-5">
		<div class="col-12">
		<?= ($this->menuadicional != '') ? $this->menuadicional : ''; ?>

		</div>
	</div>
</div>-->

<script type="text/javascript">
	$(document).ready(function () {
		$(".js-switch-small").change(function () {
			// aqui entra cuando el checkbox ya cambio, por ejemplo si esta vacio muestra checekeado y alreves
			/*alert($(this).is(":checked"));*/
			if ($(this).is(":checked")) {
				var valor = 1;
			} else {
				var valor = 0;
			}
			var id = $(this).data('id');
			var campo = $(this).data('campo');

			// metodo ajax para actualizar la informacion
			var jqxhr = $.ajax({
				url: "<?php echo $this->createUrl("sucursales/actualizarcampoajax"); ?>",
				type: "POST",
				dataType: "json",
				timeout: (120 * 1000),
				data: {
					valor: valor,
					id: id,
					campo: campo
				},
				success: function (Response, newValue) {
					if (Response.requestresult == 'ok') {
						toastr.success(Response.message, { timeOut: 500 })
					} else {
						toastr.warning(Response.message, { timeOut: 500 })
					}
				},
				error: function (e) {
					toastr.warning('Ocurrio un error inesperado', { timeOut: 500 })
				}
			});
		});
	});
</script>
<div class="row">
	<div class="col-md-8">
		<h1><?= $this->pageTitle ?> | <a class="btn btn-success btn-sm" data-toggle="modal"
				data-target="#agregarregistromodal">
				<i class="fa fa-list-ol"></i> Agregar sucursal
			</a></h1>
	</div>
</div>
<div class="panel">
	<div class="col-md-12">
		<div class="table-responsive">
			<table id="resultado" class="table table-sm table-hover datatable">
				<thead>
					<tr>
						<th></th>
						<th>Nombre</th>
						<!--<th>RFC</th>
					<th>Regimen</th>-->
						<th>Correo</th>
						<th>Ciudad</th>
						<th>Estado</th>
						<th>Codigo Postal</th>
						<th>Estatus</th>
						<th>Sucursal Principal</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($lista as $rows) { ?>
						<tr>
							<td align="center">
								<?php if ($rows['logotipo'] != '') {
									echo '<img src="' . yii::app()->baseurl . '/images/sucursales/' . $rows['logotipo'] . '" style="max-height:50px;max-width:50px;">';
								} ?>
							</td>
							<td>
								<?= $rows['nombre'] ?>
							</td>
							<!--<td><?= $rows['rfc'] ?></td>
					<td><?= $rows['idRegimen']['descripcion'] ?></td>-->
							<td>
								<?= $rows['correo'] ?>
							</td>
							<td>
								<?= $rows['idMunicipio']['Municipio_Nombre'] ?>
							</td>
							<td>
								<?= $rows['idEntidad']['Entidad_Nombre'] ?>
							</td>
							<td>
								<?= $rows['codigo_postal'] ?>
							</td>
							<td data-order="<?= $rows['estatus'] ?>">
								<center>
									<input type="checkbox" class="js-switch-small switchtoggle" data-color="#3aa99e"
										data-plugin="switchery" data-size="small" data-id="<?= $rows['id'] ?>"
										<?= ($rows['estatus'] == 1) ? 'checked' : ''; ?> data-campo="estatus" />
								</center>
							</td>
							<td data-order="<?= $rows['bodega_principal'] ?>">
								<center>
									<input type="checkbox" class="js-switch-small switchtoggle" data-color="#3aa99e"
										data-plugin="switchery" data-size="small" data-id="<?= $rows['id'] ?>"
										<?= ($rows['bodega_principal'] == 1) ? 'checked' : ''; ?> data-campo="bodega_principal" />
								</center>
							</td>

							<td>
								<a href="<?= yii::app()->createurl('sucursales/detalle') . '/' . $rows['id'] ?>"
									class="btn btn-default btn-xs"> <i class="fa fa-pencil "></i> Editar </a>
								<?php
								echo CHtml::link(
									'<i class="fa fa-trash "></i> Eliminar',
									array('Sucursales/delete', 'id' => $rows['id']),
									array(
										'submit' => array('Sucursales/delete', 'id' => $rows['id']),
										'class' => 'btn btn-danger btn-xs',
										'confirm' => 'Seguro que lo deseas eliminar?'
									)
								);
								?>

							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function () {
		$(".actualizarmunicipios").change(function () {
			if (this.value == '') {
				return false;
			}
			var campo = $(this).data('campo');

			// enviamos la petición ajaxx
			var jqxhr = $.ajax({
				url: "<?php echo $this->createUrl("site/obtenermunicipios"); ?>",
				type: "POST",
				dataType: "json",
				timeout: (120 * 1000),
				data: {
					id_entidad: this.value,
				},
				success: function (Response) {
					if (Response.requestresult == 'ok') {
						 $.notify(Response.message, "success");
						//toastr.success(Response.message, { timeOut: 500 })
							$('.ciudades').empty().append(Response.options);
						
					} else {
						 $.notify(Response.message, "warning");
						//toastr.warning(Response.message, { timeOut: 500 })
					}
				},
				error: function (e) {
					//toastr.warning('Ocurrio un error inesperado', { timeOut: 500 })
					 $.notify('Ocurrio un error inesperado', "warning");
				}
			});
		});

		$("body").on("change", ".actualizarcampo", function () {

			var valor = $(this).val();
			var id = $(this).data('id');
			var campo = $(this).data('campo');
			var model = $(this).data('model');

			var jqxhr = $.ajax({
				url: "<?php echo $this->createUrl("sucursales/actualizarcampoajax"); ?>",
				type: "POST",
				dataType: "json",
				timeout: (120 * 1000),
				data: {
					valor: valor,
					id: id,
					campo: campo,
					model: model,
				},
				success: function (Response, newValue) {
					if (Response.requestresult == 'ok') {
						toastr.success(Response.message, { timeOut: 500 })
					} else {
						toastr.warning(Response.message, { timeOut: 500 })
					}
				},
				error: function (e) {
					toastr.warning('Ocurrio un error inesperado', { timeOut: 500 })
				}
			});
		});
	});

</script>