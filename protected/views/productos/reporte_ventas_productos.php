<?php
/* @var $this ClientesController */
/* @var $model Clientes */

$this->pageTitle = 'Reporte venta de productos';
$this->breadcrumbs = array(
    'Productos' => Yii::app()->createUrl('productos/admin'),
    'Reporte venta productos',
);
$meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
?>

<!-- CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        console.log('Página cargada, iniciando...');

        // ============================================
        // 1. INICIALIZAR SELECT2 CON TIMEOUT
        // ============================================
        setTimeout(function() {
            try {
                // Destruir select2 si ya existe
                if ($('#categorias').hasClass("select2-hidden-accessible")) {
                    $('#categorias').select2('destroy');
                }

                // Verificar que hay opciones en el select
                var optionsCount = $('#categorias option').length;
                console.log('Opciones disponibles en select:', optionsCount);

                // Inicializar select2
                $('#categorias').select2({
                    placeholder: 'Seleccionar categorías',
                    allowClear: true,
                    width: '100%',
                    language: {
                        noResults: function() {
                            return "Sin resultados";
                        }
                    }
                });

                console.log('Select2 inicializado correctamente');
            } catch (e) {
                console.error('Error al inicializar select2:', e);
            }
        }, 100);

        // ============================================
        // 2. INICIALIZAR DATATABLE (solo si existe)
        // ============================================
        setTimeout(function() {
            try {
                // Tabla "todo"
                if ($('#reportesventasp_todo').length > 0) {
                    if ($.fn.DataTable.isDataTable('#reportesventasp_todo')) {
                        $('#reportesventasp_todo').DataTable().destroy();
                    }
                    $('#reportesventasp_todo').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "searching": true,
                        dom: 'Bfrtip',
                        buttons: [{
                                extend: 'csv',
                                title: 'Reporte de ventas productos',
                                text: 'Exportar a EXCEL'
                            },
                            {
                                extend: 'pdfHtml5',
                                title: 'Reporte de ventas productos',
                                text: 'Exportar a PDF'
                            },
                            {
                                extend: 'print',
                                title: 'Reporte de ventas productos',
                                text: 'Imprimir'
                            }
                        ]
                    });
                    console.log('DataTable "todo" inicializada');
                }

                // Tabla mensual
                if ($('#reportesventasp').length > 0) {
                    if ($.fn.DataTable.isDataTable('#reportesventasp')) {
                        $('#reportesventasp').DataTable().destroy();
                    }
                    $('#reportesventasp').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "searching": true,
                        dom: 'Bfrtip',
                        buttons: [{
                                extend: 'csv',
                                title: 'Reporte de ventas productos',
                                text: 'Exportar a EXCEL'
                            },
                            {
                                extend: 'pdfHtml5',
                                title: 'Reporte de ventas productos',
                                text: 'Exportar a PDF'
                            },
                            {
                                extend: 'print',
                                title: 'Reporte de ventas productos',
                                text: 'Imprimir'
                            }
                        ]
                    });
                    console.log('DataTable mensual inicializada');
                }
            } catch (e) {
                console.error('Error al inicializar DataTable:', e);
            }
        }, 150);

        // ============================================
        // 3. EVENTOS
        // ============================================
        $('.todo').on('click', function() {
            window.location.href = "<?php echo $this->createUrl('productos/reporteventas'); ?>" + "?todo=todo";
        });

        $('.mes').on('click', function() {
            $('.mes').css('border', 'none');
            var id = $(this).data("id");
            $('.mes[data-id="' + id + '"]').css('border', '2px solid #0057a6');
            $('#mes').val(id);
        });

        carga();
    });

    function carga() {
        <?php
        if (!empty($anio) && !empty($mes)) {
            echo "var anio = " . json_encode($anio) . ";\n";
            echo "var mes = " . json_encode($mes) . ";\n";
        } else {
            echo "var anio = null;\n";
            echo "var mes = null;\n";
        }
        ?>

        if (anio && mes) {
            console.log('Año:', anio);
            console.log('Mes:', mes);
            $('.mes[data-id="' + mes + '"]').css('border', '2px solid #0057a6');
        }
    }
</script>

