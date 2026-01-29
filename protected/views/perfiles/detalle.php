<?php
/* @var $this PerfilesController */
/* @var $model Perfiles */


$this->pageTitle = 'Administración de Permisos';
$this->breadcrumbs = array(
    'Mantenimiento' => Yii::app()->createUrl('mantenimiento/index'),
    'Perfiles',
);


?>
<script type="text/javascript">
    $(document).ready(function () {

        $('#lista').DataTable({
            pageLength: 100,
            "order": [[0, "asc"]],
        });

        // Metodo para generar la comision del usuario
        $("body").on("click", ".cambiarpermiso", function () {
            var id_perfil = $(this).data("idperfil");
            var id_actividad = $(this).data("idactividad");
            var valor = $(this).is(":checked");
            if (valor) {
                var valor = 1;
            } else {
                var valor = 0;
            }

            var jqxhr = $.ajax({
                url: "<?php echo $this->createUrl("perfiles/agregaractividad"); ?>",
                type: "POST",
                dataType: "json",
                timeout: (120 * 1000),
                data: {
                    id_perfil: id_perfil,
                    id_actividad: id_actividad,
                    valor: valor
                },
                success: function (Response) {
                    if (Response.requestresult == 'ok') {
                        $.notify(Response.message, 'success');
                    } else {
                        $.notify(Response.message, 'error');
                    }
                },
                error: function (e) {
                    $.notify('Ocurrio un error inesperado', 'error');
                }
            });
        });

    });
</script>

<div class="row">
    <div class="col-md-12">
        <h1>
            <?= $this->pageTitle ?>
        </h1>
        <form method="get">
            <select name="id_perfil" id="id_perfil" class="form-control" onchange="this.form.submit()">
                <option>-- Seleccione el perfil --</option>
                <?php foreach ($ListaPerfiles as $key => $value) { ?>
                    <option value="<?= $key ?>" <?= ($id_perfil == $key) ? 'selected' : ''; ?>>
                        <?= $value ?>
                    </option>
                <?php } ?>
            </select>
        </form>
        <hr>
        <?php if ($id_perfil != 0) { ?>
            <div class="table-responsive">
                <table id="lista" class="table table-bordered table-hover table-striped" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripcion</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($ListaActividades as $rows) {
                            $valor = $this->VerificarAccesoP($rows->id_actividades, $id_perfil);
                            ?>
                            <tr>
                                <td>
                                    <?= $rows->id_actividades ?>
                                </td>
                                <td>
                                    <?= $rows->nombre ?>
                                </td>
                                <td>
                                    <?= $rows->descripcion ?>
                                </td>
                                <td class="">
                                    <input type="checkbox" name="permiso" value="1" data-idperfil="<?= $id_perfil ?>"
                                        data-idactividad="<?= $rows->id_actividades ?>" class="form-control cambiarpermiso"
                                        <?= ($valor == 1) ? 'checked' : ''; ?>>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    </div>
</div>