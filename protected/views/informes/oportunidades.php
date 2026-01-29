<?php
/* @var $this AdministracionController */
$this->pageTitle = 'Reporte de Oportunidades';
$this->breadcrumbs = array(
    'Informes' => array('/informes/lista'),
    $this->pageTitle,
);
?>
<script type="text/javascript">
    $(document).ready(function () {
        // Funcion para ordenar la lista de resultados, lo ordena por la columna 3, ya que empieza 0,1,2,3,4 y muestra  5 resultados, solamente
        $('#reportetabla').DataTable({
            "searching": false,
            pageLength: 100,
            dom: 'lBfrtip',
            buttons: [
                {
                    extend: 'csv',
                    title: '<?= $this->pageTitle ?>',
                    text: 'Exportar a EXCEL',
                    'footer': true
                },
                {
                    extend: 'pdfHtml5',
                    title: '<?= $this->pageTitle ?>',
                    text: 'Exportar a PDF',
                    'footer': true
                },
                {
                    extend: 'print',
                    title: '<?= $this->pageTitle ?>',
                    text: 'Imprimir',
                    'footer': true
                }
            ]
        });

    });
</script>
<div class="row">
    <div class="col-md-12">
        <h1><?= $this->pageTitle ?></h1>
        <div class="col-md-12">
            <fieldset>
                <legend>Filtros</legend>
                <form method="GET">
                    <div class="col-md-3">
                        <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
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
                        <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
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
                        <select name="usuario" onchange="this.form.submit()" class="form-control">
                            <option value="Todos">
                                -- Todos los Usuarios --
                            </option>
                            <?php
                            foreach ($listausuarios as $rows) { ?>
                                <option value="<?= $rows['ID_Usuario'] ?>"
                                    <?= ($usuario == $rows['ID_Usuario']) ? 'selected' : ''; ?>>
                                    <?= $rows['Usuario_Nombre'] ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="estatus" onchange="this.form.submit()" class="form-control">
                            <option value="Todos">
                                -- Todos los Estatus --
                            </option>
                            <option value="GANADO" <?= ($estatus == "GANADO") ? 'selected' : ''; ?>>GANADO</option>
                            <option value="SEGUIMIENTO" <?= ($estatus == "SEGUIMIENTO") ? 'selected' : ''; ?>>SEGUIMIENTO
                            </option>
                            <option value="PERDIDO" <?= ($estatus == "PERDIDO") ? 'selected' : ''; ?>>PERDIDO</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <?php echo CHtml::submitButton('Filtro', array('class' => 'btn btn-secondary btn-ml')); ?>
                    </div>
                </form>
            </fieldset>

            <div class="table-responsive">
                <table id="reportetabla" class="table  table-bordered  table-hover">
                    <thead>
                        <tr>
                            <th>Oportunidad</th>
                            <th>Fecha Alta</th>
                            <th>Valor del Negocio</th>
                            <th>Agente</th>
                            <th>Estatus</th>
                            <th>Ultima Modif.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        $oportunidades = 0;
                        foreach ($resultados as $rows) { ?>
                            <tr>
                                <td>
                                    <?= $rows['nombre'] ?>
                                </td>
                                <td>
                                    <?= $rows['fecha_alta'] ?>
                                </td>
                                <td>$
                                    <?= number_format($rows['valor_negocio'], 2) ?>
                                </td>
                                <td>
                                    <?= $rows['rl_usuario']['Usuario_Nombre'] ?>
                                </td>
                                <td>
                                    <?= $rows['estatus'] ?>
                                </td>
                                <td>
                                    <?= $rows['fecha_ultima_modificacion'] ?>
                                </td>
                            </tr>
                            <?php
                            $total = $total + $rows['valor_negocio'];
                            $oportunidades++;
                        } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>
                                <h2 class="mb-none">Totales</h2>
                            </td>
                            <td>
                                <h2 class="mb-none">
                                    <?= $oportunidades ?> Oportunidades
                                </h2>
                            </td>
                            <td>
                                <h2 class="mb-none">$
                                    <?= number_format($total, 2) ?>
                                </h2>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>