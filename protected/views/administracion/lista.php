<?php
/* @var $this AdministracionController */
$this->pageTitle = 'Lista de prospectos';

$this->breadcrumbs = array(
    'Administracion' => array('/administracion'),
    'Lista',
);
?>
<script type="text/javascript">
    $(document).ready(function () {
        // Funcion para ordenar la lista de resultados
        $('#listaprospectos').DataTable({
            pageLength: 100
        });
        // Funcion para mostrar el modal
        $(".abrirmodal").click(function () {
            var modal = $(this).data('idmodal');
            $(modal).modal('show');
        });
    });

</script>
<?php
$opmenu = 3;
include 'menu/menu.php';
include_once 'modals/modal.agregarprospecto.php';
?>
<div class="row">
    <div class="col-md-9">
        <h1 class="m-md">Lista de Prospectos | <button type="button" class="btn btn-success abrirmodal"
                data-idmodal="#agregarprospecto">Agregar Prospecto</button></h1>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="listaprospectos" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Agente</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($listaprospectos as $rows) { ?>
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
                            <td>
                                <?= $rows->rl_usuarios->Usuario_Nombre ?>
                            </td>
                            <td class=""> </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>