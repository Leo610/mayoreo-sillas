<?php
/* @var $this ContabilidadingresosController */
$this->pageTitle = 'Pendientes de cobro';

$this->breadcrumbs = array(
    'Lista de ingresos' => array('/contabilidadingresos/index'),
    'Proyectos pendientes de pago',
);
$pc = 'Lista de cotizaciones';
if (isset($_GET['id_cliente']) && !empty(trim($_GET['id_cliente']))) {
    $this->renderpartial('//clientes/menu', array('opcionmenu' => 4));
    $cliente = Clientes::model()->find('id_cliente =' . $_GET['id_cliente']);
    $pc = 'Cotizaciones de ' . $cliente['cliente_nombre'];
}
?>
<script type="text/javascript">
    $(document).ready(function () {
        // Funcion para ordenar la lista de resultados, lo ordena por la columna 3, ya que empieza 0,1,2,3,4 y muestra  5 resultados, solamente
        $('#contabilidadingresos').DataTable({
            pageLength: 100,
            "order": [[0, "desc"]]
        });

    });
    function AgregarPago(id, identificador) {
        var jqxhr = $.ajax({
            url: "<?php echo $this->createUrl("Proyectos/Datosjs"); ?>",
            type: "POST",
            dataType: "json",
            timeout: (120 * 1000),
            data: {
                id: id,
            },
            success: function (Response, newValue) {
                if (Response.requestresult == 'ok') {
                    // Si el resultado es correcto, agregamos los datos al form del modal
                    $("#Proyecto").val(Response.DatosProyecto.id_proyecto);
                    $("#Cliente").val(Response.Cliente);
                    $("#fecha").val(Response.DatosProyecto.proyecto_fecha_alta);
                    $("#total").val(Response.DatosProyecto.proyecto_total);
                    $("#totalpagado").val(Response.DatosProyecto.proyecto_totalpagado);
                    $("#totalpendiente").val(Response.DatosProyecto.proyecto_totalpendiente);
                    $("#Contabilidadingresos_contabilidad_ingresos_identificador").val(identificador);
                    $("#Contabilidadingresos_id_moneda").val(Response.DatosProyecto.id_moneda);
                    $("#id_moneda_editable").val(Response.DatosProyecto.id_moneda);
                    if (Response.DatosProyecto.id_moneda != '') {
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
<div class="row">
    <div class="col-md-8">
        <h1>Proyectos pendientes de cobro | <a href="<?php echo Yii::app()->createUrl('contabilidadingresos/index') ?>"
                class=" btn btn-success">
                Lista de ingresos
            </a></h1>
    </div>
</div>
<div class="col-md-12">
    <fieldset>
        <legend>Filtros</legend>
        <form method="GET">
            <div class="col-md-3">
                <?php
                $this->widget(
                    'zii.widgets.jui.CJuiAutoComplete',
                    array(
                        'name' => 'producto_nombre',
                        'source' => $this->createUrl('contabilidadingresos/buscarusuario'),
                        // Opciones javascript adicionales para el plugin
                        'options' => array(
                            'minLength' => '3',
                            'select' => 'js:function(event, ui) {
                                        console.log(ui);
                                            $("#id_usuario").val(ui.item.id);
                                            
                 	            }',
                            'focus' => 'js:function(event, ui) {
                                 return false;
                                }'
                        ),
                        // 'value' => $usuario['Usuario_Nombre'],
                        'htmlOptions' => array(
                            'class' => 'form-control',
                            'placeholder' => 'Vendedor'
                        )
                    )
                );
                ?>
            </div>


            <input type="hidden" name="id_usuario" id="id_usuario">
            <div class="col-md-2" style="margin-top: 5px">
                <?php echo CHtml::submitButton('Filtro', array('class' => 'btn btn-secondary btn-ml')); ?>
            </div>

        </form>
    </fieldset>
</div>

<div id="scroll-container-fixedpenpa"
    style="overflow-x: scroll;height:50px;position: fixed;bottom: 1%; left: 0;z-index: 100;width: 100%; padding:0 10px ;">
    <div id="scroll-elem-fixedpenpa" style=" height:100%; padding: 10px">
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive tablescrolpenpa">
            <table id="contabilidadingresos" class="table  table-bordered  table-hover">
                <thead>
                    <tr>
                        <th>Num</th>
                        <th>Cliente</th>
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
                    $total = 0;
                    $totalpagado = 0;
                    $totalpendeinte = 0;
                    foreach ($ListaProyectospp as $rows) { ?>
                        <tr
                            style="background:<?= ($rows->Revisar($rows->id_proyecto, 'total') < 1) ? 'red' : '' ?>; color:<?= ($rows->Revisar($rows->id_proyecto, 'total') < 1) ? '#fff' : '#000' ?>;">
                            <td>
                                <?php
                                echo CHtml::link($rows->id_proyecto, array('proyectos/ver/' . $rows->id_proyecto), array('class' => "", 'target' => 'new'));
                                ?>
                            </td>
                            <td>
                                <?= $rows->rl_clientes['cliente_razonsocial'] ?><br>
                                <?= $rows->rl_clientes['cliente_nombre'] ?><br>
                                <?= $rows->rl_clientes['cliente_email'] ?><br>
                                <?= $rows->rl_clientes['cliente_telefono'] ?>
                            </td>
                            <td>$
                                <!-- <? //= number_format($rows->proyecto_total, 2)   ?> -->
                                <?= number_format($rows->Revisar($rows->id_proyecto, 'total'), 2) ?>
                            </td>
                            <td>$
                                <!-- <? //= number_format($rows->proyecto_totalpagado, 2)   ?> -->
                                <?= number_format($rows->Revisar($rows->id_proyecto, 'pagado'), 2) ?>
                            </td>
                            <td>$
                                <!-- <? //= number_format($rows->proyecto_totalpendiente, 2)   ?> -->
                                <?= number_format($rows->Revisar($rows->id_proyecto, 'pendiente'), 2) ?>
                            </td>
                            <td>
                                <?= $rows->rl_moneda->moneda_nombre ?>
                            </td>
                            <td>
                                <?= $rows->proyecto_fecha_alta ?>
                            </td>
                            <td class="">
                                <?php
                                if ($rows->proyecto_estatus == 7) {
                                    echo 'El proyectop fue cancelado';
                                } else {
                                    // Administracion/crmver/27
                                    echo CHtml::link(
                                        '<i class="fa fa-money"></i> Agregar',
                                        "javascript:;",
                                        array(
                                            'class' => 'btn btn-success',
                                            'style' => 'cursor: pointer;',
                                            "onclick" => "AgregarPago(" . $rows['id_proyecto'] . ",'Pedido - " . $rows['id_proyecto'] . " '); return false;"
                                        )
                                    );
                                    ?>
                                    <br>
                                    <br>
                                    <?php
                                    $registros = Contabilidadingresos::model()->findAll(
                                        array(
                                            'condition' => 'contabilidad_ingresos_identificador = :identificador',
                                            'params' => array(':identificador' => 'Pedido - ' . $rows->id_proyecto)
                                        )
                                    );
                                    foreach ($registros as $r) {
                                        echo CHtml::link('<i class="fa fa-file-pdf-o"></i>', array('contabilidadingresos/pdf?id=' . $r->id_contabilidad_ingresos), array('class' => "btn btn-danger", 'style' => 'margin-left:2px', 'target' => '_blank'));
                                    }
                                }
                                ?>
                            </td>
                            <!-- sacamos los totales ->lars 05/01/24 -->
                            <?php
                            // $total = $rows->proyecto_total + $total;
                            $total = (($rows->Revisar($rows->id_proyecto, 'total') < 1) ? 0 : $rows->proyecto_total) + $total;
                            // $totalpagado = $rows->proyecto_totalpagado + $totalpagado;
                            $totalpagado = (($rows->Revisar($rows->id_proyecto, 'pagado') < 1) ? 0 : $rows->proyecto_totalpagado) + $totalpagado;
                            // $totalpendeinte = $rows->proyecto_totalpendiente + $totalpendeinte;
                            $totalpendeinte = (($rows->Revisar($rows->id_proyecto, 'pendiente') < 1) ? 0 : $rows->proyecto_totalpendiente) + $totalpendeinte;
                            ?>
                        </tr>
                    <?php } ?>
                </tbody>
                <tr>

                    <td></td>
                    <td>
                        <h3>Totales</h3>
                    </td>
                    <td>
                        <h3>$
                            <?= number_format($total, 2) ?>
                        </h3>
                    </td>
                    <td>
                        <h3>$
                            <?= number_format($totalpagado, 2) ?>
                        </h3>
                    </td>
                    <td>
                        <h3>$
                            <?= number_format($totalpendeinte, 2) ?>
                        </h3>
                    </td>
                    <td></td>
                </tr>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        const tableWidth = $('#contabilidadingresos').width();
        // asignamos el anchotabla
        const scroll = document.querySelector("#scroll-elem-fixedpenpa");
        scroll.style.width = (tableWidth + 15) + 'px';
        document.querySelector("#scroll-container-fixedpenpa").addEventListener("scroll", function () {
            document.querySelector(".tablescrolpenpa").scrollLeft = this.scrollLeft;
        })
    })
</script>