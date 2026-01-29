<?php
/* @var $this ModulosController */
/* @var $model Modulos */


$this->pageTitle='Administración de Modulos';
$this->breadcrumbs=array(
        'Modulos'=> Yii::app()->createUrl('administracion/modulos'),
    'Modulos',
);

?>
<script type="text/javascript">
$( document ).ready(function() {

    // Funcion para mostrar el modal
    $( "#abrirmodal" ).click(function() {
        $("#formmodal").modal('show');
        $('#Modulos-form')[0].reset();
    });

    // Funcion para ordenar la lista de resultados
    $('#lista').DataTable();
   
 });

    function Actualizar(id){
        var jqxhr = $.ajax({
        url: "<?php echo $this->createUrl("Modulos/Modulosdatos"); ?>",
        type: "POST",
        dataType : "json",
        timeout : (120 * 1000),
        data: {
            id: id,
        },
        success : function(Response, newValue) {
            if (Response.requestresult == 'ok') {
               // Si el resultado es correcto, agregamos los datos al form del modal
               $("#Modulos_id_modulos").val(Response.Datos.id_modulos);
               $("#Modulos_modulos_nombre").val(Response.Datos.modulos_nombre);
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
        <h1>Administración de Modulos</h1>
        <hr>
        <div class="table-responsive">
        <table id="lista" class="table  table-bordered  table-hover ">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                </tr>
            </thead>
            <tbody>
                    <?php 
                    foreach ($lista as $rows){ ?>
                <tr>
                    <td><?=$rows->id_modulos?></td>
                    <td><?=$rows->modulos_nombre?></td>
                   
                </tr>
                <?php } ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
