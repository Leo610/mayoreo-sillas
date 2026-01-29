<?php
/* @var $this InformesController */
$this->pageTitle = 'Cuentas por cobrar';
$this->breadcrumbs = array(
    'Informes' => array('informes/lista'),
    'Reporte Cuentas por cobrar'
);
?>
<script type="text/javascript">
    $(document).ready(function() {
        // Funcion para ordenar la lista de resultados, lo ordena por la columna 3, ya que empieza 0,1,2,3,4 y muestra  5 resultados, solamente
        $('#contabilidadingresos').DataTable({
            // "paging": false,
            // "ordering": false,
            // "info": false,
            "searching": false,
            pageLength: 100,
            "order": [
                [0, "desc"]

            ],
            dom: 'lBfrtip',
            buttons: [{
                    extend: 'csv',
                    title: 'Reporte de Cuentas por Cobrar',
                    text: 'Exportar a EXCEL'
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Reporte de Cuentas por Cobrar',
                    text: 'Exportar a PDF'
                },
                {
                    extend: 'print',
                    title: 'Reporte de Cuentas por Cobrar',
                    text: 'Imprimir'
                }
            ]
        });

    });
</script>
<div class="row">
    <div class="col-md-8">
        <h1>Cuentas por cobrar</h1>
    </div>
    <div class="col-md-12">
        <fieldset>
            <legend>Filtros</legend>
            <form method="GET">
                <div class="col-md-3">
                    <select name="id_cliente" onchange="this.form.submit()" class="form-control">
                        <option value="">
                            -- Todos los clientes --
                        </option>
                        <?php
                        foreach ($ListaClientes as $rows) { ?>
                            <option value="<?= $rows['id_cliente'] ?>" <?= ($Cliente == $rows['id_cliente']) ? 'selected' : ''; ?>>
                                <?= $rows['cliente_razonsocial'] ?>
                            </option>
                        <?php }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="id_moneda" onchange="this.form.submit()" class="form-control">

                        <?php
                        foreach ($listamonedas as $rows) { ?>
                            <option value="<?= $rows['id_moneda'] ?>" <?= ($id_moneda == $rows['id_moneda']) ? 'selected' : ''; ?>>
                                <?= $rows['moneda_nombre'] ?>
                            </option>
                        <?php }
                        ?>
                    </select>
                </div>
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
                <div class="col-md-2">
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
                <div class="col-md-3">
                    <br>
                    <?php
                    $this->widget(
                        'zii.widgets.jui.CJuiAutoComplete',
                        array(
                            'name' => 'nombreusuario',
                            'source' => $this->createUrl('contabilidadingresos/buscarusuario'),
                            // Opciones javascript adicionales para el plugin
                            'options' => array(
                                'minLength' => '3',
                                'select' => 'js:function(event, ui) {
                                        console.log(ui);
                                            $("#id_usuariocc").val(ui.item.id);
                                            
                 	            }',
                                'focus' => 'js:function(event, ui) {
                                 return false;
                                }'
                            ),
                            'value' => $nombre,
                            'htmlOptions' => array(
                                'class' => 'form-control',
                                'placeholder' => 'Buscar Vendedor'
                            )
                        )
                    );
                    ?>
                </div>
                <div class="col-md-3 ">
                    <br>
                    <select name="id_bodega" class="form-control">
                        <option value="0">
                            -- Todas las bodegas --
                        </option>
                        <?php foreach ($bodegas as $row) { ?>
                            <option value="<?= $row['id'] ?>" <?= ($id_bodega == $row['id']) ? 'selected' : ''; ?>><?= $row['name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <input type="hidden" name="id_usuario" id="id_usuariocc">
                <div class="col-md-2">
                    <br>
                    <?php echo CHtml::submitButton('Filtro', array('class' => 'btn btn-secondary btn-ml')); ?>
                </div>

            </form>
        </fieldset>
    </div>
</div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="contabilidadingresos" class="table  table-bordered  table-hover">
                <thead>
                    <tr>
                        <th>Num</th>
                        <th>Cliente</th>
                        <th>Moneda</th>
                        <th>Total</th>
                        <th>Total Pagado</th>
                        <th>Total Pendiente</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $Total = 0;
                    $TotalPagado = 0;
                    $TotalPendiente = 0;
                    foreach ($ListaProyectospp as $rows) { ?>
                        <tr style="background:<?= ($rows->Revisar($rows->id_proyecto, 'total') < 1) ? 'red' : '' ?>; color:<?= ($rows->Revisar($rows->id_proyecto, 'total') < 1) ? '#fff' : '#000' ?>;">
                            <td>Pedido - <?= $rows->id_proyecto ?></td>
                            <td><?= $rows->rl_clientes['cliente_razonsocial'] ?><br><?= $rows->rl_clientes['cliente_nombre'] ?><br><?= $rows->rl_clientes['cliente_email'] ?><br><?= $rows->rl_clientes['cliente_telefono'] ?>
                            </td>
                            <td><?= $rows->rl_moneda->moneda_nombre ?></td>
                            <td>$ <? //= number_format($rows->proyecto_total, 2) 
                                    ?>
                                <?= number_format($rows->Revisar($rows->id_proyecto, 'total'), 2) ?></td>
                            <td>$ <? //= number_format($rows->proyecto_totalpagado, 2) 
                                    ?>
                                <?= number_format($rows->Revisar($rows->id_proyecto, 'pagado'), 2) ?></td>
                            <td>$ <? //= number_format($rows->proyecto_totalpendiente, 2) 
                                    ?>
                                <?= number_format($rows->Revisar($rows->id_proyecto, 'pendiente'), 2) ?></td>
                            <td><?= $rows->proyecto_fecha_alta ?></td>
                        <?php
                        $Total = (($rows->Revisar($rows->id_proyecto, 'total') < 1) ? 0 : $rows->proyecto_total) + $Total;
                        $TotalPagado = (($rows->Revisar($rows->id_proyecto, 'pagado') < 1) ? 0 : $rows->proyecto_totalpagado) + $TotalPagado;
                        $TotalPendiente = (($rows->Revisar($rows->id_proyecto, 'pendiente') < 1) ? 0 : $rows->proyecto_totalpendiente) + $TotalPendiente;
                        // $Total = $rows->proyecto_total + $Total;
                        // $TotalPagado = $rows->proyecto_totalpagado + $TotalPagado;
                        // $TotalPendiente = $rows->proyecto_totalpendiente + $TotalPendiente;
                    } ?>
                </tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        <h3>Totales</h3>
                    </td>
                    <td>
                        <h3>$
                            <?= number_format($Total, 2) ?>
                        </h3>
                    </td>
                    <td>
                        <h3>$
                            <?= number_format($TotalPagado, 2) ?>
                        </h3>
                    </td>
                    <td>
                        <h3>$
                            <?= number_format($TotalPendiente, 2) ?>
                        </h3>
                    </td>
                    <td></td>
                </tr>
            </table>
        </div>
    </div>
</div>