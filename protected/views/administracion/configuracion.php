<?php
/* @var $this AdministracionController */


$this->pageTitle = 'Configuración';

$this->breadcrumbs = array(
	'Configuración',
);


?>
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
							<td>
								<a href="<?php echo Yii::app()->createUrl('configuracion/index') ?>" class="">
									<b>Configuración</b>
								</a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>