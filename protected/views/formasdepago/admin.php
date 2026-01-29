<?php
/* @var $this FormasdepagoController */
/* @var $model Formasdepago */


$this->pageTitle='Administración de Formas de pago';
$this->breadcrumbs=array(
        'Modulos'=> Yii::app()->createUrl('administracion/modulos'),
	'Formas de pago',
);

?>
<script type="text/javascript">
$( document ).ready(function() {

    // Funcion para mostrar el modal
    $( "#abrirmodal" ).click(function() {
        $("#formmodal").modal('show');
        $('#Formasdepago-form')[0].reset();
    });

    // Funcion para ordenar la lista de resultados
    $('#lista').DataTable();
   
 });

    function Actualizar(id){
        var jqxhr = $.ajax({
        url: "<?php echo $this->createUrl("Formasdepago/Formasdepagodatos"); ?>",
        type: "POST",
        dataType : "json",
        timeout : (120 * 1000),
        data: {
            id: id,
        },
        success : function(Response, newValue) {
            if (Response.requestresult == 'ok') {
               // Si el resultado es correcto, agregamos los datos al form del modal
               $("#Formasdepago_id_formapago").val(Response.Datos.id_formapago);
               $("#Formasdepago_formapago_nombre").val(Response.Datos.formapago_nombre);
               $("#Formasdepago_formapago_descripcion").val(Response.Datos.formapago_descripcion);
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
        <h1>Administración de Formas de pago | <a href="#" class="btn btn-success" id="abrirmodal">
            Agregar Formasdepago
        </a></h1>
        
        <hr>
        <div class="table-responsive">
        <table id="lista" class="table  table-bordered  table-hover ">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripcion</th>                
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            		<?php 
            		foreach ($lista as $rows){ ?>
                <tr>
                    <td><?=$rows->id_formapago?></td>
                    <td><?=$rows->formapago_nombre?></td>
                    <td><?=$rows->formapago_descripcion?></td>
                   	<td class="">
                        <?php 
                            echo CHtml::link('<i class="fa fa-pencil fa-lg"></i> Editar', "javascript:;", array(
                                'style' => 'cursor: pointer;',
                                "onclick" => "Actualizar(".$rows['id_formapago']."); return false;"
                            ));


                   	       echo CHtml::link('<br><i class="fa fa-trash fa-lg"></i> Eliminar', array('Formasdepago/delete', 'id'=>$rows['id_formapago']),
    						  array(
    						    'submit'=>array('Formasdepago/delete', 'id'=>$rows['id_formapago']),
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