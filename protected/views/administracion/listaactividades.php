<?php
/* @var $this AdministracionController */

$this->pageTitle = 'Actividades';
$this->breadcrumbs = array(
    'Actividades',
);
?>
<script type="text/javascript">
    $(document).ready(function () {
        // Funcion para ordenar la lista de resultados
        $('#lista').DataTable({
            pageLength: 100,
            "order": [[4, "desc"]],
        });
        // Funcion para mostrar el modal
        $(".abrirmodal").click(function () {
            var modal = $(this).data('idmodal');
            $(modal).modal('show');
        });
    })
</script>
<?php
$opmenu = 2;
include_once 'menu/menu.php';
include_once 'modals/modal.agregaraccion.php' ?>
<div class="row">
    <div class="col-md-12">
        <h1 class="m-md">
            <?= $this->pageTitle ?> | <button type="button" class="btn btn-success abrirmodal"
                data-idmodal="#agregaraccion">Crear Actividad</button>
        </h1>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="lista" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Oportunidad</th>
                        <th>Cliente</th>
                        <th>Acción</th>
                        <th>Fecha</th>
                        <th>Estatus</th>
                        <th>Agente</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($listaactividades as $rows) {
                        // Verificamos si la accion ya esta vencida, para mostrarlo en color rojo.
                        if (strtotime($rows->crm_detalles_fecha) < strtotime(date('Y-m-d H:i:s')) && $rows->estatus == "NO REALIZADO") {
                            $class = 'vencido';
                        } else {
                            $class = '';
                        }
                        ?>
                        <tr class="<?= $class ?>">
                            <td>
                                <?= $rows->id_crm_detalle ?>
                            </td>
                            <td>
                                <a
                                    href="<?= Yii::app()->createurl('administracion/crmver/' . $rows->rl_oportunidad->id) ?>">
                                    #
                                    <?= $rows->rl_oportunidad->id ?>
                                    <?= $rows->rl_oportunidad->nombre ?>
                            </td>
                            <td>
                                <?= $rows->rl_cliente->cliente_nombre ?>
                            </td>
                            <td>
                                <?= $rows->rl_crmaccion->crm_acciones_nombre ?>
                            </td>
                            <td>
                                <?= $rows->crm_detalles_fecha ?>
                            </td>
                            <td>
                                <?= $rows->estatus ?>
                            </td>
                            <td>
                                <?= $rows->rl_usuarios->Usuario_Nombre ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>