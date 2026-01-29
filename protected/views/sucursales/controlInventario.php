<?php
$this->pageTitle = 'Control Inventario';
$this->breadcrumbs = array(
    'Inventario' => Yii::app()->createUrl('inventario/index'),
    $this->pageTitle
);
?>
<script type="text/javascript">
    // $(document).ready(function() {
    //     // Funcion para ordenar la lista de resultados
    //     $('#lista').DataTable();
    // });

    // Actualizar el precio del producto
    function ActualizarPrecio(idSucursalProducto, campo, value) {
        // revisamos que tenga permiso de actualizar
        let admin = <?php echo $this->VerificarAcceso(34, Yii::app()->user->id) ? 'true' : 'false'; ?>;
        if (!admin) {
            $.notify('Solo los administradores pueden modificar el valor ', "error");
            setTimeout(() => {
                location.reload();

            }, 2000);
            return false;
        }


        var jqxhr = $.ajax({
            url: "<?php echo $this->createUrl("sucursales/actualizarCampo"); ?>",
            type: "POST",
            dataType: "json",
            timeout: (120 * 1000),
            data: {
                idSucursalProducto,
                campo: campo,
                value
            },
            success: function(Response, newValue) {
                if (Response.requestresult == 'ok') {
                    // Si el resultado es correcto, le ingresamos el valor
                    $.notify("Se actualizo con exito el precio", "success");
                    let line = document.getElementById('line-' + idSucursalProducto)
                    if (Response.marcarLimite) {
                        line.style.backgroundColor = '#ee4d4d'
                        line.style.color = 'white'
                    } else {
                        line.style.backgroundColor = ''
                        line.style.color = 'black'
                    }

                    $(this).val(precio);
                } else {
                    $.notify(Response.message, "error");
                }
            },
            error: function(e) {
                $.notify('Ocurrio un error inesperado', 'error');
            }
        });

    }
</script>


<div class="row">
    <div class="col-md-12">
        <h1>Control de Inventario</h1>
        <div class="row">
            <div class="col-md-12 " style="padding: 0;">
                <form method="post" action="<?php echo $this->createUrl("sucursales/controlInventario"); ?>">
                    <div class="col-md-3">
                        <label for=""> Seleccionar Sucursal</label>
                        <?php
                        $valorSeleccionado = ($sucursalesval != '' || $sucursalesval != 0) ? $sucursalesval : '';
                        echo CHtml::listBox(
                            'sucursales',           // Nombre del campo
                            (empty($valorSeleccionado)) ? 'Sellecione una sucursal' : $valorSeleccionado,     // Valor seleccionado (puedes asignar un valor por defecto)
                            CHtml::listData(
                                $sucursales,        // Datos para el listBox
                                'id', // Atributo de valor
                                'nombre'               // Atributo de texto
                            ),
                            array('class' => 'form-control select2') // Opciones adicionales
                        );
                        ?>
                    </div>
                    <div class="col-md-3">
                        <label for=""> Buscar producto</label>
                        <?php
                        $this->widget(
                            'zii.widgets.jui.CJuiAutoComplete',
                            array(
                                'name' => 'producto_nombre',
                                'source' => $this->createUrl('productos/Productosajax'),
                                // Opciones javascript adicionales para el plugin
                                'options' => array(
                                    'minLength' => '3',
                                    'select' => 'js:function(event, ui) {
                                            $("#id_producto").val(ui.item.id);
                                            
                 	            }',
                                    'focus' => 'js:function(event, ui) {
                                 return false;
                                }'
                                ),
                                'value' => $producto_nombre,
                                'htmlOptions' => array(
                                    'class' => 'form-control',
                                    'placeholder' => 'Buscar producto'
                                )
                            )
                        );
                        ?>
                    </div>



                    <input type="hidden" value="<?= $id_producto ?>" name="id_producto" id="id_producto">

                    <div class="col-md-3" style="margin-top: 8px;">
                        <br>
                        <?php echo CHtml::submitButton('Buscar', array('class' => 'btn btn-success btn-ml')); ?>
                    </div>


                </form>
            </div>
        </div>
        <br>
        <br>
        <div class="table-responsive">
            <table id="lista" class="table  table-bordered  table-hover ">
                <thead>
                    <tr>
                        <th>Nombre Producto</th>
                        <th>
                            <table style="width: 100%;">
                                <tr>
                                    <td style="text-align: center;">Min</td>
                                    <td style="text-align: center;">Max</td>
                                </tr>
                            </table>
                        </th>
                        <th>Stock actual</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inventariosSucursales as $nombreSucursal => $inventarioSucursal) { ?>
                        <tr>
                            <th colspan="3" style="background-color: #5c515c22;">
                                <?= $nombreSucursal ?>
                            </th>
                        </tr>
                        <?php foreach ($inventarioSucursal as $productoStock) {
                            // print_r($productoStock);
                            // exit;
                        ?>
                            <tr style="background:<?= $productoStock['cantidad_stock'] <= $productoStock['minimo'] ? '#ee4d4d' : '' ?>;color:<?= $productoStock['cantidad_stock'] <= $productoStock['minimo'] ? 'white' : 'black' ?>"
                                id="line-<?= $productoStock['id'] ?>">
                                <td style="vertical-align: middle;">
                                    <div style="height: 100%;">
                                        <?= $productoStock['idProducto']['producto_nombre'] ?>
                                    </div>
                                </td>
                                <td>
                                    <table style="width: 100%; border-spacing: 10px 0;border-collapse: separate;">
                                        <tr>
                                            <td>
                                                <input type="number" class="form-control" name="actualizarmin"
                                                    onblur="ActualizarPrecio(<?= $productoStock['id'] ?>,'minimo',this.value);"
                                                    value="<?= $productoStock['minimo'] ?>">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" name="actualizarmax"
                                                    onblur="ActualizarPrecio(<?= $productoStock['id'] ?>,'maximo',this.value);"
                                                    value="<?= $productoStock['maximo'] ?>">
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td style="text-align: center;vertical-align: middle;">
                                    <div style="height: 100%;">
                                        <?= $productoStock['cantidad_stock'] ?>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>