<?php
/* @var $this ProductospreciospreciosController */
/* @var $model Productosprecios */


$this->pageTitle = 'Administración de Precios';
$this->breadcrumbs = array(
    'Modulos' => Yii::app()->createUrl('administracion/modulos'),
    'Productosprecios',
);
//echo $this->Obtenerprecio(5,5)
?>
<script type="text/javascript">
    $(document).ready(function () {
        // Funcion para ordenar la lista de resultados
        $('#lista').DataTable({
            pageLength: 100,
            order: [[0, 'desc']],
        });

    });// $( document ).ready(function() {



    // Actualizar el precio del producto
    function ActualizarPrecio(id_producto, listaprecio, precio, campo) {
        /*alert(id_producto);
        alert(listaprecio);
        alert(precio);
        alert(campo);*/
        // actualizamos el precio con ajax
        var jqxhr = $.ajax({
            url: "<?php echo $this->createUrl("productosprecios/actualizarprecio"); ?>",
            type: "POST",
            dataType: "json",
            timeout: (120 * 1000),
            data: {
                id_producto: id_producto,
                listaprecio: listaprecio,
                precio: precio,
                campo: campo
            },
            success: function (Response, newValue) {
                if (Response.requestresult == 'ok') {
                    // Si el resultado es correcto, le ingresamos el valor
                    $.notify("Se actualizo con exito el precio", "success");
                    $(this).val(precio);

                } else {
                    $.notify("Verifica los campos e intente de nuevo", "error");
                }
            },
            error: function (e) {
                $.notify('Ocurrio un error inesperado', 'error');
            }
        });

    }



</script>

<div class="row">
    <div class="col-md-12">
        <h1>Administración de Precios</h1>
        <!-- <form method="get">
            <select name="idlistaprecio" class="form-control" onchange="this.form.submit()">
                <option>-- Seleccione lista de precios --</option>
                <?php foreach ($listaprecios as $key => $value) { ?>
                    <option value="<?= $key ?>" <?= (isset($_GET['idlistaprecio']) && $_GET['idlistaprecio'] == $key) ? 'selected' : ''; ?>>
                        <?= $value ?>
                    </option>
                <?php } ?>
            </select>

        </form><br> -->

        <?php if ($datos['id_lista_precio'] != NULL) { ?>
            <p>Lista de precios: <b>
                    <?= $datos['listaprecio_nombre'] ?>
                </b></p>

            <hr>
            <div class="table-responsive">
                <table id="lista" class="table  table-bordered  table-hover ">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Clave</th>
                            <th>Precio Lista</th>
                            <th>50 - 99</th>
                            <th>100 - 199</th>
                            <th>200 o más</th>
                            <!-- <th>300 o mas</th> -->
                            <th>Distribuidores</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        foreach ($lista as $rows) {
                            ?>
                            <tr>
                                <td>
                                    <?= $rows['id_producto'] ?>
                                </td>
                                <td>
                                    <?= $rows['producto_nombre'] ?>
                                </td>
                                <td>
                                    <?= $rows['producto_clave'] ?>
                                </td>

                                <td>
                                    <input type="number" class="form-control" name="actualizarprecio"
                                        onblur="ActualizarPrecio(<?= $rows['id_producto'] ?>,<?= $datos['id_lista_precio'] ?>,this.value,'precio');"
                                        value="<?= $this->Obtenerprecio($rows['id_producto'], $datos['id_lista_precio'], 'precio') ?>">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="actualizarprecio"
                                        onblur="ActualizarPrecio(<?= $rows['id_producto'] ?>,<?= $datos['id_lista_precio'] ?>,this.value,'precio_ppp');"
                                        value="<?= $this->Obtenerprecio($rows['id_producto'], $datos['id_lista_precio'], 'precio_ppp') ?>">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="actualizarprecio"
                                        onblur="ActualizarPrecio(<?= $rows['id_producto'] ?>,<?= $datos['id_lista_precio'] ?>,this.value,'precio_oferta');"
                                        value="<?= $this->Obtenerprecio($rows['id_producto'], $datos['id_lista_precio'], 'precio_oferta') ?>">
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="actualizarprecio"
                                        onblur="ActualizarPrecio(<?= $rows['id_producto'] ?>,<?= $datos['id_lista_precio'] ?>,this.value,'precio_cambio');"
                                        value="<?= $this->Obtenerprecio($rows['id_producto'], $datos['id_lista_precio'], 'precio_cambio') ?>">
                                </td>
                                <!-- <td>
                                    <input type="number" class="form-control" name="actualizarprecio"
                                        onblur="ActualizarPrecio(<?= $rows['id_producto'] ?>,<?= $datos['id_lista_precio'] ?>,this.value,'precio_extra');"
                                        value="<?= $this->Obtenerprecio($rows['id_producto'], $datos['id_lista_precio'], 'precio_extra') ?>">
                                </td> -->
                                <td>
                                    <input type="number" class="form-control" name="actualizarprecio"
                                        onblur="ActualizarPrecio(<?= $rows['id_producto'] ?>,<?= $datos['id_lista_precio'] ?>,this.value,'costo');"
                                        value="<?= $this->Obtenerprecio($rows['id_producto'], $datos['id_lista_precio'], 'costo') ?>">
                                </td>
                            </tr>

                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } else {
            echo '<p>Seleccione una lista de precios</p>';
        } ?>
    </div>
</div>