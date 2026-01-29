<?php
/* @var $this CotizacionesController */
$this->pageTitle = 'Lista de cotizaciones';

$this->breadcrumbs = array(
    'clientes' => Yii::app()->createUrl('clientes/admin'),
    'Lista Cotizaciones',
);
$opcionmenu = 2;
?>
<script type="text/javascript">
    $(document).ready(function () {
        // Funcion para ordenar la lista de resultados
        $('#listacotizaciones').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'csv',
                    title: 'Exportar',
                    text: 'Exportar a EXCEL',
                },
            ]
        });
    });  
</script>

<?php include 'menu.php' ?>

<div class="row">
    <div class="col-md-8">
        <h1>Cotizaciones de | <a href="<?php echo Yii::app()->createUrl('/administracion/crearcotizaciones/') ?>"
                class="btn btn-success ">
                <i class="fa fa-list-ol"></i> Crear cotización
            </a></h1>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <fieldset>
            <legend>Filtros </legend>
            <form method="GET">

                <div class="col-md-3">
                    <?php $this->widget(
                        'zii.widgets.jui.CJuiDatePicker',
                        array(
                            'name' => 'fechainicio',
                            'language' => 'es',
                            'htmlOptions' => array(
                                'readonly' => "readonly",
                                'class' => 'form-control'
                            ),
                            'options' => array(
                                'dateFormat' => 'yy-mm-dd',
                            ),
                            'value' => $fechainicio
                        )
                    ); ?>
                </div>
                <div class="col-md-3">
                    <?php $this->widget(
                        'zii.widgets.jui.CJuiDatePicker',
                        array(
                            'name' => 'fechafin',
                            'language' => 'es',
                            'htmlOptions' => array(
                                'readonly' => 'readonly',
                                'class' => 'form-control'
                            ),
                            'options' => array(
                                'dateFormat' => 'yy-mm-dd',

                            ),
                            'value' => $fechafin
                        )
                    ); ?>
                </div>

                <div class="col-md-2">
                    <?php echo CHtml::submitButton('Filtro', array('class' => 'btn btn-success btn-ml')); ?>
                </div>

            </form>
        </fieldset>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="listacotizaciones" class="table  table-bordered  table-hover ">
                <thead>
                    <tr>
                        <th>Num</th>
                        <th>Cliente</th>
                        <th>Subtotal</th>
                        <th>Iva</th>
                        <th>Total</th>
                        <th>Agente</th>
                        <th>Envio de Cot</th>
                        <th>Fecha Alta</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($listacotizaciones as $rows) {
                        $subtotal = ($rows->cotizacion_total) / 1.16;
                        $iva = ($rows->cotizacion_total / 1.16) * .16;
                        $total = $rows->cotizacion_total;
                        ?>
                        <tr>
                            <td>
                                <?= $rows->id_cotizacion ?>
                            </td>
                            <td>
                                <?= $rows->rl_clientes->cliente_nombre ?>
                            </td>
                            <td>$
                                <?= number_format($subtotal, 2) ?>
                            </td>
                            <td>$
                                <?= number_format($iva, 2) ?>
                            </td>
                            <td>$
                                <?= number_format($total, 2) ?>
                            </td>
                            <td>
                                <?= $rows->rl_usuarios->Usuario_Nombre ?>
                            </td>
                            <td>
                                <?php
                                echo CHtml::link('Enviar Cotización', array('cotizaciones/enviarcotizacion/' . $rows->id_cotizacion), array('class' => "btn btn-success btn-xs"));

                                ?>
                            </td>
                            <td>
                                <?= $rows->cotizacion_fecha_alta ?>
                            </td>
                            <td>
                                <?php
                                if ($rows->cotizacion_estatus == 0) {
                                    echo CHtml::link('Terminar cotización', array('cotizaciones/Actualizarcotizacion/' . $rows->id_cotizacion), array('class' => "btn btn-success"));
                                } else {
                                    ?>
                                    <?php
                                    echo CHtml::link('<i class="fa fa-search"></i> Ver', array('cotizaciones/Actualizarcotizacion/' . $rows->id_cotizacion), array('class' => "btn btn-secondary"));
                                    ?> -
                                    <?php
                                    echo CHtml::link('<i class="fa fa-file-pdf-o"></i>
                Ver', array('cotizaciones/pdf/' . $rows->id_cotizacion), array('class' => "btn btn-danger", 'target' => '_blank'));
                                    ?>
                                    -
                                    <?php
                                    echo CHtml::link('<i class="fa fa-check"></i> Crear pedido', array('proyectos/crear/' . $rows->id_cotizacion), array('class' => "btn btn-primary"));

                                }
                                ?>

                            </td>

                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>