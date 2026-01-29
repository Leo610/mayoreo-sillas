<?php
/* @var $this UnidadesdemedidaController */
/* @var $model Unidadesdemedida */


$this->pageTitle='Administración de Unidades de medida';
$this->breadcrumbs=array(
  'Modulos'=> Yii::app()->createUrl('administracion/modulos'),
	'Acciones',
);

?>
<script type="text/javascript">
$( document ).ready(function() {

    // Funcion para mostrar el modal
    $( "#abrirmodal" ).click(function() {
        $("#formmodal").modal('show');
        $('#Unidadesdemedida-form')[0].reset();
    });

    // Funcion para ordenar la lista de resultados
    $('#lista').DataTable();
   
 });

    function Actualizar(id){
        var jqxhr = $.ajax({
        url: "<?php echo $this->createUrl("Unidadesdemedida/datos"); ?>",
        type: "POST",
        dataType : "json",
        timeout : (120 * 1000),
        data: {
            id: id,
        },
        success : function(Response, newValue) {
            if (Response.requestresult == 'ok') {
               // Si el resultado es correcto, agregamos los datos al form del modal
               $("#Unidadesdemedida_id_unidades_medida").val(Response.Datos.id_unidades_medida);
               $("#Unidadesdemedida_unidades_medida_nombre").val(Response.Datos.unidades_medida_nombre);
               $("#Unidadesdemedida_unidades_medida_abreviatura").val(Response.Datos.unidades_medida_abreviatura);
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
        <h1>Administración Unidades de medida | <a href="#" class="btn btn-success" id="abrirmodal">
            Agregar Unidad de medida
        </a></h1>
        
        <hr>
        <div class="table-responsive">
        <table id="lista" class="table  table-bordered  table-hover ">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Abreviatura</th>
                    <th>Acciones</th>  
                </tr>
            </thead>
            <tbody>
            		<?php 
            		foreach ($lista as $rows){ ?>
                <tr>
                    <td><?=$rows['id_unidades_medida']?></td>
                    <td><?=$rows['unidades_medida_nombre']?></td>
                    <td><?=$rows['unidades_medida_abreviatura']?></td>
                   	<td class="">
                        <?php 
                            echo CHtml::link('<i class="fa fa-pencil fa-lg"></i> Editar', "javascript:;", array(
                                'style' => 'cursor: pointer;',
                                "onclick" => "Actualizar(".$rows['id_unidades_medida']."); return false;"
                            ));


                   	       echo CHtml::link('<br><i class="fa fa-trash fa-lg"></i> Eliminar', array('Unidadesdemedida/delete', 'id'=>$rows['id_unidades_medida']),
    						  array(
    						    'submit'=>array('Unidadesdemedida/delete', 'id'=>$rows['id_unidades_medida']),
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