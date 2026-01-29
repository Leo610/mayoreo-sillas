<?php
$this->pageTitle = 'Inventario';
$this->pageDescription = '';
$this->opcionestitulo = '<a href="' . yii::app()->createurl('inventario/index', array('actualizarproductos' => true)) . '" class="btn btn-success btn-sm " >Actualizar productos</a>';
$this->breadcrumbs = array(
    $this->pageTitle
);
$op_menu = 1;
include 'menu.php';
include 'modal/verdisponibilidad.php';
include 'modal/stockserie.php';
?>


<div class="panel">
    <form method="get">
        <div class="row">
            <div class="col-md-2">
                <label>Categoria</label>
                <select class="form-control" data-plugin="select2" id="categoria" name="categoria">
                    <option value="0">-- Seleccione --</option>
                    <?php foreach ($categorias as $key => $value) { ?>
                        <option value="<?= $key ?>" <?= (isset($_GET["categoria"]) && $key == $_GET["categoria"]) ? 'selected' : ''; ?>>
                            <?= $value ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-2">
                <label>Subcategoria</label>
                <select class="form-control" data-plugin="select2" id="subcategoria" name="subcategoria">
                    <option value="0">-- Seleccione --</option>
                    <?php foreach ($subcategorias as $key => $value) { ?>
                        <option value="<?= $key ?>" <?= (isset($_GET["subcategoria"]) && $key == $_GET["subcategoria"]) ? 'selected' : ''; ?>>
                            <?= $value ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-2">
                <label>Sucursal</label>
                <select name="sucursal" class="form-control">
                    <option value="">-- Selecione --</option>
                    <?php foreach ($sucursales as $rows) { ?>
                        <option value="<?= $rows['id'] ?>" <?= ($rows['id'] == $sucursal) ? 'selected' : ''; ?>>
                            <?= $rows['nombre'] ?>
                        </option>';
                    <?php } ?>
                </select>
            </div>

            <div class="col-md-3">
                <label>Producto</label>
                <?php
                $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                    'name' => 'nombre_producto',
                    'value' => isset($nombre_producto) ? $nombre_producto : '', // <--- AÑADE ESTO
                    'source' => $this->createUrl('inventario/autocomplete'),
                    'options' => array(
                        'minLength' => '2',
                        'select' => 'js:function(event, ui) {
            $("#id_producto").val(ui.item.id);
        }',
                    ),
                    'htmlOptions' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Buscar producto por nombre'
                    ),
                ));
                ?>
                <input type="hidden" name="id_producto" id="id_producto" value="<?= isset($_GET['id_producto']) ? $_GET['id_producto'] : '' ?>">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-success" style="margin-top: 27px">Filtrar</button>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-md-12" style="overflow-x: scroll;">
            <div class="table-responsive">
                <table id="resultado" class="table table-sm table-hover datatable">
                    <thead>
                        <tr>
                            <th>Sucursal</th>
                            <th>Producto</th>
                            <th>Clave</th>
                            <th>Costo de compra</th>
                            <th>Familia</th>
                            <th>Categoria</th>
                            <th>Subcategoria</th>
                            <th>Stock</th>
                            <th>Stock separado</th>
                            <th>Min</th>
                            <th>Max</th>
                            <!-- <th>Por recibir</th>
                            <th>Por enviar</th>
                            <th>Ultima compra</th>
                            <th>Ultima venta</th> -->
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista as $rows) {
                            $cadena = $this->Cadenacategorias($rows['idProducto']['id_categoria']);
                            $series = SucursalesProductosSeries::model()->findAll(
                                array(
                                    'condition' => 'id_producto = :id_producto and id_sucursal =:id_sucursal and cantidad_stock > 0',
                                    'params' => array(':id_producto' => $rows['id_producto'], ':id_sucursal' => $rows['id_sucursal'])
                                )
                            );
                            // revisamos los stock de los productos
                            $colortr = 'table-success';
                            if ($rows['cantidad_stock'] <= $rows['minimo']) {
                                $colortr = 'table-danger';
                            } elseif ($rows['cantidad_stock'] > $rows['reorden']) {
                                $colortr = 'table-success';
                            } elseif ($rows['cantidad_stock'] <= $rows['reorden'] && $rows['cantidad_stock'] > $rows['minimo']) {
                                $colortr = 'table-warning';
                            }
                        ?>
                            <tr class="" style="background:<?= $rows['cantidad_stock'] <= $rows['minimo'] ? '#ee4d4d' : '' ?>; color:<?= $rows['cantidad_stock'] <= $rows['minimo'] ? 'white' : 'black' ?>">
                                <td>
                                    <?= $rows['idSucursal']['nombre'] ?>
                                </td>
                                <td><a
                                        href="<?= Yii::app()->createurl('productos/editarproducto', array('id' => $rows['id_producto'])) ?>">
                                        <?= $rows['idProducto']['producto_nombre'] ?>
                                    </a></td>
                                <td>
                                    <?= $rows['idProducto']['producto_clave'] ?>
                                </td>
                                <td>
                                    <?php
                                    $datoscompra = $this->Costocompra($rows['id_producto'], $rows['id_sucursal'], 0);
                                    echo '$ ' . number_format($datoscompra['preciocompra'], 2);
                                    ?>
                                </td>
                                <td>
                                    <?= $cadena['Familia'] ?>
                                </td>
                                <td>
                                    <?= $cadena['Categoria'] ?>
                                </td>
                                <td>
                                    <?= $cadena['Subcategoria'] ?>
                                </td>
                                <td class="cambiarcolor <?= $colortr ?>">
                                    <?= $rows['cantidad_stock'] ?>
                                </td>
                                <td>
                                    <?= $rows['cantidad_separada'] ?>
                                </td>
                                <td>
                                    <?= $rows['minimo'] ?>
                                </td>
                                <td>
                                    <?= $rows['maximo'] ?>
                                </td>
                                <!-- <td><?= $rows['cantidad_por_recibir'] ?></td>
                            <td><?= $rows['cantidad_por_enviar'] ?></td>
                            <td><?= $rows['fecha_ultima_compra'] ?></td>
                            <td><?= $rows['fecha_ultima_venta'] ?></td> -->

                                <td>
                                    <button type="button" class="btn btn-secondary btn-xs verificardisponibilidad"
                                        data-idproducto="<?= $rows['id_producto'] ?>"
                                        data-nombreproducto="<?= $rows['idProducto']['producto_nombre'] ?>">Stock</button>
                                    <?php if (!empty($series)) { ?>
                                        <button type="button" class="btn btn-secondary btn-xs btnverserie"
                                            title="Ver serie y lotes del movimiento"
                                            onclick="Obtenerstockserie(<?= $rows['id_producto'] ?>,<?= $rows['id_sucursal'] ?>)">
                                            Series
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
<script type="text/javascript">
    $(document).ready(function() {
        $('#resultado').DataTable({
            pageLength: 100,
            order: [
                [0, 'desc']
            ],
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'excelHtml5',
                    title: 'Inventario',
                    text: 'Exportar a Excel'
                },
                {
                    extend: 'csvHtml5',
                    title: 'Inventario',
                    text: 'Exportar CSV'
                },
                {
                    extend: 'print',
                    text: 'Imprimir'
                }
            ]
        });
        $("body").on("change", ".actualizarcampo", function() {

            var elemento = $(this);
            var valor = $(this).val();
            var id = $(this).data('id');
            var campo = $(this).data('campo');
            var model = $(this).data('model');

            var minimo = $(this).data('minimo');
            var maximo = $(this).data('maximo');
            var reorden = $(this).data('reorden');
            var stock = $(this).data('stock');



            var jqxhr = $.ajax({
                url: "<?php echo $this->createUrl("productos/actualizarajaxatributos"); ?>",
                type: "POST",
                dataType: "json",
                timeout: (120 * 1000),
                data: {
                    valor: valor,
                    id: id,
                    campo: campo,
                    model: model,
                },
                success: function(Response, newValue) {
                    if (Response.requestresult == 'ok') {
                        toastr.success(Response.message, {
                            timeOut: 500
                        })
                        // en base a los minimos, maximos y reorden actualizamos el color del ROW
                        if (campo == "minimo") {
                            minimo = valor;
                        }
                        if (campo == "maximo") {
                            maximo = valor;
                        }
                        if (campo == "reorden") {
                            reorden = valor;
                        }
                        if (stock <= minimo) {
                            elemento.closest('.cambiarcolor').removeClass().addClass('table-danger');
                            return false;
                        } else if (stock <= reorden && stock > minimo) {
                            elemento.closest('.cambiarcolor').removeClass().addClass('table-warning');
                            return false;
                        } else if (stock > reorden) {
                            elemento.closest('.cambiarcolor').removeClass().addClass('table-success');
                            return false;
                        }

                    } else {
                        toastr.warning(Response.message, {
                            timeOut: 500
                        })
                    }
                },
                error: function(e) {
                    toastr.warning('Ocurrio un error inesperado', {
                        timeOut: 500
                    })
                }
            });
        });
        // funcion para verificar la disponibilidad del producto
        $("body").on("click", ".verificardisponibilidad", function() {
            var id_producto = $(this).data('idproducto');
            var nombreproducto = $(this).data('nombreproducto');
            // necesitamos hacer una petición ajax para obtener la disponibilidad del producto
            var jqxhr = $.ajax({
                url: "<?php echo $this->createUrl("inventario/disponibilidadajax"); ?>",
                type: "POST",
                dataType: "json",
                timeout: (120 * 1000),
                data: {
                    id_producto: id_producto
                },
                success: function(Response, newValue) {
                    if (Response.requestresult == 'ok') {
                        toastr.success(Response.message, {
                            timeOut: 500
                        })
                        $('#nombreproductodisponibilidad').empty().append(nombreproducto)
                        // asignamos la respuesta html a la tabla
                        $('#tabledisponibilidad tbody').empty().append(Response.html)
                        // mostramos modal
                        $('#verdisponibilidad').modal('show');
                    } else {
                        toastr.warning(Response.message, {
                            timeOut: 500
                        })
                    }
                },
                error: function(e) {
                    toastr.warning('Ocurrio un error inesperado', {
                        timeOut: 500
                    })
                }
            });
        });

    });
</script>