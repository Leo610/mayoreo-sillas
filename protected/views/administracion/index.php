<?php
/* @var $this AdministracionController */

$this->pageTitle = 'Oportunidades';
$this->breadcrumbs = array(
    'Oportunidades',
);
?>
<script type="text/javascript">
    $(document).on('ready', function () {

        /*OPORTUNIDADES NORMALES*/
        $(".contenidoetapa").sortable({
            connectWith: ".contenidoetapa",
            handle: ".divoportunidad",
            items: ".itemsamover",


            start: function (event, ui) {/*Columna Inicial*/
                //alert(event);
            },

            receive: function (event, ui) {/*Columna Final*/
                var id_oportunidad = ui.item.attr('idop');
                var id_etapa = $(this).data('idetapa');
                var jqxhr = $.ajax({
                    url: "<?php echo $this->createUrl('administracion/Actualizarcampos'); ?>",
                    type: "POST",
                    dataType: "json",
                    timeout: (120 * 1000),
                    data: {
                        campo: 'id_etapa',
                        valor: id_etapa,
                        id: id_oportunidad,
                    },
                    success: function (Response, newValue) {
                        if (Response.requestresult == 'ok') {
                            $.notify(Response.message, "success");
                        } else {
                            $.notify(Response.message, "error");
                        }
                    },
                    error: function (e) {
                        $.notify(Response.message, "error");
                    }
                });
            }
        });

        /*OPORTUNIDADES RECURRENTES*/
        $(".contenidoetaparecurrente").sortable({
            connectWith: ".contenidoetaparecurrente",
            handle: ".divoportunidadrecurrente",
            items: ".itemsamoverrecurrente",

            start: function (event, ui) {/*Columna Inicial*/
                //alert(event);
            },

            receive: function (event, ui) {/*Columna Final*/
                var id_oportunidad = ui.item.attr('idop');
                var id_etapa = $(this).data('idetapa');
                var jqxhr = $.ajax({
                    url: "<?php echo $this->createUrl('administracion/Actualizarcampos'); ?>",
                    type: "POST",
                    dataType: "json",
                    timeout: (120 * 1000),
                    data: {
                        campo: 'id_etapa',
                        valor: id_etapa,
                        id: id_oportunidad,
                    },
                    success: function (Response, newValue) {
                        if (Response.requestresult == 'ok') {
                            $.notify(Response.message, "success");
                        } else {
                            $.notify(Response.message, "error");
                        }
                    },
                    error: function (e) {
                        $.notify(Response.message, "error");
                    }
                });
            }
        });

        // Funcion para mostrar el modal
        $(".abrirmodal").click(function () {
            var modal = $(this).data('idmodal');
            $(modal).modal('show');
        });

    });
</script>
<?php
$opmenu = 1;
include_once 'menu/menu.php';
include_once 'modals/modal.agregaroportunidad.php';
include 'modals/_form.php';

?>
<div class="row">
    <div class="col-md-12 oportunidades">
        <h1 class="mt-md mb-md"><?= $this->pageTitle ?> | <button type="button" class="btn btn-success abrirmodal"
                data-idmodal="#agergaroportunidadmodal">Crear Oportunidad</button></h1>
        <?php
        // foreach para obtener las etapas
        $cantidadetapas = count($Etapas);
        $widthdiv = 100 / $cantidadetapas - 2;
        foreach ($Etapas as $rows) { ?>
            <div class="divetapas" style="width:<?= $widthdiv ?>%;">
                <div class="tituloetapa">
                    <p class="mb-none tituloetapa"><?= $rows->nombre ?></p>
                </div>
                <div class="contenidoetapa" data-idetapa="<?= $rows->id ?>" id="<?= $rows->id ?>">
                    <?php
                    $totaletapa = 0;
                    // Obtenemos las oportunidades en esa etapa
                    $oportunidadesall = $this->ObtenerOportunidades($rows->id);
                    foreach ($oportunidadesall as $oprows) {
                        // Verificamos si la oportunidad lleva mas de los dias permitidos en esta etapa
                        $Fechaaltaetapa = $this->ObtenerTiempoEtapa($rows->id, $oprows['id']);
                        $Fechalimiteetapa = $this->Sumardiasfecha($Fechaaltaetapa, $rows->duracion_dias) . ' ' . date('H:i:s', strtotime($Fechaaltaetapa));
                        if (strtotime($Fechalimiteetapa) < strtotime(date('Y-m-d H:i:s'))) {
                            $class = 'vencidoop';
                        } else {
                            $class = '';
                        }
                        ?>
                        <a href="<?= Yii::app()->createURL('administracion/crmver/' . $oprows['id']) ?>"
                            title="<?= $oprows->nombre ?>" style="display:block" idop="<?= $oprows->id ?>"
                            class="itemsamover <?= $class ?>">
                            <div class="divoportunidad" id_oportunidad="<?= $oprows->id ?>" data-idoportunidad="<?= $oprows->id ?>"
                                id="<?= $oprows->id ?>">
                                <small># <?= $oprows->id ?>
                                    <?php if ($oprows->id_cliente != '' && $oprows->rl_clientes->cliente_nombre != '') {
                                        echo $oprows->rl_clientes->cliente_nombre;
                                    } ?>
                                    <?php if ($oprows->valor_negocio != '') {
                                        echo '- $ ' . number_format($oprows->valor_negocio, 2);
                                    } ?>
                                </small>
                                <p class="mb-none">
                                    <?= $oprows->nombre ?>
                                    <?php
                                    $AccionSig = $this->ObtenerSigAccion($oprows['id']);
                                    if (!empty($AccionSig)) {
                                        echo '<br>' . $AccionSig['rl_crmaccion']['crm_acciones_nombre'] . ' el ' . $AccionSig['crm_detalles_fecha'];
                                    } else {
                                        echo '<br>Sin Acción';
                                    }
                                    // Verificamos si el usuario en sesion no es el de la oportunidad mostramos de quien es
                                    if (Yii::app()->user->id != $oprows['id_usuario']) {
                                        echo '<br>De <strong>' . $oprows['rl_usuario']['Usuario_Nombre'] . '</strong>';
                                    }
                                    ?>
                                </p>
                            </div>
                        </a>
                        <?php
                        // Sumatorias
                        $totaletapa = $oprows->valor_negocio + $totaletapa;
                    } ?>
                </div>
                <div class="sumatoria">
                    SUMA $
                    <?= number_format($totaletapa, 2) ?>
                </div>
            </div>
        <?php }
        ?>
    </div>
