<?php
/* @var $this MensajesenviadosController */
/* @var $model Mensajesenviados */


$this->pageTitle='Mensajes enviados';
$this->breadcrumbs=array(
        'Administracion'=> Yii::app()->createUrl('administracion/'),
    'Mensajes enviados',
);

?>
<script type="text/javascript">
$( document ).ready(function() {
    // Funcion para ordenar la lista de resultados
    $('#lista').DataTable();   
   
 });

</script>
<?php 
$opmenu=8;
include 'menu/menu.php'; 
?>
<fieldset>
            <legend>Filtros</legend>
            <form method="GET">
            <div class="col-md-5">
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
        <div class="col-md-5">
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
                  <?php echo CHtml::submitButton('Filtro',array('class'=>'btn btn-success btn-ml')); ?>
                </div>  
          </form>
        </fieldset>

<div class="row">
    <div class="col-md-12">
        <h1 class="m-md">Mensajes enviados | <a href="<?=Yii::app()->createurl('administracion/mensajesrecibidos')?>" class="btn btn-default">Mensajes recibidos </a></h1>
        <div class="table-responsive">
            <table id="lista" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Destinatario</th>
                        <th>Asunto</th>
                        <th>Mensaje</th>
                        <th>Estatus</th>
                        <th>Fecha envio</th>
                    </tr>
                </thead>
                <tbody>
                 <?php 
                    foreach ($lista as $rows){ ?>
                <tr>
                    <td><?=$rows['rl_destinatario']['Usuario_Nombre']?></td>
                    <td><?=$rows['asunto']?></td>
                    <td><?=$rows['mensaje']?></td>
                    <td><?=$rows['estatus']?></td>
                    <td><?=$rows['fecha_alta']?></td>
                    
                </tr>
                <?php } ?>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>