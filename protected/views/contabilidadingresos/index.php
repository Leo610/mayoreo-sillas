<?php
/* @var $this ContabilidadingresosController */
$this->pageTitle = 'Lista de ingresos';

$this->breadcrumbs = array(
    'Lista de ingresos'
);
// $cot = 'Lista de ingresos';
// if (isset($_GET['id_cliente']) && !empty(trim($_GET['id_cliente']))) {
//     $this->renderpartial('//clientes/menu', array('opcionmenu' => 5));
//     $cliente = Clientes::model()->find('id_cliente =' . $_GET['id_cliente']);
//     $cot = 'Ingresos del ' . $cliente['cliente_nombre'];
// }
?>
<script type="text/javascript">
    $(document).ready(function() {
        // Funcion para ordenar la lista de resultados, lo ordena por la columna 3, ya que empieza 0,1,2,3,4 y muestra  5 resultados, solamente
        $('#contabilidadingresos').DataTable({
            pageLength: 100,
            "order": [
                [0, "desc"]
            ]
        });
    });
    // Actualizar el precio del producto
    function confirmarIngreso(id, valor) {
        if (valor == 0) {
            return false;
        }
        // if (!confirm('Favor de confirmar')) {
        //     return false;
        // }


        let dato = prompt('Ingrese el numero de movimineto');
        if (dato == "") {
            console.log('aca');
            $.notify("Para confirmar debe ingresar un número", "error");
            return false;
        }
        // actualizamos el precio con ajax
        var jqxhr = $.ajax({
            url: "<?php echo $this->createUrl("contabilidadingresos/confirmar"); ?>",
            type: "POST",
            dataType: "json",
            timeout: (120 * 1000),
            data: {
                id: id,
                dato: dato
            },
            success: function(Response, newValue) {
                if (Response.requestresult == 'ok') {
                    // Si el resultado es correcto, le ingresamos el valor
                    $.notify("Se actualizo correctamente", "success");

                } else if (Response.requestresult == 'fail') {
                    $.notify(Response.message, "error");

                } else {
                    $.notify("Verifica los campos e intente de nuevo", "error");
                }
            },
            error: function(e) {
                $.notify('Ocurrio un error inesperado', 'error');
            }
        });

    }
</script>
<div class="row">
    <div class="col-md-8">
        <h1>
            Lista de ingresos | <a href="<?php echo Yii::app()->createUrl('contabilidadingresos/pendientespago') ?>"
                class=" btn btn-success">
                Proyectos pendientes de cobro
            </a>
        </h1>
    </div>
