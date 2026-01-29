<?php
/* @var $this AdministracionController */
$this->pageTitle = 'Reporte de Fabricación';
$this->breadcrumbs = array(
    'Pizarra de fabricacion' => Yii::app()->createUrl('productos/admin'),
    'Reporte de Fabricación',
);
$readonly = ($this->VerificarAcceso(11, Yii::app()->user->id)) == 1 ? false : true;
?>
<style>
    td {
        color: black;
    }

    .ama {
        background-color: yellow !important;
    }

    .naranja {
        background-color: orange !important;
    }
</style>

<script type="text/javascript">
    var tableToExcel = (function() {
        // Define your style class template.
        var style = "<style>.green { background-color: green; }  </style>";
        var uri = 'data:application/vnd.ms-excel;base64,',
            template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->' + style + '</head><body><table>{table}</table></body></html>',
            base64 = function(s) {
                return window.btoa(unescape(encodeURIComponent(s)))
            },
            format = function(s, c) {
                return s.replace(/{(\w+)}/g, function(m, p) {
                    return c[p];
                })
            }
        return function(table, name) {
            if (!table.nodeType) table = document.getElementById(table)
            var ctx = {
                worksheet: name || 'Worksheet',
                table: eliminarAcentos(table.innerHTML)
            }
            window.location.href = uri + base64(format(template, ctx))
        }
    })()

    function eliminarAcentos(texto) {
        return texto.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
    }
</script>


<!-- <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

<script type="text/javascript">
    function ExportToExcel(type, fn, dl) {
       var elt = document.getElementById('tablareportefab');
       var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
       return dl ?
         XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
         XLSX.writeFile(wb, fn || ('Reporte de Fabricacion.' + (type || 'xlsx')));
    }

