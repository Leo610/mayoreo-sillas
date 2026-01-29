<?php
/* @var $this AdministracionController */


$this->pageTitle = 'Catalogos';

$this->breadcrumbs = array(
	'Catalogos',
);


?>

</div>
</div>
<div class="row">
	<div class="col-md-12">
		<h1>
			<?= $this->pageTitle ?>
		</h1>
		<p>Lista de
			<?= $this->pageTitle ?>
		</p>
		<br>
		<div class="container">
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped" cellspacing="0" width="100%">
					<tbody>
						<tr>

							<td class="">
								<a href="<?php echo Yii::app()->createUrl('productos/admin') ?>" class="">
									<b> Productos</b>
								</a>
							</td>
							<td class="">
								<a href="<?php echo Yii::app()->createUrl('listaprecios/admin') ?>" class="">
									<b> Lista Precios</b>
								</a>
							</td>
							<td class="">
								<a href="<?php echo Yii::app()->createUrl('productosprecios/admin') ?>" class="">
									<b> Productos precios</b>
								</a>
							</td>
						</tr>
						<tr>
							<td class="">
								<a href="<?php echo Yii::app()->createUrl('proveedores/admin') ?>" class="">
									<b> Proveedores</b>
								</a>
							</td>
							<td class="">
								<a href="<?php echo Yii::app()->createUrl('bancos/admin') ?>" class="">
									<b> Bancos</b>
								</a>
							</td>
							<td class="">
								<a href="<?php echo Yii::app()->createUrl('formasdepago/admin') ?>" class="">
									<b> Formas de Pago</b>
								</a>
							</td>
						</tr>
						<tr>

							<td class="">
								<a href="<?php echo Yii::app()->createUrl('empresas/admin') ?>" class="">
									<b> Empresas</b>
								</a>
							</td>


							<td class="">

								<a href="<?php echo Yii::app()->createUrl('clientes_frecuencias/admin') ?>" class="">
									<b> Clientes Frecuencias</b>
								</a>
							</td>
							<td class="">
								<a href="<?php echo Yii::app()->createUrl('tipo_proyectos/admin') ?>" class="">
									<b> Tipo Proyectos</b>
								</a>
							</td>
							<!-- <td class="">
								<a href="<? //php echo Yii::app()->createUrl('unidadesdemedida/admin') ?>" class="">
									<b> Unidades de medida</b>
								</a>
							</td> -->
						</tr>
						<tr>
							<td class="">
								<a href="<?php echo Yii::app()->createUrl('monedas/admin') ?>" class="">
									<b> Monedas</b>
								</a>
							</td>
							<td class="">
								<a href="<?php echo Yii::app()->createUrl('crmetapas/admin') ?>" class="">
									<b> CRM Etapas</b>
								</a>
							</td>
							<td class="">
								<a href="<?php echo Yii::app()->createUrl('cotizacionesplantterminos/admin') ?>"
									class="">
									<b> Plantilla para Cotizaciones</b>
								</a>
							</td>
						</tr>
						<tr>
							<td class="">
								<a href="<?php echo Yii::app()->createUrl('crmacciones/admin') ?>" class="">
									<b> Crm acciones</b>
								</a>
							</td>

						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>