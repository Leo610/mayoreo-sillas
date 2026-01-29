<?php
/* @var $this ListapreciosController */
/* @var $model Listaprecios */


$this->pageTitle = 'Administración de Lista precios';
$this->breadcrumbs = array(
    'Modulos' => Yii::app()->createUrl('administracion/modulos'),
    'Lista Precios',
);

?>
<script type="text/javascript">
    $(document).ready(function () {

        // Funcion para mostrar el modal
        $("#abrirmodal").click(function () {
            $("#formmodal").modal('show');
            $('#Listaprecios-form')[0].reset();
        });

        // Funcion para ordenar la lista de resultados
        $('#lista').DataTable();

    });

    function Actualizar(id) {
        var jqxhr = $.ajax({
            url: "<?php echo $this->createUrl("Listaprecios/Listapreciosdatos"); ?>",
            type: "POST",
            dataType: "json",
            timeout: (120 * 1000),
            data: {
                id: id,
            },
            success: function (Response, newValue) {
                if (Response.requestresult == 'ok') {
                    // Si el resultado es correcto, agregamos los datos al form del modal
                    $("#ListaPrecios_id_lista_precio").val(Response.Datos.id_lista_precio);
                    $("#ListaPrecios_listaprecio_nombre").val(Response.Datos.listaprecio_nombre);
                    $("#ListaPrecios_id_moneda").val(Response.Datos.id_moneda);
                    $("#ListaPrecios_default").val(Response.Datos.default);
                    // y posteriormente mostramos el modal 
                    $("#formmodal").modal('show');
                    $.notify("Se encontro la informacion", "success");
                } else {

                }
            },
            error: function (e) {
                $.notify("Verifica los campos e intente de nuevo", "error");
            }
        });
    }
</script>
<?php include 'modal/_form.php'; ?>

<div class="row">
    <div class="col-md-12">
        <h1>Administración de Lista precios <!--<a href="#" class="btn btn-success" id="abrirmodal">
            Agregar Listaprecios
        </a>--></h1>

        <hr>
        <div class="table-responsive">
            <table id="lista" class="table table-striped table-bordered dt-responsive nowrap ">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Moneda</th>
                        <th>Default</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($lista as $rows) { ?>
                        <tr>
                            <td>
                                <?= $rows->id_lista_precio ?>
                            </td>
                            <td>
                                <?= $rows->listaprecio_nombre ?>
                            </td>
                            <td>
                                <?= $rows['rl_moneda']['moneda_nombre'] ?>
                            </td>
                            <td>
                                <?= ($rows['default'] == 1) ? 'Si' : 'No'; ?>
                            </td>
                            <td class="">
                                <?php
                                // boton para ver los precios de esta lista
                                echo CHtml::link(
                                    '<i class="fa fa-list-alt"></i> Ver productos',
                                    array('productosprecios/admin', 'idlistaprecio' => $rows['id_lista_precio']),
                                    array(
                                        'submit' => array('productosprecios/admin', 'idlistaprecio' => $rows['id_lista_precio'])
                                    )
                                );

                                echo CHtml::link(
                                    '<br><i class="fa fa-pencil fa-lg"></i> Editar',
                                    "javascript:;",
                                    array(
                                        'style' => 'cursor: pointer;',
                                        "onclick" => "Actualizar(" . $rows['id_lista_precio'] . "); return false;"
                                    )
                                );


                                echo CHtml::link(
                                    '<br><i class="fa fa-trash fa-lg"></i> Eliminar',
                                    array('Listaprecios/delete', 'id' => $rows['id_lista_precio']),
                                    array(
                                        'submit' => array('Listaprecios/delete', 'id' => $rows['id_lista_precio']),
                                        'class' => 'delete',
                                        'confirm' => 'Seguro que lo deseas eliminar?'
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>