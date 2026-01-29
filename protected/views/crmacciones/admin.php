<?php
/* @var $this CrmaccionesController */
/* @var $model Crmacciones */


$this->pageTitle='Administración de Crmacciones';
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
        $('#Crmacciones-form')[0].reset();
    });

    // Funcion para ordenar la lista de resultados
    $('#lista').DataTable();
   
 });

    function Actualizar(id){
        var jqxhr = $.ajax({
        url: "<?php echo $this->createUrl("Crmacciones/datos"); ?>",
        type: "POST",
        dataType : "json",
        timeout : (120 * 1000),
        data: {
            id: id,
        },
        success : function(Response, newValue) {
            if (Response.requestresult == 'ok') {
               // Si el resultado es correcto, agregamos los datos al form del modal
               $("#Crmacciones_id_crm_acciones").val(Response.Datos.id_crm_acciones);
               $("#Crmacciones_crm_acciones_nombre").val(Response.Datos.crm_acciones_nombre);
               $("#Crmacciones_crm_acciones_icono").val(Response.Datos.crm_acciones_icono);
               $("#Crmacciones_crm_acciones_color").val(Response.Datos.crm_acciones_color);
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
        <h1>Administración de Acciones | <a href="#" class="btn btn-success" id="abrirmodal">
            Agregar acción
        </a></h1>
        
        <hr>
        <div class="table-responsive">
        <table id="lista" class="table  table-bordered  table-hover ">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Icono</th>
                    <th>Color</th> 
                    <th>Acciones</th>  
                </tr>
            </thead>
            <tbody>
            		<?php 
            		foreach ($lista as $rows){ ?>
                <tr>
                    <td><?=$rows['id_crm_acciones']?></td>
                    <td><?=$rows['crm_acciones_nombre']?></td>
                    <td><?=$rows['crm_acciones_icono']?></td>
                    <td><?=$rows['crm_acciones_color']?></td>
                   	<td class="">
                        <?php 
                            echo CHtml::link('<i class="fa fa-pencil fa-lg"></i> Editar', "javascript:;", array(
                                'style' => 'cursor: pointer;',
                                "onclick" => "Actualizar(".$rows['id_crm_acciones']."); return false;"
                            ));


                   	       echo CHtml::link('<br><i class="fa fa-trash fa-lg"></i> Eliminar', array('Crmacciones/delete', 'id'=>$rows['id_crm_acciones']),
    						  array(
    						    'submit'=>array('Crmacciones/delete', 'id'=>$rows['id_crm_acciones']),
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