</div>
<div class="col-md-12">
    <fieldset>
        <legend>Filtros</legend>
        <form>
            <div class="col-md-3">
                <?php $this->widget(
                    'zii.widgets.jui.CJuiDatePicker',
                    array(
                        'name' => 'fechainicio',
                        'language' => 'es',
                        'htmlOptions' => array(
                            'readonly' => "readonly",
                            'class' => 'form-control'
                        ),
                        'options' => array(
                            'dateFormat' => 'yy-mm-dd',
                        ),
                        'value' => $fechainicio
                    )
                ); ?>
            </div>
            <div class="col-md-3">
                <?php $this->widget(
                    'zii.widgets.jui.CJuiDatePicker',
                    array(
                        'name' => 'fechafin',
                        'language' => 'es',
                        'htmlOptions' => array(
                            'readonly' => 'readonly',
                            'class' => 'form-control'
                        ),
                        'options' => array(
                            'dateFormat' => 'yy-mm-dd',

                        ),
                        'value' => $fechafin
                    )
                ); ?>
            </div>
            <div class="col-md-3">
                <?php
                $this->widget(
                    'zii.widgets.jui.CJuiAutoComplete',
                    array(
                        'name' => 'nombreusuario',
                        'source' => $this->createUrl('contabilidadingresos/buscarusuario'),
                        // Opciones javascript adicionales para el plugin
                        'options' => array(
                            'minLength' => '3',
                            'select' => 'js:function(event, ui) {
                                        console.log(ui);
                                            $("#id_usuarioi").val(ui.item.id);
                                            
                 	            }',
                            'focus' => 'js:function(event, ui) {
                                 return false;
                                }'
                        ),
                        'value' => $nombre,
                        'htmlOptions' => array(
                            'class' => 'form-control',
                            'placeholder' => 'Buscar Vendedor'
                        )
                    )
                );
                ?>
            </div>
            <div class="col-md-3 ">
                <select name="id_bodega" class="form-control">
                    <option value="0">
                        -- Todas las bodegas --
                    </option>
                    <?php foreach ($bodegas as $row) { ?>
                        <option value="<?= $row['id'] ?>" <?= ($id_bodega == $row['id']) ? 'selected' : ''; ?>><?= $row['name'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <input type="hidden" name="id_usuario" id="id_usuarioi">
            <div class="col-md-2">
                <?php echo CHtml::submitButton('Filtro', array('class' => 'btn btn-secondary btn-ml')); ?>
            </div>
        </form>
    </fieldset>
</div>

<div id="scroll-container-fixedcoin"
    style="overflow-x: scroll;height:50px;position: fixed;bottom: 1%; left: 0;z-index: 100;width: 100%; padding:0 10px ;">
    <div id="scroll-elem-fixedcoin" style=" height:100%; padding: 10px">
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive tablescrolcoin">
            <table id="contabilidadingresos" class="table  table-bordered  table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Identificador</th>
                        <th>Forma de Pago</th>
                        <th>Banco</th>
                        <th>Moneda</th>
                        <th>Usuario</th>
                        <th>Cantidad</th>
                        <th>Fecha</th>
                        <th>Pdf</th>
                        <th>Confirmado?</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($ListaIngresos as $rows) { ?>
                        <tr>
                            <td>
                                <?= $rows->id_contabilidad_ingresos ?>
                            </td>
                            <td>
                                <?= $this->ObtenerCliente2($rows->contabilidad_ingresos_identificador);

                                ?>

                            </td>
                            <td>
                                <?= $rows->contabilidad_ingresos_identificador ?>
                            </td>
                            <td>
                                <!-- <//?= $rows['rl_formasdepago']['formapago_nombre'] ?> -->
                                <select
                                    class="form-control input-sm formaPagoSelect"
                                    data-id="<?= $rows->id_contabilidad_ingresos ?>">
                                    <?php foreach ($formapago as $row) { ?>
                                        <option value="<?= $row['id_formapago'] ?>"
                                            <?= ($rows['id_formapago'] == $row['id_formapago']) ? 'selected' : '' ?>>
                                            <?= $row['formapago_nombre'] ?>
                                        </option>
                                    <?php } ?>
                                </select>


                            </td>
                            <td>
                                <?= $rows['rl_banco']['banco_nombre'] ?>
                            </td>
                            <td>
                                <?= $rows['rl_moneda']['moneda_nombre'] ?>
                            </td>
                            <td>
                                <?= $rows['rl_usuario']['Usuario_Nombre'] ?>
                            </td>
                            <td>$
                                <?= number_format($rows->contabilidad_ingresos_cantidad, 2) ?>
                            </td>
                            <td>
                                <?= $rows->contabilidad_ingresos_fechaalta ?>
                            </td>
                            <td>
                                <?php
                                echo CHtml::link('<i class="fa fa-file-pdf-o"></i>', array('contabilidadingresos/pdf?id=' . $rows->id_contabilidad_ingresos), array('class' => "btn btn-danger", 'style' => 'margin-left:2px', 'target' => '_blank'));
                                ?>
                            </td>
                            <td>
                                <?php if ($permiso == 0) { ?>
                                    No cuenta con permiso para confirmar el ingreso.
                                <?php } else if ($rows['ingreso_confirmado'] == 1) {
                                    echo 'Confirmado por <b>' . $rows['rl_usuario_confirma']['Usuario_Nombre'] . '</b><br> el <b>' . $rows['confirmado_fecha'] . '</b>';
                                    if (!empty($rows['no_banca'])) {
                                        echo '<br> Número de movimiento: <b>' . $rows['no_banca'] . '</b>';
                                    }
                                } else { ?>
                                    <select class="form-control input-sm"
                                        onchange="confirmarIngreso(<?= $rows->id_contabilidad_ingresos ?>,this.value)">
                                        <option value="0">No</option>
                                        <option value="1">Confirmado</option>
                                    </select>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        const tableWidth = $('#contabilidadingresos').width();
        // asignamos el anchotabla
        const scroll = document.querySelector("#scroll-elem-fixedcoin");
        scroll.style.width = (tableWidth + 15) + 'px';
        document.querySelector("#scroll-container-fixedcoin").addEventListener("scroll", function() {
            document.querySelector(".tablescrolcoin").scrollLeft = this.scrollLeft;
        })
    })


    $(document).on('change', '.formaPagoSelect', function() {
        const idIngreso = $(this).data('id');
        const idFormaPago = this.value;

        // console.log('id ingreso ', idIngreso, ' id fomra pago ', idFormaPago);
        // return;

        // valida por si acaso
        if (!idIngreso || !idFormaPago) return;

        $.ajax({
            url: "<?= $this->createUrl('contabilidadingresos/actualizarformapago'); ?>",
            type: "POST",
            dataType: "json",
            timeout: 12000,
            data: {
                idIngreso,
                idFormaPago
            },
            success: function(resp) {
                if (resp.requestresult === 'ok') {
                    $.notify(resp.message || 'Forma de pago actualizada', "success");
                } else {
                    $.notify(resp.message || 'No fue posible actualizar', "error");
                }
            },
            error: function() {
                $.notify("Error de comunicación, intenta de nuevo", "error");
            }
        });
    });
</script>