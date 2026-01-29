<?php
/* @var $this MensajesrecibidosController */
/* @var $model Mensajesrecibidos */


$this->pageTitle='Mensajes recibidos';
$this->breadcrumbs=array(
        'Administracion'=> Yii::app()->createUrl('administracion/'),
        'Mensajes recibidos',
);

?>
<script type="text/javascript">
$( document ).ready(function() {
    // Funcion para ordenar la lista de resultados
    $('#lista').DataTable();   
    // Funcion para mostrar el modal
   
 });
function Actestatus(id,nuevovalor){
    // Verificamos que tengan datos
    if(id=='')
    {
        $.notify('Favor de verificar','error');
        return false;
    }
    if(nuevovalor=='')
    {
        $.notify('Favor de verificar','error');
        return false;   
    }

        var jqxhr = $.ajax({
        url: "<?php echo $this->createUrl("mensajes/actualizarestatus"); ?>",
        type: "POST",
        dataType : "json",
        timeout : (120 * 1000),
        data: {
            id: id,
            nuevovalor: nuevovalor
        },
        success : function(Response, newValue) {
            if (Response.requestresult == 'ok') {
                $.notify(Response.message, "success");
                // Al actualizar con exito y el estatus sea leido, bloqueamos el select para que nose pueda editar.
                if(nuevovalor=="LEIDO")
                {
                  $( "#estatus"+id ).prop( "disabled", true );
                }
            }else{
                 $.notify(Response.message, "error");    
            }
        },
        error: function(e){
                 $.notify("Ocurrio un error inesperado", "error");
            }
        });
    }

</script>
<?php 
$opmenu=7;
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
        <h1 class="m-md">Mensajes recibidos | <a href="<?=Yii::app()->createurl('administracion/mensajesenviados')?>" class="btn btn-default">Mensajes enviados </a></h1>
        <div class="table-responsive">
            <table id="lista" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Remitente</th>
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
                    <td><?=$rows->rl_remitente->Usuario_Nombre?></td>
                    <td><?=$rows['asunto']?></td>
                    <td><?=$rows->mensaje?></td>
                    <td>
                    <select id="estatus<?=$rows->id?>" class="form-control"onchange="Actestatus(<?=$rows->id?>,this.value);" <?=($rows->estatus=="LEIDO")?'disabled':''?>>
                      
                      <option value="LEIDO" <?=($rows->estatus=="LEIDO")?'selected':'block';?>>LEIDO</option>
                      <option value="NO LEIDO" <?=($rows->estatus=="NO LEIDO")?'selected':'';?>>NO LEIDO</option>
                    </select>
                    </td>
                    <td><?=$rows->fecha_alta?></td>
                    
                </tr>
                <?php } ?>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>