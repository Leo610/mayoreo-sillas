<!DOCTYPE html>
<html>



    <head>
        <title>REMISION
            <?= $Datos->id_proyecto ?> /
            <?= date('Y', strtotime($Datos->proyecto_fecha_alta)) ?>
        </title>
        <style>
            * {
                margin: 0;
                padding: 0;
                font-family: 'Open Sans", Arial, sans-serif';
                font-size: 10pt;
                color: #000;
            }

            body {
                width: 100%;
                font-family: 'Open Sans", Arial, sans-serif';
                font-size: 10pt;
                margin: 0;
                padding: 0;
                /* text-transform: uppercase; */
                padding-top: 50px !important;

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
                        // $img = file_get_contents(Yii::app()->getbaseUrl(true) . '/companias/2IND_OVI_MED.png');
                        if ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') {
                            $path = Yii::app()->basePath . '/../companias/LOGO_ROSCATOR.png';
                            $tituloEmpresa = 'ROSCATOR INDUSTRIAL <br>SA DE CV';
                            $subtitle = 'Lideres en Proyectos Industriales y Mobiliario';
                            $direccion = '<p style="text-align:center;font-weight: 900; font-size:12px;">Segunda Avenida #321 Col. Guerra, Guadalupe, Nuevo León. C.P. 67140.</p>';
                            $telefonosCorreos = 'ventas3@ritubulares.mx 81-81358299 Y 81-19673067';
                        } else {
                            $path = Yii::app()->basePath . '/../companias/2IND_OVI_MED.png';
                            $tituloEmpresa = $DatosConfiguracion['nombre_compania'];
                            $subtitle = 'Excelencia e Innovación Industrial';
                            $direccion = $DatosConfiguracion['direccion'];
                            $telefonosCorreos = $DatosConfiguracion['correo'] . ' &nbsp;' . $DatosConfiguracion['telefonos'];
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
                                <?= $direccion ?>
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
                                style="text-align: center; color: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; font-size: 16px; font-weight: 900;">
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
                        <?php if ($Datos['rl_usuarios']['bodega'] != '5492' && $Datos['rl_usuarios']['bodega'] != '677' && $Datos['rl_usuarios']['bodega'] != '5491') { ?>
                            <img src="data:image/jpeg;base64,<?= $data ?>" alt=""
                                style="width: 155px; height: auto; margin-top: -6mm;">
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="border:0;">
                        <hr
                            style="width: 95%; color: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; height:3px; background-color: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>;">
                    </td>
                </tr>
            </table>


            <!-- PEDIDO NO. 254
CLIENTE: JESUS ARMANDO RODRIGUEZ VARELA
DIRECCIÓN: MIGUEL HIDALGO 907
FAISANES SUR
GUADALUPE, NUEVO LEÓN
TELÉFONO: 8112878637
E-MAIL: JESUS@GMAIL.COM
 -->
            <table style="border: 0; border-collapse: collapse; border: 0;">
                <tr>
                    <td style=" width: 68%; border: 0;">
                        <div
                            style=" margin-left: 50px; border: 1px <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?> solid; border-radius: 10px; padding: 10px;">
                            <table style="border: 0;">
                                <tr>
                                    <td
                                        style="color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>; border: 0;">
                                        <b
                                            style="color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>;">Cliente:</b>
                                    </td>
                                    <td style="border: 0;font-size:10pt; text-transform: capitalize;">
                                        <?= ($DatosCliente->cliente_nombre != '') ? $DatosCliente->cliente_nombre : ''; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td
                                        style="color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>; border: 0;">
                                        <b
                                            style="color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>;">Dirección:</b>
                                    </td>
                                    <td style="border: 0;font-size:10pt; text-transform: capitalize;">

                                        <?= $DatosCliente->cliente_calle . ' #' . $DatosCliente->cliente_numeroexterior . (!empty($DatosCliente->cliente_colonia) ? ' COL. ' . $DatosCliente->cliente_colonia : ''), (($DatosCliente->cliente_numerointerior != '') ? '#' . $DatosCliente->cliente_numerointerior : ''), ' CP' . $DatosCliente->cliente_codigopostal ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td
                                        style="color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>; border: 0;">
                                        <b
                                            style="color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>;">Localidad</b>
                                    </td>
                                    <td style="border: 0;font-size:10pt; text-transform: uppercase;">
                                        <!-- <?//= !empty ($Datos['localidad']) ? $Datos['localidad'] : '';   ?> -->
                                        <?= $DatosCliente->cliente_municipio . ', ' . $DatosCliente->cliente_entidad ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td
                                        style="color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>; border: 0;">
                                        <b
                                            style="color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>;">Empresa:</b>
                                    </td>
                                    <td style="border: 0;font-size:10pt; text-transform: capitalize;">
                                        <?= ($DatosCliente['rl_empresas']['empresa'] != '') ? $DatosCliente['rl_empresas']['empresa'] : ''; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td
                                        style="color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>; border: 0;">
                                        <b
                                            style="color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>;">E-mail:</b>
                                    </td>
                                    <td style="border: 0;font-size:10pt; ">
                                        <?= ($DatosCliente->cliente_email != '') ? $DatosCliente->cliente_email : ''; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td
                                        style="color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>; border: 0;">
                                        <b
                                            style="color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>;">Teléfono:</b>
                                    </td>
                                    <td style="border: 0;font-size:10pt;;">
                                        <?= ($DatosCliente->cliente_telefono != '') ? $DatosCliente->cliente_telefono : ''; ?>
                                    </td>
                                </tr>

                            </table>
                        </div>
                    </td>
                    <td style="border:0;">
                        <div
                            style="margin-left: 10px; border: 1px <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?> solid; border-radius: 10px; padding: 10px;">
                            <table style="border: 0;">
                                <tr>
                                    <td
                                        style="color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>; border: 0;">
                                        <b
                                            style="color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>;">Pedido
                                            No:</b>
                                    </td>
                                    <td
                                        style="border: 0;font-size:10pt; text-transform: capitalize; text-align: right;">
                                        <?= $Datos['id_proyecto'] ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td
                                        style="color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>; border: 0;">
                                        <b
                                            style="color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>;">Fecha:</b>
                                    </td>
                                    <td style="border: 0;font-size:10pt; text-transform: capitalize;text-align: right;">
                                        <?= $fecha ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td
                                        style="color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>; border: 0;">
                                        <b
                                            style="color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>;">Asesor:</b>
                                    </td>
                                    <td style="border: 0;font-size:10pt; text-transform: capitalize;text-align: right;">
                                        <?= $Datos['rl_usuarios']['Usuario_Nombre'] ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td
                                        style="color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>; border: 0;">
                                        <b
                                            style="color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>;">Asesor
                                            Tel:</b>
                                    </td>
                                    <td style="border: 0;font-size:10pt; text-transform: capitalize;text-align: right;">
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
                    style="color: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; font-size:20px;">REMISION</b>
            </p>
            <!-- <p
                style="color: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>;font-size:15px;">
                A continuación ponemos a su consideración los siguientes precios
            </p> -->
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
                    $total = 0;
                    $partida = 1;
                    foreach ($Productosproyecto as $rows) { ?>
                        <tr>
                            <!-- cantidad -->
                            <td class="mono"
                                style="margin-bottom: 0; font-size:10pt;width:15mm; border: 1px solid <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; color: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>;">
                                <?= $rows['proyectos_productos_cantidad'] ?>
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
                                style="font-size:8pt;text-align:left; padding-left:10px;border: 1px solid <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; color: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>; width: 100mm;">
                                <!-- <? //= $rows['rl_producto']['producto_nombre'] . ' ' . $rows['rl_producto']['producto_clave'] 
                                    ?><br /> -->
                                <?= '<b style="font-size:8pt; color:' . (($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b') . '">' . $rows['rl_producto']['producto_nombre'] . ': ' . '</b>' . $rows['proyectos_productos_descripcion'] ?>
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
                            <!-- precio -->
                            <td style="font-size:10pt;width:20mm; border: 1px solid <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; color: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>;"
                                class="mono">$
                                <?= number_format($rows->precio_venta_producto, 2) ?>
                            </td>
                            <!-- totao -->
                            <td style="font-size:10pt;width:20mm; border: 1px solid <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; color: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>;"
                                class="mono">$
                                <?= number_format(($rows['proyectos_productos_cantidad'] * $rows['precio_venta_producto']), 2) ?>
                            </td>
                        </tr>

                        <?php $total = $total + ($rows['proyectos_productos_cantidad'] * $rows['precio_venta_producto']);
                    } ?>
                </table>
                <table style="width: 190mm; border: 0;" cellspacing="0" cellpadding="0">
                    <tr>
                        <td
                            style="border: 1px solid <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>;width: 75%; ">
                            <p
                                style="font-size:12pt; color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>;">
                                FLETE POR CUENTA Y RIESGO DEL CLIENTE <br><br>
                                <?= !empty($obs) ? 'Observaciones:' : '' ?>
                            </p>
                            <div
                                style="padding:10px; color:<?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>;">
                                <?= $obs ?>
                            </div>
                        </td>
                        <td style="padding:0;border: 1px solid <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>;"
                            valign="top">
                            <table style="margin:0;width: 100%;border:0">
                                <tr>
                                    <td style="font-size:10pt;width:20mm; border-bottom: 1px solid <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; color: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>;padding-top: 10px;padding-bottom:10px ;;border-right: 0;"
                                        class="mono">
                                        Subtotal</td>
                                    <td style="font-size:10pt;width:25mm; border-bottom: 1px solid <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; color: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>;;text-align: right;"
                                        class="mono">$
                                        <?= number_format($total, 2) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size:10pt;width:20mm; border-bottom: 1px solid <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; color: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>;padding-top: 10px;padding-bottom:10px ;;border-right: 0;"
                                        class="mono">
                                        16% IVA
                                    </td>
                                    <td style="font-size:10pt;width:25mm; border-bottom: 1px solid <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; color: <?= ($Datos['rl_usuarios']['bodega'] == '5492' || $Datos['rl_usuarios']['bodega'] == '677') ? '#202120' : '#8c663b' ?>;;text-align: right;"
                                        class="mono">$
                                        <!-- <//?= number_format(($total) * .16, 2) ?> -->
                                        <?= ($Datos->sumar_iva == 1) ? number_format(($total) * .16, 2) : 0 ?>
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
                                        <!-- <//?= number_format($total + ($total * .16), 2) ?> -->
                                        <?= ($Datos->sumar_iva == 1) ? number_format($total + ($total * .16), 2) : number_format($total, 2) ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <br>

        <!-- EX FORMATO -->
        <!--
        <div id="wrapper">
            <br>
            <table class="heading" style="width:100%; border:0;">
                <tr>
                    <td style="width:40mm;border:0; text-align:right;  vertical-align: bottom;">
                    <?php
                    if ($Datos['rl_usuarios']['bodega'] == '5492') {
                        $path = Yii::app()->basePath . '/../companias/LOGO_ROSCATOR.png';
                        $direccion = 'Segunda Avenida No 321, Colonia Guerra, Guadalupe Nuevo León C.P. 67140';
                        $direccionDatos = 'E-MAIL: ventas3@ritubulares.mx WEB: www.ritubulares.mx Cel. 81-354927209, Tel. 81-81358299';
                        $tituloEmpresa = 'ROSCATOR INDUSTRIAL, <br>SA DE CV';
                        $subtitle = 'Lideres en Proyectos Industriales y Mobiliario';
                    } else {
                        $path = Yii::app()->basePath . '/../companias/2IND_OVI_MED.png';
                        $direccion = $DatosConfiguracion['direccion'];
                        $direccionDatos = 'E-MAIL: ' . $DatosConfiguracion['correo'] . 'WEB: ' . $DatosConfiguracion['web'] . 'Tels: ' . $DatosConfiguracion['telefonos'];
                        $tituloEmpresa = $DatosConfiguracion['nombre_compania'];
                        $subtitle = 'Excelencia e Innovación Industrial';
                    }

                    // $img = file_get_contents(Yii::app()->getbaseUrl(true) . '/companias/2IND_OVI_MED.png');
                    $img = file_get_contents($path);
                    // Encode the image string data into base64
                    $data = base64_encode($img);
                    ?>
                        <img src="data:image/jpeg;base64,<?= $data ?>" alt="" style="width: 130px; height: auto;">

                    </td>

                    <td style="width:120mm;border:0;">
                        <h1 style="text-align: center;"><b style="font-size: 30px;">
                                <?= $tituloEmpresa ?>
                            </b></h1><br>
                        <b>
                            <p style="text-align: center; color: #8c663b; font-size: 16px; font-weight: 900;">
                                <?= $subtitle ?>
                            </p>
                        </b>
                    </td>

                    <td class="logotipo" style="width:40mm;border:0;">
                         <?= $this->ObtenerLogotipo(); ?> **
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
                                <?= $direccion ?>
                            </p>
                        </b>
                        <b>
                            <p style="font-weight: 900; font-size:12px;">
                                <?= $direccionDatos ?>
                            </p>
                        </b>

                    </td>
                </tr>
                <br>

                <tr>
                    <td colspan="2" style="border: 0;">
                        <p style="font-weight: 900; font-size:13px;">
                            PEDIDO NO.
                            <?= $Datos['id_proyecto'] ?> <br>
                            CLIENTE:
                            <?= ($DatosCliente->cliente_nombre != '') ? $DatosCliente->cliente_nombre : ''; ?>
                        </p>
                        <p style="font-weight: 900; font-size:13px;">DIRECCIÓN:

                            <?= $DatosCliente['cliente_calle'] ?>
                            <?= $DatosCliente['cliente_numeroexterior'] ?><br>
                            <?= $DatosCliente['cliente_colonia'] ?><br>
                            <?= ($DatosCliente->cliente_municipio != '') ? $DatosCliente->cliente_municipio : '';
                            echo ', ';
                            echo ($DatosCliente->cliente_entidad != '') ? ' ' . $DatosCliente->cliente_entidad : '';

                            ?>
                        </p>



                        <p style="font-weight: 900; font-size:13px;">Localidad de entrega:
                        <?= !empty($Datos['localidad']) ? $Datos['localidad'] : ''; ?>
                     </p>**
                        <p style="font-weight: 900; font-size:13px;">EMPRESA:
                            <? //= ($DatosCliente['rl_empresas']['empresa'] != '') ? $DatosCliente['rl_empresas']['empresa'] : ''; 
                            ?>
                        </p>**
                        <p style="font-weight: 900; font-size:13px;">TELÉFONO:
                            <?= ($DatosCliente->cliente_telefono != '') ? $DatosCliente->cliente_telefono : ''; ?>
                        </p>
                        <p style="font-weight: 900; font-size:13px;">E-MAIL:
                            <?= ($DatosCliente->cliente_email != '') ? $DatosCliente->cliente_email : ''; ?>
                        </p>

                    </td>

                    <td colspan="1" style="border: 0;">
                        <div style="margin-left:-100px">
                            <p style="font-weight: 900; font-size:13px;">FECHA:
                                <?= $fecha ?>
                            </p>
                            <p style="font-weight: 900; font-size:13px;">REMISION: <b style="color:red">
                                    <?= $Datos['id_cotizacion'] ?>
                                </b></p>
                        </div>
                        <p style="font-weight: 900; font-size:13px;">ASESOR:
                            <? //= $Datos['rl_usuarios']['Usuario_Nombre'] 
                            ?>
                        </p>**

                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center; border: 0;">
                        <p style="font-size: 16px;font-weight: 700; color: red;">REMISION</p>
                        <p style="font-size: 14px; font-weight: 900;">A CONTINUACIÓN PONEMOS A SU CONSIDERACIÓN LOS
                            SIGUIENTES PRECIOS</p>**

                    </td>
                </tr>
            </table>
            <div id="content" style="margin-right: 25px;">
                <div id="invoice_body">
                    <table>
                        <tr style="background:#8c6635;">
                            <td style="width: 20mm;">
                                <p style="color:#fff; font-weight: 900;">CANTIDAD</p>
                            </td>
                            <td style="width: 20mm;">
                                <p style="color:#fff; font-weight: 900;">IMAGEN</p>
                            </td>
                            <td style="width: 110mm;">
                                <p style="color:#fff; font-weight: 900;">DESCRIPCIÓN</p>
                            </td>
                            <td style="width: 20mm;">
                                <p style="color:#fff; font-weight: 900;">PRECIO</p>
                            </td>
                            <td style="width: 20 mm;">
                                <p style="color:#fff; font-weight: 900;">TOTAL</p>
                            </td>
                        </tr>
                        <tr>

                            <?php
                            $total = 0;
                            $partida = 1;
                            foreach ($Productosproyecto as $rows) { ?>
                            <tr>
                                cantidad
                                <td class="mono" style="font-size:10pt;width:10%;">
                                    <?= $rows['proyectos_productos_cantidad'] ?>
                                </td>
                                imagen
                                <td style="font-size:10pt;width:10%;">
                                    <?php if ($rows['rl_producto']['producto_imagen'] != '') {
                                        // $img = file_get_contents(Yii::app()->getbaseUrl(true) . '/companias/8131-2f766e5e-b985-4241-89d7-dc2842c14757.jpeg');
                                        $img = file_get_contents(Yii::app()->basePath . '/../archivos/' . $rows['rl_producto']['producto_imagen']);

                                        $data2 = base64_encode($img);
                                        ?>

                                        <img src="data:image/jpeg;base64,<?= $data2 ?>" style="max-height:50px" alt="">
                                        <img src="<? //= Yii::app()->createUrl('archivos/' . $rows['rl_producto']['producto_imagen']) 
                                                ?>"
                                            style="max-height:50px">**
                                    <?php } ?>
                                </td>
                                descripcion
                                <td style="font-size:10pt;text-align:left; padding-left:10px;">

                                    <?= '<b>' . $rows['rl_producto']['producto_nombre'] . ': ' . '</b>' . $rows['proyectos_productos_descripcion'] ?>
                                </td>
                                precio
                                <td style="font-size:10pt;width:15%;" class="mono">
                                    $
                                    <?= number_format($rows->precio_venta_producto, 2) ?>
                                </td>
                                totao
                                <td style="font-size:10pt;width:10%;" class="mono">$
                                    <?= number_format(($rows['proyectos_productos_cantidad'] * $rows['precio_venta_producto']), 2) ?>
                                </td>
                            </tr>

                            <?php
                            $total = $total + ($rows['proyectos_productos_cantidad'] * $rows['precio_venta_producto']);
                            } ?>


                        </tr>
                        <tr>
                            <td colspan="3" rowspan="3">
                                <b>Observaciones</b> <br><br>
                                <?= $obs ?>
                                </t d>
                            <td colspan="1">
                                <p style=" font-weight: 900;">SUB-TOTAL</p>
                            </td>
                            <td colspan="1">
                                <p style=" font-weight: 900;">$
                                    <?= number_format($total, 2) ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" style="border-bottom: 0; border-top:0"></td>**
                            <td colspan="1">
                                <p style=" font-weight: 900;">16% IVA</p>
                            </td>
                            <td colspan="1">
                                <p style=" font-weight: 900;">$
                                    <?= number_format(($total) * .16, 2) ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" style="border-top:0"></td>**
                            <td colspan="1">
                                <p style=" font-weight: 900; color: red;">TOTAL</p>
                            </td>
                            <td colspan="1">
                                <p style=" font-weight: 900;">$
                                    <?= number_format($total + ($total * .16), 2) ?>
                                </p>
                            </td>
                        </tr>

                    </table>

                    <br>
                    <br>
                    <br>

                    <div>
                        <b>Observaciones</b>
                        <br>
                        <br>
                        <p style="display: inline-block; margin-left: 10px;">
                            _______________________________________________________________________________________________________________________
                        </p>
                        <br>
                        <br>
                        <p style="display: inline-block; margin-left: 10px;">
                            _______________________________________________________________________________________________________________________
                        </p>
                        <br>
                        <br>
                        <p style="display: inline-block; margin-left: 10px;">
                            _______________________________________________________________________________________________________________________
                        </p>
                    </div>**

                    <br>
                    <br>
                    <br>
                    <div>
                        LAB: MONTERREY NL. (FLETE POR CUENTA Y RIESGO DEL CLIENTE)
                    </div>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>

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
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </htmlpagefooter/>
-->