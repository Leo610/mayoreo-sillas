<?php
/* @var $this DatosadicionalesController */

$this->breadcrumbs=array(
	'Cotizaciones'=>array('/cotizaciones'),
	'Crear',
);
?>
<div class="row">
	<div class="col-md-8">
		<fieldset>
		<legend>Datos adicionales</legend>
		<input type="text" name="nombrecotizacion" id="nombrecotizacion" class="form-control" placeholder="Nombre cotizacion"><br>
		<select name="id_moneda" class="form-control" id="id_moneda">
			<option value="">-- Seleccione la Moneda --</option>
			<?php foreach($Listamoneda as $crows){ ?>
				<option value="<?=$crows->id_moneda;?>">
					<?=$crows->moneda_nombre?>
				</option>
			<?php } ?>
		</select><br>
		<textarea name="condicionesgenerales" class="form-control" rows="2" id="condicionesgenerales" placeholder="Condiciones Generales"></textarea>
		</br>
		<textarea name="comentarioscotizacion" class="form-control" rows="2" id="comentariozcotizacion" placeholder="Comentarios de la cotización"></textarea>
		<p class="mt-lg text-center" style="font-weight:bold; font-size:1.3em;">"<?=$this->numtoletras($Total);?>"</p>
	</fieldset>
</div>
<?php echo CHtml::submitButton('Guardar',array('class'=>'btn btn-secondary btn-lg')); ?>