<?php
/* @var $this AdministracionController */


$this->pageTitle = 'Usuarios y perfiles';

$this->breadcrumbs = array(
	'Usuarios y perfiles',
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
								<a href="<?php echo Yii::app()->createUrl('usuarios/admin') ?>" class="">
									<b>Usuarios</b>
								</a>
							</td>
							<td>
								<a href="<?php echo Yii::app()->createUrl('perfiles/index') ?>" class="">
									<b>Perfiles</b>
								</a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>