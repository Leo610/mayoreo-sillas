<?php
/* @var $this AdministracionController */
$this->pageTitle = 'Reporte de Fabricación';
$this->breadcrumbs = array(
    'Pizarra de fabricacion' => Yii::app()->createUrl('productos/Pizarraproductos'),
    'Reporte de Fabricación',
);
$readonly = ($this->VerificarAcceso(11, Yii::app()->user->id)) == 1 ? false : true;
?>
<style>
    td {
        color: black;
    }
</style>

<script type="text/javascript">
    $(document).ready(function () {

        
        //Funcion para ordenar la lista de resultados, lo ordena por la columna 3, ya que empieza 0,1,2,3,4 y muestra  5 resultados, solamente
        $('#tablareportefab').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": true,
            dom: 'Bfrtip',
            buttons: [
                // {
                //     extend: 'csv',
                //     title: 'Reporte de Fabricación',
                //     text: 'Exportar a EXCEL',
                //     enabled: false
                // }, 
                // {
                //     extend: 'pdfHtml5',
                //     title: 'Reporte de Fabricación',
                //     text: 'Exportar a PDF',
                //     enabled: false
                // },
                // {
                //     extend: 'print',
                //     title: 'Reporte de Fabricación',
                //     text: 'Imprimir',
                //     enabled: false
                // }
                {
                    text: 'Descargar imagen',
                    action: function (e, dt, node, config) {
                        convertToImage();
                    }
                }
            ]
        });

    });
</script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/css/html2canvas.min.js"></script>

<script>
    //convert table to image            
    function convertToImage() {
        // eliminamos las clases
        var anchotabla = document.querySelector("#tablareportefab").scrollWidth;
        console.log(anchotabla);
        $('#tablareportefab').removeClass('table table-bordered table-hover');
        html2canvas(document.querySelector("#tablareportefab"), {
            // scrollY: -window.scrollY,
            // scrollX: anchotabla,
            // windowWidth: anchotabla,
            // width: anchotabla,
            // x: anchotabla,
            // logging:true,
            // windowHeight: document.documentElement.scrollHeight,
            // dpi: 300,
            // scale: 3,
        }).then(canvas => {
            canvas.toBlob(function (blob) {
                window.saveAs(blob, 'Reporte-fabricacion-<?= date('Y-m-d') ?>.jpg');
            });
            $('#tablareportefab').addClass('table table-bordered table-hover');
        });
        // regresamoslas clases

        // var resultDiv = document.getElementById("result");
        // html2canvas(document.getElementById("tablareportefab"), {
        //     onrendered: function(canvas) {
        //         var img = canvas.toDataURL("image/png");
        //         resultDiv.innerHTML = '<a download="reporte-fabricacion.jpeg" id="btndescargarreporte" href="'+img+'">test</a>';
        //         // esperamos 5 segundos y le damos click
        //         setTimeout(() => {
        //             console.log("Retrasado por 5 segundo.");
        //             $('#btndescargarreporte').click();
        //         }, "5000");
        //      }
        // });
    }
    //click event
    // var convertBtn = document.getElementById("convert");
    // convertBtn.addEventListener('click', convertToImage);
</script>

<div id="result" style="display:none">
    <!-- Result will appear be here -->
</div>
<div class="row">
    <div class="col-md-12 oportunidades">
        <h1 class="mt-md mb-md">
            <?= $this->pageTitle ?>
        </h1>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <fieldset>
            <legend>Filtros </legend>
            <form method="GET">
                <div class="col-md-3">
                    <label for=""> Bodega</label>
                    <?php
                    $valorSeleccionado = isset($_GET['bodeganombre']) ? $_GET['bodeganombre'] : '';
                        echo CHtml::listBox(
                        'bodeganombre',           // Nombre del campo
                        $valorSeleccionado,     // Valor seleccionado (puedes asignar un valor por defecto)
                        CHtml::listData(
                            $bodegaschidas,        // Datos para el listBox
                            'id_catalogo_recurrente', // Atributo de valor
                            'nombre'               // Atributo de texto
                        ),
                        array('multiple' => 'multiple', 'class' => 'form-control select2') // Opciones adicionales
                    );
                    ?>
