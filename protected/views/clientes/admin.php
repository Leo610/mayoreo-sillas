<?php
/* @var $this ClientesController */
/* @var $model Clientes */


$this->pageTitle = 'Administración de Clientes';
$this->breadcrumbs = array(
    'Modulos' => Yii::app()->createUrl('administracion/modulos'),
    'Clientes',
);

?>
<script type="text/javascript">
    $(document).ready(function() {

        // Funcion para mostrar el modal
        $("#abrirmodal").click(function() {
            $("#formmodal").modal('show');
            $('#Clientes-form')[0].reset();
        });

        // Funcion para ordenar la lista de resultados
        $('#lista').DataTable({
            pageLength: 100,
            order: [
                [0, 'desc']
            ],
            dom: 'lBfrtip',
            buttons: [{
                extend: 'csv',
                title: 'Clientes',
                text: 'Exportar a EXCEL',
                exportOptions: {
                    columns: ':not(.noExport)'
                }
            }, ]
        });

    });

    function Navegar(accion, id) {
        if (accion == 'cotizaciones') {
            console.log('cotizaciones', ' id ', id);
            window.location.href = "<?php echo $this->createUrl('cotizaciones/lista'); ?>" + "?id_cliente=" + id;
        } else if (accion == 'pedidos') {
            console.log('pedidos', ' id ', id);
            window.location.href = "<?php echo $this->createUrl('proyectos/lista'); ?>" + "?id_cliente=" + id;
        } else if (accion == 'ppagos') {
            console.log('pedidos', ' id ', id);
            window.location.href = "<?php echo $this->createUrl('contabilidadingresos/pendientespago'); ?>" + "?id_cliente=" + id;
        }
    }

    function Detalles(id) {
        window.location.href = "<?php echo $this->createUrl('clientes/detalles'); ?>" + "?id_cliente=" + id;
    }

    function Actualizar(id) {
        var jqxhr = $.ajax({
            url: "<?php echo $this->createUrl("Clientes/Clientesdatos"); ?>",
            type: "POST",
            dataType: "json",
            timeout: (120 * 1000),
            data: {
                id: id,
            },
            success: function(Response, newValue) {
                if (Response.requestresult == 'ok') {
                    // Si el resultado es correcto, agregamos los datos al form del modal
                    $("#Clientes_cliente_razonsocial").val(Response.Datos.cliente_razonsocial);
                    $("#Clientes_cliente_rfc").val(Response.Datos.cliente_rfc);
                    $("#Clientes_cliente_calle").val(Response.Datos.cliente_calle);
                    $("#Clientes_cliente_colonia").val(Response.Datos.cliente_colonia);
                    $("#Clientes_cliente_numerointerior").val(Response.Datos.cliente_numerointerior);
                    $("#Clientes_cliente_numeroexterior").val(Response.Datos.cliente_numeroexterior);
                    $("#cliente_codigopostal").val(Response.Datos.cliente_codigopostal);
                    GetColonias(Response.Datos.cliente_codigopostal);
                    setTimeout(function() {
                        $("#Clientes_cliente_colonia").val(Response.Datos.cliente_colonia);
                        $("#Clientes_cliente_municipio").val(Response.Datos.cliente_municipio);
                        $("#Clientes_cliente_entidad").val(Response.Datos.cliente_entidad);
                    }, 1000);
                    $("#Clientes_cliente_municipio").val(Response.Datos.cliente_municipio);
                    $("#Clientes_cliente_entidad").val(Response.Datos.cliente_entidad);
                    $("#Clientes_cliente_pais").val(Response.Datos.cliente_pais);
                    $("#Clientes_cliente_nombre").val(Response.Datos.cliente_nombre);
                    $("#Clientes_cliente_email").val(Response.Datos.cliente_email);
                    $("#Clientes_cliente_telefono").val(Response.Datos.cliente_telefono);
                    $("#Clientes_id_cliente").val(Response.Datos.id_cliente);
                    $("#Clientes_id_empresa").val(Response.Datos.id_empresa);
                    $("#Clientes_cliente_tipo").val(Response.Datos.cliente_tipo);
                    $("#Clientes_cliente_tipo_clasificacion").val(Response.Datos.cliente_tipo_clasificacion);
                    $("#Clientes_cliente_como_trabajarlo").val(Response.Datos.cliente_como_trabajarlo);

                    //mostramos la imagen
                    if (Response.Datos.cliente_logo != '') {
                        $('#imagendiv').show();
                    }
                    $("#imagenpro").attr("src", "../images/clientes/" + Response.Datos.cliente_logo);
                    $("#imagenoriginal").val(Response.Datos.cliente_logo);

                    $("#formmodal").modal('show');
                } else {}
            },
            error: function(e) {
                $.notify('Ocurrio un error inesperado', 'error');
            }
        });
    }
</script>
<?php include 'modal/_form.php'; ?>


