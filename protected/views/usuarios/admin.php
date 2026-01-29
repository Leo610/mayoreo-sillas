<?php
/* @var $this UsuariosController */
/* @var $model Productos */


$this->pageTitle = 'Administración de Usuarios';
$this->breadcrumbs = array(
    'Modulos' => Yii::app()->createUrl('administracion/modulos'),
    'Usuarios',
);


?>
<script type="text/javascript">
    $(document).ready(function () {

        // Funcion para mostrar el modal
        $("#abrirmodal").click(function () {
            $("#formmodal").modal('show');
            $('#usuarios-form')[0].reset();
        });

        // Funcion para ordenar la lista de resultados
        $('#lista').DataTable({
            pageLength: 100
        });

        // modal para cambiar la contraseña
        $(".abrirmodalpswd").click(function () {
            $('#usuariospswdform')[0].reset();
            var email = $(this).data('email');
            var id = $(this).data('id');
            $("#usuariospswdform #Usuarios_ID_Usuario").val(id);
            $("#usuariospswdform #Usuarios_Usuario_Email").val(email);
            $("#usuariospswdform #Usuarios_Usuario_Password").val('');
            $("#formmodalpswd").modal('show');

        });

    });

    function Actualizar(id) {
        var jqxhr = $.ajax({
            url: "<?php echo $this->createUrl("Usuarios/Usuariodatos"); ?>",
            type: "POST",
            dataType: "json",
            timeout: (120 * 1000),
            data: {
                id: id,
            },
            success: function (Response, newValue) {
                if (Response.requestresult == 'ok') {
                    $.notify(Response.message, "success");
                    // Si el resultado es correcto, agregamos los datos al form del modal
                    $("#usuarioseditar-form #Usuarios_ID_Usuario").val(Response.Datos.ID_Usuario);
                    $("#usuarioseditar-form #Usuarios_Usuario_Nombre").val(Response.Datos.Usuario_Nombre);
                    $("#usuarioseditar-form #Usuarios_Usuario_Email").val(Response.Datos.Usuario_Email);

                    $("#usuarioseditar-form #Usuarios_level").val(Response.Datos.level);
                    $("#usuarioseditar-form #Usuarios_id_perfil").val(Response.Datos.id_perfil).trigger('change');
                    $("#usuarioseditar-form #Usuarios_equipo_venta").val(Response.Datos.equipo_venta).trigger('change');
                    $("#usuarioseditar-form #Usuarios_ubicacion").val(Response.Datos.ubicacion).trigger('change');
                    $("#usuarioseditar-form #Usuarios_mercado").val(Response.Datos.mercado).trigger('change');
                    $("#usuarioseditar-form #Usuarios_zona").val(Response.Datos.zona).trigger('change');
                    $("#usuarioseditar-form #Usuarios_id_usuario_padre").val(Response.Datos.id_usuario_padre).trigger('change');
                    $("#usuarioseditar-form #Usuarios_bodega").val(Response.Datos.bodega).trigger('change');


                    // y posteriormente mostramos el modal 
                    $("#formmodaleditar").modal('show');
                } else {
                    $.notify(Response.message, "error");
                }
            },
            error: function (e) {
                $.notify(Response.message, "error");
            }
        });
    }
</script>
<?php include 'modal/_form.php'; ?>
<?php include 'modal/_formeditar.php'; ?>
<?php include 'modal/_formpsdw.php'; ?>

<div class="row">
    <div class="col-md-12">
        <h1>Administración de Usuarios | <a href="#" class="btn btn-success" id="abrirmodal">
                Agregar Usuario
            </a></h1>

        <hr>
        <div class="table-responsive">
            <table id="lista" class="table  table-bordered  table-hover ">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Perfil</th>
                        <th>Email</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($lista as $rows) { ?>
                        <tr>
                            <td>
                                <?= $rows['ID_Usuario'] ?>
                            </td>
                            <th>
                                <?= $rows['Usuario_Nombre'] ?>
                            </th>
                            <td>
                                <?= $rows['rl_perfil']['nombre'] ?>
                            </td>
                            <td>
                                <?= $rows['Usuario_Email'] ?>
                            </td>

                            <td class="">
                                <?php

                                echo CHtml::link(
                                    '<i class="fa fa-lock fa-lg" style="color:#000"></i> Contraseña',
                                    "javascript:;",
                                    array(
                                        'style' => 'cursor: pointer;',
                                        'class' => 'abrirmodalpswd',
                                        'data-email' => $rows['Usuario_Email'],
                                        'data-id' => $rows['ID_Usuario']
                                    )
                                );

                                echo CHtml::link(
                                    '<br><i class="fa fa-pencil fa-lg"></i> Editar',
                                    "javascript:;",
                                    array(
                                        'style' => 'cursor: pointer;',

                                        "onclick" => "Actualizar(" . $rows['ID_Usuario'] . "); return false;"
                                    )
                                );


                                echo CHtml::link(
                                    '<br><i class="fa fa-trash fa-lg"></i> Eliminar',
                                    array('usuarios/delete', 'id' => $rows['ID_Usuario']),
                                    array(
                                        'submit' => array('usuarios/delete', 'id' => $rows['ID_Usuario']),
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