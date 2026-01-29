<?php
/* @var $this ContabilidadegresosController */
$this->pageTitle = 'Pendientes de pago';

$this->breadcrumbs = array(
    'Contabilidad' => array('/administracion/contabilidad'),
    'Lista de egresos' => array('/contabilidadegresos/index'),
    'Pendientes de pago',
);


?>

<script type="text/javascript">
    $(document).ready(function () {
        // Funcion para ordenar la lista de resultados, lo ordena por la columna 3, ya que empieza 0,1,2,3,4 y muestra  5 resultados, solamente
        $('#contabilidadegresos').DataTable({
            "order": [[5, "desc"]]
        });
        $('#contabilidadegresosempl').DataTable({
            "order": [[5, "desc"]]
        });


    });
    function AgregarPago(id, identificador) {
        var jqxhr = $.ajax({
            url: "<?php echo $this->createUrl("Ordenesdecompra/Datos"); ?>",
            type: "POST",
            dataType: "json",
            timeout: (120 * 1000),
            data: {
                id: id,
            },
            success: function (Response, newValue) {
                if (Response.requestresult == 'ok') {
                    // Si el resultado es correcto, agregamos los datos al form del modal
                    $("#nombre").val(Response.Datos.id_orden_de_compra);
                    $("#total").val(Response.Datos.ordendecompra_total);
                    $("#totalpagado").val(Response.Datos.ordendecompra_totalpagado);
                    $("#totalpendiente").val(Response.Datos.ordendecompra_totalpendiente);
                    $("#Contabilidadegresos_contabilidad_egresos_identificador").val(identificador);
                    $("#Contabilidadegresos_id_moneda").val(Response.Datos.id_moneda);
                    $("#id_moneda_editable").val(Response.Datos.id_moneda);
                    if (Response.Datos.id_moneda != '') {
                        $("#id_moneda_editable").prop('disabled', true);
                    } else {
                        $("#id_moneda_editable").prop('disabled', false);

                    }
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

    function AgregarPagoEmp(id, identificador, nombre) {
        var jqxhr = $.ajax({
            url: "<?php echo $this->createUrl("Proyectoempleados/Datos"); ?>",
            type: "POST",
            dataType: "json",
            timeout: (120 * 1000),
            data: {
                id: id,
            },
            success: function (Response, newValue) {
                if (Response.requestresult == 'ok') {

                    // Si el resultado es correcto, agregamos los datos al form del modal
                    $("#nombre").val(nombre);
                    $("#total").val(Response.Datos.proyectos_empleados_total);
                    $("#totalpagado").val(Response.Datos.proyectos_empleados_totalpagado);
                    $("#totalpendiente").val(Response.Datos.proyectos_empleados_totalpendiente);
                    $("#Contabilidadegresos_contabilidad_egresos_identificador").val(identificador);
                    $("#Contabilidadegresos_id_moneda").val(Response.Datos.id_moneda);
                    $("#id_moneda_editable").val(Response.Datos.id_moneda);
                    if (Response.Datos.id_moneda != '') {
                        $("#id_moneda_editable").prop('disabled', true);
                    } else {
                        $("#id_moneda_editable").prop('disabled', false);
                    }
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
<?php /*include 'modal/_formepl.php';*/?>
<div class="row">
    <div class="col-md-8">
        <h1>Ordenes de compra pendientes de pago | <a
                href="<?php echo Yii::app()->createUrl('contabilidadegresos/index') ?>" class="btn btn-primary">
                Lista de Egresos
            </a></h1>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="contabilidadegresos" class="table  table-bordered  table-hover">
                <thead>
                    <tr>
                        <th>Num</th>
                        <th>Referencia</th>
                        <th>Proveedor</th>
                        <th>Total</th>
                        <th>Total Pagado</th>
                        <th>Total Pendiente</th>
                        <th>Moneda</th>
                        <th>Fecha</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($ListaOC as $rows) { ?>
                        <tr>
                            <td>
                                <?php
                                echo CHtml::link($rows->id_orden_de_compra, array('ordenesdecompra/pdf/' . $rows->id_orden_de_compra), array('class' => "", 'target' => 'new'));
                                ?>
                            </td>
                            <td>
                                <?= $rows->ordendecompra_numero_factura ?>
                            </td>
                            <td>
                                <?= $rows->rl_Proveedor->proveedor_razonsocial ?>
                            </td>
                            <td>$
                                <?= number_format($rows->ordendecompra_total, 2) ?>
                            </td>
                            <td>$
                                <?= number_format($rows->ordendecompra_totalpagado, 2) ?>
                            </td>
                            <td>$
                                <?= number_format($rows->ordendecompra_totalpendiente, 2) ?>
                            </td>
                            <td>
                                <?= $rows->rl_moneda->moneda_nombre ?>
                            </td>
                            <td>
                                <?= $rows->ordendecompra_fecha_alta ?>
                            </td>

                            <td class="">
                                <?php
                                // Administracion/crmver/27
                                echo CHtml::link(
                                    '<i class="fa fa-money"></i> Agregar',
                                    "javascript:;",
                                    array(
                                        'class' => 'btn btn-success',
                                        'style' => 'cursor: pointer;',
                                        "onclick" => "AgregarPago(" . $rows['id_orden_de_compra'] . ",'Orden De Compra - " . $rows['id_orden_de_compra'] . " '); return false;"
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

<div class="row">
    <div class="col-md-12">
        <hr class="tall">
        <h1>Empleados pendientes de pago</h1>
        <div class="table-responsive">
            <table id="contabilidadegresosempl" class="table  table-bordered  table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Proyecto</th>
                        <th>Empleado</th>
                        <th>Total</th>
                        <th>Total Pagado</th>
                        <th>Total Pendiente</th>
                        <th>Moneda</th>
                        <th>Fecha</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($Listaempleados as $rows) { ?>
                        <tr>
                            <td>
                                <?= $rows->id_empleado ?>
                            </td>
                            <td>
                                <?php
                                echo CHtml::link($rows->id_proyecto . ' ' . $rows->rl_proyecto->proyecto_nombre, array('proyectos/ver/' . $rows->id_proyecto), array('class' => "", 'target' => 'new'));
                                ?>
                                <br>
                                <?= $rows->referencia ?> - <b>
                                    <?= $rows->proyectos_empleados_cantidad ?> horas
                                </b>

                            </td>
                            <td>
                                <?= $rows->rl_empleados->empleado_nombre ?>
                            </td>
                            <td>$
                                <?= number_format($rows->proyectos_empleados_total, 2) ?>
                            </td>
                            <td>$
                                <?= number_format($rows->proyectos_empleados_totalpagado, 2) ?>
                            </td>
                            <td>$
                                <?= number_format($rows->proyectos_empleados_totalpendiente, 2) ?>
                            </td>
                            <td>
                                <?= $rows->rl_moneda->moneda_nombre ?>
                            </td>
                            <td>
                                <?= $rows->fecha_ultima_modif ?>
                            </td>
                            <td class="">
                                <?php
                                // Administracion/crmver/27
                                echo CHtml::link(
                                    '<i class="fa fa-money"></i> Agregar',
                                    "javascript:;",
                                    array(
                                        'class' => 'btn btn-success',
                                        'style' => 'cursor: pointer;',
                                        "onclick" => "AgregarPagoEmp(" . $rows['id_proyectos_empleados'] . ",'Empleado - " . $rows['id_proyectos_empleados'] . " ','" . $rows->rl_empleados->empleado_nombre . "'); return false;"
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