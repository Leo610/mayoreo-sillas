<?php
/* @var $this ContabilidadegresosController */
$this->pageTitle='Lista de egresos';

$this->breadcrumbs=array(
    'Contabilidad'=>array('/administracion/contabilidad'),
	'Lista de egresos',
);
?>
<script type="text/javascript">
$(document).ready(function() {
    // Funcion para ordenar la lista de resultados, lo ordena por la columna 3, ya que empieza 0,1,2,3,4 y muestra  5 resultados, solamente
    $('#contabilidadegresos').DataTable( {
        "order": [[ 7, "desc" ]]
    } );
    
});
</script>
<div class="row">
	<div class="col-md-8">
		<h1>Lista de Egresos | <a href="<?php echo Yii::app()->createUrl('contabilidadegresos/pendientespago')?>" class=" btn btn-primary">
      Pendientes de pago
    </a></h1>
	</div>
</div>
    <div class="col-md-12">
        <fieldset>
            <legend>Filtros</legend>
            <form>
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
<div class="row">
	<div class="col-md-12">
  <div class="table-responsive">
		<table id="contabilidadegresos" class="table  table-bordered  table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Referencia</th>
                <th>Identificador</th>
                <th>Forma de Pago</th>
                <th>Banco</th>
                <th>Moneda</th>
                <th>Usuario</th>
                <th>Cantidad</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            foreach ($ListaEgresos as $rows){
                $identificadores = $this->ObtenerProveedor($rows->contabilidad_egresos_identificador);
                 ?>
            <tr>
                <td><?=$rows->id_contabilidad_egresos?></td>
                <td><?=$identificadores['referencia']?></td>
                <td><?=$rows->contabilidad_egresos_identificador.'-'.$identificadores['identificador']?></td>
                <td><?=$rows->rl_formasdepago->formapago_nombre?></td>
                <td><?=$rows->rl_banco->banco_nombre?></td>
                 <td><?=$rows['rl_moneda']['moneda_nombre']?></td>
                <td><?=$rows->rl_usuario->Usuario_Nombre?></td>
                <td>$ <?=number_format($rows->contabilidad_egresos_cantidad,2)?></td>
                <td><?=$rows->contabilidad_egresos_fechaalta?></td>
                
            </tr>
            <?php } ?>
        </tbody>
    </table>
    </div>
	</div>
</div>

