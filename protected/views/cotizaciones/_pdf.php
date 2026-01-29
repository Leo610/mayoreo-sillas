<!DOCTYPE html>
<html>

    <head>
        <title>COTIZACIÓN
            <?= $Datos->id_cotizacion ?> /
            <?= date('Y', strtotime($Datos->cotizacion_fecha_alta)) ?>
        </title>
        <style>
            * {
                margin: 0;
                padding: 0;
                font-family: 'Verdana';
                font-size: 9pt;
                color: #000;
            }

            body {
                width: 100%;
                font-family: 'Verdana';
                font-size: 9pt;
                margin: 0;
                padding: 0;
                text-transform: uppercase;
            }

            tr,
            td,
            table,
            h1,
            h2,
            h3,
            h4,
            h5,
            h6,
            p {
                text-transform: uppercase;
            }

            p {
                margin: 0;
                padding: 0;
            }

            #wrapper {
                width: 210mm;
                margin: 5 5mm;
            }

            .page {
                height: 297mm;
                width: 210mm;
                page-break-after: always;
            }

            table {
                border-left: 1px solid #ccc;
                border-top: 1px solid #ccc;

                border-spacing: 0;
                border-collapse: collapse;

            }

            table td {
                border-right: 1px solid #ccc;
                border-bottom: 1px solid #ccc;
                padding: 2mm;
            }

            table.heading {
                height: 50mm;
            }

            h1.heading {
                font-size: 13pt;
                color: #8c6635;
                font-weight: normal;
            }

            h2.heading {
                font-size: 10pt;
                color: #000;
                font-weight: 900;
            }

            hr {
                color: #ccc;
                background: #ccc;
            }

            #invoice_body {
                min-height: 149mm;
            }

            #invoice_body,
            #invoice_total {
                width: 100%;
            }

            #invoice_body table,
            #invoice_total table {
                width: 100%;
                border-left: 1px solid #ccc;
                border-top: 1px solid #ccc;

                border-spacing: 0;
                border-collapse: collapse;

                margin-top: 5mm;
            }

            #invoice_body table td,
            #invoice_total table td {
                text-align: center;
                font-size: 8pt;
                border-right: 1px solid #ccc;
                border-bottom: 1px solid #ccc;
                padding: 2mm 0;
            }

            #invoice_body table td.mono,
            #invoice_total table td.mono {
                text-align: right;
                padding-right: 3mm;
                font-size: 10pt;
            }

            #footer {
                width: 180mm;
                margin: 0 15mm;
                padding-bottom: 3mm;
            }

            #footer table {
                width: 100%;
                /* border-left: 1px solid #ccc; */
                /* border-top: 1px solid #ccc; */

                /* background: #eee; */

                border-spacing: 0;
                /* border-collapse: collapse; */
            }

            #footer table td {
                width: 25%;
                text-align: center;
                font-size: 8pt;
                /* border-right: 1px solid #ccc; */
                /* border-bottom: 1px solid #ccc; */
            }

            .img-responsive {
                height: 20mm !important;
            }
        </style>
    </head>

    <body>
        <div id="wrapper">
            <br>
            <table class="heading" style="width:100%; border:0;">
                <tr>
                    <td style="width:40mm;border:0; text-align:right;  vertical-align: bottom;">
                        <?php
                        // $img = file_get_contents(Yii::app()->getbaseUrl(true) . '/companias/2IND_OVI_MED.png');
                        $img = file_get_contents(Yii::app()->basePath . '/../companias/2IND_OVI_MED.png');
                        // Encode the image string data into base64
                        $data = base64_encode($img);
                        ?>
                        <img src="data:image/jpeg;base64,<?= $data ?>" alt="" style="width: 130px; height: auto;">

                    </td>

                    <td style="width:120mm;border:0;">
                        <h1><b style="font-size: 30px;">
                                <?= $DatosConfiguracion['nombre_compania'] ?>
                            </b></h1><br>
                        <b>
                            <p style="text-align: center; color: #8c663b; font-size: 16px; font-weight: 900;">Excelencia
                                e Innovación
                                Industrial</p>
                        </b>
                    </td>

                    <td class="logotipo" style="width:40mm;border:0;">
                        <!-- <?= $this->ObtenerLogotipo(); ?> -->
                        <?php
                        $img = file_get_contents(Yii::app()->basePath . '/../companias/8131-2f766e5e-b985-4241-89d7-dc2842c14757.jpeg');
                        // Encode the image string data into base64
                        $data = base64_encode($img);
                        ?>
                        <img src="data:image/jpeg;base64,<?= $data ?>" alt="" style="width: 155px; height: auto;">
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align:center; border:0">
                        <b>
                            <p style=" font-weight: 900; font-size:12px;">
                                <?= $DatosConfiguracion['direccion'] ?>
                            </p>
                        </b>
                        <b>
                            <p style="font-weight: 900; font-size:12px;">E-MAIL:
                                <?= $DatosConfiguracion['correo'] ?> WEB:
                                <?= $DatosConfiguracion['web'] ?> Tels.
                                <?= $DatosConfiguracion['telefonos'] ?>
                            </p>
                        </b>

                    </td>
                </tr>
                <br>

                <tr>
                    <td colspan="2" style="border: 0;">
                        <p style="font-weight: 900; font-size:13px;">CLIENTE:
                            <?= ($DatosCliente->cliente_nombre != '') ? $DatosCliente->cliente_nombre : ''; ?>
                        </p>
                        <p style="font-weight: 900; font-size:13px;">DIRECCIÓN:
                            <?= ($DatosCliente->cliente_municipio != '') ? $DatosCliente->cliente_municipio : '';
                            echo ', ';
                            echo ($DatosCliente->cliente_entidad != '') ? ' ' . $DatosCliente->cliente_entidad : '';

                            ?>
                        </p>
                        <p style="font-weight: 900; font-size:13px;">EMPRESA:
                            <?= ($DatosCliente['rl_empresas']['empresa'] != '') ? $DatosCliente['rl_empresas']['empresa'] : ''; ?>
                        </p>
                        <p style="font-weight: 900; font-size:13px;">TELÉFONO:
                            <?= ($DatosCliente->cliente_telefono != '') ? $DatosCliente->cliente_telefono : ''; ?>
                        </p>
                        <p style="font-weight: 900; font-size:13px;">E-MAIL:
                            <?= ($DatosCliente->cliente_email != '') ? $DatosCliente->cliente_email : ''; ?>
                        </p>

                    </td>

                    <td colspan="1" style="border: 0;">
                        <p style="font-weight: 900; font-size:13px;">FECHA:
                            <?= $fecha ?>
                        </p>
                        <p style="font-weight: 900; font-size:13px;">COTIZACIÓN: <b style="color:red">
                                <?= $Datos['id_cotizacion'] ?>
                            </b></p>
                        <!-- <p style="font-weight: 900; font-size:13px;">ASESOR:
                            <? //= $Datos['rl_usuarios']['Usuario_Nombre'] ?>
                        </p> -->

                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center; border: 0;">
                        <p style="font-size: 16px;font-weight: 700; color: red;">COTIZACIÓN</p>
                        <p style="font-size: 14px; font-weight: 900;">A CONTINUACIÓN PONEMOS A SU CONSIDERACIÓN LOS
                            SIGUIENTES PRECIOS</p>

                    </td>
                </tr>
            </table>
            <div id="content" style="margin-right: 25px;">
                <div id="invoice_body">
                    <!-- <h2 class="heading">En base a sus requerimientos le mostramos la siguiente cotización:
                    </h2> -->
                    <table>
                        <tr style="background:#8c6635;">
                            <td>
                                <p style="color:#fff; font-weight: 900;">CANTIDAD</p>
                            </td>
                            <td>
                                <p style="color:#fff; font-weight: 900;">IMAGEN</p>
                            </td>
                            <td>
                                <p style="color:#fff; font-weight: 900;">DESCRIPCIÓN</p>
                            </td>

                            <!-- <td>
                                <p style="color:#fff; font-weight: 900;">COLOR <br> ESTRUCTURA</p>
                            </td>
                            <td>
                                <p style="color:#fff; font-weight: 900;">COLOR <br> TAPICERIA</p>
                            </td> -->
                            <td>
                                <p style="color:#fff; font-weight: 900;">PRECIO</p>
                            </td>
                            <td>
                                <p style="color:#fff; font-weight: 900;">TOTAL</p>
                            </td>

                        </tr>
                        <tr>
                            <?php
                            $partida = 1;
                            foreach ($Detalleprod as $rows) { ?>
                            <tr>
                                <!-- cantidad -->
                                <td class="mono" style="font-size:7pt;width:10%;">
                                    <?= $rows['cotizacion_producto_cantidad'] ?>
                                </td>
                                <!-- imagen -->
                                <td style="font-size:7pt;width:10%;">
                                    <?php if ($rows['rl_producto']['producto_imagen'] != '') {
                                        // $img = file_get_contents(Yii::app()->getbaseUrl(true) . '/companias/8131-2f766e5e-b985-4241-89d7-dc2842c14757.jpeg');
                                        $img = file_get_contents(Yii::app()->basePath . '/../archivos/' . $rows['rl_producto']['producto_imagen']);

                                        $data2 = base64_encode($img);
                                        ?>

                                        <img src="data:image/jpeg;base64,<?= $data2 ?>" style="max-height:50px" alt="">
                                        <!-- <img src="<? //= Yii::app()->createUrl('archivos/' . $rows['rl_producto']['producto_imagen']) ?>"
                                            style="max-height:50px"> -->
                                    <?php } ?>
                                </td>
                                <!-- descripcion -->
                                <td style="font-size:7pt;text-align:left; padding-left:10px;">
                                    <!-- <? //= $rows['rl_producto']['producto_nombre'] . ' ' . $rows['rl_producto']['producto_clave'] ?><br /> -->
                                    <?= $rows['cotizacion_producto_descripcion'] ?>
                                    <?php
                                    if (!empty($rows['especificaciones_extras'])) { ?>
                                        <br>
                                        <br>
                                        <p style="font-size:7pt;text-align:left; "><b>Especificaciones extras</b>
                                        </p>
                                        <br>
                                        <p>
                                            <b style="font-size:7pt;text-align:left; ">
                                                <?= $rows['especificaciones_extras'] ?>
                                            </b>
                                        </p>
                                    <?php } ?>
                                    <?php
                                    if (!empty($rows['color'])) { ?>
                                        <br>
                                        <br>
                                        <p style="font-size:7pt;text-align:left; "><b>Color estructura: </b>
                                            <?= $rows['especificaciones_extras'] ?>
                                        </p>

                                    <?php } ?>
                                    <?php
                                    if (!empty($rows['color_tapiceria'])) { ?>

                                        <p style="font-size:7pt;text-align:left; "><b>Color tapiceria: </b>
                                            <?= $rows['color_tapiceria'] ?>
                                        </p>

                                    <?php } ?>
                                </td>

                                <!-- color -->
                                <!-- <td style="font-size:7pt;width:10%;">
                                    <? //= $rows['color'] ?>
                                </td> -->
                                <!-- color tapiceria -->
                                <!-- <td style="font-size:7pt;width:10%;">
                                    <? //= $rows['color_tapiceria'] ?>
                                </td> -->
                                <!-- precio -->
                                <td style="font-size:7pt;width:15%;" class="mono">
                                    <?php if (!$descuentos) {
                                        if (!empty($rows['tipo_descuetno'])) {
                                            if ($rows['tipo_descuetno'] == 'porcentaje') {
                                                $desc = '(Descuento del ' . $rows['descuento'] . '%)';
                                            } else {
                                                $desc = '(Descuento de $' . $rows['descuento'] . ')';
                                            } ?>
                                            $
                                            <?= number_format($rows->cotizacion_producto_unitario, 2) . ' ' . $desc ?>;
                                        <?php } else { ?>
                                            $
                                            <?= number_format($rows->cotizacion_producto_unitario, 2); ?>
                                        <?php }
                                        ?>
                                    <?php } else { ?>
                                        $
                                        <?= number_format($rows->cotizacion_producto_unitario, 2) ?>
                                    <?php } ?>
                                </td>
                                <!-- totao -->
                                <td style="font-size:7pt;width:10%;" class="mono">$
                                    <?= number_format($rows['cotizacion_producto_total'], 2) ?>
                                </td>

                                <!-- <td style="font-size:6pt;width:8%; ">
                                    <? //= $rows['rl_producto']['rl_unidadesdemedida']['unidades_medida_nombre'] ?>
                                </td> -->
                            </tr>
                        <?php } ?>
                        </tr>
                        <tr>
                            <td colspan="3" style="border-bottom: 0;"></td>
                            <td colspan="1">
                                <p style=" font-weight: 900;">SUB-TOTAL</p>
                            </td>
                            <td colspan="1">
                                <p style=" font-weight: 900;">$
                                    <?= number_format($Datos['cotizacion_total'] / 1.16, 2) ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" style="border-bottom: 0; border-top:0"></td>
                            <td colspan="1">
                                <p style=" font-weight: 900;">16% IVA</p>
                            </td>
                            <td colspan="1">
                                <p style=" font-weight: 900;">$
                                    <?= number_format(($Datos['cotizacion_total'] / 1.16) * .16, 2) ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" style="border-top:0"></td>
                            <td colspan="1">
                                <p style=" font-weight: 900; color: red;">TOTAL</p>
                            </td>
                            <td colspan="1">
                                <p style=" font-weight: 900;">$
                                    <?= number_format($Datos['cotizacion_total'], 2) ?>
                                </p>
                            </td>
                        </tr>
                    </table>

                    <!-- <table> -->
                    <? //php
                    //$partida = 1;
                    // foreach ($Detalleprod as $rows) { ?>
                    <!-- <tr>
                                <td style="font-size:7pt;width:8%;">
                                    <? //= $partida++ ?>
                                </td>
                                <td>
                                    <? //php if ($rows['rl_producto']['producto_imagen'] != '') {
                                    // $img = file_get_contents(Yii::app()->getbaseUrl(true) . '/' . 'archivos/' . $rows['rl_producto']['producto_imagen']);
                                    // $data2 = base64_encode($img);
                                    
                                    ?>
                                        <img src="data:image/jpeg;base64,<? //= $data2 ?>" style="max-height:50px" alt="">
                                        <img src="<? //= Yii::app()->createUrl('archivos/' . $rows['rl_producto']['producto_imagen']) ?>"
                                            style="max-height:50px">
                                    <? //php } ?>
                                </td>
                                <td style="font-size:7pt;text-align:left; padding-left:10px;">
                                    <? //= $rows['rl_producto']['producto_nombre'] . ' ' . $rows['rl_producto']['producto_clave'] ?><br />
                                    <? //= $rows['cotizacion_producto_descripcion'] ?>
                                </td>
                                <td style="font-size:6pt;width:8%; ">
                                    <? //= $rows['rl_producto']['rl_unidadesdemedida']['unidades_medida_nombre'] ?>
                                </td>
                                <td style="font-size:7pt;width:15%;" class="mono">$
                                    <? //= number_format($rows['cotizacion_producto_unitario'], 2) ?>
                                </td>
                                <td class="mono" style="font-size:7pt;width:15%;">
                                    <? //= $rows['cotizacion_producto_cantidad'] ?>
                                </td>
                                <td style="font-size:7pt;width:15%;" class="mono">$
                                    <? //= number_format($rows['cotizacion_producto_total'], 2) ?>
                                </td>
                            </tr> -->
                    <? //php } ?>

                    <!-- <tr>
                            <td colspan="5"></td>
                            <td></td>
                            <td></td>
                        </tr> -->

                    <!-- <tr>
                            <td colspan="5" style="border: 0;"></td>
                            <td>SubTotal:</td>
                            <td class="mono">$
                                <? //= number_format($Datos['cotizacion_total'] / 1.16, 2) ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5"></td>
                            <td>Iva:</td>
                            <td class="mono">$
                                <? //= number_format(($Datos['cotizacion_total'] / 1.16) * .16, 2) ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5">
                                <? //= $this->numtoletras($Datos['cotizacion_total'], $Datos['rl_lista_precio']['rl_moneda']['moneda_nombre']) ?>
                            </td>
                            <td>Total:</td>
                            <td class="mono">$
                                <? //= number_format($Datos['cotizacion_total'], 2) ?>
                            </td>
                        </tr>
                    </table> -->

                    <table style="width:100%;  border:0;">
                        <tr>
                            <td colspan="2" style="border:0;" valign="top">
                                <p style="font-weight: 900;">FLETE COTIZADO</p>
                            </td>
                            <td colspan="4">
                                <?= ($Datos['condiciones_de_pago'] != '') ? $Datos['condiciones_de_pago'] : ''; ?>

                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="border: 0;">
                                <p style="font-weight: 900; color: red;">CONDICIONES</p><!--excluciones/-->
                            </td>
                            <td colspan="4">
                                <p>
                                    <?= $Datos['exclusiones'] ?>
                                </p>
                                <p>
                                    <?= $Datos['tiempo_fabricacion'] ?>
                                </p>
                                <p>
                                    <?= $Datos['vigencia_propuesta'] ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>


            <!-- <? //= ($Datos['tiempo_fabricacion'] != '') ? '<br>Tiempo de fabriación:<br>' . $Datos['tiempo_fabricacion'] : ''; ?>
            <? //= ($Datos['exclusiones'] != '') ? '<br>Exclusiones:<br>' . $Datos['exclusiones'] : ''; ?>
            <? //= ($Datos['vigencia_propuesta'] != '') ? '<br>Vigencia:<br>' . $Datos['vigencia_propuesta'] : ''; ?>
            <? //= ($Datos['nombre_encargado'] != '') ? '<br>Nombre del Encargado:<br>' . $Datos['nombre_encargado'] : ''; ?> -->
            <br />
        </div>

        <htmlpagefooter name="footer">
            <div id="footer">
                <table style="border: 0;">
                    <tr>
                        <td style="text-align:center; border: 0;">
                            <h1 class="heading">ASESOR:
                                <?= $Datos['rl_usuarios']['Usuario_Nombre'] ?>
                            </h1>
                            <p style="font-size: 13; font-weight:900">Cel.
                                <?= $Datos['rl_usuarios']['tel'] ?>
                            </p>
                            <br>

                            <!-- <? //= ($DatosConfiguracion['direccion'] != '') ? $DatosConfiguracion['direccion'] . '<br>' : '' ?>
                            <? //= ($DatosConfiguracion['descripcion'] != '') ? $DatosConfiguracion['descripcion'] . '<br>' : '' ?>
                            <? //= ($DatosConfiguracion['correo'] != '') ? $DatosConfiguracion['correo'] . ' / ' : '' ?>
                            <? //= ($DatosConfiguracion['telefonos'] != '') ? $DatosConfiguracion['telefonos'] . ' / ' : '' ?>
                            <? //= ($DatosConfiguracion['web'] != '') ? $DatosConfiguracion['web'] . '  ' : '' ?> -->
                        </td>
                    </tr>
                </table>
            </div>
        </htmlpagefooter>
        <sethtmlpagefooter name="footer" value="on" />

    </body>

</html>