<style>
    .boton {
        margin-bottom: 0px;
        cursor: pointer;
        border-radius: 15px 15px 15px 15px;
        box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.3);
        padding-left: 15px;
        padding-right: 15px;
        padding-top: 10px;
        padding-bottom: 10px;
        color: black;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <h1>Reporte de venta de productos</h1>
        <div class="row">
            <div class="col-md-12">
                <fieldset>
                    <legend>Filtros</legend>
                    <form method="get" action="<?php echo $this->createUrl("productos/reporteventas"); ?>">
                        <div class="col-md-12">
                            <p><b>Mes</b></p>
                        </div>
                        <div class="col-md-12" style="display: flex; flex-direction: row; justify-content: space-between; padding-bottom: 15px;">
                            <?php
                            $num = 0;
                            foreach ($meses as $mesNombre) {
                                $num = $num + 1;
                            ?>
                                <p data-id="<?= $num ?>" class="boton mes">
                                    <?= $mesNombre ?>
                                </p>
                            <?php } ?>
                        </div>
                        <div class="col-md-3">
                            <p><b>Año</b></p>
                            <select class="form-control" name="anio">
                                <?php foreach ($anios as $anioo) { ?>
                                    <option value="<?php echo $anioo['anio'] ?>" <?php echo ($anioo['anio'] == $anio) ? 'selected' : '' ?>>
                                        <?php echo $anioo['anio'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <p><b>Ventas por bodega</b></p>
                            <select class="form-control" name="ventaxbodega">
                                <option value="0">Seleccionar opción</option>
                                <?php foreach ($listabodegas as $id => $nombre) { ?>
                                    <option value="<?php echo $id; ?>" <?php echo ($id == $ventaxbodega) ? 'selected' : '' ?>>
                                        <?php echo CHtml::encode($nombre); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <p><b>Ventas por vendedor</b></p>
                            <select class="form-control" name="ventaxusu">
                                <option value="0">Seleccionar opción</option>
                                <?php foreach ($vendedores as $id => $Usuario_Nombre) { ?>
                                    <option value="<?php echo $id; ?>" <?php echo ($id == $ventaxusu) ? 'selected' : '' ?>>
                                        <?php echo CHtml::encode($Usuario_Nombre); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <p><b>Categorías</b></p>
                            <select class="form-control" name="categorias[]" id="categorias" multiple="multiple">
                                <?php
                                if (!empty($valoresCategorias)) {
                                    $categoriasSeleccionadas = isset($_GET['categorias']) && is_array($_GET['categorias'])
                                        ? $_GET['categorias']
                                        : [];

                                    foreach ($valoresCategorias as $id => $nombre):
                                ?>
                                        <option value="<?php echo htmlspecialchars($id); ?>"
                                            <?php echo in_array($id, $categoriasSeleccionadas) ? 'selected="selected"' : ''; ?>>
                                            <?php echo htmlspecialchars($nombre); ?>
                                        </option>
                                <?php
                                    endforeach;
                                } else {
                                    echo '<option value="">No hay categorías disponibles</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <input type="hidden" name="mes" id="mes">

                        <div class="col-md-12" style="padding-top: 15px;">
                            <?php echo CHtml::submitButton('Buscar', array('class' => 'btn btn-success btn-ml')); ?>
                        </div>
                    </form>

                    <div class="col-md-2" style="padding-top: 15px;">
                        <p class="boton todo" style="text-align: center;">Mostrar todo el año</p>
                    </div>
                </fieldset>
            </div>
        </div>

        <?php if (!empty($todo)) { ?>
            <div class="table-responsive">
                <table id="reportesventasp_todo" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Bodega de Fabricacion</th>
                            <th>Precio</th>
                            <th>Total cantidad</th>
                            <th>Total vendido</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($productos as $pp) {
                            $result = $this->todo($pp['id_producto']);
                        ?>
                            <tr>
                                <td><?= $pp['producto_nombre'] ?></td>
                                <td><?= $pp['rl_catalogosrecurrentes']['nombre'] ?></td>
                                <?php if (!empty($result)) { ?>
                                    <td>$<?= number_format($result[0]['precio'], 2) ?></td>
                                    <td><?= $result[0]['cantidad'] ?></td>
                                    <td>$<?= number_format($result[0]['total'], 2) ?></td>
                                <?php } else { ?>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>

        <?php if (!empty($udia)) { ?>
            <div class="table-responsive">
                <table id="reportesventasp" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Bodega</th>
                            <th>Precio</th>
                            <?php
                            for ($columna = 1; $columna <= $udia; $columna++) {
                            ?>
                                <th><?= $columna ?></th>
                            <?php } ?>
                            <th>Total</th>
                            <th>Total vendido</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $precioActual = null;
                        foreach ($productos as $pp) { ?>
                            <tr>
                                <td><?= $pp['producto_nombre'] ?></td>
                                <td><?= $pp['rl_catalogosrecurrentes']['nombre'] ?></td>
                                <td>
                                    <?php
                                    $mostrarPrecio = true;
                                    foreach ($fechas as $f) {
                                        $result = $this->funcionpv($pp['id_producto'], $f);
                                        if (!empty($result)) {
                                            $nuevoPrecio = $result[0];
                                            if ($mostrarPrecio && $precioActual !== $nuevoPrecio) {
                                                echo '$' . number_format($nuevoPrecio, 2);
                                                $precioActual = $nuevoPrecio;
                                                $mostrarPrecio = false;
                                            }
                                        }
                                    }
                                    ?>
                                </td>
                                <?php
                                $totalMonto = 0;
                                foreach ($fechas as $f) {
                                ?>
                                    <td>
                                        <?php
                                        $result = $this->funcion($pp['id_producto'], $f);
                                        if (!empty($result)) {
                                            echo (int) $result[0]['cantidad'];
                                            $totalMonto += (float) $result[0]['cantidad'];
                                        }
                                        ?>
                                    </td>
                                <?php } ?>
                                <td><?= $totalMonto ?></td>
                                <td><?= '$' . number_format($totalMonto * $precioActual, 2) ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    </div>
</div>