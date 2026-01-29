<?php
/* @var $this AdministracionController */


$this->pageTitle = 'Modulos';

$this->breadcrumbs = array(
	'Modulos',
);
?>
<div class="row">
	<div class="col-md-12">
		<h1>Lista de modulos</h1>
		<p>A continuación la lista de modulos, en los cuales usted podra agregar, crear, editar o eliminar los
			diferentes modulos, para su administración.</p>
		<br>
		<div class="container">
			<div class="table-responsive">
				<!-- una tranza por que el tiempo se agota -->
				<table class="table table-bordered table-hover table-striped" cellspacing="0" width="100%">
					<tbody>
						<tr>
							<!-- <td>
								<a href="<? //php echo Yii::app()->createUrl('configuracion/index') ?>" class="">
									<b>Configuración</b>
								</a>
							</td>

							<td>
								<a href="<? //php echo Yii::app()->createUrl('productos/admin') ?>" class="">
									<b>Productos</b>
								</a>
							</td> -->
							<!-- <td>
								<a href="<? //php echo Yii::app()->createUrl('listaprecios/admin') ?>" class="">
									<b>Lista Precios</b>
								</a>
							</td> -->
							<!-- <td>
								<a href="<? //php echo Yii::app()->createUrl('formasdepago/admin') ?>" class="">
									<b>Formas de Pago</b>
								</a>
							</td> -->
						</tr>
						<tr>
							<!--
		<a href="<?php echo Yii::app()->createUrl('proveedores/admin') ?>" class="">
			Proveedores
		</a>

		<a href="<?php echo Yii::app()->createUrl('bancos/admin') ?>" class="">
			Bancos
		</a>
		-->
							<!-- <td>
								<a href="<? //php echo Yii::app()->createUrl('productosprecios/admin') ?>" class="">
									<b>Productos precios</b>
								</a>
							</td>
							<td>
								<a href="<? //php echo Yii::app()->createUrl('clientes_frecuencias/admin') ?>" class="">
									<b>Clientes Frecuencias</b>
								</a>
							</td>

							<td>
								<a href="<? //php echo Yii::app()->createUrl('crmetapas/admin') ?>" class="">
									<b>CRM Etapas</b>
								</a>
							</td> -->
							<!-- <td>
								<a href="<? //php echo Yii::app()->createUrl('monedas/admin') ?>" class="">
									<b>Monedas</b>
								</a>
							</td> -->
							<!-- <td>
								<a href="<? //php echo Yii::app()->createUrl('empresas/admin') ?>" class="">
									<b>Empresas</b>
								</a>
							</td> -->
						</tr>
						<tr>
							<!--
		<a href="<?php echo Yii::app()->createUrl('productosproveedores/admin') ?>" class="">
			Productos Proveedores
		</a>
		-->


							<!-- <td>
								<a href="<? //php echo Yii::app()->createUrl('cotizacionesplantterminos/admin') ?>"
									class="">
									<b>Plantilla para Cotizaciones</b>
								</a>
							</td>
							<td>
								<a href="<? //php echo Yii::app()->createUrl('crmacciones/admin') ?>" class="">
									<b>Crm acciones</b>
								</a>
							</td> -->
							<!-- <td>
								<a href="<? //php echo Yii::app()->createUrl('usuarios/admin') ?>" class="">
									<b>Usuarios</b>
								</a>
							</td> -->
							<!-- <td>
								<a href="<? //php echo Yii::app()->createUrl('tipo_proyectos/admin') ?>" class="">
									<b>Tipo Proyectos</b>
								</a>
							</td> -->

							<!-- <td>
								<a href="<? //php echo Yii::app()->createUrl('unidadesdemedida/admin') ?>" class="">
									<b>Unidades de medida</b>
								</a>
							</td> -->
						</tr>
						<tr>
							<!-- <td>
								<a href="<? //php echo Yii::app()->createUrl('monedas/admin') ?>" class="">
									<b>Monedas</b>
								</a>
							</td> -->

						</tr>
						<tr>


							<!-- <td>
								<a href="<? //php echo Yii::app()->createUrl('perfiles/index') ?>" class="">
									<b>Perfiles</b>
								</a>
							</td> -->
						</tr>
						<!--
		<a href="<?php echo Yii::app()->createUrl('modulos/admin') ?>" class="">
			Modulos
		</a>
		-->
						<!-- <tr>
							

						</tr> -->





						<?php $count = 0; ?>
						<?php foreach ($arreglo_modulos as $row) { ?>
							<?php if ($count % 3 === 0) { ?>
								<tr> <!-- Abre una nueva fila después de cada tercer elemento -->
								<?php } ?>
								<td>
									<a href="<?php echo $row['url'] ?>" class="">
										<b>
											<?= $row['nombre'] ?>
										</b>
									</a>
								</td>
								<?php if ($count % 3 === 2) { ?>
								</tr> <!-- Cierra la fila después del tercer elemento -->
							<?php } ?>
							<?php $count++; ?>
						<?php } ?>
						<?php if ($count % 3 !== 0) { ?>
							<?php // Cerrar la última fila si no está completa ?>
							<?php while ($count % 3 !== 0) { ?>
								<td></td> <!-- Añade celdas vacías para completar la fila -->
								<?php $count++; ?>
							<?php } ?>
							</tr> <!-- Cierra la última fila incompleta -->
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>