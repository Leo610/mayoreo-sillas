<?php
/* @var $this RutasController */
/* @var $model Rutas */


$this->pageTitle='Administración de Rutas';
$this->breadcrumbs=array(
        'Aministracion'=> Yii::app()->createUrl('administracion/rutas'),
    'Rutas',
);

?>
<script type="text/javascript">
$( document ).ready(function() {
    // Funcion para ordenar la lista de resultados
    $('#lista').DataTable();   
    // Funcion para mostrar el modal
    $( ".abrirmodal" ).click(function() {
        var modal = $(this).data('idmodal');
        $(modal).modal('show');
    }); 
 });

</script>
<?php 
$opmenu=6;
include 'menu/menu.php'; 
include 'modals/modal.crearruta.php';
?>

<div class="row">
    <div class="col-md-12">
        <h1 class="m-md">Administración de Rutas | <button class="btn btn-success abrirmodal" data-idmodal="#formmodalcrearruta" id="abrirmodal">
            Agregar Rutas
        </button></h1>

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
        
        <hr>
        <div class="table-responsive">
        <table id="lista" class="table  table-bordered  table-hover ">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Vendedor</th>
                    <th>Nombre</th>
                    <th>Fecha Desde</th>
                    <th>Fecha Hasta</th>
                    <th>Estatus</th>
                    <th>Comentarios</th> 
                    <th>Acciones</th>            
                    
                </tr>
            </thead>
            <tbody>
                    <?php 
                    foreach ($lista as $rows){ ?>
                <tr>
                    <td><?=$rows->id?></td>
                    <td><?=$rows['rl_vendedor']['Usuario_Nombre']?></td>
                    <td><?=$rows->nombre?></td>
                    <td><?=$rows->fecha_desde?></td>
                    <td><?=$rows->fecha_hasta?></td>
                    <td><?=$rows->estatus?></td>
                    <td><?=$rows->comentarios?></td>
                    <td>
                    <?php 
                    echo CHtml::link('<i class="fa fa-search"></i> Detalle',array('administracion/rutadetalle/'.$rows['id']),array('class'=>"btn btn-success"));
                                ?>
                   </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        </div>
    </div>
</div>