</div>



<div class="row">
    <div class="col-md-12  oportunidadesrecurrentes">
        <hr>
        <h1 class="mt-md mb-md">Oportunidades Recurrentes</h1>
        <?php
        // foreach para obtener las etapas
        $cantidadetapas = count($Etapas);
        $widthdiv = 100 / $cantidadetapas - 2;
        foreach ($Etapas as $rows) { ?>
            <div class="divetapasrecurrentes" style="width:<?= $widthdiv ?>%;">
                <div class="tituloetapa">
                    <p class="mb-none tituloetapa"><?= $rows->nombre ?></p>
                </div>
                <div class="contenidoetaparecurrente" data-idetapa="<?= $rows->id ?>" id="<?= $rows->id ?>">
                    <?php
                    $totaletapa = 0;
                    // Obtenemos las oportunidades en esa etapa
                    $oportunidadesall = $this->ObtenerOportunidades($rows->id, 1);
                    foreach ($oportunidadesall as $oprows) {
                        // Verificamos si la oportunidad lleva mas de los dias permitidos en esta etapa
                        $Fechaaltaetapa = $this->ObtenerTiempoEtapa($rows->id, $oprows['id']);
                        $Fechalimiteetapa = $this->Sumardiasfecha($Fechaaltaetapa, $rows->duracion_dias) . ' ' . date('H:i:s', strtotime($Fechaaltaetapa));
                        if (strtotime($Fechalimiteetapa) < strtotime(date('Y-m-d H:i:s'))) {
                            $class = 'vencidoop';
                        } else {
                            $class = '';
                        }
                        ?>
                        <a href="<?= Yii::app()->createURL('administracion/crmver/' . $oprows['id']) ?>"
                            title="<?= $oprows->nombre ?>" style="display:block" idop="<?= $oprows->id ?>"
                            class="itemsamoverrecurrente <?= $class ?>">
                            <div class="divoportunidadrecurrente" id_oportunidad="<?= $oprows->id ?>"
                                data-idoportunidad="<?= $oprows->id ?>" id="<?= $oprows->id ?>">
                                <small># <?= $oprows->id ?>
                                    <?php if ($oprows->id_cliente != '' && $oprows->rl_clientes->cliente_nombre != '') {
                                        echo $oprows->rl_clientes->cliente_nombre;
                                    } ?>
                                    <?php if ($oprows->valor_negocio != '') {
                                        echo '- $ ' . number_format($oprows->valor_negocio, 2);
                                    } ?>
                                </small>
                                <p class="mb-none">
                                    <?= $oprows->nombre ?>
                                    <?php
                                    $AccionSig = $this->ObtenerSigAccion($oprows['id']);
                                    if (!empty($AccionSig)) {
                                        echo '<br>' . $AccionSig['rl_crmaccion']['crm_acciones_nombre'] . ' el ' . $AccionSig['crm_detalles_fecha'];
                                    } else {
                                        echo '<br>Sin Acción';
                                    }
                                    // Verificamos si el usuario en sesion no es el de la oportunidad mostramos de quien es
                                    if (Yii::app()->user->id != $oprows['id_usuario']) {
                                        echo '<br>De <strong>' . $oprows['rl_usuario']['Usuario_Nombre'] . '</strong>';
                                    }
                                    ?>
                                </p>
                            </div>
                        </a>
                        <?php
                        // Sumatorias
                        $totaletapa = $oprows->valor_negocio + $totaletapa;
                    } ?>
                </div>
                <div class="sumatoria">
                    SUMA $
                    <?= number_format($totaletapa, 2) ?>
                </div>
            </div>
        <?php }
        ?>
    </div>
</div>