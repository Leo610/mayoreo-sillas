<?php
$this->pageTitle = 'Movimientos de inventario';
$this->pageDescription = '';
$this->breadcrumbs = array(
    $this->pageTitle
);
$op_menu = 4;
include 'menu.php';
include 'modal/movimientosserie.php';
?>
<div class="row">
    <div class="col-md-8">
        <h1><?= $this->pageTitle ?></h1>
    </div>
</div>
<div class="panel">
    <form method="get" name="form">
        <div class="row">
            <div class="col-12 col-md-4">
                <select name="tipomov" class="form-control" onchange="this.form.submit()">
                    <option value="0">-- Todos los movimientos --</option>
                    <option value="1" <?= ($tipomov == 1) ? 'selected' : ''; ?>>Movimientos de Entrada </option>
                    <option value="2" <?= ($tipomov == 2) ? 'selected' : ''; ?>>Movimientos de Salida </option>
                </select>
            </div>
            <div class="col-12 col-md-4">
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'name' => 'fecha_desde',
                    'language' => 'es',
                    'htmlOptions' => array(
                        'onchange' => 'this.form.submit()',
                        'dateFormat' => 'yy-mm-dd',
                        'class' => 'form-control',
                        'size' => '30',
                        // textdomain(text_domain)tField size
                        'maxlength' => '10',
                        // textField maxlength
                        'placeholder' => 'Fecha desde',
                    ),
                    'value' => $fecha_desde,
                    'options' => array(
                        'dateFormat' => 'yy-mm-dd',
                        'showOtherMonths' => true,
                        // show dates in other months
                        'selectOtherMonths' => true,
                        // can seelect dates in other months
                        'changeYear' => true,
                        // can change year
                        'changeMonth' => true,
                        'showButtonPanel' => true,
                        'onClose' => 'js:function(selectedDate) { $("#fecha_hasta").datepicker("option", "minDate", selectedDate); }',
                    ),
                )
                );
                ?>
            </div>
            <div class="col-12 col-md-4">
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'name' => 'fecha_hasta',
                    'language' => 'es',
                    'htmlOptions' => array(
                        'onchange' => 'this.form.submit()',
                        'dateFormat' => 'yy-mm-dd',
                        'class' => 'form-control',
                        'size' => '30',
                        // textdomain(text_domain)tField size
                        'maxlength' => '10',
                        // textField maxlength
                        'placeholder' => 'Fecha desde',
                    ),
                    'value' => $fecha_hasta,
                    'options' => array(
                        'dateFormat' => 'yy-mm-dd',
                        'showOtherMonths' => true,
                        // show dates in other months
                        'selectOtherMonths' => true,
                        // can seelect dates in other months
                        'changeYear' => true,
                        // can change year
                        'changeMonth' => true,
                        'showButtonPanel' => true,
                        'onClose' => 'js:function(selectedDate) { $("#fecha_desde").datepicker("option", "maxDate", selectedDate); }',
                    ),
                )
                );
                ?>
            </div>


            <!--<div class="col">
                <select name="familia" data-plugin="select2" onchange="this.form.submit()">
                    <option value="">-- Familia --</option>
                    <?php foreach ($familias as $rows) { ?>
                        <option value="<?= $rows['id'] ?>" <?= ($rows['id'] == $cadena["Familia_id"]) ? 'selected' : ''; ?> ><?= $rows['nombre'] ?></option>
                    <?php } ?>
                </select>
            </div>-->

        </div>
        <div class="row">
            <div class="col-12 col-md-4">
                <select class="form-control" data-plugin="select2" id="categoria" name="categoria"
                    onchange="this.form.submit()">
                    <option value="0">-- Categoria --</option>
                    <?php foreach ($listacategorias as $key => $value) { ?>
                        <option value="<?= $key ?>" <?= ($key == $cadena["Categoria_id"]) ? 'selected' : ''; ?>>
                            <?= $value ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-12 col-md-4">
                <select class="form-control" data-plugin="select2" id="subcategoria" name="subcategoria"
                    onchange="this.form.submit()">
                    <option value="0">-- Subcategoria --</option>
                    <?php foreach ($listasubcategorias as $key => $value) { ?>
                        <option value="<?= $key ?>" <?= ($key == $cadena["Subcategoria_id"]) ? 'selected' : ''; ?>>
                            <?= $value ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-12 col-md-4">
                <select name="sucursal" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Selecione Sucursal --</option>
                    <?php foreach ($sucursales as $rows) { ?>
                        <option value="<?= $rows['id'] ?>" <?= ($rows['id'] == $sucursal) ? 'selected' : ''; ?>>
                            <?= $rows['nombre'] ?>
                        </option>';
                    <?php } ?>
                </select>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table id="resultado" class="table table-sm table-hover datatable" style="font-size: 10px;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Sucursal</th>
                            <th>Producto</th>
                            <th>Clave</th>
                            <th>Familia</th>
                            <th>Categoria</th>
                            <th>Subcategoria</th>
                            <th>Tipo Mov</th>
                            <th>Origen</th>
                            <th>Stock antes</th>
                            <th>Cantidad Mov</th>
                            <th>Stock final</th>
                            <th>Usuario</th>
                            <th>Fecha Mov</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista as $rows) {
                            $cadena = $this->Cadenacategorias($rows['idProducto']['id_categoria']);
                            $colortr = 'table-success';
                            if ($rows['tipo'] == 2) {
                                $colortr = 'table-danger';
                            }
                            ?>
                            <tr class="">
                                <td>
                                    <?= $rows['id'] ?>
                                </td>
                                <td>
                                    <?= $rows['idSucursal']['nombre'] ?>
                                </td>
                                <td>
                                    <?= $rows['idProducto']['producto_nombre'] ?>
                                </td>
                                <td>
                                    <?= $rows['idProducto']['producto_clave'] ?>
                                </td>
                                <td>
                                    <?= $cadena['Familia'] ?>
                                </td>
                                <td>
                                    <?= $rows['idProducto']['rl_categoria']['nombre'] ?>
                                </td>
                                <td>
                                    <?= $rows['idProducto']['rl_subcategoria']['nombre'] ?>
                                </td>
                                <td class="<?= $colortr ?>">
                                    <?= ($rows['tipo'] == 2) ? 'SALIDA' : 'ENTRADA'; ?>
                                </td>
                                <td><?= $this->Regresarorigenmov($rows['tipo_identificador'], $rows['id_identificador']) ?>
                                </td>
                                <td>
                                    <?= $rows['cantidad_stock_antes'] ?>
                                </td>
                                <td>
                                    <?= $rows['cantidad_mov'] ?>
                                </td>
                                <td>
                                    <?= $rows['cantidad_stock_final'] ?>
                                </td>
                                <td>
                                    <?= $rows['idUsuario']['Usuario_Nombre'] ?>
                                </td>
                                <td>
                                    <?= $rows['fecha_movimiento'] ?>
                                </td>
                                <td>
                                    <?php if (!empty($rows['idMovseries'])) { ?>
                                        <button type="button" class="btn btn-secondary btn-xs btnverserie"
                                            title="Ver serie y lotes del movimiento"
                                            onclick="Obtenermovimientoserie(<?= $rows['id'] ?>)">
                                            <i class="fa fa-bar-chart" aria-hidden="true"></i>
                                        </button>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>