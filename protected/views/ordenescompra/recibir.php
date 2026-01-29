<?php
$this->pageTitle = 'Recibir orden de compra';

$this->pageDescription = '';
$this->breadcrumbs = array(
    $this->pageTitle
);

// menu de inventario
$this->renderpartial('//inventario/menu', array('op_menu' => 10));
?>
<div class="panel">
    <?php if (empty($ordencompra)) { ?>
        <form method="get">
            <div class="row">
                <div class="col-md-12">
                    <div class="input-group">
                        <input type="number" id="numero_oc" name="numero_oc" class="form-control"
                            placeholder="Ingrese numero de orden de compra">
                        <span class="input-group-btn">
                            <button class="btn btn-success" type="submit" name="buscar" value="1">Buscar orden de
                                compra</button>
                        </span>
                    </div>
                </div>
            </div>
            <br>
            <div class="table-responsive">
                <table id="resultado" class="table table-sm table-hover datatable">
                    <thead>
                        <tr>
                            <th># OC</th>
                            <th>Fecha</th>
                            <th>Sucursal</th>
                            <th>Solicitante</th>
                            <th>Proveedor</th>
                            <th>Total</th>
                            <th>Total Pagado</th>
                            <th>Total Pendiente</th>
                            <th>Tipo</th>
                            <th>Estatus</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ordenescompra as $rows) { ?>
                            <tr>
                                <td>
                                    <?= $rows['id'] ?>
                                </td>
                                <td>
                                    <?= $rows['fecha_alta']; ?>
                                </td>
                                <td>
                                    <?= $rows['idSucursal']['nombre'] ?>
                                </td>
                                <td>
                                    <?= $rows['idUsuariosolicita']['nombre'] ?>
                                </td>
                                <td>
                                    <?= $rows['idProveedor']['nombre'] ?>
                                </td>
                                <td>$
                                    <?= number_format($rows['total'], 2) ?>
                                </td>
                                <td>$
                                    <?= number_format($rows['total_pagado'], 2) ?>
                                </td>
                                <td>$
                                    <?= number_format($rows['total_pendiente'], 2) ?>
                                </td>
                                <td>
                                    <?php if ($rows['tipo_oc'] == 0) {
                                        echo '<span class="badge badge-default">NORMAL</span>';
                                    } elseif ($rows['tipo_oc'] == 1) {
                                        echo '<span class="badge badge-danger">URGENTE</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $estatus = $this->EstatusOC($rows['estatus']);
                                    echo $estatus['badge'] ?>
                                </td>
                                <td>
                                    <?php
                                    echo CHtml::link(
                                        $estatus['boton'],
                                        yii::app()->createurl('ordenescompra/recibir/', array('numero_oc' => $rows['id'], 'buscar' => 1)),
                                        array(
                                            'style' => 'margin-right:3px;',
                                            'class' => 'btn btn-default btn-xs',
                                        )
                                    );

                                    //
                                    echo '<a href="' . Yii::app()->createUrl('ordenescompra/pdf', array('id' => $rows['id'])) . '" target="_blank" class="btn btn-danger btn-xs"><i class="fa fa-print" aria-hidden="true"></i>  </a>';
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </form>
    <?php } else {
        $this->opcionestitulo = '<a href="' . Yii::app()->createUrl('ordenescompra/pdf', array('id' => $ordencompra['id'])) . '" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-print" aria-hidden="true"></i> Imprimir OC </a>';
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <tr>
                            <td># Orden de Compra</td>
                            <td class="text-danger font-weight-bold">
                                <?= $ordencompra['id'] ?>
                            </td>
                            <td>Sucursal</td>
                            <td class="font-weight-bold">
                                <?= $ordencompra['idSucursal']['nombre'] ?>
                            </td>
                            <td>Usuario Solicita</td>
                            <td class="font-weight-bold">
                                <?= $ordencompra['idUsuariosolicita']['Usuario_Nombre'] ?>
                            </td>
                            <td>Fecha</td>
                            <td class="font-weight-bold">
                                <?= $ordencompra['fecha_alta'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Proveedor</td>
                            <td class="font-weight-bold">
                                <?= $ordencompra['idProveedor']['proveedor_nombre'] ?>
                            </td>
                            <td>Generó</td>
                            <td class="font-weight-bold">
                                <?= $ordencompra['idUsuariocrea']['Usuario_Nombre'] ?>
                            </td>
                            <td>Estatus</td>
                            <td class="font-weight-bold">
                                <?php
                                $estatus = $this->EstatusOC($ordencompra['estatus']);
                                echo $estatus['badge']
                                    ?>
                            </td>
                            <td>Prioridad</td>
                            <td class="font-weight-bold">
                                <?php if ($ordencompra['tipo_oc'] == 0) {
                                    echo '<span class="badge badge-default">NORMAL</span>';
                                } elseif ($ordencompra['tipo_oc'] == 1) {
                                    echo '<span class="badge badge-danger">URGENTE</span>';
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Comentarios</td>
                            <td class="font-weight-bold" colspan="7">
                                <?= $ordencompra['comentarios'] ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-9">
                <div class="table-responsive">
                    <table id="listaprouctos" class="table table-sm table-hover table-striped" style="font-size: 11px;">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Clave</th>
                                <!-- <th>U.M.</th> -->
                                <th>Subtotal</th>
                                <th>Cantidad</th>
                                <th>Total</th>
                                <th style="display: none">Recibido</th>
                                <th style="display: none">Por recibir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <?php foreach ($conceptos as $rows) { ?>
                                <tr id="tr<?= $rows['id'] ?>">
                                    <td>
                                        <?= $rows['concepto'] ?>
                                    </td>
                                    <td>
                                        <?= $rows['idProducto']['producto_clave'] ?>
                                    </td>
                                    <td>
                                        <!-- //$rows['idProducto']['idUm']['nombre'] -->
                                        <?= $rows['idProducto']['rl_unidadesdemedida']['unidades_medida_nombre'] ?>
                                    </td>
                                    <td>$
                                        <?= number_format($rows['unitario'] + $rows['iva'], 2) ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <?= $rows['cantidad'] ?>
                                    </td>
                                    <td>$
                                        <?= number_format($rows['total'], 2) ?>
                                    </td>
                                    <td id="cantidadrecibida<?= $rows['id'] ?>" style="text-align: center; display: none">
                                        <?= $rows['cantidad_recibida'] ?>
                                    </td>
                                    <td id="botones<?= $rows['id'] ?>" style="display: none">
                                        <?php if ($rows['cantidad_pendiente'] > 0 && ($rows['idProducto']['tipo'] == 2 || $rows['idProducto']['tipo'] == 5 || $rows['idProducto']['tipo'] == 4)) { ?>
                                            <div class="input-group">
                                                <input type="number" class="form-control form-control-sm"
                                                    value="<?= $rows['cantidad_pendiente'] ?>" style="width: 90px;"
                                                    id="partida<?= $rows['id'] ?>" data-id="<?= $rows['id'] ?>">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-success btn-sm"
                                                        onclick="Recibirpartida('#partida<?= $rows['id'] ?>')">OK</button>
                                                </span>
                                            </div>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php if ($rows['idProducto']['control_por_serie'] == 1 && $ordencompra['estatus'] == 4) {
                                    // si el producto es controlado, mostramos 2 input por cantidad
                                    ?>
                                    <tr>
                                        <td colspan="8">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <p class="mb-1"> * Ingrese la serie y lote de los productos</p>
                                                </div>
                                                <?php for ($i = 0; $i < $rows['cantidad']; $i++) { ?>
                                                    <div class="col-md-2">
                                                        <input type="text" name="serie[]"
                                                            class="form-control verificarinput serialziarinput"
                                                            placeholder="Ingrese la serie" />
                                                        <input type="text" name="lote[]"
                                                            class="form-control verificarinput serialziarinput"
                                                            placeholder="Ingrese el lote" />
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-3">
                <div class="table-responsive">
                    <table id="tabladetotales" class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th colspan="3" class="text-center">Totales</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>SubTotal</td>
                                <td id="contenidosubtotal">$
                                    <?= number_format($ordencompra['subtotal'], 2) ?>
                                </td>
                            </tr>
                            <tr>
                                <td>IVA</td>
                                <td id="contenidoiva">$
                                    <?= number_format($ordencompra['iva'], 2) ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="lead">Total</td>
                                <td class="lead font-weight-bold" id="contenidototal">$
                                    <?= number_format($ordencompra['total'], 2) ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-12 text-right">
                <?php if ($ordencompra['estatus'] == 4) { ?>
                    <button type="button" class="btn btn-success btn-sm" onclick="Actualizarestatus(5)">
                        <i class="fa fa-check-circle" aria-hidden="true"></i>
                        Recibir Orden de Compra
                    </button>
                <?php } ?>
            </div>
        </div>

        <script type="text/javascript">

            // funcion para actualizar el estatus de la oc 
            function Actualizarestatus(estatusnuevo) {
                /*
                    1= NUEVA REQUISICION
                    2= ORDEN DE COMPRA ABIERTA
                    3= POR AUTORIZAR
                    4= ORDEN DE COMPRA AUTORIZADA
                    5 = CERRADA Y LIBERADA A PAGOS
                    9 = CANCELADA
                */

                var continuar = 1;
                var serieylote = '';
                if ($('.verificarinput').lenght != 0) {
                    $('.verificarinput').each(function () {
                        if ($(this).val() == '' || $(this).val() == ' ') {
                            continuar = 0;
                            return;
                        }
                    });
                    var serieylote = $('.serialziarinput').serialize();
                }

                if (continuar == 0) {
                    toastr.warning('Favor de seleccionar la serie y el lote de los productos controlados', { timeOut: 500 })
                    return false;
                }

                if (confirm('Confirme actualización') == false) {
                    return false;
                }

                // ajax para actualizar 
                var jqxhr = $.ajax({
                    url: "<?php echo $this->createUrl("ordenescompra/actualizarestatus"); ?>",
                    type: "POST",
                    dataType: "json",
                    timeout: (120 * 1000),
                    data: {
                        estatusnuevo: estatusnuevo,
                        id_oc: <?= $ordencompra['id'] ?>,
                        serieylote: serieylote
                    },
                    success: function (Response) {
                        if (Response.requestresult == 'ok') {
                            $.notify(Response.message, "success");
                            // toastr.success(Response.message, { timeOut: 500 })
                            setTimeout(() => {
                                location.reload();

                            }, 1500);
                        } else {
                            $.notify(Response.message, "error")
                        }
                    },
                    error: function (e) {
                        $.notify('Ocurrio un error inesperado', 'error')
                    }
                });
            }


            function Recibirpartida(input) {
                if (confirm('Favor de confirmar') == false) {
                    return false;
                }
                var valor = $(input).val();
                var id = $(input).data('id');
                // revisamos que no sea 0
                if (valor == '' || valor == 0 || id == '') {
                    toastr.warning('Ingrese el valor', { timeOut: 500 })
                }
                // peticion ajax para recibir oc
                var jqxhr = $.ajax({
                    url: "<?php echo $this->createUrl("inventario/recibirpartidaoc"); ?>",
                    type: "POST",
                    dataType: "json",
                    timeout: (120 * 1000),
                    data: {
                        cantidadarecibir: valor,
                        id: id,
                        id_oc: <?= $ordencompra['id'] ?>
                    },
                    success: function (Response) {
                        if (Response.requestresult == 'ok') {
                            // limpiamos el campo
                            $.notify(Response.message, "success");
                            // si la cantidad pendiente es = 0 ocultamos el form
                            if (Response.cantidadpendiente == 0) {
                                $('#botones' + id).empty();
                            } else {
                                // cantidad value
                                $('#partida' + id).val(Response.cantidadpendiente)
                            }
                            $('#cantidadrecibida' + id).empty().append(Response.cantidadrecibida);


                        } else {
                            $.notify(Response.message, "error")
                        }
                    },
                    error: function (e) {
                        $.notify('Ocurrio un error inesperado', 'error')
                    }
                });
            }

            function Recibirpartida(input) {
                if (confirm('Favor de confirmar') == false) {
                    return false;
                }
                var valor = $(input).val();
                var id = $(input).data('id');
                // peticion ajax para recibir oc
                var jqxhr = $.ajax({
                    url: "<?php echo $this->createUrl("inventario/recibirpartidaoc"); ?>",
                    type: "POST",
                    dataType: "json",
                    timeout: (120 * 1000),
                    data: {
                        cantidadarecibir: valor,
                        id: id,
                        id_oc: <?= $ordencompra['id'] ?>
                    },
                    success: function (Response) {
                        if (Response.requestresult == 'ok') {
                            // limpiamos el campo
                            $.notify(Response.message, "success");
                            // si la cantidad pendiente es = 0 ocultamos el form
                            if (Response.cantidadpendiente == 0) {
                                $('#botones' + id).empty();
                            } else {
                                // cantidad value
                                $('#partida' + id).val(Response.cantidadpendiente)
                            }
                            $('#cantidadrecibida' + id).empty().append(Response.cantidadrecibida);


                        } else {
                            $.notify(Response.message, "error")
                        }
                    },
                    error: function (e) {
                        $.notify('Ocurrio un error inesperado', "error")
                    }
                });
            }
        </script>
    <?php } ?>
</div>