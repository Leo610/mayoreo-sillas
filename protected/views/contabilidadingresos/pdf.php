<!DOCTYPE html>
<html>

<head>
    <title>Recibo de pago
        <?= $ingreso->id_contabilidad_ingresos ?> /
        <?= date('Y', strtotime($ingreso->contabilidad_ingresos_fechaalta)) ?>
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
                    if ($proyecto['rl_usuarios']['bodega'] == '5492' || $proyecto['rl_usuarios']['bodega'] == '677') {
                        $path = Yii::app()->basePath . '/../companias/LOGO_ROSCATOR.png';
                        $tituloEmpresa = 'ROSCATOR INDUSTRIAL <br>SA DE CV';
                        $subtitle = 'Lideres en Proyectos Industriales y Mobiliario';
                        $direccion = 'Segunda Avenida #321 Col. Guerra, Guadalupe, Nuevo León. C.P. 67140.';
                        $telefonosCorreos = 'ventas3@ritubulares.mx  &nbsp; 81-81358299 Y 81-19673067';
                        $email = 'ventas3@ritubulares.mx ';
                    } else {
                        $path = Yii::app()->basePath . '/../companias/2IND_OVI_MED.png';
                        $tituloEmpresa = $DatosConfiguracion['nombre_compania'];
                        $subtitle = 'Excelencia e Innovación Industrial';
                        $direccion = $DatosConfiguracion['direccion'];
                        $telefonosCorreos = $DatosConfiguracion['correo'] . ' &nbsp;' . $DatosConfiguracion['telefonos'];
                        $email = $DatosConfiguracion['correo'];
                    }

                    $img = file_get_contents($path);
                    // Encode the image string data into base64
                    $data = base64_encode($img);
                    ?>
                    <img src="data:image/jpeg;base64,<?= $data ?>" alt=""
                        style="width: 130px; height: auto;margin-top: <?= ($proyecto['rl_usuarios']['bodega'] == '5492' || $proyecto['rl_usuarios']['bodega'] == '677') ? '-39mm' : '-29mm' ?>">

                </td>

                <td style="width:120mm;border:0;">
                    <h1 style="text-align: center;"><b style="font-size: 30px;">
                            <?= $tituloEmpresa ?>
                        </b></h1><br>
                    <b>
                        <p style=" font-weight: 900; font-size:12px;text-align: center;">
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
                            style="text-align: center; color:<?= ($proyecto['rl_usuarios']['bodega'] == '5492' || $proyecto['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; font-size: 16px; font-weight: 900;">
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
                    <?php if ($proyecto['rl_usuarios']['bodega'] != '5492' && $proyecto['rl_usuarios']['bodega'] != '677' && $Datos['rl_usuarios']['bodega'] != '5491') { ?>
                        <img src="data:image/jpeg;base64,<?= $data ?>" alt=""
                            style="width: 155px; height: auto; margin-top: -6mm">
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="border:0;">
                    <hr
                        style="width: 95%; color: <?= ($proyecto['rl_usuarios']['bodega'] == '5492' || $proyecto['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; height:3px; background-color: <?= ($proyecto['rl_usuarios']['bodega'] == '5492' || $proyecto['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>;">
                </td>
            </tr>
        </table>
        <br>

        <table style=" margin-left:20px;width: 190mm; border:0;">
            <tr>
                <td style="border: 0;">
                    <p
                        style=" font-weight:900; font-size:14px;background: <?= ($proyecto['rl_usuarios']['bodega'] == '402' || $proyecto['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; color: white; text-align: center; padding: 8px 20px;">
                        Número de recibo:</p>
                    <p
                        style=" color: <?= ($proyecto['rl_usuarios']['bodega'] == '402' || $proyecto['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; font-size: 14px; padding: 8px 20px; text-align: center; border: 1px solid <?= ($proyecto['rl_usuarios']['bodega'] == '402' || $proyecto['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; border-bottom-right-radius:20px; border-bottom-left-radius:20px;">
                        <?= $ingreso['id_contabilidad_ingresos'] ?>
                    </p>

                </td>
                <td style="border: 0; width: 100mm;"></td>
                <td style="border: 0; ">
                    <p
                        style=" font-weight:900; font-size:14px;background: <?= ($proyecto['rl_usuarios']['bodega'] == '5492' || $proyecto['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; color: white; text-align: center; padding: 8px 20px;">
                        Fecha:</p>
                    <p
                        style=" color: <?= ($proyecto['rl_usuarios']['bodega'] == '5492' || $proyecto['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; font-size: 14px; padding: 8px 20px; text-align: center; border: 1px solid <?= ($proyecto['rl_usuarios']['bodega'] == '5492' || $proyecto['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; border-bottom-right-radius:20px; border-bottom-left-radius:20px;">
                        <?= $fecha_formateada ?>
                    </p>
                </td>
            </tr>
        </table>

        <br>
        <br>
        <p
            style="color: <?= ($proyecto['rl_usuarios']['bodega'] == '5492' || $proyecto['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; font-size: 18px; font-weight: 900; text-align: center; ">
            RECIBO DE PAGO</p>
        <br>

        <table style="margin-left:20px; width: 190mm; border:0;">
            <tr style="border: 0;">
                <td style="border:0;">
                    <p
                        style="font-weight: 900; font-size: 18px; background:<?= ($proyecto['rl_usuarios']['bodega'] == '5492' || $proyecto['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; color: white; padding: 10px 0; border:1px solid<?= ($proyecto['rl_usuarios']['bodega'] == '5492' || $proyecto['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; border-top-left-radius: 20px; border-top-right-radius: 20px;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DETALLES
                        DEL CLIENTE</p>
                </td>
            </tr>
            <tr>
                <td style="border: 0;">
                    <p
                        style=" margin-top:-10px;font-size: 14px; border:1px solid <?= ($proyecto['rl_usuarios']['bodega'] == '5492' || $proyecto['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
                        <br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b
                            style="color:<?= ($proyecto['rl_usuarios']['bodega'] == '5492' || $proyecto['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; font-size: 14px;">Usuario</b>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <?= $ingreso['rl_usuario']['Usuario_Nombre'] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b
                            style="color:<?= ($proyecto['rl_usuarios']['bodega'] == '5492' || $proyecto['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; font-size: 14px;">Pedido
                            No.</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <?= $proyecto['id_proyecto'] ?>
                        <br>
                        <br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b
                            style="color:<?= ($proyecto['rl_usuarios']['bodega'] == '5492' || $proyecto['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; font-size: 14px;">Cliente:</b>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <?= $proyecto['rl_clientes']['cliente_nombre'] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b
                            style="color: <?= ($proyecto['rl_usuarios']['bodega'] == '5492' || $proyecto['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; font-size: 14px;">Total
                            del pedido:</b>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <?= '$' . number_format($proyecto['proyecto_total'], 2) ?>
                        <br>
                        <br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b
                            style="color:<?= ($proyecto['rl_usuarios']['bodega'] == '5492' || $proyecto['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; font-size: 14px;">Direccion:</b>
                        &nbsp;&nbsp;&nbsp;
                        <?= $proyecto['rl_clientes']['cliente_entidad'] . ', ' . $proyecto['rl_clientes']['cliente_municipio'] ?>
                        <br>
                        <br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b
                            style="color:<?= ($proyecto['rl_usuarios']['bodega'] == '5492' || $proyecto['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; font-size: 14px;">E-mail:</b>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <?= $proyecto['rl_clientes']['cliente_email'] ?>
                        <br>
                        <br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b
                            style="color:<?= ($proyecto['rl_usuarios']['bodega'] == '5492' || $proyecto['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; font-size: 14px;">Telefono:</b>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <?= $proyecto['rl_clientes']['cliente_telefono'] ?>
                        <br>
                        <br>


                    </p>
                </td>

            </tr>
        </table>
        <br>
        <br>
        <table style="margin-left:20px; width: 190mm; border:0;">
            <tr style="border: 0;">
                <td colspan="2" style="border:0;">
                    <p
                        style="font-weight: 900; font-size: 18px; background:<?= ($proyecto['rl_usuarios']['bodega'] == '5492' || $proyecto['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; color: white; padding: 10px 0; border:1px solid<?= ($proyecto['rl_usuarios']['bodega'] == '5492' || $proyecto['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; border-top-left-radius: 20px; border-top-right-radius: 20px;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PROYECTO

                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="border: 0;">
                    <p
                        style="padding:0px 10px; margin-top:-10px;font-size: 14px; border:1px solid <?= ($proyecto['rl_usuarios']['bodega'] == '5492' || $proyecto['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
                        <br>
                        FABRICACION
                        DE
                        <?php foreach ($productos as $pro) {
                            // echo $pro['cantidad'] . ' ' . $pro['nombre'] . ' en color ' . $pro['color'] . '/ tapiceria color ' . $pro['colortapi'] . '.';
                            echo $pro['cantidad'] . ' ' . $pro['nombre'] . ' en color ' . $pro['color'] . '/ ' . (!empty($pro['colortapi']) ? 'tapizado color ' . $pro['colortapi'] : '') . (!empty($pro['esex']) ? 'Expecificaciones extra: ' . $pro['esex'] : '') . '';
                        } ?>
                        <?= ($palabra === 'FINIQUITO') ? 'Quedando
                            Liquidado el pedido.' : '' ?> para embarcar mas tardar el día
                        <?= date('Y-m-d', strtotime($productos[0]['fecha'])) ?>.

                        <br>
                        <br>
                    </p>
                </td>
            </tr>
        </table>
        <br>
        <br>
        <table style="margin-left:20px; width: 190mm; border:0;">
            <tr style="border: 0;">
                <td colspan="2" style="border:0;">
                    <p
                        style="font-weight: 900; font-size: 18px; background:<?= ($proyecto['rl_usuarios']['bodega'] == '5492' || $proyecto['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; color: white; padding: 10px 0; border:1px solid<?= ($proyecto['rl_usuarios']['bodega'] == '5492' || $proyecto['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; border-top-left-radius: 20px; border-top-right-radius: 20px;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DATOS
                        DEL PAGO
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="border: 0;">
                    <p
                        style="padding:0px 10px; margin-top:-10px;font-size: 14px; border:1px solid <?= ($proyecto['rl_usuarios']['bodega'] == '5492' || $proyecto['rl_usuarios']['bodega'] == '677') ? '#6f2693' : '#8c663b' ?>; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
                        <br>
                        SE RECIBIO <b>LA CANTIDAD DE </b> $
                        <?= number_format($ingreso['contabilidad_ingresos_cantidad'], 2) ?> (
                        <?= $this->numtoletras($ingreso['contabilidad_ingresos_cantidad']) ?>) <b>COMO
                            <?= $palabra ?>
                        </b>
                        <!-- <?= ($palabra === 'ANTICIPO') ? 'QUEDANDO PENDIENTE $' . number_format($ingreso['pendiente'], 2) : '' ?> -->
                        <?= ($palabra === 'ANTICIPO') ? 'QUEDANDO PENDIENTE $' . number_format($ingreso['pendiente'], 2) . ' (' . $this->numtoletras($ingreso['pendiente']) . ' )' : '' ?>

                        <br>
                        <br>
                    </p>
                </td>
            </tr>
        </table>
        <br>
        <!-- <table style="width: 100%; border:0;">
                <tr>
                    <td style="border:0;">
                        <hr style="width: 95%;  height:1px; ">
                    </td>
                </tr>
                 <tr>
                    <td style="border: 0;">
                        <p style="text-align: center;">
                            USUARIO:
                            <?= $ingreso['rl_usuario']['Usuario_Nombre'] ?>
                        </p>
                    </td>
                </tr> 

            </table> -->