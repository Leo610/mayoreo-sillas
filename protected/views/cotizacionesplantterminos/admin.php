<?php
/* @var $this CotizacionesplantterminosController */
/* @var $model Cotizacionesplantterminos */


$this->pageTitle = 'Administración de Cotizacionesplantterminos';
$this->breadcrumbs = array(
    'Modulos' => Yii::app()->createUrl('administracion/modulos'),
    'Cotizacionesplantterminos',
);

?>
<script type="text/javascript">
    $(document).ready(function () {

        // Funcion para mostrar el modal
        $("#abrirmodal").click(function () {
            $("#formmodal").modal('show');
            $('#Cotizacionesplantterminos-form')[0].reset();
        });

        // Funcion para ordenar la lista de resultados
        $('#lista').DataTable();

    });

    function Actualizar(id) {
        var jqxhr = $.ajax({
            url: "<?php echo $this->createUrl("Cotizacionesplantterminos/Cotizacionesplantterminosdatos"); ?>",
            type: "POST",
            dataType: "json",
            timeout: (120 * 1000),
            data: {
                id: id,
            },
            success: function (Response, newValue) {
                if (Response.requestresult == 'ok') {
                    // Si el resultado es correcto, agregamos los datos al form del modal
                    $("#Cotizacionesplantterminos_id").val(Response.Datos.id);
                    $("#Cotizacionesplantterminos_nombre").val(Response.Datos.nombre);
                    $("#Cotizacionesplantterminos_condiciones_pago").val(Response.Datos.condiciones_pago);
                    $("#Cotizacionesplantterminos_tiempo_fabricacion").val(Response.Datos.tiempo_fabricacion);
                    $("#Cotizacionesplantterminos_exclusiones").val(Response.Datos.exclusiones);
                    $("#Cotizacionesplantterminos_vigencia_propuesta").val(Response.Datos.vigencia_propuesta);
                    $("#Cotizacionesplantterminos_comentario").val(Response.Datos.comentario);
                    $("#Cotizacionesplantterminos_nombre_encargado").val(Response.Datos.nombre_encargado);
                    $("#Cotizacionesplantterminos_condiciones_generales").val(Response.Datos.condiciones_generales);
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
        <h1>Plantilla para Cotizaciones | <a href="#" class="btn btn-success" id="abrirmodal">
                Agregar Plantilla para Cotizaciones
            </a></h1>

        <hr>
        <div class="table-responsive">
            <table id="lista" class="table  table-bordered  table-hover ">
                <thead>
                    <tr>
                        <th>ID</th>
                        <!-- <th>Nombre</th> -->
                        <th>Nombre Encargado</th>
                        <th>Flete Cotizado</th>
                        <th>Tiempo Fabricación</th>
                        <!-- <th>Exclusiones</th>                 -->
                        <!-- <th>Vigencia Propuesta</th> -->
                        <th>Comentario</th>
                        <th>Condiciones Generales</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($lista as $rows) { ?>
                        <tr>
                            <td><?= $rows->id ?></td>
                            <!-- <td><? //=$rows->nombre?></td> -->
                            <td><?= $rows->nombre_encargado ?></td>
                            <td><?= $rows->condiciones_pago ?></td>
                            <td><?= $rows->tiempo_fabricacion ?></td>
                            <!-- <td><? //=$rows->exclusiones?></td> -->
                            <!-- <td><? //=$rows->vigencia_propuesta?></td> -->
                            <td><?= $rows->comentario ?></td>
                            <td><?= $rows->condiciones_generales ?></td>
                            <td class="">
                                <?php

                                echo CHtml::link('<i class="fa fa-search-plus fa-lg"></i>
                            Editar', array('cotizacionesplantterminos/detalle/' . $rows['id']), array());





                                echo CHtml::link(
                                    '<br><i class="fa fa-trash fa-lg"></i> Eliminar',
                                    array('Cotizacionesplantterminos/delete', 'id' => $rows['id']),
                                    array(
                                        'submit' => array('Cotizacionesplantterminos/delete', 'id' => $rows['id']),
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