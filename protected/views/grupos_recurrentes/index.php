<?php
/* @var $this Grupos_recurrentesController */
/* @var $model GruposRecurrentes */


$this->pageTitle='Administracion de '.$Datos->nombre;
$this->breadcrumbs=array(
	'Administracion'=>array('Administracion/modulos'),
	$Datos->nombre,
);

?>
<script type="text/javascript">
$( document ).ready(function() {

    // Funcion para mostrar el modal
    $( "#abrirmodal" ).click(function() {
        $("#formmodal").modal('show');
        $('#Catalogos_recurrentes-form')[0].reset();
    });

    // Funcion para ordenar la lista de resultados
    $('#lista').DataTable();
   
 });

    function Actualizar(id){
    	$('#Catalogos_recurrentes-form')[0].reset();
        var jqxhr = $.ajax({
        url: "<?php echo $this->createUrl("catalogos_recurrentes/datos"); ?>",
        type: "POST",
        dataType : "json",
        timeout : (120 * 1000),
        data: {
            id: id,
        },
        success : function(Response, newValue) {
            if (Response.requestresult == 'ok') {
            	$.notify(Response.message, "success");
               // Si el resultado es correcto, agregamos los datos al form del modal
               $("#CatalogosRecurrentes_id_catalogo_recurrente").val(Response.Datos.id_catalogo_recurrente);
               $("#CatalogosRecurrentes_nombre").val(Response.Datos.nombre);
               $("#CatalogosRecurrentes_num").val(Response.Datos.num);
               $("#CatalogosRecurrentes_descripcion").val(Response.Datos.descripcion);
               // y posteriormente mostramos el modal 
               $("#formmodal").modal('show');

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
<?php include 'modal/_form.php'; ?>

<div class="row">
    <div class="col-md-12">
        <h1><?=$this->pageTitle?> | <a href="#" class="btn btn-success" id="abrirmodal">
            Agregar
        </a></h1>
        
        <hr>
    <div class="table-responsive">
        <table id="lista" class="table table-bordered table-hover table-striped" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>                                 
                    <th>Num</th>
                    <th>Descripción</th>
                    <th>Acciones</th>   
                </tr>
            </thead>
            <tbody>
            		<?php 
            		foreach ($Lista as $rows){ ?>
                <tr>
                    <td><?=$rows->id_catalogo_recurrente?></td>
                    <td><?=$rows->nombre?></td>
                    <td><?=$rows->num?></td>
                    <td><?=$rows->descripcion?></td>
                   	<td class="">
                        <?php 
                            echo CHtml::link('<i class="fa fa-pencil fa-lg"></i> Editar', "javascript:;", array(
                                'style' => 'cursor: pointer;',
                                "onclick" => "Actualizar(".$rows['id_catalogo_recurrente']."); return false;"
                            ));


                           echo CHtml::link('<br><i class="fa fa-trash fa-lg"></i> Eliminar', array('catalogos_recurrentes/delete', 'id'=>$rows['id_catalogo_recurrente']),
                  array(
                    'submit'=>array('catalogos_recurrentes/delete', 'id'=>$rows['id_catalogo_recurrente']),
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