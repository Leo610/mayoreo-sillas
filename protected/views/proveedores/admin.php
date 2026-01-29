<?php
/* @var $this ProveedoresController */
/* @var $model Proveedores */


$this->pageTitle = 'Administración de Proveedores';
$this->breadcrumbs = array(
    'Modulos' => Yii::app()->createUrl('administracion/modulos'),
    'Proveedores',
);

?>
<script type="text/javascript">
    $(document).ready(function() {

        // Funcion para mostrar el modal
        $("#abrirmodal").click(function() {
            $("#formmodal").modal('show');
            $('#Proveedores-form')[0].reset();
        });

        // Funcion para ordenar la lista de resultados
        $('#lista').DataTable({
            pageLength: 100,
            dom: 'lBfrtip',
            buttons: [{
                extend: 'csv',
                title: 'Exportar',
                text: 'Exportar a EXCEL',
                exportOptions: {
                    columns: ':not(.noExport)'
                }
            }, ]
        });

    });

    function Actualizar(id) {
        var jqxhr = $.ajax({
            url: "<?php echo $this->createUrl("Proveedores/datos"); ?>",
            type: "POST",
            dataType: "json",
            timeout: (120 * 1000),
            data: {
                id: id,
            },
            success: function(Response, newValue) {
                if (Response.requestresult == 'ok') {
                    // Si el resultado es correcto, agregamos los datos al form del modal
                    $("#Proveedores_proveedor_razonsocial").val(Response.Datos.proveedor_razonsocial);
                    $("#Proveedores_proveedor_rfc").val(Response.Datos.proveedor_rfc);
                    $("#Proveedores_proveedor_calle").val(Response.Datos.proveedor_calle);
                    $("#Proveedores_proveedor_colonia").val(Response.Datos.proveedor_colonia);
                    $("#Proveedores_proveedor_numerointerior").val(Response.Datos.proveedor_numerointerior);
                    $("#Proveedores_proveedor_numeroexterior").val(Response.Datos.proveedor_numeroexterior);
                    $("#Proveedores_proveedor_codigopostal").val(Response.Datos.proveedor_codigopostal);
                    $("#Proveedores_proveedor_municipio").val(Response.Datos.proveedor_municipio);
                    $("#Proveedores_proveedor_entidad").val(Response.Datos.proveedor_entidad);
                    $("#Proveedores_proveedor pais").val(Response.Datos.proveedor_pais);
                    $("#Proveedores_proveedor_nombre").val(Response.Datos.proveedor_nombre);
                    $("#Proveedores_proveedor_email").val(Response.Datos.proveedor_email);
                    $("#Proveedores_proveedor_telefono").val(Response.Datos.proveedor_telefono);
                    $("#Proveedores_id_proveedor").val(Response.Datos.id_proveedor);
                    // y posteriormente mostramos el modal 
                    $("#formmodal").modal('show');

                } else {

                }
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
        <h1>Administración de Proveedores | <a href="#" class="btn btn-success" id="abrirmodal">
                Agregar Proveedores
            </a></h1>

        <hr>
        <div class="table-responsive">
            <table id="lista" class="table  table-bordered  table-hover ">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Razon Social</th>
                        <th>RFC</th>
                        <th class="noExport">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($lista as $rows) { ?>
                        <tr>
                            <td>
                                <?= $rows['id_proveedor'] ?>
                            </td>
                            <td>
                                <?= $rows['proveedor_nombre'] ?>
                            </td>
                            <td>
                                <?= $rows['proveedor_email'] ?>
                            </td>
                            <td>
                                <?= $rows['proveedor_telefono'] ?>
                            </td>
                            <td>
                                <?= $rows['proveedor_razonsocial'] ?>
                            </td>
                            <td>
                                <?= $rows['proveedor_rfc'] ?>
                            </td>
                            <td class="noExport">
                                <?php
                                echo CHtml::link(
                                    '<i class="fa fa-pencil fa-lg"></i> Editar',
                                    "javascript:;",
                                    array(
                                        'style' => 'cursor: pointer;',
                                        "onclick" => "Actualizar(" . $rows['id_proveedor'] . "); return false;"
                                    )
                                );


                                echo CHtml::link(
                                    '<br><i class="fa fa-trash fa-lg"></i> Eliminar',
                                    array('Proveedores/delete', 'id' => $rows['id_proveedor']),

                                    array(
                                        'submit' => array('Proveedores/delete', 'id' => $rows['id_proveedor']),
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