<?php
/* @var $this Clientes_frecuenciasController */
/* @var $model Clientes_frecuencias */


$this->pageTitle='Administración de Clientes frecuencias';
$this->breadcrumbs=array(
        'Modulos'=> Yii::app()->createUrl('administracion/modulos'),
	'Clientes_frecuencias',
);

?>
<script type="text/javascript">
$( document ).ready(function() {

    // Funcion para mostrar el modal
    $( "#abrirmodal" ).click(function() {
        $("#formmodal").modal('show');
        $('#ClientesFrecuencias-form')[0].reset();
    });

    // Funcion para ordenar la lista de resultados
    $('#lista').DataTable();
   
 });

    function Actualizar(id){
        var jqxhr = $.ajax({
        url: "<?php echo $this->createUrl("Clientes_frecuencias/Datos"); ?>",
        type: "POST",
        dataType : "json",
        timeout : (120 * 1000),
        data: {
            id: id,
        },
        success : function(Response, newValue) {
            if (Response.requestresult == 'ok') {
               // Si el resultado es correcto, agregamos los datos al form del modal
               $("#ClientesFrecuencias_id").val(Response.Datos.id);
               $("#ClientesFrecuencias_id_cliente").val(Response.Datos.id_cliente);
               $("#ClientesFrecuencias_id_frecuencia").val(Response.Datos.id_frecuencia);
               $("#ClientesFrecuencias_nombre_dia").val(Response.Datos.nombre_dia);
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
        <h1>Administración de Clientes frecuencia | <a href="#" class="btn btn-success" id="abrirmodal">
            Agregar Clientes frecuencia
        </a></h1>
        
        <hr>
        <div class="table-responsive">
        <table id="lista" class="table  table-bordered  table-hover ">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Frecuencia</th>
                    <th>Nombre Día</th>                
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            		<?php 
            		foreach ($lista as $rows){ ?>
                <tr>
                    <td><?=$rows->id?></td>
                    <td><?=$rows['rl_clientes']['cliente_nombre']?></td>
                    <td><?=$rows['rl_frecuencia']['nombre']?></td>
                    <td><?=$rows->nombre_dia?></td>
                   	<td class="">
                        <?php 
                            echo CHtml::link('<i class="fa fa-pencil fa-lg"></i> Editar', "javascript:;", array(
                                'style' => 'cursor: pointer;',
                                "onclick" => "Actualizar(".$rows['id']."); return false;"
                            ));


                   	       echo CHtml::link('<br><i class="fa fa-trash fa-lg"></i> Eliminar', array('clientes_frecuencias/delete', 'id'=>$rows['id']),
    						  array(
    						    'submit'=>array('clientes_frecuencias/delete', 'id'=>$rows['id']),
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