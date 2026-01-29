<?php
/* @var $this AdministracionController */
$this->pageTitle = 'Ingresos';
$this->breadcrumbs = array(
    'Informes' => array('informes/lista'),
    'Reporte de ingresos',
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
                    title: 'Reporte de Ingresos',
                    text: 'Exportar a EXCEL'
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Reporte de Ingresos',
                    text: 'Exportar a PDF'
                },
                {
                    extend: 'print',
                    title: 'Reporte de Ingresos',
                    text: 'Imprimir'
                }
            ]
        });

    });
</script>
<div class="row">
    <div class="col-md-12">
        <h1>Lista de Ingresos</h1>
        <div class="col-md-12">
            <fieldset>
                <legend>Filtros</legend>
                <form method="GET">
                    <div class="col-md-3">
                        <select name="id_banco" onchange="this.form.submit()" class="form-control">
                            <option value="">
                                -- Todos los bancos --
                            </option>
                            <?php
                            foreach ($ListaBancos as $rows) { ?>
                                <option value="<?= $rows['id_banco'] ?>" <?= ($id_banco == $rows['id_banco']) ? 'selected' : ''; ?>>
                                    <?= $rows['banco_nombre'] ?>
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
                    <div class="col-md-2">
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
                                            $("#id_usuarioii").val(ui.item.id);
                                            
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
                    <input type="hidden" name="id_usuario" id="id_usuarioii">
                    <div class="col-md-2">
                        <br>
                        <?php echo CHtml::submitButton('Filtro', array('class' => 'btn btn-secondary btn-ml')); ?>
                    </div>
                </form>
            </fieldset>

            <div class="table-responsive">
                <table id="contabilidadingresos" class="table  table-bordered  table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Identificador</th>
                            <th>Forma de Pago</th>
                            <th>Banco</th>
                            <th>Moneda</th>
                            <th>Usuario</th>
                            <th>Cantidad</th>
                            <th>Fecha</th>
                            <th>Confirmado?</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $Total = 0;
                        $TotalPagado = 0;
                        $TotalPendiente = 0;
                        foreach ($ListaIngresos as $rows) { ?>
                            <tr
                                style="background:<?= ($rows->revisaringresos($rows->id_contabilidad_ingresos)) ? 'red' : '' ?>; color:<?= ($rows->revisaringresos($rows->id_contabilidad_ingresos)) ? '#fff' : '#000' ?>;">
                                <td>
                                    <?= $rows->id_contabilidad_ingresos ?>
                                </td>
                                <td>
                                    <?= $this->ObtenerCliente2($rows->contabilidad_ingresos_identificador); ?>
                                </td>
                                <td>
                                    <?= $rows->contabilidad_ingresos_identificador ?>
                                </td>
                                <td>
                                    <?= $rows['rl_formasdepago']['formapago_nombre'] ?>
                                </td>
                                <td>
                                    <?= $rows['rl_banco']['banco_nombre'] ?>
                                </td>
                                <td>
                                    <?= $rows['rl_moneda']['moneda_nombre'] ?>
                                </td>
                                <td>
                                    <?= $rows['rl_usuario']['Usuario_Nombre'] ?>
                                </td>
                                <td>$
                                    <?= ($rows->revisaringresos($rows->id_contabilidad_ingresos)) ? '-' . number_format($rows->contabilidad_ingresos_cantidad, 2) : number_format($rows->contabilidad_ingresos_cantidad, 2) ?>
                                    <!-- <? //= number_format($rows->contabilidad_ingresos_cantidad, 2) 
                                            ?> -->
                                </td>
                                <td>
                                    <?= $rows->contabilidad_ingresos_fechaalta ?>
                                </td>
                                <td>
                                    <?php if ($rows['ingreso_confirmado'] == 1) {
                                        echo 'Confirmado';
                                    } else {
                                        echo 'No Confirmado';
                                    } ?>
                                </td>
                            </tr>
                        <?php
                            $Total = (($rows->revisaringresos($rows->id_contabilidad_ingresos)) ? 0 : $rows->contabilidad_ingresos_cantidad) + $Total;
                            // $Total = $rows->contabilidad_ingresos_cantidad + $Total;
                        } ?>
                    </tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            <h3>Totales</h3>
                        </td>
                        <td></td>
                        <td>
                            <h3 class="bold">$
                                <?= number_format($Total, 2) ?>
                            </h3>
                        </td>
                        <td></td>
                        <td></td>
                </table>
            </div>
        </div>
    </div>
</div>