<div class="row">
    <div class="col-md-12">
        <h1>Administración de Clientes | <a href="#" class="btn btn-success" id="abrirmodal">
                Agregar Clientes
            </a></h1>

    </div>
    <div class="col-md-12">
        <fieldset>
            <legend>Filtros </legend>
            <form method="GET">
                <div class="col-md-3">
                    <?php echo $form->labelEx($model, 'cliente_tipo'); ?>
                    <?php echo $form->dropDownList(
                        $model,
                        'cliente_tipo',
                        $listatipo,
                        array(
                            'empty' => 'Todos',
                            'class' => 'form-control',
                            'options' => array(
                                isset($_GET['Clientes']['cliente_tipo']) ? $_GET['Clientes']['cliente_tipo'] : '' => array('selected' => true)

                            )
                        )
                    ); ?>
                    <?php echo $form->error($model, 'cliente_tipo'); ?>
                </div>
                <div class="col-md-3">
                    <?php echo $form->labelEx($model, 'como_contacto'); ?>
                    <?php echo $form->dropDownList(
                        $model,
                        'como_contacto',
                        array(
                            'Facebook' => 'Facebook',
                            'Instagram' => 'Instagram',
                            'WhatsApp' => 'WhatsApp',
                            'Llamada a celular' => 'Llamada a celular',
                            'Llamada a telefonos de oficina' => 'Llamada a telefonos de oficina',
                            'Correo electronico' => 'Correo electronico',
                            'TikTok' => 'TikTok',
                            'Google business' => 'Google business',
                        ),
                        array(
                            'empty' => 'Todos',
                            'class' => 'form-control',
                            'options' => array(
                                isset($_GET['Clientes']['como_contacto']) ? $_GET['Clientes']['como_contacto'] : '' => array('selected' => true)

                            )
                        )
                    ); ?>
                    <?php echo $form->error($model, 'como_contacto'); ?>
                </div>


                <div class="col-md-2" style="margin-top: 30px;">
                    <?php echo CHtml::submitButton('Filtrar', array('class' => 'btn btn-success btn-ml')); ?>
                </div>

            </form>
        </fieldset>
    </div>
    <hr>

</div>


<div class="row">
    <div class="col-12">


        <div class="table-responsive">
            <table id="lista" class="table  table-bordered  table-hover ">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <!-- <th>Razon Social</th>
                    <th>RFC</th> -->
                        <th>Agente</th>
                        <th class="noExport">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($lista as $rows) { ?>
                        <tr>
                            <td>
                                <?= $rows->id_cliente ?>
                            </td>
                            <td>
                                <?= $rows->cliente_nombre ?>
                            </td>
                            <td>
                                <?= $rows->cliente_email ?>
                            </td>
                            <td>
                                <?= $rows->cliente_telefono ?>
                            </td>
                            <!--
                    <td><? //=$rows->cliente_razonsocial
                        ?></td>
                    <td><? //=$rows->cliente_rfc
                        ?></td>
                    <td><? //=$rows->rl_listaprecios->listaprecio_nombre
                        ?></td>
                    -->
                            <td>
                                <?= $rows->rl_usuarios->Usuario_Nombre ?>
                            </td>


                            <td class="noExport">

                                <?php
                                echo CHtml::link(
                                    '<i style="font-size: 24px; margin-left:15px" class=" glyphicon glyphicon-edit"></i>',
                                    "javascript:;",
                                    array(
                                        'style' => 'cursor: pointer;',
                                        "onclick" => "Detalles(" . $rows['id_cliente'] . "); return false;"
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

<!-- <? //php
        // echo CHtml::link(
        //     '<i class="fa fa-pencil fa-lg"></i> Editar',
        //     "javascript:;",
        //     array(
        //         'style' => 'cursor: pointer;',
        //         "onclick" => "Actualizar(" . $rows['id_cliente'] . "); return false;"
        //     )
        // );
        // echo CHtml::link(
        //     '<br><i class="fa fa-trash fa-lg"></i> Eliminar',
        //     array('clientes/delete', 'id' => $rows['id_cliente']),
        //     array(
        //         'submit' => array('clientes/delete', 'id' => $rows['id_cliente']),
        //         'class' => 'delete',
        //         'confirm' => 'Seguro que lo deseas eliminar?'
        //     )
        // );
        // echo CHtml::link(
        //     '<br><i class="fa fa-list-alt"></i> Cotizaciones',
        //     "javascript:;",
        //     array(
        //         'style' => 'cursor: pointer;',
        //         "onclick" => "Navegar('cotizaciones'," . $rows['id_cliente'] . "); return false;"
        //     )
        // );
        // echo CHtml::link(
        //     '<br><i class="fa fa-file-powerpoint-o"></i> Pedidos',
        //     "javascript:;",
        //     array(
        //         'style' => 'cursor: pointer;',
        //         "onclick" => "Navegar('pedidos'," . $rows['id_cliente'] . "); return false;"
        //     )
        // );
        // echo CHtml::link(
        //     '<br><i class="fa fa-money"></i> Pendientes de pago',
        //     "javascript:;",
        //     array(
        //         'style' => 'cursor: pointer;',
        //         "onclick" => "Navegar('ppagos'," . $rows['id_cliente'] . "); return false;"
        //     )
        // );


        ?> -->