<!-- 
                    <select class="form-control" name="bodeganombre" id="bodeganombre" multiple>
                        <option value="">Todas las Bodegas</option>
                        <?php foreach ($bodegaschidas as $lisb) { ?>
                            <option value="<?= $lisb['id_catalogo_recurrente'] ?>">
                                <?= $lisb['nombre'] ?>
                            </option>
                        <?php } ?>
                    </select> -->

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
    <div class="col-md-12">
        <div class="table-responsive">
            <div class="table-responsive">

                <table id="tablareportefab" class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>INDUSTRIAL OVISA SA DE CV</th>
                            <th>
                                <?= date('d-m-Y') ?>
                            </th>
                            <th colspan="3" style="width: 70%;">CONTROL DE ORDENES DE COMPRA PENDIENTES FABRICACIÓN Y
                                ENTREGA</th>
                        </tr>
                        <tr>
                            <th style="border-right:none ;" colspan="2">ROSCATOR INDUSTRIAL SA DE CV</th>
                            <th style="width: 3%;border-left: none;"></th>
                            <?php foreach ($listabodegas as $lb) { ?>
                                <th style="width: 25%;background-color: #ffff00;">PRODUCCIÓN
                                    <?= $lb['nombre'] ?>
                                </th>
                            <?php } ?>
                            <!-- <th style="width: 25%;background-color: #ffff00;">PRODUCCIÓN OVISA-CARLOS CASTILLO</th>
                            <th style="width: 25%;background-color: #ffff00;">PRODUCCIÓN ROSCATOR-JAVIER NEYRA</th> -->

                        </tr>
                        <tr style="background-color: #ffff00;">
                            <th style="padding: 0;" colspan="3">
                                <table class="table-bordered" style="width: 100%;">
                                    <tr style="width: 100%;height: 100%;">
                                        <th style="min-width: 40px; max-width: 40px;">OC</th>
                                        <th style="min-width: 50px; max-width: 60px;">Vendedor</th>
                                        <th style="min-width: 65px; max-width: 65px;">Fecha pedido</th>
                                        <th style="min-width: 110px; max-width: 110px;">Nombre cliente</th>
                                        <th style="min-width: 120px; max-width: 120px;">Localidad</th>
                                        <th style="min-width: 90px; max-width: 90px;">Fecha promesa</th>
                                    </tr>
                                </table>
                            </th>
                            <?php foreach ($listabodegas as $lis) { ?>
                                <th style="padding: 0;">
                                    <table class="table-bordered" style="width: 100%;">
                                        <tr style="width: 100%;height: 100%;">
                                            <?php
                                            // for ($i = 0; $i < $nombreBodegas['Ovisa']; $i++) {
                                            for ($i = 0; $i < 3; $i++) { ?>
                                                <th style="min-width: 50px; max-width: 50px;">CANT</th>
                                                <th style="min-width: 150px; max-width: 150px;">MOBILIARIO</th>
                                            <?php } ?>
                                        </tr>
                                    </table>
                                </th>
                            <?php } ?>
                            <!-- <th style="padding: 0;">
                                <table class="table-bordered" style="width: 100%;">
                                    <tr style="width: 100%;height: 100%;">
                                        <?php
                                        // for ($i = 0; $i < $nombreBodegas['Ovisa']; $i++) {
                                        for ($i = 0; $i < 3; $i++) { ?>
                                            <th style="min-width: 50px; max-width: 50px;">CANT</th>
                                            <th style="min-width: 150px; max-width: 150px;">MOBILIARIO</th>
                                        <?php } ?>
                                    </tr>
                                </table>
                            </th>
                            <th style="padding: 0;">
                                <table class="table-bordered" style="width: 100%;">
                                    <tr style="width: 100%;height: 100%;">
                                        <?php
                                        // for ($i = 0; $i < $nombreBodegas['Roscator']; $i++) {
                                        for ($i = 0; $i < 3; $i++) { ?>
                                            <th style="min-width: 50px; max-width: 50px;">CANT</th>
                                            <th style="min-width: 150px; max-width: 150px;">MOBILIARIO</th>
                                        <?php } ?>
                                    </tr>
                                </table>
                            </th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $bodegas = [];
                        foreach ($listabodegas as $lisbode) {
                            $bodegas[] = $lisbode['id_catalogo_recurrente'];
                                  
                            
                        }

                  
                        foreach ($proyectos as $row) {




                            // $bodegaOvisa = isset($row['proyectos_productos']['401']) ? $row['proyectos_productos']['401'] : array();
                            // $bodegaRoscator = isset($row['proyectos_productos']['402']) ? $row['proyectos_productos']['402'] : array();
                            $arrayiniciales = explode(' ', $row['rl_usuarios']['Usuario_Nombre']);
                            ?>
                            <!-- if para ver si el estatus esta cancelado hace 7 dias y no se inserte-->
                            <?php if ($row['proyecto_estatus'] == 7 && $fechaant == date('Y-m-d', strtotime($row['proyecto_ultima_modificacion']))) {
                                continue;
                                // agregamos un else if para ver si el estatus es igual 8 de ser asi que tampoco se inserte 
                            } else if ($row['proyecto_estatus'] == 8) {
                                continue;
                            } else { ?>
                                    <tr>
                                        <td style="padding: 0;" colspan="3">
                                            <table class="table-bordered" style="width: 100%;">
                                                <tr
                                                    style="width: 100%;height: 100%; <?= ($row['proyecto_estatus'] == 2) ? 'background: #4C72FF;' : ($row['proyecto_estatus'] == 6 ? 'background: yellow;' : ($row['proyecto_estatus'] == 7 ? 'background: red;' : '')) ?>">
                                                    <td
                                                        style="min-width: 40px; max-width: 40px; <?php if (
                                                            $hoy === date('Y-m-d', strtotime($row['proyecto_fecha_alta'])) && $row['proyecto_estatus'] != 2 &&
                                                            $row['proyecto_estatus'] != 6 && $row['proyecto_estatus'] != 7
                                                        ) {
                                                            echo 'background-color: orange;';
                                                        } ?>">
                                                    <?= $row['id_proyecto'] ?>
                                                    </td>
                                                    <td style="min-width: 50px; max-width: 60px;"
                                                        title="<?= $row['rl_usuarios']['Usuario_Nombre'] ?>">
                                                    <?= mb_substr($arrayiniciales[0], 0, 1) . mb_substr($arrayiniciales[1], 0, 1) ?>
                                                    </td>
                                                    <td style="min-width: 65px; max-width: 65px;">
                                                    <?= date('Y-m-d', strtotime($row['proyecto_fecha_alta'])) ?>
                                                    </td>
                                                    <td style="min-width: 110px; max-width: 110px;">
                                                    <?= $row['rl_clientes']['cliente_nombre'] ?>
                                                    </td>
                                                    <td style="min-width: 120px; max-width: 120px;">
                                                    <?= $row['localidad'] ?>
                                                    </td>
                                                    <td style="min-width: 90px; max-width: 90px;">
                                                    <?= date("Y-m-d", strtotime(array_values($row['proyectos_productos'])[0][0]['fecha_de_entrega'])) ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                       
                                        <?php foreach($bodegas as  $bode){
                                            $productosBodega = isset($row['proyectos_productos'][''.$bode.'']) ? $row['proyectos_productos'][''.$bode.''] : array();
                                            ?>
                                            <td style="padding: 0;">
                                         <?php if (!empty($productosBodega)) { 
                                            ?>
                                            

                                        <table class="table-bordered" style="width: 100%;">

                                            <tr style="width: 100%;height: 100%;">
                                                <?php foreach ($productosBodega as $key => $producto) {
                                                    if ($key > 2) {
                                                        continue;
                                                    }
                                                    ?>
                                                    <td style="min-width: 50px; max-width: 50px;"><?= $producto['proyectos_productos_cantidad'] ?></td>
                                                    <td style="min-width: 150px; max-width: 150px;"><?= implode(',', array_filter(array($producto['rl_producto']['producto_nombre'], $producto['color'], $producto['color_tapiceria'], '<b>' . $producto['especificaciones_extras'] . '</b>'))) ?></td>
                                                <?php } ?>
                                                <?php for ($i = count($productosBodega); $i < 3; $i++) { ?>
                                                    <td style="min-width: 50px; max-width: 50px;"></td>
                                                    <td style="min-width: 150px; max-width: 150px;"></td>
                                                <?php } ?>
                                            </tr>

                                             
                                            <?php if (count($productosBodega) > 2) { ?>
                                                <tr>
                                                    <?php foreach ($productosBodega as $key => $producto) {
                                                        if ($key < 3 || $key > 5) {
                                                            continue;
                                                        }
                                                        ?>
                                                        <td style="min-width: 50px; max-width: 50px;"><?= $producto['proyectos_productos_cantidad'] ?></td>
                                                        <td style="min-width: 150px; max-width: 150px;"><?= implode(',', array_filter(array($producto['rl_producto']['producto_nombre'], $producto['color'], $producto['color_tapiceria'], '<b>' . $producto['especificaciones_extras'] . '</b>'))) ?></td>
                                                    <?php } ?>
                                                </tr>
                                            <?php } ?>
                                            <?php if (count($productosBodega) > 5) { ?>
                                                <tr>
                                                    <?php foreach ($productosBodega as $key => $producto) {
                                                        if ($key < 6 || $key > 8) {
                                                            continue;
                                                        }
                                                        ?>
                                                        <td style="min-width: 50px; max-width: 50px;"><?= $producto['proyectos_productos_cantidad'] ?></td>
                                                        <td style="min-width: 150px; max-width: 150px;"><?= implode(',', array_filter(array($producto['rl_producto']['producto_nombre'], $producto['color'], $producto['color_tapiceria'], '<b>' . $producto['especificaciones_extras'] . '</b>'))) ?></td>
                                                    <?php } ?>
                                                </tr>
                                            <?php } ?>
                                        </table>
                                        <?php } ?>
                                    </td>
                                    <?php }?>
                                        <!-- <td style="padding: 0;">
                                    <?php if (!empty($bodegaOvisa)) { ?>
                                        <table class="table-bordered" style="width: 100%;">

                                            <tr style="width: 100%;height: 100%;">
                                                <?php foreach ($bodegaOvisa as $key => $producto) {
                                                    if ($key > 2) {
                                                        continue;
                                                    }
                                                    ?>
                                                    <td style="min-width: 50px; max-width: 50px;"><?= $producto['proyectos_productos_cantidad'] ?></td>
                                                    <td style="min-width: 150px; max-width: 150px;"><?= implode(',', array_filter(array($producto['rl_producto']['producto_nombre'], $producto['color'], $producto['color_tapiceria'], '<b>' . $producto['especificaciones_extras'] . '</b>'))) ?></td>
                                                <?php } ?>
                                                <?php for ($i = count($bodegaOvisa); $i < 3; $i++) { ?>
                                                    <td style="min-width: 50px; max-width: 50px;"></td>
                                                    <td style="min-width: 150px; max-width: 150px;"></td>
                                                <?php } ?>
                                            </tr>

                                             agregado por dvb  **
                                            <?php if (count($bodegaOvisa) > 2) { ?>
                                                <tr>
                                                    <?php foreach ($bodegaOvisa as $key => $producto) {
                                                        if ($key < 3 || $key > 5) {
                                                            continue;
                                                        }
                                                        ?>
                                                        <td style="min-width: 50px; max-width: 50px;"><?= $producto['proyectos_productos_cantidad'] ?></td>
                                                        <td style="min-width: 150px; max-width: 150px;"><?= implode(',', array_filter(array($producto['rl_producto']['producto_nombre'], $producto['color'], $producto['color_tapiceria'], '<b>' . $producto['especificaciones_extras'] . '</b>'))) ?></td>
                                                    <?php } ?>
                                                </tr>
                                            <?php } ?>
                                            <?php if (count($bodegaOvisa) > 5) { ?>
                                                <tr>
                                                    <?php foreach ($bodegaOvisa as $key => $producto) {
                                                        if ($key < 6 || $key > 8) {
                                                            continue;
                                                        }
                                                        ?>
                                                        <td style="min-width: 50px; max-width: 50px;"><?= $producto['proyectos_productos_cantidad'] ?></td>
                                                        <td style="min-width: 150px; max-width: 150px;"><?= implode(',', array_filter(array($producto['rl_producto']['producto_nombre'], $producto['color'], $producto['color_tapiceria'], '<b>' . $producto['especificaciones_extras'] . '</b>'))) ?></td>
                                                    <?php } ?>
                                                </tr>
                                            <?php } ?>
                                        </table>
                                    <?php } ?>
                                </td>

                                <td style="padding: 0;">
                                    <?php if (!empty($bodegaRoscator)) { ?>
                                        <table class="table-bordered" style="width: 100%;">
                                            <tr style="width: 100%;height: 100%;">
                                                <?php foreach ($bodegaRoscator as $key => $producto) {
                                                    if ($key > 2) {
                                                        continue;
                                                    }
                                                    ?>
                                                    <td style="min-width: 50px; max-width: 50px;"><?= $producto['proyectos_productos_cantidad'] ?></td>
                                                    <td style="min-width: 150px; max-width: 150px;"><?= implode(',', array_filter(array($producto['rl_producto']['producto_nombre'], $producto['color'], $producto['color_tapiceria'], '<b>' . $producto['especificaciones_extras'] . '</b>'))) ?></td>
                                                <?php } ?>
                                                <?php for ($i = count($bodegaRoscator); $i < 3; $i++) { ?>
                                                    <td style="min-width: 50px; max-width: 50px;"></td>
                                                    <td style="min-width: 150px; max-width: 150px;"></td>
                                                <?php } ?>
                                            </tr>

                                          agregado por dvb  **
                                            <?php if (count($bodegaRoscator) > 2) { ?>
                                                <tr>
                                                    <?php foreach ($bodegaRoscator as $key => $producto) {
                                                        if ($key < 3 || $key > 5) {
                                                            continue;
                                                        }
                                                        ?>
                                                        <td style="min-width: 50px; max-width: 50px;"><?= $producto['proyectos_productos_cantidad'] ?></td>
                                                        <td style="min-width: 150px; max-width: 150px;"><?= implode(',', array_filter(array($producto['rl_producto']['producto_nombre'], $producto['color'], $producto['color_tapiceria'], '<b>' . $producto['especificaciones_extras'] . '</b>'))) ?></td>
                                                    <?php } ?>
                                                </tr>
                                            <?php } ?>
                                            <?php if (count($bodegaRoscator) > 5) { ?>
                                                <tr>
                                                    <?php foreach ($bodegaRoscator as $key => $producto) {
                                                        if ($key < 6 || $key > 8) {
                                                            continue;
                                                        }
                                                        ?>
                                                        <td style="min-width: 50px; max-width: 50px;"><?= $producto['proyectos_productos_cantidad'] ?></td>
                                                        <td style="min-width: 150px; max-width: 150px;"><?= implode(',', array_filter(array($producto['rl_producto']['producto_nombre'], $producto['color'], $producto['color_tapiceria'], '<b>' . $producto['especificaciones_extras'] . '</b>'))) ?></td>
                                                    <?php } ?>
                                                </tr>
                                            <?php } ?>
                                        </table>
                                    <?php } ?>
                                </td> -->
                                        <td style=" display: none;"></td>
                                        <td style="display: none;"></td>
                                    </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>