</script> -->
<script type="text/javascript">
    $(document).ready(function() {


        // Funcion para ordenar la lista de resultados
        $('#tablareportefab').DataTable({
            pageLength: 100,
            dom: 'lBfrtip',
            stateSave: true,
            order: [
                [5, 'asc']
            ],
            columnDefs: [{
                    targets: [5],
                    orderData: [5]
                } // Indicar que utilice data-order de la sexta columna
            ],
            buttons: [{
                text: 'Descargar csv',
                action: function(e, dt, node, config) {
                    tableToExcel('tablareportefab');
                }
            }]
        });
        // new $.fn.dataTable.FixedHeader(table);

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
            canvas.toBlob(function(blob) {
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

<div id="scroll-container-fixed"
    style="overflow-x: scroll;height:50px;position: fixed;bottom: 1%; left: 0;z-index: 100;width: 100%; padding:0 10px ;">
    <div id="scroll-elem-fixed" style=" height:100%; padding: 10px">
    </div>
</div>
<?php include 'modal/modal_empacar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive tablescrol">
            <table id="tablareportefab" class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th colspan="6">INDUSTRIAL OVISA SA DE CV</th>

                        <th colspan="40" style="width: 70%;">
                            <?= date('d-m-Y') ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; CONTROL DE ORDENES DE COMPRA
                            PENDIENTES FABRICACIÓN Y
                            ENTREGA
                        </th>

                    </tr>
                    <tr>
                        <th colspan="6" style="border-right:none ;">ROSCATOR INDUSTRIAL SA DE CV</th>
                        <!-- <th style="width: 3%;border-left: none;"></th> -->
                        <?php foreach ($listabodegas as $lb) {

                            // ocupamos obtener el proyecto con la cantidad maxima de concpetos para poder saber cuantas debemos mostrar
                            $sql = 'SELECT 
                                    COUNT(*) AS productosporpedido,proyectosproductos.id_proyecto
                                     FROM proyectosproductos WHERE 
                                    bodega = ' . $lb["id_catalogo_recurrente"] . ' and id_proyecto IN (select id_proyecto from proyectos where proyecto_estatus not in(7,8) and id_etapa is not NULL)

                                    GROUP BY id_proyecto
                                    ORDER BY productosporpedido desc limit 1';
                            $cntprodproy = Yii::app()->dbadvert->createCommand($sql)->queryAll();

                            if (empty($cntprodproy)) {
                                $cntprodproy[0]['productosporpedido'] = 1;
                            }

                        ?>
                            <th colspan="<?= $cntprodproy[0]['productosporpedido'] * 2 ?>"
                                style="width: 25%;background-color: #ffff00;">PRODUCCIÓN
                                <?= $lb['nombre'] ?>
                            </th>
                        <?php } ?>


                    </tr>
                    <tr style="background-color: #ffff00;">
                        <th style="min-width: 20px; max-width: 20px;">OC</th>
                        <th style="min-width: 50px; max-width: 60px;">Vendedor</th>
                        <th style="min-width: 65px; max-width: 65px;">Fecha pedido</th>
                        <th style="min-width: 110px; max-width: 110px;">Nombre cliente</th>
                        <th style="min-width: 120px; max-width: 120px;">Localidad</th>
                        <th style="min-width: 90px; max-width: 90px;">Fecha promesa</th>
                        <?php foreach ($listabodegas as $lis) {
                            // ocupamos obtener el proyecto con la cantidad maxima de concpetos para poder saber cuantas debemos mostrar
                            $sql = 'SELECT 
                                    COUNT(*) AS productosporpedido,proyectosproductos.id_proyecto
                                     FROM proyectosproductos WHERE 
                                    bodega = ' . $lis["id_catalogo_recurrente"] . ' and id_proyecto IN (select id_proyecto from proyectos where proyecto_estatus not in(7,8) and id_etapa is not NULL)

                                    GROUP BY id_proyecto
                                    ORDER BY productosporpedido desc limit 1';
                            $cntprodproy = Yii::app()->dbadvert->createCommand($sql)->queryAll();

                            if (empty($cntprodproy)) {
                                $cntprodproy[0]['productosporpedido'] = 1;
                            }
                            // for ($i = 0; $i < $nombreBodegas['Ovisa']; $i++) {
                            for ($i = 0; $i < $cntprodproy[0]['productosporpedido']; $i++) { ?>
                                <th style="min-width: 50px; max-width: 50px;">CANT</th>
                                <th style="min-width: 150px; max-width: 150px;">MOBILIARIO</th>
                            <?php } ?>
                        <?php } ?>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $bodegas = [];
                    foreach ($listabodegas as $lisbode) {
                        $bodegas[] = $lisbode['id_catalogo_recurrente'];
                    }

                    foreach ($proyectos as $row) {
                        $arrayiniciales = explode(' ', $row['rl_usuarios']['Usuario_Nombre']);

                    ?>

                        <!-- if para ver si el estatus esta cancelado hace 7 dias y no se inserte-->
                        <?php if ($row['proyecto_estatus'] == 7 && date('Y-m-d', strtotime($row['proyecto_ultima_modificacion'])) <= $fechaant) {
                            // echo $fechaant;
                            continue;
                            // agregamos un else if para ver si el estatus es igual 8 de ser asi que tampoco se inserte 
                        } else if ($row['proyecto_estatus'] == 8) {
                            continue;
                        } else { ?>
                            <tr
                                style="width: 100%;height: 100%; <?= ($row['proyecto_estatus'] == 2) ? 'background: #4C72FF;' : ($row['proyecto_estatus'] == 6 ? 'background: yellow;' : ($row['proyecto_estatus'] == 7 ? 'background: red;' : '')) ?>">
                                <td style="min-width: 20px; max-width: 20px; <?php if (
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
                                <td style="min-width: 90px; max-width: 90px;"
                                    data-order="<?= date("Y-m-d", strtotime(array_values($row['proyectos_productos'])[0][0]['fecha_de_entrega'])) ?>">
                                    <?= $this->Fechacortadaslash(date("Ymd", strtotime(array_values($row['proyectos_productos'])[0][0]['fecha_de_entrega'])))
                                    //date("Y-m-d", strtotime(array_values($row['proyectos_productos'])[0][0]['fecha_de_entrega']))        
                                    ?>
                                </td>
                                <?php foreach ($bodegas as $bode) {
                                    $productosBodega = isset($row['proyectos_productos']['' . $bode . '']) ? $row['proyectos_productos']['' . $bode . ''] : array();

                                    // ocupamos obtener el proyecto con la cantidad maxima de concpetos para poder saber cuantas debemos mostrar
                                    $sql = 'SELECT 
                                                        COUNT(*) AS productosporpedido,proyectosproductos.id_proyecto
                                                         FROM proyectosproductos WHERE 
                                                        bodega = ' . $bode . ' and id_proyecto IN (select id_proyecto from proyectos where proyecto_estatus not in(7,8) and id_etapa is not NULL)

                                                        GROUP BY id_proyecto
                                                        ORDER BY productosporpedido desc limit 1';
                                    $cntprodproy = Yii::app()->dbadvert->createCommand($sql)->queryAll();

                                    if (empty($cntprodproy)) {
                                        $cntprodproy[0]['productosporpedido'] = 1;
                                    }

                                ?>

                                    <?php foreach ($productosBodega as $key => $producto) {
                                        // vamos a catalogos recurrentes para ver si hay algun cambio
                                        $cambios = CatalogosRecurrentes::model()->find(
                                            array(
                                                'condition' => '`update` is not null and  num = :num',
                                                'params' => array(':num' => $producto['id_proyectos_productos'])
                                            )
                                        ); ?>
                                        <td style="min-width: 50px; max-width: 50px;">
                                            <?= number_format($producto['proyectos_productos_cantidad'], 0, '', '') //$producto['proyectos_productos_cantidad']                       
                                            ?>
                                        </td>
                                        <td id="td-<?= $producto['id_proyectos_productos'] ?>"
                                            style="min-width: 150px; max-width: 150px; <?= (!empty($cambios) && $producto['empacado'] == 0 && date('Y-m-d', strtotime($cambios['fecha_alta'] . ' +3 days')) >= date('Y-m-d')) ? 'background-color: orange' : ($producto['empacado'] == 1 ? 'background-color: yellow' : '') ?>">
                                            <?= implode(
                                                ',',
                                                // array_filter(
                                                array(
                                                    (!empty($producto['rl_producto']['producto_nombre'])) ? trim($producto['rl_producto']['producto_nombre']) : ' ',
                                                    (!empty($producto['color'])) ? trim($producto['color']) : ' ',
                                                    (!empty($producto['color_tapiceria'])) ? trim($producto['color_tapiceria']) : ' ',
                                                    '<b>' . (!empty($producto['especificaciones_extras'])) ? trim($producto['especificaciones_extras']) : ' ' . '</b>'
                                                )
                                                // )
                                            ) ?>
                                            <?php
                                            $VerificarAcceso = $this->VerificarAcceso(33, Yii::app()->user->id);
                                            if ($VerificarAcceso) { ?>
                                                <br>
                                                <?php if ($producto['empacado']) { ?>
                                                    <button class="btn btn-empacar btn-danger"
                                                        id="proyecto_producto_empaque_<?= $producto['id_proyectos_productos'] ?>"
                                                        style="margin: 3vmin 0;"
                                                        data-producto_id="<?= $producto['id_proyectos_productos'] ?>">Desempacar</button>
                                                <?php } else { ?>
                                                    <button class="btn btn-empacar btn btn-success"
                                                        id="proyecto_producto_empaque_<?= $producto['id_proyectos_productos'] ?>"
                                                        style="margin: 3vmin 0;"
                                                        data-producto_id="<?= $producto['id_proyectos_productos'] ?>">Empacar</button>
                                                <?php } ?>
                                            <?php }
                                            ?>

                                        </td>
                                    <?php } ?>

                                    <?php
                                    if (empty($productosBodega)) {
                                        // ssi no tiene productos, mostramos 1 al menos
                                        $key = -1;
                                    }
                                    for ($i = ($key + 1); $i < ($cntprodproy[0]['productosporpedido']); $i++) { ?>
                                        <td style="min-width: 50px; max-width: 50px;"></td>
                                        <td style="min-width: 150px; max-width: 150px;"></td>
                                    <?php } ?>
                                <?php } ?>
                                <!-- <td style=" display: none;"></td>
                                                    <td style="display: none;"></td> -->
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            const tableWidth = $('#tablareportefab').width();
            // asignamos el anchotabla
            const scroll = document.querySelector("#scroll-elem-fixed");
            scroll.style.width = (tableWidth + 15) + 'px';
            document.querySelector("#scroll-container-fixed").addEventListener("scroll", function() {
                document.querySelector(".tablescrol").scrollLeft = this.scrollLeft;
            })
        })
    </script>