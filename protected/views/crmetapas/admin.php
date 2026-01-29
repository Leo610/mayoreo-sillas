<?php
/* @var $this CrmetapasController */
/* @var $model Crmetapas */


$this->pageTitle='Administración de Crmetapas';
$this->breadcrumbs=array(
        'Modulos'=> Yii::app()->createUrl('administracion/modulos'),
	'Crmetapas',
);

?>
<script type="text/javascript">
$( document ).ready(function() {

    // Funcion para mostrar el modal
    $( "#abrirmodal" ).click(function() {
        $("#formmodal").modal('show');
        $('#Crmetapas-form')[0].reset();
    });

    // Funcion para ordenar la lista de resultados
    $('#lista').DataTable();
   
 });

    function Actualizar(id){
        var jqxhr = $.ajax({
        url: "<?php echo $this->createUrl("Crmetapas/Crmetapasdatos"); ?>",
        type: "POST",
        dataType : "json",
        timeout : (120 * 1000),
        data: {
            id: id,
        },
        success : function(Response, newValue) {
            if (Response.requestresult == 'ok') {
               // Si el resultado es correcto, agregamos los datos al form del modal
               $("#Crmetapas_id").val(Response.Datos.id);
               $("#Crmetapas_nombre").val(Response.Datos.nombre);
               $("#Crmetapas_duracion_dias").val(Response.Datos.duracion_dias);
               $("#Crmetapas_orden").val(Response.Datos.orden);
               // y posteriormente mostramos el modal 
               $("#formmodal").modal('show');

            }else{
                
            }
        },
        error: function(e){
              $.notify('Ocurrio un error inesperado','error');
            }
        });
    }
</script>
<?php include 'modal/_form.php'; ?>

<div class="row">
    <div class="col-md-12">
        <h1>Administración de Crm Etapas | <a href="#" class="btn btn-success" id="abrirmodal">
            Agregar Crm Etapas
        </a></h1>
        
        <hr>
        <div class="table-responsive">
        <table id="lista" class="table  table-bordered  table-hover ">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Duracion Días</th>
                    <th>Orden</th>                
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            		<?php 
            		foreach ($lista as $rows){ ?>
                <tr>
                    <td><?=$rows['id']?></td>
                    <td><?=$rows['nombre']?></td>
                    <td><?=$rows['duracion_dias']?></td>
                    <td><?=$rows['orden']?></td>
                   	<td class="">
                        <?php 
                            echo CHtml::link('<i class="fa fa-pencil fa-lg"></i> Editar', "javascript:;", array(
                                'style' => 'cursor: pointer;',
                                "onclick" => "Actualizar(".$rows['id']."); return false;"
                            ));


                   	       echo CHtml::link('<br><i class="fa fa-trash fa-lg"></i> Eliminar', array('Crmetapas/delete', 'id'=>$rows['id']),
    						  array(
    						    'submit'=>array('Crmetapas/delete', 'id'=>$rows['id']),
    						    'class' => 'delete','confirm'=>'Seguro que lo deseas eliminar?'
    						  )
    						);
    						?>
                   </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        </div>
    </div>
</div>