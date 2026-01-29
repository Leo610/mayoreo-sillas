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

        $(document).ready(function () {
            // Funcion para ordenar la lista de resultados
            $('#tablareportefab2').DataTable({
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'csv',
                        title: 'Exportar',
                        text: 'Exportar a EXCEL',
                    },
                ],
                order: [[0, 'desc']], // Ordenar por la primera columna en orden ascendente
                pageLength: 100,
                lengthMenu: [10, 25, 50, 100],
            });
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
            <table id="tablareportefab2" class="table  table-bordered  table-hover ">
                <?php
                $bodegas = [];
                $longmax = 0;
                foreach ($listabodegas as $lisbode) {
                    $bodegas[] = $lisbode['id_catalogo_recurrente'];
                }
                foreach ($proyectos as $row) {
                    foreach ($bodegas as $bode) {
                        $productosBodega = isset($row['proyectos_productos']['' . $bode . '']) ? $row['proyectos_productos']['' . $bode . ''] : array();
                        if (!empty($productosBodega)) {
                            $logprod = count($productosBodega);
                            if ($logprod > $longmax) {

                                $longmax = $logprod;
                            }
                        }


                    }
                }
                ?>
                <thead>
                    <tr>
                        <th colspan="4">INDUSTRIAL OVISA SA DE CV</th>
                        <th colspan="2">
                            <?= date('d-m-y') ?>
                        </th>
                        <th colspan="<?= $longmax * 2 ?>">CONTROL DE ORDENES DE COMPRA PENDIENTES
                            FABRICACIÓN
                            Y
                            ENTREGA
                        </th>

                    </tr>
                    <tr>
                        <th colspan="6">
                            <?= $this->pageTitle ?>ROSCATOR INDUSTRIAL SA DE CV
                        </th>
                        <?php foreach ($listabodegas as $lis) {
                            $cantidadMaxima = $nombreBodegas[$lis['id_catalogo_recurrente']];
                            if ($cantidadMaxima == '') {
                                $cantidadMaxima = 1;
                            }
                            ?>
                            <th colspan="<?= $cantidadMaxima * 2 ?> ">PRODUCCIÓN
                                <?= $lis['nombre'] ?>
                            </th>
                        <?php } ?>
                    </tr>
                    <tr>
                        <th>OC</th>
                        <th>VENDEDOR</th>
                        <th>FECHA PEDIDO</th>
                        <th>NOMBRE CLIENTE</th>
                        <th>LOCALIDAD</th>
                        <th>FECHA PROMESA</th>
                        <?php
                        // echo "<pre>";
                        // print_r($listabodegas);
                        // echo "</pre>";
                        
                        foreach ($listabodegas as $key => $lis) {
                            $cantidadMaxima = $nombreBodegas[$lis['id_catalogo_recurrente']];
                            if ($cantidadMaxima == '') {
                                $cantidadMaxima = 1;
                            }
                            for ($index = 0; $index < $cantidadMaxima; $index++) {
                                // echo $cantidadMaxima;
                                ?>
                                <th>CANT</th>
                                <th>MOBILIARIO</th>
                            <?php }
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    $bodegas = [];
                    foreach ($listabodegas as $lisbode) {
                        $bodegas[] = $lisbode['id_catalogo_recurrente'];
                    }
                    foreach ($proyectos as $row) {
                        $arrayiniciales = explode(' ', $row['rl_usuarios']['Usuario_Nombre']); ?>
                        <?php if ($row['proyecto_estatus'] == 7 && $fechaant == date('Y-m-d', strtotime($row['proyecto_ultima_modificacion']))) {
                            continue;
                            // agregamos un else if para ver si el estatus es igual 8 de ser asi que tampoco se inserte 
                        } else if ($row['proyecto_estatus'] == 8) {
                            continue;
                        } else { ?>
                                <tr
                                    style="width: 100%;height: 100%; <?= ($row['proyecto_estatus'] == 2) ? 'background: #4C72FF;' : ($row['proyecto_estatus'] == 6 ? 'background: yellow;' : ($row['proyecto_estatus'] == 7 ? 'background: red;' : '')) ?>">
                                    <td style="min-width: 40px; max-width: 40px; <?php if (
                                        $hoy === date('Y-m-d', strtotime($row['proyecto_fecha_alta'])) && $row['proyecto_estatus'] != 2 &&
                                        $row['proyecto_estatus'] != 6 && $row['proyecto_estatus'] != 7
                                    ) {
                                        echo 'background-color: orange;';
                                    } ?>">
                                    <?= $row['id_proyecto'] ?>
                                    </td>
                                    <td style="min-width: 50px; max-width: 60px;"
                                        title="<?= $row['rl_usuarios']['Usuario_Nombre'] ?>">
                                    <?= mb_substr($arrayiniciales[0], 0, 1) . mb_substr($arrayiniciales[1], 0, 1) //aqui hay un error                                                                                                                                                                                                                                                                                                                                     ?>
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

                                    <?php

                                    $var = 0;

                                    foreach ($bodegas as $bode) {
                                        $productosBodega = isset($row['proyectos_productos']['' . $bode . '']) ? $row['proyectos_productos']['' . $bode . ''] : array();
                                        $paramastds = count($productosBodega);

                                        $cantidad = $nombreBodegas[$bode];
                                        if ($cantidad == '') {
                                            $cantidad = 1;
                                        }
                                        $faltantes = $cantidad - $paramastds;

                                        ?>
                                    <?php if (!empty($productosBodega)) { ?>
                                        <?php foreach ($productosBodega as $producto) {
                                            $var++; ?>

                                                <td>
                                                <?= $producto['proyectos_productos_cantidad'] ?>
                                                    <br>
                                                    cantidad
                                                <?= $cantidad ?>
                                                    <br>
                                                    paramastds
                                                <?= $paramastds ?>
                                                    <br>
                                                    faltantes
                                                <?= $faltantes ?>

                                                </td>

                                                <td>
                                                <?= implode(',', array_filter(array($producto['rl_producto']['producto_nombre'], $producto['color'], $producto['color_tapiceria'], '<b>' . $producto['especificaciones_extras'] . '</b>'))) ?>
                                                </td>
                                        <?php } ?>
                                    <?php }

                                    if ($faltantes > 0) {
                                        for ($i = 0; $i < $faltantes; $i++) {
                                            ?>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                        <?php }
                                    }
                                    ?>

                                <?php }
                        }
                    }
                    ?>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>