<?php
/* @var $this ProyectosController */
$this->pageTitle='Lista de ordenes de compra';

$this->breadcrumbs=array(
	'Lista ordenes de compra',
);
?>
<script type="text/javascript">
$(document).ready(function() {
    // Funcion para ordenar la lista de resultados, lo ordena por la columna 3, ya que empieza 0,1,2,3,4 y muestra  5 resultados, solamente
    $('#listaordenesdecompra').DataTable( {
  dom: 'Bfrtip',
  buttons: [
  {
  extend: 'csv',
  title: 'Exportar',
  text: 'Exportar a EXCEL',
  },
  ]
  });
    
});
</script>
<div class="row">
	<div class="col-md-12">
	<h1>Ordenes de compra  | <a href="<?php echo Yii::app()->createUrl('ordenesdecompra/crear') ?>" class="btn btn-danger" >
        Crear Orden de Compra
        </a></h1>
	</div>
	<div class="col-md-12">
		<fieldset>
			<legend>Filtros</legend>
      <form method="GET">
			 <div class="col-md-3">
				  <select name="ordendecompra_estatus" onchange="this.form.submit()" class="form-control">
						<option value="">
              -- Seleccione estatus --
            </option>
						<option value="0" <?=($estatusorden==0 and $estatusorden!='Todos')?'selected':'';?>>Solo los pendientes</option>
						<option value="1" <?=($estatusorden==1)?'selected':'';?>>Solo las Abiertas</option>
            <option value="2" <?=($estatusorden==2)?'selected':'';?>>Solo las Finalizadas</option>
            <option value="4" <?=($estatusorden==4)?'selected':'';?>>Solo las Cerradas</option>
            <option value="3" <?=($estatusorden==3)?'selected':'';?>>Solo las Canceladas</option>
					</select>
      </div>
      <div class="col-md-3">
           <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                  'name'=>'fechainicio',
                  'language' => 'es',                                     
                  'htmlOptions'=>array(                                           
                    'readonly'=>"readonly",
                    'class'=>'form-control'
                  ),
                  'options'=>array(                                               
                          'dateFormat'=>'yy-mm-dd',                                                       
                  ),
                  'value'=>$fechainicio
          )); ?>
        </div>
        <div class="col-md-3">
          <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name'=>'fechafin',                                                                
                'language' => 'es',                                     
                'htmlOptions'=>array(                                           
                  'readonly'=>'readonly',
                  'class'=>'form-control'
                ),
                'options'=>array(                                               
                        'dateFormat'=>'yy-mm-dd',

                ),
                'value'=>$fechafin
        )); ?>
        </div>
        <div class="col-md-2">
          <?php echo CHtml::submitButton('Filtro',array('class'=>'btn btn-secondary btn-ml')); ?>
        </div>

      </form>
		</fieldset>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
  <div class="table-responsive">
    <table id="listaordenesdecompra" class="table table-bordered table-hover ">
      <thead>
          <tr>
              <th>ID</th>
              <th>Proveedor</th>
              <th>Usuario</th>
              <th>Estatus</th>
              <th>Total</th>
              <th>Moneda</th>
              <th>Ultima Modificacion</th>
              <th>Acción</th>
          </tr>
      </thead>
      <tbody>
      		<?php 
      		foreach ($listaordendecompra as $rows){ ?>
          <tr>
          		<td><?=$rows['id_orden_de_compra']?></td>
              <td><?=$rows['rl_Proveedor']['proveedor_nombre']?></td>
              <td><?=$rows['rl_usuarios']['Usuario_Nombre']?></td>
              <td><?=$this->ObtenerEstatusOC($rows['ordendecompra_estatus'])?></td>
              <td>$ <?=number_format($rows['ordendecompra_total'],2)?></td>
              <td><?=$rows['rl_moneda']['moneda_nombre']?></td>
              <td><?=$rows['ordendecompra_ultimamodif']?></td>
              <td>
              	<?php 
             	    echo CHtml::link('<i class="fa fa-search"></i> Ver',array('ordenesdecompra/ver/'.$rows['id_orden_de_compra']),array('class'=>"btn btn-secondary"));
								?>

                <?php 
                if($rows['ordendecompra_estatus']==2 or $rows['ordendecompra_estatus']==1)
                {
                  echo CHtml::link('<i class="fa fa-file-pdf-o"></i> PDF',array('ordenesdecompra/pdf/'.$rows['id_orden_de_compra']),array('class'=>"btn btn-danger",'target'=>'new'));
                }
                ?>
              </td>
         </tr>
         <?php } ?>
     </tbody>
    </table>
    </div>
	</div>
</div>

