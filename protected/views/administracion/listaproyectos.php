<?php
/* @var $this ProyectosController */
$this->pageTitle='Lista de proyectos';

$this->breadcrumbs=array(
  'Administracion'=>array('/administracion'),
	'Lista Proyectos',
);
?>
<script type="text/javascript">
$(document).ready(function() {
    // Funcion para ordenar la lista de resultados, lo ordena por la columna 3, ya que empieza 0,1,2,3,4 y muestra  5 resultados, solamente
    $('#listaproyectos').DataTable( {
        "order": [[ 6, "desc" ]]
    } );
    
});
</script>
<div class="row">
	<div class="col-md-12">
	<h1>Lista de proyectos</h1>
	</div>
	<div class="col-md-12">
		<fieldset>
			<legend>Filtros </legend>
      <form method="GET">
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
		<table id="listaproyectos" class="table table-bordered table-hover ">
      <thead>
          <tr>
              <th>Num</th>
              <th>Nombre</th>
              <th>Cliente</th>
              <th>Estatus</th>
              <th>Supervisor</th>
              <th>Usuario</th>
              <th>Fecha Alta</th>
          </tr>
      </thead>
      <tbody>
      		<?php 
      		foreach ($listaproyectos as $rows){ ?>
          <tr>
          		<td><?=$rows['numero_proyecto']?></td>
              <td><?=$rows['proyecto_nombre']?></td>
          		<td><?=$rows['rl_clientes']['cliente_razonsocial']?></td>
          		<td><?=$rows['proyecto_estatus']?></td>
              <td><?=$rows['rl_supervisor']['empleado_nombre']?></td>
              <td><?=$rows['rl_usuarios']['Usuario_Nombre']?></td>
              <td><?=$rows['proyecto_fecha_alta']?></td>  
         </tr>
         <?php } ?>
     </tbody>
  </table>
  </div>
	</div>
</div>

