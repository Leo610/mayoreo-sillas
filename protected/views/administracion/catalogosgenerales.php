<?php
/* @var $this AdministracionController */


$this->pageTitle = 'Catalogos generales';

$this->breadcrumbs = array(
	'Catalogos generales',
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
						<?php $count = 0; ?>
						<?php foreach ($listagrupos as $row) { ?>
							<?php if ($count % 3 === 0) { ?>
								<tr> <!-- Abre una nueva fila después de cada tercer elemento -->
								<?php } ?>
								<td>
									<a href="<?php echo Yii::app()->createUrl('grupos_recurrentes/index/' . $row->id_grupo_recurrente) ?>"
										class="">
										<b>
											<?= $row->nombre ?>
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