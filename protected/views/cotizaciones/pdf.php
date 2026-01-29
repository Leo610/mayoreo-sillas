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
                font-family: 'Open Sans", Arial, sans-serif';
                font-size: 10pt;
                /* color: #000; */
            }

            body {
                width: 100%;
                font-family: 'Open Sans", Arial, sans-serif';
                font-size: 10pt;
                margin: 0;
                /* padding: 20px; */
                /* text-transform: uppercase; */
                padding-top: 20px !important;
                padding-bottom: 20px !important;

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
                /* text-transform: uppercase; */
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
                /* page-break-after: always;
                page-break-before: always; */

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
                padding: 1mm;
            }

            table.heading {
                /* height: 50mm; */
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
                position: absolute;
                bottom: 0;
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

            .condiciones b,
            em,
            strong,
            p,
            tr td,
            ul li {
                font-size: 8pt;
                /* border: 1px solid red !important; */
            }

            .condiciones ul li {
                margin-left: 7px;
                /* border: 1px solid red !important; */
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
                        // echo "<pre>";
                        // print_r($Datos['rl_usuarios']['bodega']);
                        // echo "</pre>";
                        // exit();
                        // $img = file_get_contents(Yii::app()->getbaseUrl(true) . '/companias/2IND_OVI_MED.png');
                        if ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') {
                            $path = Yii::app()->basePath . '/../companias/LOGO_ROSCATOR.png';
                            $tituloEmpresa = 'ROSCATOR INDUSTRIAL<br>SA DE CV';
                            $direccion = '<p style="text-align:center;font-weight: 900; font-size:12px;">Segunda Avenida #321 Col. Guerra, Guadalupe, Nuevo León. C.P. 67140.</p>';
                            $telefonosCorreos = 'ventas3@ritubulares.mx 81-81358299 Y 81-19673067';
                            $subtitle = 'Lideres en Proyectos Industriales y Mobiliario';
                        } else {
                            $path = Yii::app()->basePath . '/../companias/2IND_OVI_MED.png';
                            $tituloEmpresa = $DatosConfiguracion['nombre_compania'];
                            $subtitle = 'Excelencia e Innovación Industrial';
                            $direccion = $DatosConfiguracion['direccion'];
                            $telefonosCorreos = $DatosConfiguracion['correo'] . ' &nbsp;' . $DatosConfiguracion['telefonos'];
                            ;
                        }

                        $img = file_get_contents($path);
                        // Encode the image string data into base64
                        $data = base64_encode($img);
                        ?>
                        <img src="data:image/jpeg;base64,<?= $data ?>" alt=""
                            style="width: 130px; height: auto; margin-top: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '-39mm' : '-29mm' ?>">

                    </td>

                    <td style="width:120mm;border:0;">
                        <h1 style="text-align: center;"><b style="font-size: 30px;">
                                <?= $tituloEmpresa ?>
                            </b></h1><br>
                        <b>
                            <p style=" font-weight: 900; font-size:12px;">

                                <?= $direccion; ?>

                            </p>
                        </b>
                        <b>
                            <p style="font-weight: 900; font-size:12px; text-align:center;">
                                <?= $telefonosCorreos ?>
                            </p>
                        </b>
                        <br>
                        <b>
                            <p
                                style="text-align: center; color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; font-size: 16px; font-weight: 900;">
                                <?= $subtitle ?>
                            </p>
                        </b>
                    </td>

                    <td class="logotipo" style="width:40mm;border:0;">
                        <!-- <?= $this->ObtenerLogotipo(); ?> -->
                        <?php
                        $img = file_get_contents(Yii::app()->basePath . '/../companias/8131-2f766e5e-b985-4241-89d7-dc2842c14757.jpeg');
                        // Encode the image string data into base64
                        $data = base64_encode($img);
                        ?>
                        <?php
                        if ($Datos['rl_usuarios']['bodega'] != '5492' && $Datos['rl_usuarios']['bodega'] != '677' && $Datos['rl_usuarios']['bodega'] != '5491') { ?>

                            <img src="data:image/jpeg;base64,<?= $data ?>" alt=""
                                style="width: 155px; height: auto; margin-top: -6mm;">

                        <?php }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="border:0;">
                        <hr
                            style="width: 95%; color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; height:3px; background-color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>;">
                    </td>
                </tr>
            </table>


            <table style="border: 0; border-collapse: collapse; border: 0;">
                <tr>
                    <td style=" width: 45%; border: 0;">
                        <div
                            style=" margin-left: 100px; border: 1px <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?> solid; border-radius: 10px; padding: 10px;">
                            <table style="border: 0;">
                                <tr>
                                    <td
                                        style="color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>; border: 0;">
                                        <b
                                            style="color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>;">Cliente:</b>
                                    </td>
                                    <td
                                        style="border: 0;font-size:10pt; text-transform: capitalize; color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>;">
                                        <?= ($DatosCliente->cliente_nombre != '') ? $DatosCliente->cliente_nombre : ''; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color:#8c6635; border: 0;"><b
                                            style="color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>;">Dirección:</b>
                                    </td>
                                    <td
                                        style="border: 0;font-size:10pt; text-transform: capitalize; color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>;">
                                        <?= ($DatosCliente->cliente_municipio != '') ? $DatosCliente->cliente_municipio : ''; ?>
                                        <?= ($DatosCliente->cliente_entidad != '') ? ', ' . $DatosCliente->cliente_entidad : ''; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color:#8c6635; border: 0;"><b
                                            style="color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>;">Empresa:</b>
                                    </td>
                                    <td
                                        style="border: 0;font-size:10pt; text-transform: capitalize; color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>;">
                                        <?= ($DatosCliente['rl_empresas']['empresa'] != '') ? $DatosCliente['rl_empresas']['empresa'] : ''; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color:#8c6635; border: 0;"><b
                                            style="color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>;">E-mail:</b>
                                    </td>
                                    <td
                                        style="border: 0;font-size:10pt;  color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>;">
                                        <?= ($DatosCliente->cliente_email != '') ? $DatosCliente->cliente_email : ''; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color:#8c6635; border: 0;"><b
                                            style="color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>;">Teléfono:</b>
                                    </td>
                                    <td
                                        style="border: 0;font-size:10pt; color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>;">
                                        <?= ($DatosCliente->cliente_telefono != '') ? $DatosCliente->cliente_telefono : ''; ?>
                                    </td>
                                </tr>

                            </table>
                        </div>
                    </td>
                    <td style="border:0;">
                        <div
                            style="margin-left: 100px; border: 1px <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?> solid; border-radius: 10px; padding: 10px;">
                            <table style="border: 0;">
                                <tr>
                                    <td style="color:#8c6635; border: 0;"><b
                                            style="color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>;">Cotización:</b>
                                    </td>
                                    <td
                                        style="border: 0;font-size:10pt; text-transform: capitalize;text-align: right; color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>;">
                                        <?= $Datos->id_cotizacion ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color:#8c6635; border: 0;"><b
                                            style="color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>;">Fecha:</b>
                                    </td>
                                    <td
                                        style="border: 0;font-size:10pt; text-transform: capitalize;text-align: right; color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>;">
                                        <?= $fecha ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color:#8c6635; border: 0;"><b
                                            style="color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>;">Asesor:</b>
                                    </td>
                                    <td
                                        style="border: 0;font-size:10pt; text-transform: capitalize;text-align: right; color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>;">
                                        <?= $Datos['rl_usuarios']['Usuario_Nombre'] ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color:#8c6635; border: 0;"><b
                                            style="color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>;">Asesor
                                            Tel:</b></td>
                                    <td
                                        style="border: 0;font-size:10pt; text-transform: capitalize;text-align: right; color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>;">
                                        <?= $Datos['rl_usuarios']['tel'] ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <br>

        <div style="text-align: center;">
            <p><b
                    style="color: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; font-size:20px;">COTIZACIÓN</b>
            </p>
            <p
                style="color: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>;font-size:15px;">
                A continuación ponemos a su consideración los siguientes precios
            </p>
            <br>

            <div style="margin-left: 50px;">
                <table style="width: 190mm; border: 0;">

                    <tr>
                        <td
                            style="color:white; text-align: center; background: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; border-top-left-radius: 15px;font-size: 10pt;">
                            Cantidad</td>
                        <td
                            style="color:white; text-align: center; background: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>;font-size: 10pt;">
                            Imagen</td>
                        <td
                            style="color:white; text-align: center; background: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>;font-size: 10pt;">
                            Descripción
                        </td>
                        <td
                            style="color:white; text-align: center; background: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>;font-size: 10pt;">
                            Precio</td>
                        <td
                            style="color:white; text-align: center; background: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; border-top-right-radius: 15px;font-size: 10pt; border:0;">
                            Total</td>
                    </tr>


                    <?php

                    $partida = 1;
                    foreach ($Detalleprod as $rows) { ?>
                        <tr>
                            <!-- cantidad -->
                            <td class="mono"
                                style="margin-bottom: 0; font-size:10pt;width:15mm; border: 1px solid <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; color: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>;">
                                <?= $rows['cotizacion_producto_cantidad'] ?>
                            </td>
                            <!-- imagen -->
                            <td
                                style="font-size:10pt;width:15mm; border: 1px solid <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; text-align:center;">
                                <?php if ($rows['rl_producto']['producto_imagen'] != '') {
                                    // $img = file_get_contents(Yii::app()->getbaseUrl(true) . '/companias/8131-2f766e5e-b985-4241-89d7-dc2842c14757.jpeg');
                                    $img = file_get_contents(Yii::app()->basePath . '/../archivos/' . $rows['rl_producto']['producto_imagen']);

                                    $data2 = base64_encode($img);
                                    ?>

                                    <img src="data:image/jpeg;base64,<?= $data2 ?>" style="max-height:50px" alt="">
                                    <!-- <img src="<? //= Yii::app()->createUrl('archivos/' . $rows['rl_producto']['producto_imagen']) 
                                            ?>"
                                            style="max-height:50px"> -->
                                <?php } ?>
                            </td>
                            <!-- descripcion -->
                            <td
                                style="font-size:8pt;text-align:left; padding-left:10px;border: 1px solid <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>; width: 100mm;">
                                <!-- <? //= $rows['rl_producto']['producto_nombre'] . ' ' . $rows['rl_producto']['producto_clave'] 
                                    ?><br /> -->
                                <?= '<b style="font-size:8pt; color:' . (($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b') . '">' . $rows['rl_producto']['producto_nombre'] . ': ' . '</b>' . $rows['cotizacion_producto_descripcion'] ?>

                                <?php
                                if (!empty($rows['especificaciones_extras'])) { ?>
                                    <br>

                                    <p
                                        style="font-size:9pt;text-align:left; color: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>;">
                                        <b
                                            style="color: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>;">Especificaciones
                                            extras: </b>
                                        <?= $rows['especificaciones_extras'] ?>.
                                    </p>


                                <?php } ?>
                                <?php
                                if (!empty($rows['color'])) { ?>
                                    <p
                                        style="font-size:10pt;text-align:left; color: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>;">
                                        <b
                                            style="color: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>;">Color
                                            estructura: </b>
                                        <?= $rows['color'] ?>
                                    </p>

                                <?php } ?>
                                <?php
                                if (!empty($rows['color_tapiceria'])) { ?>

                                    <p
                                        style="font-size:10pt;text-align:left; color: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>;">
                                        <b
                                            style="color: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>;">Color
                                            tapiceria: </b>
                                        <?= $rows['color_tapiceria'] ?>
                                    </p>

                                <?php } ?>
                            </td>
                            <td style="font-size:10pt;width:20mm; border: 1px solid <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>;color: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>;"
                                class="mono">
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
                            <td style="font-size:10pt;width:20mm; border: 1px solid <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; color: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>;"
                                class="mono">$
                                <?= number_format($rows['cotizacion_producto_total'], 2) ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
                <table style="width: 190mm; border: 0;" cellspacing="0" cellpadding="0">
                    <tr>
                        <td
                            style="border: 1px solid <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>;width: 75%; ">
                            <p
                                style="font-size:12pt; color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>;">
                                Condiciones generales:
                            </p>
                            <div style="padding:10px;">
                                <?= $Datos['condiciones_de_pago']; ?>
                            </div>
                        </td>
                        <td style="padding:0;border: 1px solid <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>;"
                            valign="top">
                            <table style="margin:0;width: 100%;border:0">
                                <tr>
                                    <td style="font-size:10pt;width:20mm; border-bottom: 1px solid <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; color: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>;padding-top: 10px;padding-bottom:10px ;border-right: 0;"
                                        class="mono">
                                        Subtotal</td>
                                    <td style="font-size:10pt;width:25mm; border-bottom: 1px solid <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; color: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>;;text-align: right;"
                                        class="mono">$
                                        <!-- <//?= number_format($Datos['cotizacion_total'], 2) ?> -->
                                        <!-- <//?= number_format($Datos['cotizacion_total'] / 1.16, 2) ?> -->
                                        <?= ($Datos->sumar_iva == 1) ? number_format($Datos['cotizacion_total'] / 1.16, 2) : number_format($Datos['cotizacion_total'], 2) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size:10pt;width:20mm; border-bottom: 1px solid <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; color: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>;padding-top: 10px;padding-bottom:10px ;;border-right: 0;"
                                        class="mono">
                                        16% IVA
                                    </td>
                                    <td style="font-size:10pt;width:25mm; border-bottom: 1px solid <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; color: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>;;text-align: right;"
                                        class="mono">$
                                        <!-- <//?= number_format(($Datos['cotizacion_total'] / 1.16) * .16, 2) ?> -->
                                        <?= ($Datos->sumar_iva) ? number_format(($Datos['cotizacion_total'] / 1.16) * .16, 2) : 0 ?>
                                        <!-- 0 -->
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size:10pt;width:20mm;  color: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>;padding-top: 10px;padding-bottom:10px ;border-bottom:0;border-right: 0;"
                                        class="mono">
                                        TOTAL
                                    </td>
                                    <td style="font-size:10pt;width:25mm;  color: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>;border-bottom:0;text-align: right;"
                                        class="mono">$
                                        <?= number_format($Datos['cotizacion_total'], 2) ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <br>

        <!-- Nueva tabla para la "tablitita" -->

        <!-- <htmlpagefooter name="footer" style="position: absolute; bottom: 0;">

            <div
                style="width: 80%; margin-left:90px;  border: 1px solid #8c6635; border-top-left-radius:15px;  border-top-right-radius:15px;">
                <table style="width: 100%; border :0;">
                    <tr>
                        <td style="text-align: center; border:0;">
                            <p style=" text-align: center; font-size: 13; color: #8c6635; font-size:10pt;">Asesor:
                                <?= $Datos['rl_usuarios']['Usuario_Nombre'] ?>
                            </p>
                        </td>
                        <td style="border-left: 1px solid #8c6635; border-bottom: 0; border-right: 0; border-top:0;">
                        </td>
                        <td style="text-align: center; border:0;">
                            <p style=" font-size: 13; color: #8c6635;font-size:10pt;">Cel.
                                <?= $Datos['rl_usuarios']['tel'] ?>
                            </p>
                        </td>
                    </tr>
                </table>
            </div>


            <div style="background: #8c6635; width: 100%;">
                <br>
                <br>
                <table style="width: 90%; margin-left: 40px; border:0;">
                    <tr>
                        <td style="border:0; text-align: center;font-size:10pt;">
                            <?php
                            $img = file_get_contents(Yii::app()->basePath . '/../companias/icono_ubicacion.png');
                            $dataiu = base64_encode($img);
                            ?>
                            <img src="data:image/jpeg;base64,<?= $dataiu ?>"
                                style="width: 18px; height: auto;font-size:10pt;" alt="">
                        </td>
                        <td style="border:0; text-align: center;font-size:10pt;">

                            <?php
                            $img = file_get_contents(Yii::app()->basePath . '/../companias/icono_email.png');
                            $datae = base64_encode($img);
                            ?>
                            <img src="data:image/jpeg;base64,<?= $datae ?>" style="width: 18px; height: auto;" alt="">

                        </td>
                        <td style="border:0; text-align: center;font-size:10pt;">

                            <?php
                            $img = file_get_contents(Yii::app()->basePath . '/../companias/icono_web.png');
                            $dataweb = base64_encode($img);
                            ?>
                            <img src="data:image/jpeg;base64,<?= $dataweb ?>"
                                style="width: 18px; height: auto;font-size:10pt;" alt="">

                        </td>
                        <td style="border:0; text-align: center;font-size:10pt;">

                            <?php
                            $img = file_get_contents(Yii::app()->basePath . '/../companias/icono_telephone.png');
                            $datatel = base64_encode($img);
                            ?>
                            <img src="data:image/jpeg;base64,<?= $datatel ?>"
                                style="width: 18px; height: auto;font-size:10pt;" alt="">

                        </td>
                    </tr>
                    <tr>
                        <td style="color:white; border:0;font-size:10pt;">

                            <?= $DatosConfiguracion['direccion'] ?>

                        </td>
                        <td style="color:white; border:0;font-size:10pt;">
                            <?= $DatosConfiguracion['correo'] ?>
                        </td>
                        <td style="color:white; border:0;font-size:10pt;">
                            <?= $DatosConfiguracion['web'] ?>
                        </td>
                        <td style="color:white; border:0;font-size:10pt;">
                            <?= $DatosConfiguracion['telefonos'] ?>
                        </td>
                    </tr>
                </table>
                <br>
                <br>
            </div>

        </htmlpagefooter> -->
        <!-- 
    