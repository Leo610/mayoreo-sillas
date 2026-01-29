<?php
/* @var $this PerfilesController */
/* @var $model Perfiles */


$this->pageTitle = 'Administración de Perfiles';
$this->breadcrumbs = array(
    'Mantenimiento' => Yii::app()->createUrl('mantenimiento/index'),
    'Perfiles',
);

?>
<script type="text/javascript">
    $(document).ready(function () {

        // Funcion para mostrar el modal
        $("#abrirmodal").click(function () {
            $('#Perfiles-form')[0].reset();
            $("#formmodal").modal('show');

        });

        // Funcion para ordenar la lista de resultados
        $('#lista').DataTable({
            pageLength: 100
        });

    });

    function Actualizar(id) {
        var jqxhr = $.ajax({
            url: "<?php echo $this->createUrl("Perfiles/datos"); ?>",
            type: "POST",
            dataType: "json",
            timeout: (120 * 1000),
            data: {
                id: id,
            },
            success: function (Response, newValue) {
                $('#Perfiles-form')[0].reset();
                if (Response.requestresult == 'ok') {
                    // Si el resultado es correcto, agregamos los datos al form del modal
                    $("#Perfiles_id_perfil").val(Response.Datos.id_perfil);
                    $("#Perfiles_nombre").val(Response.Datos.nombre);
                    $("#Perfiles_descripcion").val(Response.Datos.descripcion);
                    // y posteriormente mostramos el modal 
                    $("#formmodal").modal('show');

                } else {

                }
            },
            error: function (e) {
                $.notify('Ocurrio un error inesperado', 'error');
            }
        });
    }
</script>
<?php include 'modal/_form.php'; ?>

<div class="row">
    <div class="col-md-12">
        <h1>
            <?= $this->pageTitle ?> | <a href="#" class="btn btn-success" id="abrirmodal">
                Agregar
            </a>
        </h1>
        <hr>
        <div class="table-responsive">
            <table id="lista" class="table table-bordered table-hover table-striped" cellspacing="0" width="100%">
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
                    foreach ($lista as $rows) { ?>
                        <tr>
                            <td>
                                <?= $rows->id_perfil ?>
                            </td>
                            <td>
                                <?= $rows->nombre ?>
                            </td>
                            <td>
                                <?= $rows->descripcion ?>
                            </td>
                            <td class="">
                                <a href="<?= Yii::app()->createUrl('perfiles/detalle?id_perfil=' . $rows['id_perfil']) ?>">
                                    <i class="fa fa-key" aria-hidden="true"></i> Permisos
                                </a><br>
                                <?php


                                echo CHtml::link(
                                    '<i class="fa fa-pencil fa-lg"></i> Editar',
                                    "javascript:;",
                                    array(
                                        'style' => 'cursor: pointer;',
                                        "onclick" => "Actualizar(" . $rows['id_perfil'] . "); return false;"
                                    )
                                );


                                echo CHtml::link(
                                    '<br><i class="fa fa-trash fa-lg"></i> Eliminar',
                                    array('Perfiles/delete', 'id' => $rows['id_perfil']),
                                    array(
                                        'submit' => array('Perfiles/delete', 'id' => $rows['id_perfil']),
                                        'class' => 'delete',
                                        'confirm' => 'Seguro que lo deseas eliminar?'
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