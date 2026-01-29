<?php
/* @var $this Tipo_proyectosController */
/* @var $model Tipo_proyectos */


$this->pageTitle='Administración de Tipo_proyectos';
$this->breadcrumbs=array(
        'Modulos'=> Yii::app()->createUrl('administracion/modulos'),
	'Tipo_proyectos',
);

?>
<script type="text/javascript">
$( document ).ready(function() {

    // Funcion para mostrar el modal
    $( "#abrirmodal" ).click(function() {
        $("#formmodal").modal('show');
        $('#Tipo_proyectos-form')[0].reset();
    });

    // Funcion para ordenar la lista de resultados
    $('#lista').DataTable();
   
 });

    function Actualizar(id){
        var jqxhr = $.ajax({
        url: "<?php echo $this->createUrl("Tipo_proyectos/Datos"); ?>",
        type: "POST",
        dataType : "json",
        timeout : (120 * 1000),
        data: {
            id: id,
        },
        success : function(Response, newValue) {
            if (Response.requestresult == 'ok') {
               // Si el resultado es correcto, agregamos los datos al form del modal
               $("#TipoProyectos_id_tipo_proyecto").val(Response.Datos.id_tipo_proyecto);
               $("#TipoProyectos_nombre").val(Response.Datos.nombre);
               $("#TipoProyectos_serie_cotizacion").val(Response.Datos.serie_cotizacion);
               $("#TipoProyectos_sere_proyecto").val(Response.Datos.sere_proyecto);
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
        <h1>Administración de Tipo Proyectos | <a href="#" class="btn btn-success" id="abrirmodal">
            Agregar Tipo Proyectos
        </a></h1>
        
        <hr>
        <div class="table-responsive">
        <table id="lista" class="table  table-bordered  table-hover ">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Serie Cotizacion</th>
                    <th>Serie Proyecto</th>             
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            		<?php 
            		foreach ($lista as $rows){ ?>
                <tr>
                    <td><?=$rows->id_tipo_proyecto?></td>
                    <td><?=$rows->nombre?></td>
                    <td><?=$rows->serie_cotizacion?></td>
                    <td><?=$rows->sere_proyecto?></td>
                   	<td class="">
                        <?php 
                            echo CHtml::link('<i class="fa fa-pencil fa-lg"></i> Editar', "javascript:;", array(
                                'style' => 'cursor: pointer;',
                                "onclick" => "Actualizar(".$rows['id_tipo_proyecto']."); return false;"
                            ));


                   	       echo CHtml::link('<br><i class="fa fa-trash fa-lg"></i> Eliminar', array('Tipo_proyectos/delete', 'id'=>$rows['id_tipo_proyecto']),
    						  array(
    						    'submit'=>array('Tipo_proyectos/delete', 'id'=>$rows['id_tipo_proyecto']),
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