<?php
$this->pageTitle = 'Orden de compra # ' . $ordencompra['id'];
$this->pageDescription = '';
$this->breadcrumbs = array(
    'Ordenes de Compra' => Yii::app()->createurl('ordenescompra/index'),
    $this->pageTitle
);
$this->opcionestitulo = '<a href="' . Yii::app()->createUrl('ordenescompra/pdf', array('id' => $ordencompra['id'])) . '" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-print" aria-hidden="true"></i> Imprimir OC </a>';
// menu de inventario
$this->renderpartial('//inventario/menu', array('op_menu' => 6));
//
include 'modal/editarconcepto.php';
// si el estatus es por recibir, lo mandamos a recibir
if ($ordencompra['estatus'] == 4) {
    $this->redirect(Yii::app()->baseurl . '/ordenescompra/recibir?numero_oc=' . $ordencompra['id'] . '&buscar=1&idsucursal=' . $ordencompra['id_sucursal']);
}



$permiso = $this->VerificarAcceso(32, Yii::app()->user->id);
?>
<script type="text/javascript">
    setTimeout(function() {
        ObtenerdatosOC();
    }, 500);

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
                id_oc: <?= $ordencompra['id'] ?>
            },
            success: function(Response) {
                console.log(Response);
                if (Response.requestresult == 'ok') {
                    $.notify(Response.message, "success");
                    location.reload();
                } else {
                    $.notify(Response.message, "error")
                    // toastr.warning(Response.message, { timeOut: 500 })
                }
            },
            error: function(e) {
                $.notify('Ocurrio un error inesperado', "error")
            }
        });
    }
    // datos del producto
    function Datosproducto(idproducto) {
        // enviamos la petición ajaxx
        var jqxhr = $.ajax({
            url: "<?php echo $this->createUrl("ordenescompra/datosproducto"); ?>",
            type: "POST",
            dataType: "json",
            timeout: (120 * 1000),
            data: {
                idproducto: idproducto,
                id_proveedor: <?= $ordencompra['id_proveedor'] ? $ordencompra['id_proveedor'] : 0 ?>
            },
            success: function(Response) {
                if (Response.requestresult == 'ok') {
                    $.notify(Response.message, "success");
                    console.log(Response);
                    if (Response.datos != null) {
                        $('#unitarioproducto').val(Response.datos.costo);
                        Obtenertotal(1);
                    }
                } else {
                    toastr.warning(Response.message, {
                        timeOut: 500
                    })
                }
            },
            error: function(e) {
                $.notify('Ocurrio un error inesperado', "error")
            }
        });
    }
    // datos de la oc
    function ObtenerdatosOC() {
        // enviamos la petición ajaxx
        var jqxhr = $.ajax({
            url: "<?php echo $this->createUrl("ordenescompra/datosoc"); ?>",
            type: "POST",
            dataType: "json",
            timeout: (120 * 1000),
            data: {
                id: <?= $ordencompra['id'] ?>,
            },
            success: function(Response) {
                console.log(Response);
                if (Response.requestresult == 'ok') {
                    $.notify(Response.message, "success");
                    // alert(Response.message, { timeOut: 500 });
                    // $.notify(Response.message, "success");
                    // insertamos los valores
                    $('#contenidosubtotal').empty().append(Response.subtotalformat);
                    $('#contenidoiva').empty().append(Response.ivaformat);
                    $('#contenidototal').empty().append(Response.totalformat);
                    // insertamos los conceptos en la tabla
                    $('#listaprouctos > tbody').empty().append(Response.conceptostr);
                } else {
                    toastr.warning(Response.message, {
                        timeOut: 500
                    })
                }
            },
            error: function(e) {
                $.notify('Ocurrio un error inesperado', "error")
            }
        });
    }
    // obtenemos el total en base al unitario
    function Obtenertotal(tipo) {

        if (tipo == 1) {
            var unitarioproducto = $('#unitarioproducto').val();
            var cantidadproducto = $('#cantidadproducto').val();
            if (unitarioproducto == '') {
                return false;
            }
            var totalproducto = unitarioproducto * cantidadproducto;
            $('#totalproducto').val(totalproducto);
        }
        if (tipo == 2) {
            var unitarioproducto = $('#editarpartida_unitario').val();
            var cantidadproducto = $('#editarpartida_cantidad').val();
            if (unitarioproducto == '') {
                return false;
            }
            var totalproducto = unitarioproducto * cantidadproducto;
            $('#editarpartida_total').val(totalproducto);
        }
        if (tipo == 3) {
            var unitarioproducto = $('#unitarioproducto_manual').val();
            var cantidadproducto = $('#cantidadproducto_manual').val();
            if (unitarioproducto == '') {
                return false;
            }
            var totalproducto = unitarioproducto * cantidadproducto;
            $('#totalproducto_manual').val(totalproducto);
        }
    }
    // funcion para agregar el producto a la cotización
    function Agregarproducto() {
        var id_producto = $('#id_producto_seleccionado').val();
        var unitarioproducto = $('#unitarioproducto').val();
        var cantidadproducto = $('#cantidadproducto').val();
        if (unitarioproducto == '' || cantidadproducto == '' || id_producto == '' || cantidadproducto == 0) {
            toastr.warning('Favor de llenar todos los campos', {
                timeOut: 500
            })
            return false;
        }
        // enviamos la peticion para insertar el producto
        var jqxhr = $.ajax({
            url: "<?php echo $this->createUrl("ordenescompra/agregarproductooc"); ?>",
            type: "POST",
            dataType: "json",
            timeout: (120 * 1000),
            data: {
                id_producto: id_producto,
                unitarioproducto: unitarioproducto,
                cantidadproducto: cantidadproducto,
                id: <?= $ordencompra['id'] ?>
            },
            success: function(Response) {
                if (Response.requestresult == 'ok') {
                    // limpiamos el campo
                    $('#buscador').val('');
                    $.notify(Response.message, "success");
                    ObtenerdatosOC();
                    $('#id_producto_seleccionado').val('');
                    $('#unitarioproducto').val('');
                    $('#cantidadproducto').val(1);
                    $('#totalproducto').val('');
                    $('#claveprodsel').val('')
                } else {
                    toastr.warning(Response.message, {
                        timeOut: 500
                    })
                }
            },
            error: function(e) {
                $.notify('Ocurrio un error inesperado', "error")
            }
        });
    }
    //
    function Agregarproductomanual() {
        var concepto_os = $('#concepto_os').val();
        var unitarioproducto = $('#unitarioproducto_manual').val();
        var cantidadproducto = $('#cantidadproducto_manual').val();
        if (unitarioproducto == '' || cantidadproducto == '' || cantidadproducto == 0 || concepto_os == '') {
            toastr.warning('Favor de llenar todos los campos', {
                timeOut: 500
            })
            return false;
        }
        // enviamos la peticion para insertar el producto
        var jqxhr = $.ajax({
            url: "<?php echo $this->createUrl("ordenescompra/agregarproductoocmanual"); ?>",
            type: "POST",
            dataType: "json",
            timeout: (120 * 1000),
            data: {
                concepto_os: concepto_os,
                unitarioproducto: unitarioproducto,
                cantidadproducto: cantidadproducto,
                id: <?= $ordencompra['id'] ?>
            },
            success: function(Response) {
                if (Response.requestresult == 'ok') {
                    // limpiamos el campo
                    $('#buscador').val('');
                    $.notify(Response.message, "success");
                    ObtenerdatosOC();
                    $('#unitarioproducto_manual').val('');
                    $('#cantidadproducto_manual').val(1);
                    $('#totalproducto_manual').val('');
                    $('#concepto_os').val('');
                } else {
                    toastr.warning(Response.message, {
                        timeOut: 500
                    })
                }
            },
            error: function(e) {
                $.notify('Ocurrio un error inesperado', "error")
            }
        });
    }

    // funcion para eliminar el producto de la oc
    function Eliminarpartida(id) {
        if (confirm('Confirme eliminación') == false) {
            return false;
        }
        // enviamos la peticion para insertar el producto
        var jqxhr = $.ajax({
            url: "<?php echo $this->createUrl("ordenescompra/eliminarpartida"); ?>",
            type: "POST",
            dataType: "json",
            timeout: (120 * 1000),
            data: {
                id: id,
                id_oc: <?= $ordencompra['id'] ?>
            },
            success: function(Response) {
                if (Response.requestresult == 'ok') {
                    // limpiamos el campo
                    $.notify(Response.message, "success");
                    // eliminamos el tr
                    $('#partida' + id).remove();
                    ObtenerdatosOC();
                } else {
                    toastr.warning(Response.message, {
                        timeOut: 500
                    })
                }
            },
            error: function(e) {
                $.notify('Ocurrio un error inesperado', "error")
            }
        });
    }
    // funcion para editar una partida
    function Datospartida(id) {
        // enviamos la peticion para insertar el producto
        var jqxhr = $.ajax({
            url: "<?php echo $this->createUrl("ordenescompra/datospartida"); ?>",
            type: "POST",
            dataType: "json",
            timeout: (120 * 1000),
            data: {
                id: id,
                id_oc: <?= $ordencompra['id'] ?>
            },
            success: function(Response) {
                if (Response.requestresult == 'ok') {
                    // limpiamos el campo
                    $.notify(Response.message, "success");
                    // asignamos los datos
                    var src = $('#editarpartida_img').data('src');
                    var img = src + '/' + $('#editarpartida_img').data('imgno');
                    if (Response.img != '') {
                        var img = src + Response.img;
                        // var img = src + '/productos/' + Response.img;
                    }
                    $('#editarpartida_img').attr('src', img);
                    $('#editarpartida_nombre').val(Response.nombre);
                    $('#editarpartida_clave').val(Response.clave);
                    $('#editarpartida_cantidad').val(Response.concepto.cantidad);
                    $('#editarpartida_unitario').val(Response.concepto.unitario);
                    $('#id_ordencompra').val(Response.concepto.id_orden_compra);
                    $('#id_partida').val(Response.concepto.id);
                    $('#editarpartida_total').val(Response.concepto.cantidad * Response.concepto.unitario);
                    $('#id_producto').val(Response.concepto.id_producto);
                    // abrimos el modal de la partida
                    $('#editarpartidamodal').modal('show')
                } else {
                    toastr.warning(Response.message, {
                        timeOut: 500
                    })
                }
            },
            error: function(e) {
                $.notify('Ocurrio un error inesperado', "error")
            }
        });
    }
</script>
<div class="panel">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-sm table-hover table-responsive">
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
                <?php if ($ordencompra['estatus'] == 1 || $ordencompra['estatus'] == 2 || $ordencompra['estatus'] == 3) { ?>
                    <tr id="agregarproductotr">
                        <td>Concepto</td>
                        <td colspan="7">
                            <div class="row">
                                <div class="col-md-1">
                                    <input type="text" class="form-control" name="claveprodsel" id="claveprodsel"
                                        readonly="true">
                                    <input type="hidden" class="form-control" name="id_producto_seleccionado"
                                        id="id_producto_seleccionado" readonly="true">
                                </div>
                                <div class="col-md-5">
                                    <?php
                                    $this->widget(
                                        'zii.widgets.jui.CJuiAutoComplete',
                                        array(
                                            'name' => 'buscador',
                                            'id' => 'buscador',
                                            'source' => $this->createUrl('administracion/buscadorproductooc', array('proveedor' => $ordencompra['id_proveedor'])),
                                            // Opciones javascript adicionales para el plugin
                                            'options' => array(
                                                'minLength' => '2',
                                                'autoFocus' => true,
                                                'select' => 'js:function(event, ui) {
                                            $("#id_producto_seleccionado").val(ui.item.id_producto);
                                            $("#claveprodsel").val(ui.item.clave);
                                           
                                            
                                            Datosproducto(ui.item.id_producto);
                                            }',
                                                'focus' => 'js:function(event, ui) {
                                            $("#id_producto_seleccionado").val("");
                                            $("#claveprodsel").val("");
                                              return false;
                                          }'
                                            ),
                                            'htmlOptions' => array(
                                                'class' => 'form-control',
                                                'placeholder' => 'Buscar producto por nombre o clave'
                                            )
                                        )
                                    );
                                    ?>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" min="0" class="form-control" id="unitarioproducto"
                                        placeholder="unitario" onchange="Obtenertotal(1);">
                                </div>
                                <div class="col-md-2">
                                    <input type="number" min="0" class="form-control" id="cantidadproducto" value="1"
                                        placeholder="cantidad" onchange="Obtenertotal(1);">
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <input type="number" id="totalproducto" class="form-control" disabled="true"
                                            placeholder="total">
                                        <span class="input-group-btn">
                                            <button class="btn btn-success" type="button"
                                                onclick="Agregarproducto()">Agregar</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr id="agregarproductotr">
                        <td>Concepto manual</td>
                        <td colspan="7">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" name="concepto_os" id="concepto_os" class="form-control"
                                        placeholder="Ingrese el concepto">
                                </div>
                                <div class="col-md-2">
                                    <input type="number" min="0" class="form-control" id="unitarioproducto_manual"
                                        placeholder="unitario sin IVA" onchange="Obtenertotal(3);">
                                </div>
                                <div class="col-md-2">
                                    <input type="number" min="0" class="form-control" id="cantidadproducto_manual" value="1"
                                        placeholder="cantidad" onchange="Obtenertotal(3);">
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <input type="number" id="totalproducto_manual" class="form-control" disabled="true"
                                            placeholder="total">
                                        <span class="input-group-btn">
                                            <button class="btn btn-success" type="button"
                                                onclick="Agregarproductomanual()">Agregar</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-9">
            <table id="listaprouctos" class="table table-sm table-hover table-responsive table-striped"
                style="font-size: 11px;">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Clave</th>

                        <th>Unitario</th>
                        <th>IVA</th>
                        <th>Subtotal</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="col-md-3">
            <table id="tabladetotales" class="table table-responsive table-hover table-sm">
                <thead>
                    <tr>
                        <th colspan="3" class="text-center">Totales</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>SubTotal</td>
                        <td id="contenidosubtotal"></td>
                    </tr>
                    <tr>
                        <td>IVA</td>
                        <td id="contenidoiva"></td>
                    </tr>
                    <tr>
                        <td class="lead">Total</td>
                        <td class="lead font-weight-bold" id="contenidototal"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-12 text-right">
            <?php if ($ordencompra['estatus'] == 1) { ?>
                <button type="button" class="btn btn-success btn-sm" onclick="Actualizarestatus(2)">
                    <i class="fa fa-check-circle" aria-hidden="true"></i>
                    Solicitar Requisición
                </button>
                <?php } elseif ($ordencompra['estatus'] == 2) {
                if ($permiso == 1) { ?>
                    <button type="button" class="btn btn-success btn-sm" onclick="Actualizarestatus(4)">
                        <i class="fa fa-check-circle" aria-hidden="true"></i>
                        Autorizar Orden de Compra
                    </button>
                <?php } else { ?>
                    No cuenta con permisos para autorizar orden de compra.
                <?php }
            } elseif ($ordencompra['estatus'] == 4) {
                if ($permiso == 1) { ?>
                    <button type="button" class="btn btn-success btn-sm" onclick="Actualizarestatus(5)">
                        <i class="fa fa-check-circle" aria-hidden="true"></i>
                        Recibir Orden de Compra
                    </button>
                <?php } else { ?>
                    No cuenta con permisos para autorizar orden de compra.
            <?php }
            } ?>
            <?php if ($ordencompra['estatus'] == 1 || $ordencompra['estatus'] == 2 || $ordencompra['estatus'] == 3 || $ordencompra['estatus'] == 4) { ?>
                <button type="button" class="btn btn-danger btn-sm" onclick="Actualizarestatus(9)">
                    <i class="fa fa-ban" aria-hidden="true"></i>
                    Cancelar
                </button>
            <?php } ?>
        </div>
    </div>
</div>